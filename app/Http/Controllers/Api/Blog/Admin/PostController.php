<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Http\Requests\BlogPostCreateRequest;
use App\Models\BlogPost;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Blog\Admin\PostResource;
use Throwable; // Імпортуємо базовий клас помилок для діагностики

class PostController extends BaseController
{
    use DispatchesJobs;

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {}

    /**
     * Отримати список усіх статей
     */
    public function index()
    {
        try {
            // Безпечний виклик нашої моделі BlogPost із відношенням категорії
            $posts = BlogPost::with(['category'])->paginate(25);

            return response()->json($posts);
        } catch (Throwable $e) {
            // ДІАГНОСТИКА: якщо тут станеться збій, ми побачимо причину прямо у вкладці Network
            return response()->json([
                'error' => true,
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Отримати одну статтю для редагування
     */
    public function show($id)
    {
        $post = BlogPost::with(['category'])->findOrFail($id);
        return response()->json(['data' => $post]);
    }

    /**
     * Створити нову статтю
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = BlogPost::create($data);

        if ($item) {
            $this->dispatch(new BlogPostAfterCreateJob($item));
            return response()->json(['success' => true, 'message' => 'Успішно збережено']);
        }

        return response()->json(['success' => false, 'message' => 'Помилка збереження'], 400);
    }

    /**
     * Оновити існуючу статтю
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        $result = $item->update($request->all());

        return $result
            ? response()->json(['success' => true, 'message' => 'Успішно збережено'])
            : response()->json(['message' => 'Помилка збереження'], 400);
    }

    /**
     * Видалити статтю
     */
    public function destroy($id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);
            return response()->json(['success' => true, 'message' => 'Видалено успішно']);
        }

        return response()->json(['success' => false, 'message' => 'Помилка видалення'], 400);
    }
}
