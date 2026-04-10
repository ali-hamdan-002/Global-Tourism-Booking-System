<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\photo;
use App\Models\wallet;
use Illuminate\Http\Request;
use App\Http\Resources\WalletResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $created = $this->created_at;
        $time = Carbon::parse($created);
        $age = $this->birth;
        $user_age = Carbon::parse($age);
        $wallet = Wallet::where('walletable_id', $this->id)->where('walletable_type', 'App\User')->first();
        $photo=photo::where('photoable_type', 'App\\User')->where('photoable_id', $this->id)->first();
        $url = $photo ? $photo->url : 'no_found_photo';

        return [
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "phone_number" => $this->phone_number,
            "location" => $this->location,
            "annual_income" => $this->annual_income,
            "gender" => $this->gender,
            "country" => $this->country,
            "time since sign up" => $time->diffForHumans(),
            "user_age" => $user_age->diffForHumans(),
           'balance'=>$wallet->balance,
           "level" => $wallet->level,
           "points" => $wallet->points,
           "amount spent" => $wallet->amount_spent,
           'url'=>$url,




            // 'wallet' => new WalletResource($this->whenLoaded('wallet')),
        ];
    }
}
