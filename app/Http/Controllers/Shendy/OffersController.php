<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OffersController extends Controller
{
 public function index()
{
    return view('offers.index');
}

    public function create()
    {
        return view('offers.create');
    }
      public function show($id)
    {
        return view('offers.show', compact('id'));
    }
     public function edit($id)
    {
        return view('offers.edit', compact('id'));
    }
    public function delete($id)
    {
        //
    }
    public function followup($offerId){
        return view('offers.followUp', compact('offerId'));
    }
    public function offerStatus(Offer $offer)
    {
        return view('offers.offer-status', compact('offer'));
    }

}
    
