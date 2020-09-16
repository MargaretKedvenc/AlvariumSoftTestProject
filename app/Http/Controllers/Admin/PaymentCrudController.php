<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaymentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Payment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payment');
        $this->crud->setEntityNameStrings('Способ оплаты', 'Способы оплаты');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
              'name' => 'name',
              'label' => "Название",
            ],
            [
              'name' => 'slug',
              'label' => 'Slug (для отображения в адресной строке)'
            ],
            [
              'name' => 'status',
              'Label' => 'Статус'
            ]
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PaymentRequest::class);

        $this->crud->addFields([
            [
              'name' => 'name',
              'label' => 'Название',
              'type' => 'text'
            ],
            [
              'name' => 'description',
              'label' => 'Описание (не активно)',
            ],
            [
              'name' => 'status',
              'type' => 'select2_from_array',
              'label' => 'Статус',
              'options' => [
                '1' => 'Опубликован',
                '0' => 'Не опубликован'
              ],
            ],
            [
              'name' => 'sort',
              'type' => 'number',
              'label' => 'Сортировка',
              'default' => '1'
            ]
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    protected function setupShowOperation()
    {
      $this->crud->addColumns([
        [
          'name' => 'name',
          'label' => 'Название'
        ],
        [
          'name' => 'slug',
          'label' => 'Slug (для отображения в адресной строке)'
        ],
        [
          'name' => 'status',
          'label' => 'Статус',
          'type' => 'closure',
          'function' => function ($entry) {
            if ($entry->status == 1) {
              return 'Опубликован';
            } elseif ($entry->status == 0) {
              return 'Не опубликован';
            }
          }
        ],
        [
          'name' => 'description',
          'label' => 'Описание'
        ],
        [
          'name' => 'sort',
          'label' => 'Сортировка',
          'visibleInShow' => false,
        ]
      ]);
    }
}
