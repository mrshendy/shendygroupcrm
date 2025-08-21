<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OffersController extends Controller
{
      function __construct(){
        $this->middleware('permission:offer-list|offer-create|offer-edit|offer-delete', ['only' => ['index','store']]);
        $this->middleware('permission:offer-create', ['only' => ['create','store']]);
        $this->middleware('permission:offer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:offer-delete', ['only' => ['destroy']]);
    }
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
    
