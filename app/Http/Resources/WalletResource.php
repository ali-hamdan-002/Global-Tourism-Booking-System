<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

        'balance' => $this->balance,
            'level' => $this->level,
            'points' => $this->points,
           ' amount spent' => $this->amount_spent,
        ];
    }
}
