<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FastOrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FastOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FastOrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\FastOrder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/fastorder');
        $this->crud->setEntityNameStrings('Быстрый заказ', 'Быстрые заказы');
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
                'name' => 'phone',
                'label' => "Телефон",
            ],
            [
                'name' => 'email',
                'label' => "Email",
            ],
            [
                'type' => "select",
                'name' => 'product_id', // the column that contains the ID of that connected entity;
                'entity' => 'product', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Models\Product",
                'label' => "Товар",
            ],
            [
                'type' => "text",
                'name' => 'quantity',
                'label'=>'Количество'
            ],
          [
            'type' => "text",
            'name' => 'created_at',
            'label'=>'Создан'
          ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(FastOrderRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Имя",
            ],
            [
                'name' => 'phone',
                'type' => 'text',
                'label' => "Телефон",
            ],
            [
                'name' => 'email',
                'type' => 'email',
                'label' => "Email",
            ],
            [
                'label' => "Товар",
                'type' => 'select',
                'name' => 'product_id', // the db column for the foreign key
                'entity' => 'product', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Product",
            ],
            [
                'label' => "Количество",
                'type' => 'number',
                'name' => 'quantity',
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
