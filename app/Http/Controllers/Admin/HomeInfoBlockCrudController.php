<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HomeInfoBlockRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HomeInfoBlockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HomeInfoBlockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\HomeInfoBlock');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/homeinfoblock');
        $this->crud->setEntityNameStrings('Инфо блок', 'Инфо блоки');
    }

    protected function setupListOperation()
    {
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('create');

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
                'label' => "Описание",
            ],
            [
                'name' => 'sort',
                'label' => "Сортировка",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HomeInfoBlockRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
            ],
            [
                'name' => 'image',
                'label' => 'Изображение',
                'type' => 'image',
            ],
            [
                'name' => 'text',
                'label' => 'Контент',
                'type' => 'textarea',
            ],
            [
                'name' => 'sort',
                'label' => "Сортировка",
                'type' => 'number',
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
        'name' => 'sort',
        'label' => 'Сортировка',
      ],
      [
        'name' => 'status',
        'visibleInShow' => false
      ],
      [
        'name' => 'image',
        'label' => 'Изображение',
        'type' => 'image'
      ],
      [
        'name' => 'text',
        'label' => 'Описание'
      ]
    ]);
  }
}
