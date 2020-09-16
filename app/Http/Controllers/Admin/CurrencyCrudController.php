<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\FastOrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CurrencyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CurrencyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Currency');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/currency');
        $this->crud->setEntityNameStrings('Курс', 'Установка курса');
    }

    protected function setupListOperation()
    {
       $this->crud->addColumns([
            [
                'name' => 'value',
                'label' => 'Значение',
                'suffix' => ' грн'
            ],
           [
               'name' => 'updated_at',
               'label' => 'Дата последнего обновления'
           ],
       ]);
        $this->crud->removeButton('delete');
        $this->crud->removeButton('show');
        $this->crud->removeButton('create');
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(CurrencyRequest::class);

        $this->crud->addFields([
            [
                'name' => 'value',
                'type' => 'text',
                'label' => "Значение",
            ],
        ]);
    }
}
