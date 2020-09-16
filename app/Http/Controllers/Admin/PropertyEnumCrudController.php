<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyEnumRequest;
use App\Models\Property;
use App\Models\PropertyEnum;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PropertyEnumCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PropertyEnumCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\PropertyEnum');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/propertyenum');
        $this->crud->setEntityNameStrings('Значение', 'Значения свойств');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
                [
                    'name' => 'property_id',
                    'label' => 'Свойство',
                    'type' => 'closure',
                    'function' => function($entry)
                    {
                        return $entry->property->name;
                    }
                ],
                [
                    'name' => 'value',
                    'label' => 'Значение',
                ],
                [
                    'name' => 'sort',
                    'label' => 'Сортировка',
                ],
        ]);
    }

    protected function setupShowOperation() {
      $this->setupListOperation();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PropertyEnumRequest::class);

      $this->crud->addFields([
        [
          'name' => 'property_id',
          'label' => 'Свойство',
          'type' => 'select2',
          'entity' => 'property',
          'attribute' => "name",
          'model' => "App\Models\Property",
        ],
        [
          'name' => 'value',
          'label' => 'Значение',
          'type' => 'text',
        ]
      ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
