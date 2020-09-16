<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrdersRequest;
use App\Jobs\SendMail;
use App\Mail\OrderClosedSendMailUser;
use App\Mail\OrderSendMailAdmin;
use App\Mail\OrderSendMailUser;
use App\Mail\OrderUpdateSendMailUser;
use App\Models\Contacts;
use App\Models\Currency;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Status;
use App\Notifications\NewOrder;
use App\Notifications\OrderUpdate;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ClosedOrder;
use Illuminate\Support\Facades\Notification;
use PDF;

/**
 * Class OrdersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrdersCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
    update as traitUpdate;
  }
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup()
  {
    $this->crud->addButtonFromView('line', 'openPdf', 'pdfButton', 'beginning');
    $this->crud->setModel('App\Models\Order');
    $this->crud->setRoute(config('backpack.base.route_prefix') . '/orders');
    $this->crud->setEntityNameStrings('Заказ', 'Заказы');

    $this->delivery = Delivery::all()->pluck('name', 'id');
    $this->payment = Payment::all()->pluck('name', 'id');
    $this->user = User::all()->pluck('name', 'id');
    $this->status = Status::all()->pluck('name', 'id');
  }

  public function store(Request $request)
  {
    $this->crud->hasAccessOrFail('create');

    // execute the FormRequest authorization and validation, if one is required
    $request = $this->crud->validateRequest();
    $item = $this->crud->create($this->crud->getStrippedSaveRequest());
    $this->data['entry'] = $this->crud->entry = $item;

    //update products quantity
    $products = json_decode($request->products);
    foreach ($products as $product) {
      $productDb = Product::find($product->id);
      $newQuantity = $productDb->quantity - $product->in_order_quantity;
      $productDb->update(['quantity' => $newQuantity]);
      $productDb->save();
    }
//    Notification::route('mail', env('MAIL_ADMIN'))
//      ->notify(new NewOrder($item));
//    if (isset($request->user_id)) {
//      $user = User::find($request->user_id);
//      if (!empty($user)) {
//        $user->notify(new NewOrder($item));
//      }
//    } elseif (!empty($request->email)) {
//      Notification::route('mail', $request->email)
//        ->notify(new NewOrder($item));
//    }

    // show a success message
    \Alert::success(trans('backpack::crud.insert_success'))->flash();

    // save the redirect choice for next time
    $this->crud->setSaveAction();

    return $this->crud->performSaveAction($item->getKey());
  }

  public function update()
  {
    // execute the FormRequest authorization and validation, if one is required
    $request = $this->crud->validateRequest();
    $response = $this->traitUpdate();

    $item = $this->data['entry'];
    if (isset($request->user_id)) {
      $user = User::find($request->user_id);
      if (!empty($user)) {
        if ($request->status_id == 4) {
          $user->notify(new ClosedOrder($item));
        } else {
          $user->notify(new OrderUpdate($item));
        }
      }
    } elseif (!empty($request->email)) {
      if ($request->status_id == 4) {
        Notification::route('mail', $request->email)
          ->notify(new ClosedOrder($item));
      } else {
        Notification::route('mail', $request->email)
          ->notify(new OrderUpdate($item));
      }
    }
    return $response;
  }

  protected function setupListOperation()
  {
    $this->crud->addColumns([
      [
        'name' => 'name',
        'label' => "Название",
        'type' => 'text',
      ],
      [
        'name' => 'status_id',
        'label' => 'Статус',
        'type' => 'closure',
        'function' => function ($entry) {
          return $entry->status->name;
        }
      ],
      [
        'name' => 'total',
        'label' => 'Сумма',
        'type' => 'text',
        'suffix' => ' грн.'
      ],
      [
        'name' => 'created_at',
        'label' => 'Создан',
        'type' => 'text'
      ],
    ]);
  }

  protected function setupCreateOperation()
  {
    $count = Order::latest()->first();;
    if (!empty($count)) {
      $number = $count->id + 1;
    } else {
      $number = 1;
    }
    $name = 'Заказ №' . $number;
    $this->crud->setValidation(OrdersRequest::class);

    $this->crud->addFields([
      [
        'name' => 'name',
        'label' => "Название",
        'type' => 'text',
        'tab' => 'Базовая информация',
        'value' => $name
      ],
      [
        'name' => 'status_id',
        'label' => 'Статус',
        'type' => 'select2_from_array',
        'tab' => 'Базовая информация',
        'options' => $this->status,
      ],
      [
        'name' => 'delivery_id',
        'label' => 'Выбрать способ доставки',
        'entity' => 'delivery',
        'attribute' => "name",
        'model' => Delivery::class,
        'type' => 'select',
        'tab' => 'Доставка',
        'options' => (function ($query) {
          $options = $query->get();
          return $options;
        }),
      ],
      [
        'name' => 'payment_id',
        'label' => 'Выбрать способ оплаты',
        'entity' => 'payment',
        'attribute' => "name",
        'model' => Payment::class,
        'type' => 'select',
        'tab' => 'Базовая информация',
        'options' => (function ($query) {
          $options = $query->get();
          return $options;
        }),
      ],
    ]);
    $this->crud->addField([
      'name' => 'products',
      'type' => 'custom_products',
      'tab' => 'Товары'
    ]);
    $this->crud->addField([
      'name' => 'total',
      'type' => 'custom_total',
      'tab' => 'Товары'
    ]);
    $this->crud->addField([
      'name' => 'first_name',
      'label' => "Имя",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'text',
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'last_name',
      'label' => "Фамилия",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'text',
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'phone',
      'label' => "Телефон",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'text',
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'email',
      'label' => "Email",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'email',
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'user_id',
      'label' => "Пользователь",
      'entity' => 'user',
      'attribute' => "name",
      'model' => User::class,
      'type' => 'select2',
      'tab' => 'Личные данные'
    ]);
    $this->crud->addField([
      'name' => 'delivery_info',
      'type' => 'custom_delivery',
      'tab' => 'Доставка'
    ]);
  }

  protected function setupShowOperation()
  {
    $this->setupListOperation();

    $this->crud->addColumns([
      [
        'name' => 'products',
        'type' => 'tableCustom',
        'label' => "Товары",
      ],
      [
        'name' => 'delivery_id',
        'label' => 'Способ доставки',
        'entity' => 'delivery',
        'attribute' => "name",
        'model' => Delivery::class,
        'type' => 'select',
        'tab' => 'Доставка',
      ],
      [
        'name' => 'delivery_info.area.name',
        'type' => 'text',
        'label' => "Область",
      ],
      [
        'name' => 'delivery_info.locality.name',
        'type' => 'text',
        'label' => "Город",
      ],
      [
        'name' => 'delivery_info.department.name',
        'type' => 'text',
        'label' => "Отделение",
      ],
      [
        'name' => 'delivery_info.house',
        'type' => 'text',
        'label' => "Улица и номер дома",
      ],
      [
        'name' => 'delivery_info.flat',
        'type' => 'text',
        'label' => "Номер квартиры",
      ],
      [
        'name' => 'user_id',
        'label' => 'Пользователь',
        'entity' => 'user',
        'attribute' => "name",
        'model' => User::class,
        'type' => 'select',
        'tab' => 'Базовая информация',
        'options' => $this->user,
      ],
      [
        'name' => 'profile.first_name',
        'type' => 'text',
        'label' => "Имя",
      ],
      [
        'name' => 'profile.last_name',
        'type' => 'text',
        'label' => "Фамилия",
      ],
      [
        'name' => 'profile.phone',
        'type' => 'text',
        'label' => "Телефон",
      ],
      [
        'name' => 'profile.email',
        'type' => 'text',
        'label' => "Email",
      ],
      [
        'name' => 'payment_id',
        'label' => 'Способ оплаты',
        'entity' => 'payment',
        'attribute' => "name",
        'model' => Payment::class,
        'type' => 'select',
        'tab' => 'Базовая информация',
      ],
    ]);
  }

  protected function setupUpdateOperation()
  {
    $this->crud->addField([
      'name' => 'name',
      'type' => 'text',
      'label' => 'Название',
      'tab' => 'Базовая информация',
    ]);
    $this->crud->addField([
      'name' => 'products',
      'type' => 'custom_products',
      'tab' => 'Товары'
    ]);
    $this->crud->addField([
      'name' => 'total',
      'type' => 'custom_total',
      'tab' => 'Товары'
    ]);
    $entryData = Order::find($this->crud->request->id);
    $this->crud->addField([
      'name' => 'first_name',
      'label' => "Имя",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'text',
      'default' => $entryData->profile['first_name'],
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'last_name',
      'label' => "Фамилия",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'text',
      'default' => $entryData->profile['last_name'],
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'phone',
      'label' => "Телефон",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'text',
      'default' => $entryData->profile['phone'],
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'email',
      'label' => "Email",
      'fake' => true,
      'store_in' => 'profile',
      'type' => 'email',
      'default' => $entryData->profile['email'],
      'tab' => 'Личные данные'
    ]);

    $this->crud->addField([
      'name' => 'delivery_id',
      'label' => 'Выбрать способ доставки',
      'entity' => 'delivery',
      'attribute' => "name",
      'model' => Delivery::class,
      'type' => 'select',
      'tab' => 'Доставка',
      'options' => (function ($query) {
        $options = $query->get();
        return $options;
      }),
    ]);

    $this->crud->addField([
      'name' => 'delivery_info',
      'type' => 'custom_delivery',
      'tab' => 'Доставка'
    ]);

    $this->crud->addField([
      'name' => 'payment_id',
      'label' => 'Тип оплаты',
      'type' => 'select2_from_array',
      'options' => $this->payment,
      'tab' => 'Базовая информация'
    ]);

    $this->crud->addField([
      'name' => 'status_id',
      'label' => 'Статус',
      'type' => 'select2_from_array',
      'options' => $this->status,
      'tab' => 'Базовая информация',
    ]);
    $this->crud->addField([
      'name' => 'user_id',
      'label' => "Пользователь",
      'entity' => 'user',
      'attribute' => "name",
      'model' => User::class,
      'type' => 'select2',
      'default' => $entryData->user_id,
      'tab' => 'Личные данные'
    ]);
  }

  public function openPdf($id)
  {
    $order_data = Order::find($id);
    $contacts_data = Contacts::first();
    $orderPdf = PDF::loadView('admin.order_print', compact('order_data', 'contacts_data'));
    return $orderPdf->stream('order.pdf');
  }
}
