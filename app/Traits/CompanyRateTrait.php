<?php

namespace App\Traits;

use App\Models\booking;
use App\Models\company;
use App\Models\company_rating;
use App\Models\offer;
use App\Models\offer_rating;
use App\Models\wallet;



trait CompanyRateTrait
{

     function rate_company($id)
    {
           $sum=company_rating::where('companie_id',$id)->sum('rate');
          $no=company_rating::where('companie_id',$id)->count();$av=0;
           if( $no>0)
           {
            $av=$sum / $no;

            $offer=company::findorfail($id);
            $offer->evaluation =$av;
            $offer->save();
           }
          // return $no;
    }
}
