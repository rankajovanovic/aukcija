<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserOffers()
    {
        $user = Auth::user();
        $offers = $user->offers()->with('item')->get();

        return response()->json($offers);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
            'price' => 'required'
        ]);
            
        $item = Item::findOrFail($data['item_id']);
        $minPrice = $item['price'];
        if( $minPrice >= $data['price'] ) {
            return response()->json([
                'error' => 'Your bid must be greater than the bid'
            ]);        
        }
        $check = Offer::where('user_id', '=', $data['user_id'])->where('item_id', '=', $data['item_id'])->get();

        if($check) {
           return response()->json(['error' => 'The offer already exists for this item']); 
        }
        
        $offer = Offer::create($data);

        return response()->json($offer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        return response()->json($offer);  
    }
}
