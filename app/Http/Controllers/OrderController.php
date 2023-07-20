<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Bottle;
use App\Models\BottleOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\Perfume;
use DB;
use Exception;

class OrderController extends Controller
{
    public function __construct(
        private Client $client,
        private Order $order,
        private BottleOrder $bottleOrder
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $client = $this->client::find($request->input('client_id'));
            // $user = User::find($request->input('user_id'));
            if (! $client) {
                return $this->sendError('Client not found', '', 404);
            }

            $perfume = Perfume::find($request->input('perfume'));
            if (! $perfume) {
                return $this->sendError('Perfume with ID '.$request->input('perfume').' not found', '', 404);
            }

            // New order
            $order = new Order;

            // Associate the client
            $order->client()->associate($client->id);

            // Associate the perfume
            $order->perfume()->associate($perfume->id);

            $order->save();

            $bottles_list = $request->input('bottles');
            $quantities_list = $request->input('quantities');

            for ($i = 0; $i < count($bottles_list); $i++) {
                $bottle = Bottle::find($bottles_list[$i]);
                if (! $bottle) {
                    return $this->sendError('Bottle with ID '.$bottles_list[$i].' not found', '', 404);
                }
                $order->bottles()->attach($bottle->id, ['quantity' => $quantities_list[$i]]);
            }

            $order->save();

            DB::commit();

            return $this->sendResponse('order placed', new OrderResource($order));

        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception and return an error response
            return $this->sendError('Error occurred while storing the order', $e->getMessage(), 500);
        }
    }

    public function get_client_orders($client_id)
    {
        $client = $this->client::find($client_id);

        if (! $client) {
            return $this->sendError('client not found', '', 404);
        }

        return $this->sendResponse('', (OrderResource::collection($client->orders()->get())));

    }

    public function mark_prepared($order_id, $bottle_id)
    {
        try {
            $order = $this->order::find($order_id);
            if (! $order) {
                return $this->sendError('order not found', '', 404);
            }
            $order->bottles()->updateExistingPivot($bottle_id, ['status' => 'prepared']);

            return $this->sendResponse('marked as prepared');

        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function mark_pending($order_id, $bottle_id)
    {
        try {
            $order = $this->order::find($order_id);
            if (! $order) {
                return $this->sendError('order not found', '', 404);
            }
            $order->bottles()->updateExistingPivot($bottle_id, ['status' => 'pending']);

            return $this->sendResponse('marked as pending');

        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function delete_bottle_from_order($order_id, $bottle_id)
    {
        try {
            $order = $this->order::find($order_id);
            if (! $order) {
                return $this->sendError('order not found', '', 404);
            }
            $order->bottles()->detach($bottle_id);

            return $this->sendResponse('deleted');

        } catch (Exception $e) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($order_id)
    {
        $order = Order::find($order_id);

        if ($order) {
            // Detach any related bottles from the pivot table
            $order->bottles()->detach();

            // Delete the order
            $order->delete();

            // Return a success response or perform further actions
            return $this->sendResponse('Order deleted successfully');

        } else {
            // Handle error if order is not found
            return $this->sendError('Order not found', '', 404);
        }
    }
}
