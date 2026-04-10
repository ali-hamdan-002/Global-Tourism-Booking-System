<?php

namespace App\Http\Controllers;


use App\Models\card;
use App\Models\User;
use App\Models\offer;
use App\Models\photo;
use App\Models\wallet;
use App\Models\booking;
use App\Models\company;
use App\Models\Favourite;
use App\Traits\ImageTrait;
use App\Models\offer_rating;
use Illuminate\Http\Request;
use App\Models\tourist_trips;
use App\Models\company_rating;
use App\Traits\OfferRateTrait;
use Illuminate\Support\Carbon;
use App\Traits\CompanyRateTrait;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\TouristTripResource;
use App\Http\Resources\ComnyDetelesResourse;
use App\Http\Resources\TouristTripCollection;
use App\Http\Resources\ComnyDetelesCollection;
use App\Http\Resources\DetailsTouristTripResource;
use App\Http\Resources\DetailsTouristTripCollection;
use App\Http\Resources\FollowBookingTripsCollection;

class UserController extends Controller
{
     use ImageTrait;
     use OfferRateTrait;
     use CompanyRateTrait;

public function edite_profile(Request $request)
{//1
            $id=  Auth::guard('user-api')->user()->id;
            $record = User::findOrFail($id);

            $data = $request->only([
                'last_name',
                'first_name',
                'email',
                'phone_number',
                'location',
                'annual_income',
                'gender',
                'country',
                'balance',
                'password',
                'birth',
            ]);
             $photo= $request->photo;
             $photo = Photo::create([
                'filename' => '/images/user/' ,
                'photoable_type' => User::class,
                'photoable_id' => $id,
                 'url' =>  $photo ,
                ]);
            $data = array_filter($data, function ($value) {
                return !is_null($value);
            });

            $record->update($data);

            return response()->json([['message' => 'done']]);
}
   public function show_profile(Request $request)
{
    $id=  Auth::guard('user-api')->user()->id;
    $profile=User::find($id);
    $created=$profile->created_at;
    $time=Carbon::parse($created);
    $age=$profile->birth;
    $user_age=Carbon::parse($age);
    $user_photo=photo::where('photoable_type','App\Models\User')->where('photoable_id',$id)->latest()->first();
    $photo=null;
    if($user_photo){
        $photo=$user_photo->url;
    }
    return response()->json([[
        'id'=>$profile->id,
        'last_name'=>$profile->last_name,
        'first_name'=>$profile->first_name,
        'email'=>$profile->email,
        'phone_number'=>$profile->phone_number,
        'location'=>$profile->location,
        'annual_income'=>$profile->annual_income,
        'gender'=>$profile->gender,
        'country'=>$profile->country,
        'balance'=>$profile->balance,
        'level'=>$profile->level,
        'boints'=>$profile->boints,
        'time_since_signup'=>$time->diffForHumans(),
        'user_age'=>$user_age->diffForHumans(),
        'photo'=>$photo,
        'birth'=>$profile->birth
    ]]);
}
public function change_password(Request $request)
{
      $request->validate([
          'old_password' => 'required|string|min:6',
          'password' => 'required|string|min:6|confirmed',
      ]);
       $user=auth()->user();
      if(Hash::check($request->old_password,$user->password))
      {
          $hamdan=bcrypt($request->password);
          $new=User::findorfail($user->id);
          $new->password=$hamdan;
          return response()->json(['password is changed']);
      }
      else{
          return "password is not true";
      }
}
public function show_all_offer(Request $request)
{
        $offer=offer::where('status','accept')->get();
        return response()->json( $offer);
}
public function delete_account()
{
        $id=auth()->user()->id;
        $user=User::find($id)->delete();
        return response()->json(['done']);
}
public function add_image(Request $request)
{
     $id = Auth::id();
    $poto=photo::where('photoable_type','App\Models\User')->where('photoable_id', $id)->first();
    if($poto)
       {
     $filePath = public_path($poto->filename);

     $poto->delete();

     if (file_exists($filePath)) {
        unlink($filePath);
     }
       }
        $request->validate([
            'profile_image' => 'required|image|mimes:png,jpg,jpeg,gif',
        ]);

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $fileName = $this->saveImage($image, 'user');

            $photo = Photo::create([
                'filename' => '/images/user/' . $fileName,
                'photoable_type' => User::class,
                'photoable_id' => $id,
                 'url' =>  URL('images/user/'.$fileName),
            ]);
            return response()->json([
                'message' => 'Photo added successfully',
            ]);
                     }
         return response()->json([
            'message' => 'No image uploaded'
        ], 400);
}
public function show_image(Request $request)
{
            $id = auth()->user()->id;
            $photo = Photo::where('photoable_id', $id)->where('photoable_type', 'App\Models\User') ->first();
           if (!$photo) {
           return response()->json(['error' => 'Photo not found'], 404);
          }
          return response()->json([$photo->url]);
}
public function show_all_tourist_trip()
{
         $offers = Offer::take(15)->get();
         return response()->json(new TouristTripCollection($offers));
}
public function show_details_of_tourist_trip(Request $request)
{
         $id = $request->id;
         $trip = offer::where('id', $id)->first();
            return response()->json(new DetailsTouristTripResource($trip));
}
//تابع حجز رحلة سياحية
public function user_booking(Request $request)
{
            $user_id = auth()->user()->id;
            $wallet_code = $request->wallet_code;

            $wallet = wallet::where('walletable_type', 'App\\User') ->where('walletable_id', $user_id)->first();

            if ( !Hash::check($wallet_code, $wallet->wallet_code)) {
                return response()->json(['message' => 'Invalid wallet code'], 403);
            }
            $no_od_people=$request->number_of_people;
            $offer_id=$request->offer_id;
            //
            $booking=booking::where('offer_id', $offer_id)->where('status','active')->get();
            $number=0;
            foreach($booking as $book)
            {
                $number = $number + ($book->number_of_people);
            }

           $capacity=tourist_trips::where('offer_id', $offer_id)->first();
            $offer_capacity= $capacity->capacity;

           if (($offer_capacity >= ($number + $no_od_people)) && ($wallet->balance >= ($capacity->cost * $no_od_people)))
           {
            booking::create([
                'offer_id' =>   $offer_id,
                'user_id' =>$user_id,
                'number_of_people' =>  $no_od_people,
                'total_price' =>$capacity->cost * $no_od_people,
                ]);
                return response()->json([
                    'message'=>'Your request is pending. If accepted, it will be deducted from your wallet'

                 ]);

           }
           return response()->json([
           'message'=>'You cannot book this flight. You may not have enough money, and perhaps the number of seats available is less than the number of seats you want.'

        ]);
}
//تابع تقيم عرض
public function user_rate_an_offer(Request $request)
{
            $existingRating = offer_rating::where('user_id',auth()->user()->id) ->where('offer_id', $request->offer_id) ->first();
       //     return "12";

           if($existingRating) {
                $qassem=offer_rating::findorfail($existingRating->id);
                $qassem->rate=$request->rate;
                $qassem->save();
                $this->rate($request->offer_id);
                return response()->json(['message'=>'done']);
             }
             offer_rating::create([
                'offer_id' =>  $request->offer_id,
                'user_id' =>auth()->user()->id,
                'rate' => $request->rate,
                'comment' =>$request->comment,
                ]);
                $this->rate($request->offer_id);
                return response()->json(['message'=>'done']);
}
 //تابع تقيم شركة
