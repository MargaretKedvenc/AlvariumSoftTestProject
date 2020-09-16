<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HomeSliderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HomeSliderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HomeSliderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\HomeSlider');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/homeslider');
        $this->crud->setEntityNameStrings('Слайд на Home page', 'Слайды на Home page');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'text',
                'label' => "Текст",
            ],
            [
                'name' => 'link',
                'label' => "Ссылка",
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'label' => "Изображение",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HomeSliderRequest::class);

        $this->crud->addFields([
            [
                'name' => 'text',
                'type' => 'text',
                'label' => "Текст",
            ],
            [
                'name' => 'link',
                'type' => 'text',
                'label' => "Ссылка",
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'crop' => true,
                'aspect_ratio' => 1.2,
                'label' => "Изображение",
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
