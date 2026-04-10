<?php

namespace App\Http\Resources;

use App\Models\offer;
use App\Models\photo;
use App\Models\company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $company=company::find($this->id)->first();

        $photo_company = $company ? photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $this->id)->pluck('url')->first() : null;
    //     $offer=offer::where('companie_id',$this->id)->get();
    //  // $url=$photo_company->url;
    //  $tt=new TouristTripCollection($offer);
    //  // return response()->json(new TouristTripCollection($offers));
        return [
        'id'=>$this->id,
        'name'=>$this->name,
        "location"=> $this->location,
        "descr"=> $this->descr,
        "phone"=> $this->phone,
        "evaluation"=> $this->evaluation,
        "service_type"=> $this->service_type,
        "status"=> $this->status,
        'photo'=>$photo_company,
        // 'offer'=>$tt

        ];
    }
}
