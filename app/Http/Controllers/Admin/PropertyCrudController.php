<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyRequest;
use App\Jobs\DeleteProperties;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PropertyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PropertyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }

    public function setup()
    {
        $this->crud->setModel('App\Models\Property');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/property');
        $this->crud->setEntityNameStrings('Свойство', 'Свойства');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
          [
            'name' => 'name',
            'label' => 'Название',
          ],
          [
            'name' => 'type',
            'label' => 'Тип',
            'type' => 'select_from_array',
            'options' => [2 => 'Список']
          ],
          [
            'name' => 'status',
            'label' => "Статус",
            'type' => 'select_from_array',
            'options' => [1 => 'Опубликовано', 0 => 'Не опубликовано'],
          ],
          [
            'name' => 'sort',
            'label' => "Сортировка",
          ],
        ]);
    }

    protected function setupShowOperation() {
        $this->setupListOperation();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PropertyRequest::class);

      $this->crud->addFields([
        [
          'name' => 'name',
          'type' => 'text',
          'label' => 'Название',
        ],
        [
          'name' => 'status',
          'label' => "Опубликовано",
          'type' => 'boolean',
          'default' => true,
        ],
        [
          'name' => 'sort',
          'type' => 'number',
          'default' => 500,
          'label' => "Сортировка",
        ],
      ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
  public function destroy($id)
  {
    ini_set('max_execution_time', 900);

    DeleteProperties::dispatch($id);
    $response = $this->traitDestroy($id);

    return $response;
  }
}
