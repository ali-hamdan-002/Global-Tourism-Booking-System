<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;



trait ImageTrait
{

     function saveimage($photo ,$folder)
    {
       //time().'_'.
        $fileName =time().'_'.$photo->getClientOriginalName();
        $path = public_path('images/' . $folder);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $photo->move($path, $fileName);

        return $fileName;


    // $fileName = time() . '_' . $photo->getClientOriginalName();
    // $path = public_path('images/' . $folder);


    // if (!file_exists($path)) {
    //     mkdir($path, 0755, true);
    // }


    // $photo->move($path, $fileName);

    // return $fileName;
    }
}

