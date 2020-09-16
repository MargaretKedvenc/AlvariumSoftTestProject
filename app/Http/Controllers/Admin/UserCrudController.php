<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/users');
        $this->crud->setEntityNameStrings('Пользователь', 'Пользователи');
    }

    protected function setupListOperation()
    {
      $this->crud->allowAccess('delete');
      $this->crud->allowAccess('create');
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->addColumn([
            'label'     => "Имя",
            'type'       => 'text',
            'name'      => 'name',
            'priority'  => 1,
        ]);
      $this->crud->addColumn([
        'label'     => "Фамилия",
        'type'      => 'text',
        'name'      => 'last_name',
      ]);
        $this->crud->addColumn([
            'label'     => "Электронная почта",
            'type'        => 'text',
            'name'      => 'email',
            'priority'  => 2,
        ]);
        $this->crud->addColumn([
            'name'        => 'is_admin',
            'label'       => 'Роль',
            'type'        => 'radio',
            'options'     => [
                                0 => "User",
                                1 => "Admin"
                            ]
        ]);
      $this->crud->addColumn([
        'label'     => "Телефон",
        'type'      => 'text',
        'name'      => 'phone',
      ]);
    }

    protected function setupCreateOperation() {

        $this->crud->setValidation(UserRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Имя пользователя',
            ],
            [
              'label'     => "Фамилия",
              'type'      => 'text',
              'name'      => 'last_name',
            ],
            [
                'name' => 'email',
                'type' => 'email',
                'label' => 'Электронная почта',
            ],
            [
                'name'        => 'is_admin', // the name of the db column
                'label'       => 'Роль пользователя', // the input label
                'type'        => 'radio',
                'options'     => [
                    // the key will be stored in the db, the value will be shown as label;
                    0 => "User",
                    1 => "Admin"
                ],
            ],
          [
            'name' => 'phone',
            'label' => 'Телефон',
            'type' => 'text',
          ],
          [
            'label'     => "Пароль",
            'type'      => 'password',
            'name'      => 'password',
          ]
        ]);
    }

    protected function setupUpdateOperation()
    {
      $this->crud->setValidation(UserRequest::class);

      $this->crud->addFields([
        [
          'name' => 'name',
          'type' => 'text',
          'label' => 'Имя пользователя',
        ],
        [
          'label'     => "Фамилия",
          'type'      => 'text',
          'name'      => 'last_name',
        ],
        [
          'name' => 'email',
          'type' => 'email',
          'label' => 'Электронная почта',
        ],
        [
          'name'        => 'is_admin', // the name of the db column
          'label'       => 'Роль пользователя', // the input label
          'type'        => 'radio',
          'options'     => [
            // the key will be stored in the db, the value will be shown as label;
            0 => "User",
            1 => "Admin"
          ],
        ],
        [
          'name' => 'phone',
          'label' => 'Телефон',
          'type' => 'text',
        ],
        [
          'label'     => "Пароль",
          'type'      => 'text',
          'name'      => 'password',
        ]
      ]);
    }
    protected function setupShowOperation()
    {
      $this->setupListOperation();
    }
}