public function user_rate_an_company(Request $request)
{

            $existingRating = company_rating::where('user_id',auth()->user()->id) ->where('companie_id', $request->companie_id) ->first();
            if($existingRating) {
                $qassem=company_rating::findorfail($existingRating->id);
                $qassem->rate=$request->rate;
                $qassem->save();
                $this->rate_company($request->companie_id);
                return response()->json(['message'=>'done']);
              }
            company_rating::create([
                'companie_id' =>  $request->companie_id,
                'user_id' =>auth()->user()->id,
                'rate' => $request->rate,
                'comment' =>$request->comment,
                ]);   $this->rate_company($request->companie_id);
                return response()->json(['message'=>'done']);
}

public function show_favourite(Request $request)
        {
         //   $favourite = User::find(auth()->user()->id)->favourites()->with('tourist_trips')->get();
         $favourite = User::find(auth()->user()->id)->favourites()->with('tourist_trips')->get();

            $company = User::find(auth()->user()->id)->favourites()->with('company')->get();
            //return $company;


$a=[];
            foreach($favourite as $favourites){
                $i=0;
                $v=1;
                foreach($company as $companies){
                    $ali[]=$companies->company->name;
                }

                $favourite=Favourite::where('user_id',auth()->user()->id)->where('offer_id',$favourites['id'])->where('status','favourite')->first();
                if($favourite==null){
                    $v=0;

                }
                $a[]=["id"=>$favourites['id'],
                "date"=>$favourites['trip_date'],
                "source"=>$favourites['source'],
                "destination"=>$favourites['destination'],
                "capacity"=>$favourites['tourist_trips']['capacity'],
                "price"=>$favourites['tourist_trips']['cost'],
                "evalution"=>$favourites['evalution'],
               "companyName"=>$ali[$i],
               "favorite"=>$v,
               'photo'=>photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $favourites['id'])->pluck('url')->first() ,
               'photo_company'=>photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $favourites['id'])->pluck('url')->first() ,

            ];
            $i++;
            }
               if($a!=null){ return response()->json(
                $a
            ,200);}
            //return $a;
            else  return response()->json(
        []
            ,200);
            //return $favourite;
        }
        public function add_to_favourite(Request $request)
        {
            Favourite::create([
                'user_id'=>auth()->user()->id,
                'offer_id'=>$request->id,
                'status'=>'favourite',
            ]);
            return response()->json([
                'success'
            ],200);
        }
        public function delete_from_favourite(Request $request)
        {
            $favourite = Favourite::where('offer_id',$request->id);
            $favourite->delete();
            return response()->json([
                'success'
            ],200);
        }
        public function add_to_delete(Request $request)
        {
            Favourite::create([
                'user_id'=>auth()->user()->id,
                'offer_id'=>$request->id,
                'status'=>'delete',
            ]);
            return response()->json([
                'success'
            ],200);
        }
        public function delete_from_delete(Request $request)
        {
            $favourite = Favourite::where('offer_id',$request->id);
            $favourite->delete();
            return response()->json([
                'success'
            ],200);
        }
        public function show_delete(Request $request)
        {
            $favourite = User::find(auth()->user()->id)->deletes()->with('tourist_trips')->get();
             $company = User::find(auth()->user()->id)->deletes()->with('company')->get();
             //return $company;


 $a=[];
             foreach($favourite as $favourites){
                 $i=0;
                 $v=0;
                 foreach($company as $companies){
                     $ali[]=$companies->company->name;
                 }

                 $favourite=Favourite::where('user_id',auth()->user()->id)->where('offer_id',$favourites['id'])->where('status','delete')->first();
                 if($favourite==null){
                     $v=1;

                 }
                 $a[]=["id"=>$favourites['id'],
                 "date"=>$favourites['trip_date'],
                 "source"=>$favourites['source'],
                 "destination"=>$favourites['destination'],
                 "capacity"=>$favourites['tourist_trips']['capacity'],
                 "price"=>$favourites['tourist_trips']['cost'],
                 "evalution"=>$favourites['evalution'],
                "companyName"=>$ali[$i],
                "favorite"=>$v,
                'photo'=>photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $favourites['id'])->pluck('url')->first() ,
                'photo_company'=>photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $favourites['id'])->pluck('url')->first() ,

            ];
             $i++;
             }
                if($a!=null){ return response()->json(
                 $a
             ,200);}
             //return $a;
             else  return response()->json(
         []
             ,200);
             //return $favourite;
        }


