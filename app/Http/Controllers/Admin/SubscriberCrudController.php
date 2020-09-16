<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubscriberRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Http\Requests\CreateSubscriberRequest as StoreRequest;
use App\Http\Requests\UpdateSubscriberRequest as UpdateRequest;

/**
 * Class SubscriberCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscriberCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        $this->crud->setModel('App\Models\Subscriber');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/subscriber');
        $this->crud->setEntityNameStrings('Подписчик', 'Подписчики');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'email',
                'type' => 'email',
                'label' => "Email",
            ],
            [
                'name' => 'status',
                'label' => 'Активен',
                'type' => 'boolean',
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);

        $this->crud->addFields([
            [
                'name' => 'email',
                'type' => 'email',
                'label' => "Email",
            ],
            [
                'name' => 'status',
                'label' => 'Активен',
                'type' => 'checkbox',
                'default' => 1,
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);

        $this->crud->addFields([
            [
                'name' => 'email',
                'type' => 'email',
                'label' => "Email",
            ],
            [
                'name' => 'status',
                'label' => 'Активен',
                'type' => 'checkbox',
                'default' => 1,
            ],
        ]);
    }
  protected function setupShowOperation()
  {
    $this->setupListOperation();
  }
}
