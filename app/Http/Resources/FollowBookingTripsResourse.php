<?php

namespace App\Http\Resources;

use App\Models\booking;
use App\Models\offer;
use App\Models\photo;
use App\Models\company;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\tourist_trips;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowBookingTripsResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         $company_id=offer::where('id',$this->offer_id)->pluck('companie_id')->first();
         $company_name=company::where('id',$company_id)->pluck('name')->first();
         $photo_company = $company_id ? photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $company_id)->first() : null;
          $touristTrip=tourist_trips::where('offer_id', $this->offer_id)->first();
          $favourite=Favourite::where('user_id',auth()->user()->id)->where('offer_id',$this->offer_id)->where('status','favourite')->first();
         $bool=0;
         if($favourite)
         {
             $bool=1;
         }
         return [
             'id' =>  $this->id,
             'offer_id' =>  $this->offer_id,
               'companyName' => $company_name,
             'date' => $this->offer->trip_date,
             'start_hour' => $this->offer->start_hour,
             'source' => $this->offer->source,
             'destination'=>$this->offer->destination,
             'companyphotourl' => $photo_company ? $photo_company->url : 'default_url_here',
             'offer_photo'=> $photo_company=photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $this->offer->id)->pluck('url')->first(),
             'trip_end_date' => $touristTrip ? $touristTrip->trip_end_date : null,  // تأكد من عدم وجود null
             'price' => $touristTrip ? $touristTrip->cost : null,                   // تأكد من عدم وجود null
             'capacity' => $touristTrip ? $touristTrip->capacity : null,
               'favoret'=>$bool,
               'evalution'=>$this->offer->evalution,
               'evalution_company'=>$this->offer->company->evaluation,
                'people_booking_in_offer'=>booking::where('offer_id',$this->offer_id)->where('status','active')->count(),
        ];
    }
}
