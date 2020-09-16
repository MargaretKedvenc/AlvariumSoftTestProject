<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HomeOurHistoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HomeOurHistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HomeOurHistoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\HomeOurHistory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/homeourhistory');
        $this->crud->setEntityNameStrings('Наша история', 'Наша история');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('delete');
        $this->crud->removeButton('show');
        $this->crud->removeButton('create');
        $this->crud->addColumns([
            [
                'name' => 'title',
                'label' => "Заголовок",
            ],
            [
                'name' => 'sub_title',
                'label' => "Текст под заголовком",
            ],
            [
                'name' => 'text',
                'label' => "Контент",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HomeOurHistoryRequest::class);

        $this->crud->addFields([
            [
                'name' => 'title',
                'type' => 'text',
                'label' => "Заголовок",
            ],
            [
                'name' => 'sub_title',
                'label' => 'Текст под заголовком',
                'type' => 'summernote',
            ],
            [
                'name' => 'text',
                'label' => 'Контент',
                'type' => 'summernote',
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
