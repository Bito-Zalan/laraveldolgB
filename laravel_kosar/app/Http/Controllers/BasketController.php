<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function index()
    {
        return response()->json(Basket::all());
    }

    public function show($user_id, $item_id)
    {
        $basket = Basket::where('user_id', $user_id)
            ->where('item_id', "=", $item_id)
            ->get();
        return $basket[0];
    }

    public function store(Request $request)
    {
        $item = new Basket();
        $item->user_id = $request->user_id;
        $item->item_id = $request->item_id;

        $item->save();
    }

    public function update(Request $request, $user_id, $item_id)
    {
        $item = $this->show($user_id, $item_id);
        $item->user_id = $request->user_id;
        $item->item_id = $request->item_id;

        $item->save();
    }

    public function destroy($user_id, $item_id)
    {
        $this->show($user_id, $item_id)->delete();
    }
    public function showBasketWithUserData()
    {
        $user = Auth::user();


        if (!$user) {
            return response()->json(['message' => 'Felhasználó nem található.'], 404);
        }


        $basket = Basket::with('product')
            ->where('user_id', $user->id)
            ->get();


        if ($basket->isEmpty()) {
            return response()->json([
                'message' => 'A felhasználónak nincs kosara.',
                'user' => $user,
                'basket' => []
            ]);
        }

        return response()->json([
            'user' => $user,
            'basket' => $basket
        ]);
    }
    public function showBasketForYear($user_id, $year)
    {

        $basket = Basket::with('product')
            ->where('user_id', $user_id)
            ->get();

        if ($basket->isEmpty()) {
            return response()->json([
                'message' => 'A felhasználónak nincs kosara.',
                'basket' => []
            ]);
        }

        $filteredBasket = $basket->filter(function ($item) use ($year) {
            return $item->product->created_at->year == $year;
        });

        if ($filteredBasket->isEmpty()) {
            return response()->json([
                'message' => 'Nincs termék a kosárban az adott évhez.',
                'basket' => []
            ]);
        }

        return response()->json([
            'user_id' => $user_id,
            'year' => $year,
            'basket' => $filteredBasket
        ]);
    }
}
