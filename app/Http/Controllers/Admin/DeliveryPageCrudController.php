<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeliveryPageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DeliveryPageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DeliveryPageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\DeliveryPage');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/deliverypage');
        $this->crud->setEntityNameStrings('Доставка и оплата', 'Доставка и оплата');
    }

    protected function setupListOperation()
    {
      $this->crud->removeButton('delete');
      $this->crud->removeButton('show');
      $this->crud->removeButton('create');
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => "Название",
            ],
            [
                'name' => 'text',
                'label' => "Контент",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DeliveryPageRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'text',
                'label' => 'Контент',
                'type' => 'summernote',
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
}
