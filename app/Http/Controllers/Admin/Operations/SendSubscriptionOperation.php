<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Subscriber;
use App\Models\Subscription;
use Illuminate\Support\Facades\Route;

trait SendSubscriptionOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupSendSubscriptionRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/sendsubscription/{entityId}', [
            'as'        => $routeName.'.sendsubscription',
            'uses'      => $controller.'@sendsubscription',
            'operation' => 'sendsubscription',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupSendSubscriptionDefaults()
    {
        $this->crud->allowAccess('sendsubscription');

        $this->crud->operation('sendsubscription', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'sendsubscription', 'view', 'admin.buttons.sendsubscription');
        });
    }


    public function sendsubscription($id)
    {
        $this->crud->hasAccessOrFail('sendsubscription');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? 'sendsubscription '.$this->crud->entity_name;

        $subscription = Subscription::findOrFail($id);
        $subscribers = Subscriber::where('status', 1)->get();
        $subscription->subscribers = $subscribers->pluck('id');
        $subscription->status = Subscription::PROCESS_STAGE;
        $subscription->save();

        // load the view
        return redirect()->route('subscription.index');
    }
}
