<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuItemRequest;
use App\Models\Menu;
use App\Models\MenuItem;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class MenuItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\MenuItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/menuitem');
        $this->crud->setEntityNameStrings('Пункт меню', 'Пункты меню');
      $this->crud->denyAccess(['create']);
    }

    protected function setupListOperation()
    {

        $this->crud->addColumns([
            [
                'name' => 'menu_id',
                'label' => "Меню",
                'entity' => 'menu',
                'attribute' => "name",
                'model' => Menu::class,
                'type' => 'select',
            ],
            [
                'name' => 'name',
                'label' => "Название",
            ],
            [
                'name' => 'link',
                'label' => "Ссылка",
            ],
            [
                'name' => 'sort',
                'type' => 'number',
                'label' => "Сортировка",
            ],
            [
                'name' => 'target',
                'label' => 'Открыть в новом окне',
                'type' => 'boolean',
            ],
          [
            'name' => 'status',
            'label' => 'Статус',
            'type' => 'closure',
            'function' => function ($entry) {
              if ($entry->status == 1) {
                return 'Опубликован';
              } else {
                return 'Не опубликован';
              }
            }
          ],
        ]);

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'menu_id',
            'label'=> 'Меню'
        ], function () {
            $menus = Menu::all(['id', 'name'])->keyBy('id')->map(function($item) {
                return $item->name;
            })->toArray();
            return $menus;
        }, function ($value) {
            $this->crud->addClause('where', 'menu_id', $value);
        });
    }

    protected function setupCreateOperation()
    {
//      this operation disabled
        $this->crud->setValidation(MenuItemRequest::class);

        $this->crud->addFields([
            [
                'name' => 'menu_id',
                'label' => "Меню",
                'entity' => 'menu',
                'attribute' => "name",
                'model' => \App\Models\Menu::class,
                'type' => 'select',
                'options'   => function () {
                  return Menu::all();
                },
                'default' => $this->request->menu_id,
            ],
            [
                'name' => 'name',
                'label' => "Название",
                'type' => 'text',
            ],
            [
                'name' => 'link',
                'label' => "Ссылка",
            ],
            [
                'name' => 'target',
                'label' => 'Открыть в новом окне',
                'type' => 'checkbox',
                'default' => 0,
            ],
            [
                'name' => 'sort',
                'type' => 'number',
                'label' => "Сортировка",
                'default' => 500,
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
      $this->crud->setValidation(MenuItemRequest::class);

      $this->crud->addFields([
        [
          'name' => 'menu_id',
          'label' => "Меню",
          'entity' => 'menu',
          'attribute' => "name",
          'model' => \App\Models\Menu::class,
          'type' => 'select',
          'options' => (function ($query) {
            $options = $query->get();
            return $options;
          }),
        ],
        [
          'name' => 'name',
          'label' => "Название",
          'type' => 'text',
        ],
        [
          'name' => 'link',
          'label' => "Ссылка",
        ],
        [
          'name' => 'target',
          'label' => 'Открыть в новом окне',
          'type' => 'checkbox',
          'default' => 0,
        ],
        [
          'name' => 'sort',
          'type' => 'number',
          'label' => "Сортировка",
          'default' => 500,
        ],
        [
          'name' => 'status',
          'label' => 'Статус',
          'type' => 'select_from_array',
          'default' => 1,
          'options' => [
            0 => 'Не опубликован',
            1 => 'Опубликован',
          ]
        ],
      ]);
    }
    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
