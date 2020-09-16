<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DeliveryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DeliveryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DeliveryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Delivery');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/delivery');
        $this->crud->setEntityNameStrings('Способ доставки', 'Способы доставки');
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
            'label' => 'Статус',
            'type' => 'select_from_array',
            'tab' => 'Базовая информация',
            'default' => 1,
            'options' => [
              0 => 'Не опубликован',
              1 => 'Опубликован',
            ]
          ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DeliveryRequest::class);

      $this->crud->addFields([
        [
          'name' => 'name',
          'label' => 'Название',
          'type' => 'text'
        ],
        [
          'name' => 'description',
          'label' => 'Описание',
          'type' => 'textarea'
        ],
        [
          'name' => 'status',
          'type' => 'select2_from_array',
          'label' => 'Статус',
          'options' => [
            '1' => 'Опубликован',
            '0' => 'Не опубликован'
          ],
        ]
      ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->addFields([
          [
            'name' => 'name',
            'label' => 'Название',
            'type' => 'text'
          ],
          [
            'name' => 'description',
            'label' => 'Описание',
            'type' => 'textarea'
          ],
          [
            'name' => 'status',
            'type' => 'select2_from_array',
            'label' => 'Статус',
            'options' => [
              '1' => 'Опубликован',
              '0' => 'Не опубликован'
            ],
          ]
        ]);
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
