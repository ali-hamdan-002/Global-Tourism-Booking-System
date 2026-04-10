<?php

namespace App\Traits;

use App\Models\wallet;



trait WalletTrait
{

     function wallet($id,$path)
    {
        $wallet_code = mt_rand(100000, 999999);

        $wallet=wallet::create([
            'points' => 0,
            'level' => '0',
            'wallet_code' =>bcrypt($wallet_code),
            'walletable_id'=>$id,
            'walletable_type'=>$path,
          ]);
          return $wallet_code;
    }
}
