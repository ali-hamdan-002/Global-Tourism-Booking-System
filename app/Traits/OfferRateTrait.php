<?php

namespace App\Traits;

use App\Models\booking;
use App\Models\offer;
use App\Models\offer_rating;
use App\Models\wallet;



trait OfferRateTrait
{

     function rate($id)
    {
           $sum=offer_rating::where('offer_id',$id)->sum('rate');
          $no=offer_rating::where('offer_id',$id)->count();$av=0;
           if( $no>0)
           {
            $av=$sum / $no;

            $offer=offer::findorfail($id);
            $offer->evalution=$av;
            $offer->save();
           }
          // return $no;
    }
}
