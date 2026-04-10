<?php

namespace App\Http\Resources;

use App\Models\company;
use App\Models\Favourite;
use App\Models\offer;
use App\Models\photo;
use Illuminate\Http\Request;
use App\Models\tourist_trips;
use Illuminate\Http\Resources\Json\JsonResource;

class TouristTripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $company=company::find($this->company->id)->first();
        $company_name=$this->company->name;
        $photo_company = $company ? photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $company->id)->first() : null;
        $touristTrip=tourist_trips::where('offer_id', $this->id)->first();
        $favourite=Favourite::where('user_id',auth()->user()->id)->where('offer_id',$this->id)->where('status','favourite')->first();
        $bool=0;
        if($favourite)
        {
            $bool=1;
        }
        return [
            'id' =>  $this->id,
          //  'idaa'=>$this->company->id,
            'companyName' => $company_name,
            'date' => $this->trip_date,
            'start_hour' => $this->start_hour,
            'source' => $this->source,
            'destination'=>$this->destination,
            'companyphotourl' => $photo_company ? $photo_company->url : 'default_url_here',
            'offer_photo'=> $photo_company=photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $this->id)->pluck('url')->first(),
            'trip_end_date' => $touristTrip ? $touristTrip->trip_end_date : null,  // تأكد من عدم وجود null
            'price' => $touristTrip ? $touristTrip->cost : null,                   // تأكد من عدم وجود null
            'capacity' => $touristTrip ? $touristTrip->capacity : null,
               'favoret'=>$bool,
               'evalution'=>$this->evalution,
              // 'station_description' => $this->tourist_trips->trip_stations,
        ];
    }
}
