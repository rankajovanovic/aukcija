<?php

namespace App\Services;
use App\Models\Item;
use Illuminate\Support\Carbon;

class AuctionService {

  public function checkIfTheAuctionTimeHasExpired($id) {

  $item = Item::findOrFail($id);
  $itemTime = $item['end_time'];
  $timeNow = Carbon::now();

  return $timeNow->greaterThan($itemTime)  ? true : false;

  }

  public function chooseTheWinnerOfAuction($id) {

    $item = Item::with('user', 'offers')->findOrFail($id);

    $offers = $item['offers'];
    $offers_length = count($offers);
    $start = 0;
    $winner = null;

    for ($i = 0; $i < $offers_length; $i++) {
      if( $start < $offers[$i]['price'] ) {
        $start = $offers[$i]['price'];
        $winner = $offers[$i];
      }
    }

    return $winner;

  }

  public function sellItemToTheWinner($id, $winner) {

    $item = Item::findOrFail($id);

    $item['buyer_id'] = $winner['user_id'];
    $item['buy_price'] = $winner['price'];
    $item['active'] = 0;

    return $item;
  }

  public function handleItemAuction($id) {

    if( !$this->checkIfTheAuctionTimeHasExpired($id)) {
      return response()->json(['error' => 'Unable to complete auction.']);
    }

    $winner = $this->chooseTheWinnerOfAuction($id);
    $this->sellItemToTheWinner($id, $winner);

    return response()->json(['message' => 'Auction completed successfully.']);
  }
}