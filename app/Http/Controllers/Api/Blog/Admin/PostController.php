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

class PostController extends BaseController
{
    use DispatchesJobs;

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {}

    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate(10);
        return PostResource::collection($paginator);
    }

    public function show($id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        // Якщо пост не знайдено, повертаємо чітку відповідь
        if (!$item) {
            return response()->json(['message' => 'Пост з ID ' . $id . ' не знайдено в базі'], 404);
        }

        // Повертаємо ресурс, якщо пост знайдено
        return new PostResource($item);
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = BlogPost::create($data);
        if ($item) {
            $this->dispatch(new BlogPostAfterCreateJob($item));
            return ['success' => true, 'message' => 'Успішно збережено'];
        }
        return ['success' => false, 'message' => 'Помилка збереження'];
    }

    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) return ['message' => "Запис id=[{$id}] не знайдено"];
        $result = $item->update($request->all());
        return $result ? ['success' => true, 'message' => 'Успішно збережено'] : ['message' => 'Помилка збереження'];
    }

    public function destroy($id)
    {
        $result = BlogPost::destroy($id);
        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);
            return ['success' => true, 'message' => 'Видалено успішно'];
        }
        return ['success' => false, 'message' => 'Помилка видалення'];
    }
}
