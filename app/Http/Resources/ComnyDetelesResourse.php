<?php

namespace App\Http\Resources;

use App\Models\offer;
use App\Models\photo;
use App\Models\company;
use App\Models\company_rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComnyDetelesResourse extends JsonResource
{

    public function toArray(Request $request): array
    {
        $company=company::find($this->id)->first();
        $photo_company = $company ? photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $this->id)->pluck('url')->first() : null;
        $offer=offer::where('companie_id',$this->id)->get();
        $tt=new TouristTripCollection($offer);
        $ww=company_rating::where('companie_id',$this->id)->count();
         return [
        'id'=>$this->id,
        'name'=>$this->name,
        "location"=> $this->location,
        "descr"=> $this->descr,
        "phone"=> $this->phone,
        "evaluation"=> $this->evaluation,
        "service_type"=> $this->service_type,
        "status"=> $this->status,
        'people_rate'=>$ww,
        'photo'=> $company ? photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $this->id)->pluck('url')->first() : null,
         'offer'=>$tt,


        ];
    }
}
