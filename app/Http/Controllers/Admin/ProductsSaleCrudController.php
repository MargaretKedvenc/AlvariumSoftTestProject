<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductsSaleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductsSaleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductsSaleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\ProductsSale');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/productssale');
        $this->crud->setEntityNameStrings('Акционный товар', 'Акционные товары');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
          [
            'name' => 'product_id',
            'label' => 'Товар',
            'type' => 'select',
            'entity' => 'product',
            'attribute' => "name",
            'model' => "App\Models\Product",
          ],
          [
            'name' => 'sale_id',
            'label' => 'Акция',
            'type' => 'select',
            'entity' => 'Sale',
            'attribute' => "name",
            'model' => "App\Models\Sale",
          ]
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ProductsSaleRequest::class);

      $this->crud->addFields([
        [
          'name' => 'product_id',
          'label' => 'Товар',
          'type' => 'select2',
          'entity' => 'product',
          'attribute' => "name",
          'model' => "App\Models\Product",
        ],
        [
          'name' => 'sale_id',
          'label' => 'Акция',
          'type' => 'select2',
          'entity' => 'Sale',
          'attribute' => "name",
          'model' => "App\Models\Sale",
        ]
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
