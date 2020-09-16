<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PublicOfferRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PublicOfferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PublicOfferCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\PublicOffer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/publicoffer');
        $this->crud->setEntityNameStrings('Публичный договор (оферта)', 'Публичный договор (оферта)');
    }

    protected function setupListOperation()
    {
      $this->crud->removeButton('delete');
      $this->crud->removeButton('show');
      $this->crud->removeButton('create');
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->addColumn([
          'name' => 'name',
          'type' => 'text',
          'label' => "Название",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PublicOfferRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'text',
                'type' => 'summernote',
                'label' => "Текст",
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
