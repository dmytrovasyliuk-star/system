<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use Illuminate\Http\Request;
use App\Repositories\BlogPostRepository;

class PostController extends BaseController
{
    public function __construct(private BlogPostRepository $blogPostRepository)
    {
        // parent::__construct();
    }

    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();
        return $paginator;
    }

    public function store(Request $request)
    {
        // Заглушка для майбутніх лабораторних
    }

    public function update(Request $request, string $id)
    {
        // Заглушка для майбутніх лабораторних
    }

    public function destroy(string $id)
    {
        // Заглушка для майбутніх лабораторних
    }
}