public function show_profail(Request $request)
{
    $user=Auth::user();
    $id = auth()->user()->id;
    $profail = User::find($id);
    $created = $profail->created_at;
    $time = Carbon::parse($created);
    $age = $profail->birth;
    $user_age = Carbon::parse($age);
    $wallet = Wallet::where('walletable_id', $id)->where('walletable_type', 'App\User')->first();

    return response()->json([new UserResource($profail)]);
    if ($wallet) {

        $data = [
            "id" => $profail->id,
            "first_name" => $profail->first_name,
            "last_name" => $profail->last_name,
            "email" => $profail->email,
            "phone_number" => $profail->phone_number,
            "location" => $profail->location,
            "annual_income" => $profail->annual_income,
            "gender" => $profail->gender,
            "country" => $profail->country,
            "time since sign up" => $time->diffForHumans(),
            "user_age" => $user_age->diffForHumans(),
            "balance" => $wallet->balance, //
            "level" => $wallet->level,
            "points" => $wallet->points,
            "amount spent" => $wallet->amount_spent,
        ];

        return response()->json([$data]); //
    } else {

        return response()->json(['error' => 'محفظة المستخدم غير موجودة'], 404);
    }
}
/////////******************** */
///يشوف كل الشركات
public function show_all_company()
{
    $company=company::where('status','active')->get();
    return response()->json(new CompanyCollection($company));
}
//تفاصل شركة ما
public function show_details_of_company(Request $request)
{
        $id=$request->id;
        $company=company::where('id',$id)->get();

        return response()->json(new ComnyDetelesCollection($company));
}//Collection
//التريند
public function show_all_company_trend()
{
    $company=company::where('status','active')->take(15)->get();
    return response()->json(new CompanyCollection($company));
}
//رحلات تريند

