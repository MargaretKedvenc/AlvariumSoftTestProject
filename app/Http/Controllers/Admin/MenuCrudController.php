<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Menu');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/menu');
        $this->crud->setEntityNameStrings('Меню', 'Меню');
        $this->crud->addButtonFromModelFunction('line', 'open_menu_items', 'openMenuItems', 'beginning');
        $this->crud->denyAccess(['update', 'create', 'delete']);
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
            ],
            [
                'name' => 'slug',
                'type' => 'text',
                'label' => "Slug",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MenuRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
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
