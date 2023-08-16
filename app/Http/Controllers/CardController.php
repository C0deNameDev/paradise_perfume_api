<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Models\Admin;
use App\Models\Card;
use App\Models\SuperAdmin;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function __construct(private Card $card, private User $user)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(AuthRequest $req)
    {

        return ['zmar'];
    }

    public function get_by_client($user_id)
    {
        $user = $this->user::find($user_id);
        if (! $user) {
            return $this->sendError('user not found', '', 404);

        }

        $client = $user->person;

        dd($client->cards()->get());
    }

    public function get_auth()
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (! $user) {
                return $this->sendError('user not found', '', 404);

            }

            $client = $user->person;

            return $this->sendResponse('cards retrieved', CardResource::collection($client->cards()->orderBy('payed', 'asc')->get()));

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function get_card_info($card_id)
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (! ($user->person_type === SuperAdmin::class || $user->person_type === Admin::class)) {
                $user_cards = $user->person->cards()->get();
                if (! $user_cards->contains('id', $card_id)) {
                    return $this->sendError('this card does not belong to you', '', 403);
                }
            }

            $card = $this->card::find($card_id);
            $orders = $card->orders()->get()->where('status', 'closed');
            $orders_result = [];

            $orders_number = count($orders);

            for ($i = 0; $i < ((int) env('max_buys_per_card') - $orders_number); $i++) {
                array_push($orders_result, [
                    'order_id' => 0,
                    'perfume_id' => 0,
                    'perfume_name' => '0',
                    'total_price' => 0,
                    'payment_date' => 0,
                ]);
            }
            foreach ($orders as $order) {
                $total_price = 0;
                $perfume = $order->perfume;

                foreach ($order->bottles()->get() as $bottle) {
                    $total_price += ($bottle->price + ($bottle->price * $perfume->extra_price) / 100);
                }

                array_push($orders_result, [
                    'order_id' => $order->id,
                    'perfume_id' => $perfume->id,
                    'perfume_name' => $perfume->name,
                    'total_price' => $total_price,
                    'payment_date' => $order->updated_at,
                ]);
            }

            return $this->sendResponse('info retrieved', ['card_id' => $card->id, 'orders' => $orders_result]);
        } catch (Exception $e) {
            return $this->sendError('internal server error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCardRequest $request)
    {

        try {
            $user = $this->user::find($request->input('user_id'));

            if (! $user) {
                return $this->sendError('user not found', '', 404);
            }

            $client = $user->person;

            $client->cards()->save(new Card(['payed' => $request->input(('payed'))]));

            return $this->sendResponse('card created');
        } catch (Exception $e) {
            return $this->sendError('internal server error', $e->getMessage(), 500);
        }

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        //
    }
}