public function show_tourist_trip_trends()
{
    $offers = Offer::with('tourist_trips.trip_stations')->take(15)->get();
    return response()->json(new TouristTripCollection($offers));
}
// ريكومندد
public function show_tourist_trip_recommended()
{
    $offers = Offer::with('tourist_trips.trip_stations')->take(15)->get();
    return response()->json(new TouristTripCollection($offers));
}
//النقتط والرصيد
public function show_level()
{
    $id=auth()->user()->id;
    $level=wallet::where('walletable_id', $id)->where('walletable_type', 'App\User')->select('points')->first();
    return response()->json([$level]);
}

///////اضافة بطاقة
public function add_card(Request $request)
{
    $card=card::where('card_number', $request->card_number)->where('type',$request->type)->where('security_code',$request->security_code)->first();
    if($card)
    {
        return response()->json(['message:'=>'البطاقة مضافة مسبقا']);
    }
    card::create([
        'card_number' =>  $request->card_number,
        'type' => $request->type,
        'security_code' =>$request->security_code,
        'user_id' =>auth()->user()->id,
        ]);
        return response()->json(['message:'=>'تمت اضافة بطاقة']);
}
//اضافة رصيد
public function add_balane(Request $request)
{
    $level=wallet::where('walletable_id', auth()->user()->id)->where('walletable_type', 'App\User')->first();
    $wallet=wallet::findorfail($level->id);
    $wallet->balance +=$request->balance;
    $wallet->save();
    return response()->json(['message:'=>'done']);
}
///عرض كل البطاقات
public function show_card()
{
    $card=card::where('user_id',auth()->user()->id)->get();
    $level=wallet::where('walletable_id', auth()->user()->id)->where('walletable_type', 'App\User')->first();
    $balance=$level->balance;
    $balance=floatval($balance);
     return response()->json([[
     'card'=>$card,
     'balance'=>$balance,]


     ]);
}
//حذف بطاقة
public function delete_card(Request $request)
{
    $card=card::find($request->id);

    if( $card){
    if($card->security_code == $request->security_code)
    {
        $card->delete();
        return response()->json(['message:'=>'done']);
    }
    return response()->json(['message:'=>'security_code is false']);}
    else{
        return response()->json(['message:'=>'no found card']);
    }
}

