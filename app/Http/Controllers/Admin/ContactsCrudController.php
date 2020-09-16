<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContactsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Contacts');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/contacts');
        $this->crud->setEntityNameStrings('Контакты', 'Контакты');
    }

    protected function setupListOperation()
    {
      $this->crud->removeButton('delete');
      $this->crud->removeButton('show');
      $this->crud->removeButton('create');
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->addColumns([
          [
            'name' => 'name',
            'type' => 'text',
            'label' => "Название",
          ],
          [
            'name' => 'title',
            'type' => 'text',
            'label' => "Заголовок",
          ],
          [
            'name' => 'address',
            'label' => 'Адрес',
            'type' => 'table',
            'columns' => [
              'value' => 'Адрес',
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 0,
          ],
          [
            'name' => 'phone',
            'label' => 'Телефон',
            'type' => 'table',
            'columns' => [
              'value' => 'Номер',
              'name' => 'Имя',
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 0,
          ],
          [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'table',
            'columns' => [
              'email' => 'Email',
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 0,
          ],
          [
            'name' => 'schedule',
            'label' => 'График работы',
            'type' => 'summernote',
          ],
          [
            'name' => 'short_schedule',
            'label' => 'Краткий график работы (для "шапки" и "подвала" сайта)',
            'type' => 'table',
            'columns' => [
              'value' => 'Значение',
            ],
            'max' => 2, // maximum rows allowed in the table
            'min' => 2,
          ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ContactsRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Название",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'title',
                'type' => 'text',
                'label' => "Заголовок",
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'address',
                'label' => 'Адрес',
                'type' => 'table',
                'columns' => [
                    'value' => 'Адрес',
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0,
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'phone',
                'label' => 'Телефон',
                'type' => 'table',
                'columns' => [
                    'value' => 'Номер',
                    'name' => 'Имя',
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0,
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'email',
                'label' => 'Email',
                'type' => 'table',
                'columns' => [
                    'email' => 'Email',
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0,
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'schedule',
                'label' => 'График работы',
                'type' => 'summernote',
                'tab' => 'Базовая информация',
            ],
            [
                'name' => 'short_schedule',
                'label' => 'Краткий график работы (для "шапки" и "подвала" сайта)',
                'type' => 'table',
                'columns' => [
                    'value' => 'Значение',
                ],
                'max' => 2, // maximum rows allowed in the table
                'min' => 2,
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
