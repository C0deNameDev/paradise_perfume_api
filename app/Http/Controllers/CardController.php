<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\User;
use Exception;

class CardController extends Controller
{
    public function __construct(private User $user)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(AuthRequest $req)
    {
        return ['zmar'];
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
