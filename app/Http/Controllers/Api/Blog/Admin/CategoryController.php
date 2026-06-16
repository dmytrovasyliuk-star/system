<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Models\BlogCategory;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;
// ПІДКЛЮЧАЄМО НАШ НОВИЙ РЕСУРС:
use App\Http\Resources\Api\Blog\Admin\CategoryResource;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        // parent::__construct();
    }

    public function index()
    {
        // Отримуємо пагіновані дані (як у тебе і було)
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        // ПОВЕРТАЄМО ДАНІ ЧЕРЕЗ РЕСУРС:
        return CategoryResource::collection($paginator);
    }

    public function show($id)
    {
        // Шукаємо категорію за ID через правильну модель
        $category = \App\Models\BlogCategory::findOrFail($id);

        // Повертаємо її у форматі JSON
        return response()->json(['data' => $category]);
    }
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        $item = (new BlogCategory())->create($data);

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }
    public function destroy($id)
    {
        // Шукаємо саме через BlogCategory
        $category = \App\Models\BlogCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Категорію видалено']);
    }
}
