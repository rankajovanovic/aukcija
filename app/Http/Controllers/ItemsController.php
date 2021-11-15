<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemQuery = Item::query();
        $itemQuery->with('user', 'offers');

        $search = $request->header('searchText');
        $itemQuery->where( function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orwhereHas('user', function($que) use ($search) {
                    $que->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                });
        });

        $itemQuery->where('active', 1);
        $items = $itemQuery->orderByDesc('created_at')->take($request->header('pagination'))->get();
        $count = $itemQuery->count();
    
        return [$items, $count];
    }

       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $data = $request->validated();
        $data['end_time'] = Carbon::now()->addDays(10);
        $item = Item::create($data);

        return response()->json($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::with('user', 'offers')->findOrFail($id);
        
        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json($item);    
    }

    public function getUserBuyItems() 
    {
        $items = Item::where('buyer_id', '=', Auth::user()->id)->get();

        return response()->json($items);
    }

    public function getUserSoldItems() 
    {
        $items = Item::where('user_id', '=', Auth::user()->id)
                        ->where('active', '=',  '0')
                        ->get();

        return response()->json($items);
    }
}