/////////////////////////////////////////////////////
public function filter_trip(Request $request)
{
    //$offersArray=[];
    $mincost= $request->minPrice;
    $maxcost= $request->maxPrice;
    if($mincost==null&&$maxcost==null){
        $mincost=500;
        $maxcost=30000;
    }
    if ($request->rate!=null)
    $offers = offer::where('evalution',$request->rate)->with('tourist_trips')->get();
    if ($request->rate==null)
    $offers = offer::with('tourist_trips')->get();
    $offersArray = $offers->toArray();

    if($request->sort==0)
   usort($offersArray, function ($a, $b) {
        $costA = $a['tourist_trips']['cost']; 
        $costB = $b['tourist_trips']['cost'];

        return $costB <=> $costA; 

    });
    else if($request->sort==1)
  usort($offersArray, function ($a, $b) {

        $costA = $a['tourist_trips']['cost']; 
        $costB = $b['tourist_trips']['cost'];
       return $costA <=> $costB; 
    });
    else if($request->sort==2)
   usort($offersArray, function ($a, $b) {

        $costA = $a['evalution'];
        $costB = $b['evalution'];
       return $costB <=> $costA; 
    });
    else if($request->sort==3)
   usort($offersArray, function ($a, $b) {

        $costA = $a['evalution']; 
        $costB = $b['evalution'];
       return $costA <=> $costB;
    });
    else if($request->sort==5)
  usort($offersArray, function ($a, $b) {

        $costA = $a['id']; 
        $costB = $b['id'];
       return $costB <=> $costA;
    });

    $filteredOffer = array_filter($offersArray, function ($product) use ($mincost, $maxcost) {
        return $product['tourist_trips']['cost'] > $mincost && $product['tourist_trips']['cost'] < $maxcost;
    });

$g=[];
foreach($filteredOffer as $filteredOffers){
    $i=0;
    $v=1;
    foreach($filteredOffer as $filteredOfferss){
        $b[] = company::where('id', $filteredOfferss['companie_id'])->first()->name;

    }
    $favourite=Favourite::where('user_id',auth()->user()->id)->where('offer_id',$filteredOffers['id'])->where('status','favourite')->first();
    if($favourite==null){
        $v=0;
    }
    $g[]=["id"=>$filteredOffers['id'],
     "date"=>$filteredOffers['trip_date'],
     "source"=>$filteredOffers['source'],
     "destination"=>$filteredOffers['destination'],
     "capacity"=>$filteredOffers['tourist_trips']['capacity'],
     "price"=>$filteredOffers['tourist_trips']['cost'],
     "evalution"=>$filteredOffers['evalution'],
    "companyName"=>$b[$i],
    "favorite"=>$v,
    'photo'=>photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $filteredOffers['id'])->pluck('url')->first() ,
    'photo_company'=>photo::where('photoable_type', 'App\Models\company')->where('photoable_id', $filteredOffers['id'])->pluck('url')->first() ,



];
$i++;
}

return $g;
}


public function follow_trips()
{

             $id=auth()->user()->id;
             $booking_pending=booking::where('status','pending')->whereHas('user',function($query) use ($id) {
             $query->where('id', $id);} )->get();
             $booking_active=booking::where('status','active')->whereHas('user',function($query) use ($id) {
             $query->where('id', $id);} )->get();
             $booking_cancel=booking::where('status','cancel')->whereHas('user',function($query) use ($id) {
             $query->where('id', $id);} )->get();


return response()->json([['accepted' => new FollowBookingTripsCollection($booking_active),
'cancel' => new FollowBookingTripsCollection($booking_cancel),
'pending' => new FollowBookingTripsCollection($booking_pending),
]]);

}

}






