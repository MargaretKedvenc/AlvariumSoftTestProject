<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SaleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SaleCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup()
  {
    $this->crud->setModel('App\Models\Sale');
    $this->crud->setRoute(config('backpack.base.route_prefix') . '/sale');
    $this->crud->setEntityNameStrings('Акция', 'Акции');
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
        'label' => "Название",
      ],
      [
        'name' => 'type_id',
        'type' => "select",
        'entity' => 'saleType',
        'attribute' => "name",
        'model' => "App\Models\SaleType",
        'label' => "Тип акции",
      ],
      [
        'name' => 'sum',
        'label' => "Сумма",
      ],
      [
        'name' => 'start',
        'type' => 'date',
        'label' => "Дата начала",
      ],
      [
        'name' => 'finish',
        'type' => 'date',
        'label' => "Дата окончания",
      ],
      [
        'name' => 'status',
        'label' => "Статус",
        'type' => 'select_from_array',
        'options' => [1 => 'Активна', 0 => 'Не активна'],
      ],
    ]);
  }

  protected function setupCreateOperation()
  {
    $this->crud->setValidation(SaleRequest::class);

    $this->crud->addFields([
      [
        'name' => 'name',
        'type' => 'text',
        'label' => "Название",
      ],
      [
        'name' => 'type_id',
        'type' => 'select2',
        'label' => "Тип акции",
        'entity' => 'saleType',
        'attribute' => 'name',
        'model' => "App\Models\SaleType",
      ],
      [
        'name' => 'sum',
        'type' => 'number',
        'label' => "Сумма",
      ],
      [
        'name' => 'start',
        'type' => 'date',
        'label' => "Дата начала",
      ],
      [
        'name' => 'finish',
        'type' => 'date',
        'label' => "Дата окончания",
      ],
      [
        'name' => 'status',
        'type' => 'boolean',
        'label' => "Активна",
        "default" => true,
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
  }
}
