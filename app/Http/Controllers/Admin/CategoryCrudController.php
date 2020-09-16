<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Redirect;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
  use ListOperation;
  use CreateOperation;
  use UpdateOperation;
  use DeleteOperation;
  use ShowOperation;


  public function setup()
  {
    $this->crud->setModel('App\Models\Category');
    $this->crud->setRoute(config('backpack.base.route_prefix') . '/category');
    $this->crud->setEntityNameStrings('Categories', 'Categories');

    $categories = $this->categories();

  }

    /**
     * @return array
     */
    public function categories() : array
    {
        $categories = (new Category())->get();
        $response = [];
        foreach ($categories as $category){
            $response[$category->id] = $category->title;
        }
        return $response;

  }

  protected function setupListOperation()
  {
    //$this->crud->removeButton('delete');
   //$this->crud->addButtonFromView('line', 'Delete', 'customDelete', 'end');
    $this->crud->addColumns([
      [
        'name' => 'id',
        'label' => "ID",
      ],
      [
        'name' => 'title',
        'label' => "Name",
      ],
      [
        'name' => 'sort',
        'label' => "Sort",
      ],
    ]);
  }

  protected function setupShowOperation() {
    $this->setupListOperation();
  }

  protected function setupCreateOperation()
  {
    $this->crud->setValidation(CategoryRequest::class);

    $this->crud->addFields([
      [
        'name' => 'title',
        'type' => 'text',
        'label' => "Name",
        'tab' => 'Base Info',
      ],

      [
        'name' => 'sort',
        'type' => 'number',
        'default' => 500,
        'label' => "Сортировка",
        'tab' => 'Базовая информация',
      ],

    ]);
  }

  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }

  public function customDelete() {
    $this->crud->hasAccessOrFail('delete');
  }
}
