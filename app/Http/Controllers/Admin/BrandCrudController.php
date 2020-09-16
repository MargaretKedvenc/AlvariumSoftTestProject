<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BrandRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BrandCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BrandCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Brand');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/brand');
        $this->crud->setEntityNameStrings('Производитель', 'Производители');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => "ID",
            ],
            [
                'name' => 'name',
                'label' => "Имя",
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'label' => "Изображение",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BrandRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => "Имя",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'slug',
                'label' => "Slug (оставьте пустым для автоматической генерации)",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'label' => "Изображение",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'text',
                'type' => 'textarea',
                'label' => "Текст",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'order',
                'type' => 'number',
                'label' => "Сортировка",
                'default' => 500,
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
    $this->crud->addColumns([
      [
        'name' => 'name',
        'label' => 'Название'
      ],
      [
        'name' => 'slug',
        'label' => 'Slug (для отображения в адресной строке)'
      ],
      [
        'name' => 'status',
        'label' => 'Статус',
        'type' => 'closure',
        'visibleInShow' => false
      ],
      [
        'name' => 'text',
        'label' => 'Описание'
      ],
      [
        'name' => 'order',
        'label' => 'Сортировка',
      ],
      [
        'name' => 'image',
        'type' => 'image',
        'label' => 'Изображение'
      ]
    ]);
  }
}
