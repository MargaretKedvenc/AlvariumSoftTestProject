<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\Property;
use App\Models\Sale;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Request;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
    store as traitStore;
  }
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
    update as traitUpdate;
  }
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup()
  {
    $this->crud->setModel('App\Models\Product');
    $this->crud->setRoute(config('backpack.base.route_prefix') . '/product');
    $this->crud->setEntityNameStrings('Товар', 'Товары');

    $this->sale = Sale::where('status', 1)->get();
  }

  protected function setupListOperation()
  {
    $this->crud->disableResponsiveTable();
    $this->crud->addColumns([
      [
        'name' => 'name',
        'type' => 'text',
        'label' => "Название",
      ],
      [
        'name' => 'vendor_code',
        'type' => 'text',
        'label' => "Артикул",
      ],
      [
        'name' => 'category_id',
        'label' => "Категория",
        'type' => 'closure',
        'function' => function ($entry) {
          return $entry->category->name;
        }
      ],
      [
        'name' => 'price',
        'label' => 'Цена',
        'type' => 'text',
      ],
      [
        'name' => 'currency',
        'label' => 'Валюта',
        'type' => 'text',
      ],
      [
        'name' => 'quantity',
        'label' => 'Количество',
        'type' => 'text',
        'function' => function ($entry) {
          if ($entry->quantity == 0) {
            return '0';
          } else {
            return $entry->quantity;
          }
        }
      ],
      [
        'name' => 'sale_id',
        'label' => "Акция",
        'entity' => 'sales',
        'attribute' => "name",
        'model' => Sale::class,
        'type' => 'select',
        'options' => $this->sale,
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
          } else {
            return 'Опубликован(под заказ)';
          }
        }
      ],
//      [
//        'name' => 'sale',
//        'label' => "Акция",
//        'visibleInShow' => false
//      ],
      [
        'name' => 'sort',
        'type' => 'number',
        'label' => "Сортировка",
      ]
    ]);

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'category_id',
            'label'=> 'Категория'
        ], function () {
            $items = Category::with('category')->whereNotNull('category_id')->get()->keyBy('id')->map(function($item) {
                return $item->category->name . ' - ' . $item->name;
            })->toArray();
            return $items;
        }, function ($value) {
            $this->crud->addClause('where', 'category_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'sale_id',
            'label'=> 'Акция'
        ],
            function () {
                return Sale::all()->keyBy('id')->map(function($item) {
                    return $item->name;
                })->toArray();
            },
            function($value) {
                $this->crud->addClause('where', 'sale_id', $value);
            });

        $this->crud->addFilter([
            'type' => 'dropdown',
            'name' => 'status',
            'label'=> 'Опубликовано'
        ],
            function () {
                return [
                    0 => 'Не опубликовано',
                    1 => 'Опубликовано',
                ];
            },
            function($value) {
                $this->crud->addClause('where', 'status', $value);
            });
  }

  //Save wholesalePrice to price table
  public function store()
  {
    $request = $this->crud->validateRequest();
    $response = $this->traitStore();

    $entryCurrency = ($this->data['entry']->currency);
    $entryID = ($this->data['entry']->id);
    if (isset($request->wholesalePrice)) {
      $priceDecoded = json_decode($request->wholesalePrice);

      Price::where('product_id', '=', $entryID)->delete();

      foreach ($priceDecoded as $price) {
        $wholesalePrice = new Price();
        $wholesalePrice->quantity = $price->quantity;
        $wholesalePrice->currency = $entryCurrency;
        $wholesalePrice->value = $price->value;
        $wholesalePrice->product_id = $entryID;
        $wholesalePrice->save();
      }
    }
    return $response;
  }

  public function update()
  {
    $request = $this->crud->validateRequest();
    $response = $this->traitUpdate();

    $entryCurrency = ($this->data['entry']->currency);
    $entryID = ($this->data['entry']->id);

    Price::where('product_id', '=', $entryID)->delete();

    if (isset($request->wholesalePrice)) {
      $priceDecoded = json_decode($request->wholesalePrice);
      foreach ($priceDecoded as $price) {
        if(isset($price->quantity) && isset($price->value)) {
          $wholesalePrice = new Price();
          $wholesalePrice->quantity = $price->quantity;
          $wholesalePrice->currency = $entryCurrency;
          $wholesalePrice->value = $price->value;
          $wholesalePrice->product_id = $entryID;
          $wholesalePrice->save();
        }
      }
    }
    return $response;
  }

  protected function setupCreateOperation()
  {
    $this->crud->setValidation(ProductRequest::class);

    $this->crud->addFields([
      [
        'name' => 'name',
        'type' => 'text',
        'label' => "Название",
        'tab' => 'Базовая информация',
      ],
      [
        'name' => 'vendor_code',
        'type' => 'text',
        'label' => "Артикул",
        'tab' => 'Базовая информация',
      ],
      [
        'name' => 'slug',
        'type' => 'text',
        'label' => "URL сегмент (slug). Оставьте пустым для автоматической генерации",
        'tab' => 'Базовая информация',
      ],
      [
        'label' => "Изображение",
        'name' => "image",
        'type' => 'image',
        'upload' => true,
        'crop' => true,
        'aspect_ratio' => 0.8,
        'tab' => 'Базовая информация',
      ],
      [
        'name' => 'images',
        'label' => 'Дополнительные изображения',
        'type' => 'upload_multiple',
        'upload' => true,
        'tab' => 'Дополнительные изображения',
      ],
      [
        'name' => 'images',
        'label' => 'Дополнительные изображения',
        'type' => 'upload_multiple',
        'upload' => true,
        'tab' => 'Дополнительные изображения',
      ],
      [
        'name' => 'description',
        'label' => 'Описание',
        'type' => 'ckeditor',
        'tab' => 'Базовая информация',
      ],
      [
        'name' => 'category_id',
        'label' => "Категория",
        'entity' => 'category',
        'attribute' => "name",
        'model' => Category::class,
        'type' => 'select',
        'tab' => 'Базовая информация',
        'options' => (function ($query) {
          $options = $query->with('category')->whereNotNull('category_id')->get();
          $options->map(function ($item) {
            if ($item->category) {
              $item->name = $item->category->name . ' - ' . $item->name;
            }
            return $item;
          });
          return $options;
        }),
      ],
      [
        'name' => 'showcase',
        'label' => 'Ярлык',
        'type' => 'select_from_array',
        'allows_null' => true,
        'options' => [
          'Топ продаж' => 'Топ продаж',
          'Новинка' => 'Новинка',
          'Защита' => 'Защита',
        ],
        'tab' => 'Базовая информация',
      ],
      [
        'name' => 'price',
        'label' => 'Цена',
        'type' => 'text',
        'tab' => 'Базовая информация',
      ],
      [
        'name' => 'currency',
        'label' => 'Валюта',
        'type' => 'select_from_array',
        'tab' => 'Базовая информация',
        'options' => [
          'UAH' => 'UAH',
          'USD' => 'USD'
        ]
      ],
      [
        'name' => 'brand_id',
        'label' => "Производитель",
        'entity' => 'brand',
        'attribute' => "name",
        'model' => Brand::class,
        'type' => 'select',
        'tab' => 'Базовая информация',
        'options' => (function ($query) {
          $options = $query->get();
          return $options;
        }),
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
          2 => 'Опубликован(под заказ)']
      ],
      [
        'name' => 'sort',
        'type' => 'number',
        'label' => "Сортировка",
        'tab' => 'Базовая информация',
        'default' => 500,
      ],
      [
        'name' => 'quantity',
        'type' => 'number',
        'label' => "Количество",
        'tab' => 'Базовая информация',
        'default' => 1,
      ],
      [
        'name' => 'meta_title',
        'type' => 'text',
        'label' => "meta_title",
        'tab' => 'CEO',
      ],
      [
        'name' => 'meta_keywords',
        'type' => 'text',
        'label' => "meta_keywords",
        'tab' => 'CEO',
      ],
      [
        'name' => 'meta_description',
        'type' => 'text',
        'label' => "meta_description",
        'tab' => 'CEO',
      ],
      [
        'name' => 'wholesalePrice',
        'label' => 'Оптовые цены',
        'type' => 'wholesalePriceField',
        'tab' => "Оптовые цены",
        'entity_singular' => 'Оптовая цена', // used on the "Add X" button
        'columns' => [
          'quantity' => 'Количество',
          'value' => 'Цена (в валюте установленной для данного товара)',
        ],
        'max' => 4, // maximum rows allowed in the table
        'min' => 0, // minimum rows allowed in the table
      ],
    ]);

    $similars = [];
    for ($i = 1; $i <= 10; $i++) {
      $similars[] = [
        'label' => 'Сопутствующий товар ' . $i,
        'fake' => true,
        'allows_null' => true,
        'tab' => 'Сопутствующие товары',
        'store_in' => 'similar_products',
        'type' => 'custom_select2_grouped',
        'name' => 'similar_product_' . $i,
        'entity' => 'product',
        'model' => 'App\Models\Product',
        'attribute' => 'name',
        'group_by' => 'category',
        'group_by_attribute' => 'name',
        'group_by_relationship_back' => 'products',
      ];
    }

    $this->crud->addFields($similars);

    $extras = [];
    $properties = Property::with('propertyEnums')->get();
    $product = Product::find($this->request->id);
    $chosenProperties = [];
    if ($product) {
      $chosenProperties = $product->properties;
    }
    foreach ($properties as $property) {
      $nameProperty = 'property_' . $property->id;
      $field = [
        'name' => $nameProperty,
        'label' => $property->name,
        'fake' => true,
        'tab' => 'Свойства',
        'type' => 'select_from_array',
        'allows_null' => true,
        'store_in' => 'properties'
      ];
      if (!empty($chosenProperties[$nameProperty])) {
        $field['default'] = $chosenProperties[$nameProperty];
      }

      $enums = [];
      foreach ($property->propertyEnums as $enum) {
        $enums[$enum->slug] = $enum->value;
      }
      $field['options'] = $enums;
      $extras[] = $field;
    }

    $this->crud->addFields($extras);

  }

  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
    $this->crud->setValidation(ProductRequest::class);

  }

  protected function setupShowOperation()
  {
    $this->setupListOperation();
    $this->crud->addColumns([
      [
        'name' => 'description',
        'label' => 'Описание',
        'type' => 'text',
      ],
      [
        'name' => 'image',
        'label' => "Изображение",
        'type' => 'image'
      ],
      [
        'name' => 'images',
        'label' => "Дополнительные изображения",
        'type' => 'array'
      ],
      [
        'name' => 'quantity',
        'label' => 'Количество',
        'type' => 'number'
      ],
      [
        'name' => 'brand_id',
        'label' => 'Производитель',
        'type' => 'closure',
        'function' => function($entry){
          $brand = $entry->brand->name;
          return $brand;
        }
      ],
      [
        'name' => 'wholesalePrice',
        'label' => 'Оптовые цены',
        'type' => 'closure',
        'function' => function ($entry) {
          $prices = $entry->prices;
          foreach ($prices as $price) {
            $optPrices = ['quantity' => $price->quantity, 'price' => $price->value.' '.$price->currency];
            $pr[] = implode(' шт: ', $optPrices);
          }
          if (count($pr) > 1) {
            $pr = implode('; ', $pr);
          } else {
            $pr = implode('.', $pr);
          }
          if (!empty($optPrices)) {
            return $pr;
          }
          return '';
        }
      ],
      [
        'name' => 'slug',
        'label' => 'Slug (для отображения в адресной строке)'
      ],
      [
        'name' => 'prom_id',
        'label' => 'id товара на prom.ua'
      ],
      [
        'name' => 'showcase',
        'label' => 'Ярлык',
      ]
    ]);
  }
}
