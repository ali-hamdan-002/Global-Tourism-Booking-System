<?php

namespace App\Http\Resources;

use App\Models\offer;
use App\Models\photo;
use App\Models\company;
use App\Models\company_rating;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\tourist_trips;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsTouristTripResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $favourite = Favourite::where('user_id', auth()->user()->id)->where('offer_id', $this->id)->where('status', 'favourite')->first();
        $bool = $favourite ? 1 : 0;
        $delete = Favourite::where('user_id', auth()->user()->id)->where('offer_id', $this->id)->where('status', 'delete')->first();
        $hid = $delete ? 1 : 0;
        $peoble_rate=company_rating::where('companie_id',$this->company->id)->count();
        return [
            'id' =>  $this->id,
            'id_company'=>$this->company->id,
            'company_name' => company::where('id',$this->companie_id)->pluck('name')->first(),
            'trip_date' => $this->trip_date,
            'start_hour' => $this->start_hour,
            'source' => $this->source,
            'destination' => $this->destination,
            'evalution' => $this->evalution,
            'trip_end_date' =>  $this->tourist_trips->trip_end_date,
            'end_hour' =>  $this->tourist_trips->end_hour,
            'capacity' => $this->tourist_trips->capacity,
            'cost' => $this->tourist_trips->cost,
            'favoret' => $bool,
            'delete'=>$hid,
            'evalution_company'=>$this->company->evaluation,
            'peoble_rate'=>company_rating::where('companie_id',$this->company->id)->count(),
            'companyphotourl' => photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $this->company->id)->pluck('url')->first(),
            'offer_photo' => photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $this->id)->pluck('url'),
            'station_description' => $this->tourist_trips->trip_stations , // assuming trip_stations is a relation
        ];
    }
}



