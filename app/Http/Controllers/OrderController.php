<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Bottle;
use App\Models\bottle_order;
use App\Models\BottleOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\Perfume;
use App\Models\User;
use DB;
use Exception;

class OrderController extends Controller
{
    public function __construct(
        private Client $client,
        private Order $order,
        private User $user,
        private BottleOrder $bottleOrder,
        private Perfume $perfume,
        private bottle_order $bottle_order,
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

            $perfume = Perfume::find($request->input('perfume_id'));
            if (! $perfume) {
                return $this->sendError('Perfume with ID '.$request->input('perfume_id').' not found', '', 404);
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

        } catch (Exception $e) {
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

        return $this->sendResponse('ok', (OrderResource::collection($client->orders()->get())));

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

            $pivot = $this->bottle_order::where('bottle_id', $bottle_id)->where('order_id', $order_id)->first();

            if ($pivot) {
                if ($pivot->quantity === 1) {
                    $order->bottles()->detach($bottle_id);
                } else {
                    DB::table('bottle_order')
                        ->where('order_id', $order_id)
                        ->where('bottle_id', $bottle_id)
                        ->update(['quantity' => $pivot->quantity - 1]);

                }
            }

            return $this->sendResponse('deleted');

        } catch (Exception $e) {
            return $this->sendError('error', $e->getMessage(), 500);
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

    public function get_by_client($user_id)
    {
        $user = $this->user::find($user_id);
        if (! $user) {
            return $this->sendError('User not found', '', 404);
        }

        $client = $user->person;
        if (! $client) {
            return $this->sendError('client not found', '', 404);
        }

        $orders = $client->orders()->get();
        $orders_coll = [];

        foreach ($orders as $order) {
            $total_price = 0;
            $perfume = $this->perfume::find($order->perfume_id);
            if (! $perfume) {
                return $this->sendError('perfume not found', '', 404);
            }

            $bottlesIds = [];
            // $bottles_status = [];
            // $bottles_quant = [];
            foreach ($order->bottles as $bottle) {

                $quantity = $bottle->pivot->quantity;
                $status = $bottle->pivot->status;
                // dd($quantity);
                $total_price += ($bottle->price + (($bottle->price * $perfume->extra_price) / 100)) * $quantity;
                array_push($bottlesIds, $bottle->id);
                // array_push($bottles_quant, $quantity);
                // array_push($bottles_status, $status);
                // for ($i = 0; $i < $quantity; $i++) {
                // }
            }

            array_push($orders_coll, [
                'id' => $order->id,
                'perfume_id' => $perfume->id,
                'status' => $order->status,
                'total_price' => $total_price,
                'bottles' => $bottlesIds,
                // 'bottles_quantities' => $bottles_quant,
                // 'bottles_status' => $bottles_status,
            ]);
        }
        // dd($orders_coll);

        return $this->sendResponse('orders retrieved', $orders_coll);
        /**
         * {
         *  perfume_id -> integer,
         *  bottles -> array of integers
         *  total_price -> double
         *  status
         * } */
    }
}
