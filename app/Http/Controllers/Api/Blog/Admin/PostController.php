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

class PostController extends BaseController
{
    use DispatchesJobs;

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {
    }

    public function index()
    {
        return $this->blogPostRepository->getAllWithPaginate();
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

        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $result = $item->update($request->all());

        if ($result) {
            return ['success' => true, 'message' => 'Успішно збережено'];
        }

        return ['message' => 'Помилка збереження'];
    }

    public function destroy($id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            // Використовуємо dispatch з затримкою 20 секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);
            return ['success' => true, 'message' => 'Видалено успішно'];
        }

        return ['success' => false, 'message' => 'Помилка видалення'];
    }
}
