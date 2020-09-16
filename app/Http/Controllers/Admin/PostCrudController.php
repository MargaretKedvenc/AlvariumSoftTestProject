<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Post');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/post');
        $this->crud->setEntityNameStrings('Пост', 'Посты');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => "Название",
            ],
            [
                'name' => 'image',
                'label' => "Изображение",
                'type' => 'image',
            ],
            [
                'name' => 'text',
                'label' => "Контент",
            ],
            [
                'name' => 'status',
                'label' => 'Опубликовано',
                'type' => 'boolean',
            ],
            [
                'name' => 'sort',
                'label' => "Сортировка",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PostRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'slug',
                'type' => 'text',
                'label' => "URL сегмент (slug). Оставьте пустым для автоматической генерации",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'image',
                'label' => 'Изображение',
                'type' => 'image',
                'tab' => 'Базовая информация',
            ],
            [
                'name' => "text_preview",
                'label' => "Краткое описание",
                'type' => 'textarea',
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'text',
                'label' => 'Контент',
                'type' => 'summernote',
                'tab' => 'Базовая информация',
            ],
            [
              'name' => 'meta_title',
              'type' => 'text',
              'label' => "Meta title",
              'tab' => 'Мета теги',
            ],
            [
              'name' => 'meta_keywords',
              'type' => 'text',
              'label' => "Meta keywords",
              'tab' => 'Мета теги',
            ],
            [
              'name' => 'meta_description',
              'type' => 'text',
              'label' => "Meta description",
              'tab' => 'Мета теги',
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    protected function setupShowOperation()
    {
      $this->setupListOperation();
      $this->crud->addColumn([
        'name' => 'text_preview',
        'label' => 'Краткое описание'
      ]);
    }
}
