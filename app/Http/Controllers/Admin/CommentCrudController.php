<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentRequest;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CommentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Comment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comment');
        $this->crud->setEntityNameStrings('Отзыв', 'Отзывы');

      $this->user = User::all()->pluck('name', 'id');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => "Имя",
            ],
            [
                'name' => 'lastname',
                'label' => "Фамилия",
            ],
            [
              'name' => 'categoryComment.name',
              'label' => "Тип комментария",
            ],
            [
                'name' => 'text',
                'label' => "Текст",
            ],
            [
                'label' => "Товар",
                'type' => 'select',
                'name' => 'product_id', // the db column for the foreign key
                'entity' => 'product', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Product",
            ],
            [
                'name' => 'status',
                'label' => 'Опубликовано',
                'type' => 'boolean',
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(CommentRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'type' => 'text',
                'label' => "Имя",
            ],
            [
                'name' => 'lastname',
                'type' => 'text',
                'label' => "Фамилия",
            ],
            [
                'name' => 'text',
                'type' => 'text',
                'label' => "Текст",
            ],
            [
                'name' => "mark",
                'type' => 'select_from_array',
                'default' => null,
                'options' => [
                  null => '-',
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ],
                'label' => "Оценка",
            ],
            [
                'name' => 'category_comment_id',
                'type' => 'select2',
                'entity' => 'categoryComment', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\CategoryComment", // foreign key model
                'label' => "Тип комментария",
            ],
            [
                'name' => 'user_id',
                'type' => 'select2',
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\User", // foreign key model
                'label' => "Пользователь (обязателен для отзыва о товаре, иначе оставить пустым)",
            ],
            [
                'name' => 'product_id',
                'type' => 'select2',
                'entity' => 'product', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Product", // foreign key model
                'default' => NULL, // set the default value of the select2
                'label' => "Продукт (обязателен для отзыва о товаре, иначе оставить пустым)",
            ],
            [
                'name' => 'status',
                'type' => 'checkbox',
                'default' => true,
                'label' => "Опубликовано",
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
    $this->crud->addColumn([
      'label'     => "Фамилия",
      'type'      => 'text',
      'name'      => 'lastname',
    ]);
    $this->crud->addColumn([
      'label'     => "Оценка",
      'type'      => 'text',
      'name'      => 'mark',
    ]);
    $this->crud->addColumn([
      'label'     => "Сортировка",
      'type'      => 'text',
      'name'      => 'order',
    ]);
    $this->crud->addColumn([
      'label'     => "Тип отзыва",
      'name'      => 'category_comment_id',
      'entity' => 'categoryComment',
      'attribute' => "name",
      'model' => User::class,
      'type' => 'select',
      'options' => $this->user,
    ]);
    $this->crud->addColumn([
      'name' => 'user_id',
      'label' => 'Пользователь',
      'entity' => 'user',
      'attribute' => "name",
      'model' => User::class,
      'type' => 'select',
      'options' => $this->user,
    ]);
  }
}
