<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBottleRequest;
use App\Http\Requests\UpdateBottleRequest;
use App\Http\Resources\BottleResource;
use App\Models\Bottle;
use App\Models\bottle_order;
use App\Models\Order;
use Exception;

class BottleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private ImageKitProvider $imageKitProvider, private Bottle $bottle, private bottle_order $bottle_order, private Order $order)
    {
    }

    public function index()
    {
        $bottles = Bottle::all(['id', 'volume', 'picture', 'price']);

        return $this->sendResponse('', $bottles);
    }

    public function get_by_order($order_id)
    {
        try {
            $bottles_coll = [];
            $order = $this->order::find($order_id);
            if ($order) {
                $bottles_status = [];
                $bottles_quant = [];
                foreach ($order->bottles as $bottle) {
                    $status = $bottle->pivot->status;
                    $quantity = $bottle->pivot->quantity;
                    array_push($bottles_coll, $bottle);
                    array_push($bottles_status, $status);
                    array_push($bottles_quant, $quantity);
                }
            }
            // foreach ($bottles as $bottle) {
            //     $pivot = $this->bottle_order::where('bottle_id', $bottle->id)->where('order_id', $order_id)->first();
            //     // dd($pivot);
            //     if ($pivot) {
            //         $bottles_status = [];
            //         $bottles_quant = [];
            //         $status = $pivot->status;
            //         $quantity = $pivot->quantity;
            //         array_push($bottles_coll, $bottle);
            //         array_push($bottles_status, $status);
            //         array_push($bottles_quant, $quantity);
            //         // dd($pivot->quantity);
            //         // for ($i = 0; $i < $pivot->quantity; $i++) {
            //         // }
            //     }
            // }
            // print_r($bottles_coll);

            // return;
            // return $this->sendResponse('', );
            return $this->sendResponse('', ['bottles' => BottleResource::collection($bottles_coll), 'quantities' => $bottles_quant, 'status' => $bottles_status]);
        } catch (Exception $e) {
            return $this->sendError('error while fetching bottles', $e->getMessage(), 500);
        }

    }

    public function get_by_id($bottle_id)
    {
        $bottle = $this->bottle::find($bottle_id);
        if (! $bottle) {
            return $this->sendError('bottle not found', '', 404);
        }

        return $this->sendResponse('bottle found', new BottleResource($bottle));
    }

    public function get_bottle_picture($bottle_id)
    {
        $bottle = $this->bottle::find($bottle_id);
        if (! $bottle) {
            return $this->sendError('bottle not found', '', 404);
        }

        return $this->sendResponse('picture found', $this->imageKitProvider->get_bottle_picture($bottle->picture));
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
    public function store(StoreBottleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bottle $bottle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bottle $bottle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBottleRequest $request, Bottle $bottle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bottle $bottle)
    {
        //
    }
}
