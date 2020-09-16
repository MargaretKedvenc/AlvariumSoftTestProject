<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\SendSubscriptionOperation;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubscriptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscriptionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use SendSubscriptionOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Subscription');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/subscription');
        $this->crud->setEntityNameStrings('Рассылка', 'Рассылки');
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
                'name' => 'text',
                'label' => 'Контент',
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'label' => 'Статус',
                'type' => 'select_from_array',
                'options' => [
                    Subscription::START_STAGE => 'Черновик',
                    Subscription::PROCESS_STAGE => 'Рассылается',
                    Subscription::COMPLETE_STAGE => 'Завершено',
                ],
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(SubscriptionRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
            ],
            [
                'name' => 'text',
                'label' => 'Контент',
                'type' => 'ckeditor',
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
        $this->crud->addColumns([
            [
                'name' => 'subscribers',
                'visibleInShow' => false,
            ],
        ]);
    }
}
