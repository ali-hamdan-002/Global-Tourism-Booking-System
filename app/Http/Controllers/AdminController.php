<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\offer;
use App\Models\photo;
use App\Models\wallet;
use App\Models\booking;
use App\Models\company;
use App\Traits\ImageTrait;
use App\Traits\WalletTrait;
use App\Mail\sendCodeWallet;
use App\Models\trip_station;
use Illuminate\Http\Request;
use App\Models\tourist_trips;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\CompanyCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TouristTripCollection;
use App\Http\Resources\DetailsTouristTripResource;


class AdminController extends Controller
{
    use WalletTrait;
     use ImageTrait;

    //////////////الادمن يضيف شركة///////////////
    public function add_company(Request $request)
    {
      $id=auth()->user()->id;
      $admin=company::where('admin_id', $id)->first();
      if($admin != null){
        return  response()->json(["you can't create a  company because you already have a company"]);}
      $company= new company();
      $company->name =$request->name;
      $company->location=$request->location;
      $company->descr =$request->descr;
      $company->phone =$request->phone;
      $company->admin_id =auth()->user()->id;
      $company->service_type =$request->service_type;
      $company->save();$path= 'App\\Company';
      $wallet = $this->wallet($company->id,$path);
      $admin=Admin::find($id);
      $email=$admin->email;
      Mail::to($email)->send(new sendCodeWallet($wallet));
      return response()->json([
        ["تمت اضافة الشركة بنجاح"]
        ]);
    }

    //الادمن يشوف تفاصيل الشركة تبعو
    public function show_company()
    {
        $admin_id=auth()->user()->id;
         $comppany=company::where('admin_id', $admin_id)->get();
         return response()->json(new CompanyCollection($comppany));
    }
// بروفايل الادمن
    public function show_profail(Request $request)
    {
           $id=auth()->user()->id;
           $admin=Admin::find($id);
           $company=company::where('admin_id',$id)->first();
           return response()->json([
            $admin
           ]);
    }
// تعديل بروفايل الادمن
    public function edite_profail(Request $request)
    {
              $id=auth()->user()->id;
            //   $company=company::where('admin_id',$id)->first();
            //    $company_id=$company->id;

               $admin=Admin::findOrFail($id);
               $admin->first_name = $request->first_name;
               $admin->last_name = $request->last_name;
               $admin->phone_number = $request->phone_number;
               $admin->save();

            //    $new_company=company::findOrFail($company_id);
            //    $new_company->name = $request->company_name;
            //    $new_company->location = $request->company_location;
            //    $new_company->descr = $request->company_descr;
            //    $new_company->phone = $request->company_phone;
            //    $new_company->save();

               return response()->json([
                  'message' =>' done'
               ]);
    }
    //تعديل معلومات الشركة لسا ماعملتا ع البوست مان
    public function edite_company(Request $request)
    {
        $id=auth()->user()->id;
       // return $id;
          $company=company::where('admin_id',$id)->first();
        //  return $company;
           $company_id=$company->id;
        //   return $company_id;
           $new_company=company::findOrFail($company_id);
           $new_company->name = $request->name;
           $new_company->location = $request->location;
           $new_company->descr = $request->descr;
           $new_company->phone = $request->phone;
           $new_company->save();

           return response()->json([
              'message' =>' done'
           ]);
    }

public function add_offer_trip_tourist(Request $request)
{
             $admin_id = auth()->user()->id;
            $comp = Company::where('admin_id', $admin_id)->first();

             if (!$comp || in_array($comp->status, ['pending', 'refuse'])) {
           return response()->json(['message' => 'You cannot create an offer because your company is not active'], 403);
            }
          $companie_id = $comp->id;

           $validatedData = $request->validate([
          'data' => 'required|json',
          'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           ]);

            $data = json_decode($validatedData['data'], true);

             $jsonValidator = Validator::make($data, [
         'trip_date' => 'required|date',
         'start_hour' => 'required|date_format:H:i',
         'source' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'trip_end_date' => 'required|date',
        'end_hour' => 'required|date_format:H:i',
        'cost' => 'required|numeric',
        'capacity' => 'required|integer',
        'station' => 'required|array',
        'station.*.descerp' => 'required|string|max:255',
        'station.*.stations_strat_houre' => 'required|date_format:H:i',
        'station.*.stations_end_houre' => 'required|date_format:H:i',
        'station.*.transportation' => 'required|string|max:255',
        'station.*.hotel' => 'nullable|string|max:255',
        'station.*.restaurant' => 'nullable|string|max:255',
        'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($jsonValidator->fails()) {
        return response()->json(['errors' => $jsonValidator->errors()], 422);
    }

    $offer = Offer::create([
        'trip_date' => $data['trip_date'],
        'start_hour' => $data['start_hour'],
        'source' => $data['source'],
        'destination' => $data['destination'],
        'companie_id' => $companie_id
    ]);


    $touristTrip = tourist_trips::create([
        'trip_end_date' => $data['trip_end_date'],
        'end_hour' => $data['end_hour'],
        'cost' => $data['cost'],
        'capacity' => $data['capacity'],
        'offer_id' => $offer->id
    ]);

    // Create trip stations
         foreach ($data['station'] as $station) {
          trip_station::create([
            'description' => $station['descerp'],
            'stations_strat_houre' => $station['stations_strat_houre'],
            'stations_end_houre' => $station['stations_end_houre'],
            'transportation' => $station['transportation'],
            'hotel' => $station['hotel'] ?? null,
            'restaurant' => $station['restaurant'] ?? null,
            'tourist_trip_id' => $touristTrip->id
         ]);
           }


          if ($request->hasFile('photos')) {

          foreach ($request->file('photos') as $photoFile) {

         $fileName = $this->saveImage($photoFile,'offer');
            Photo::create([
                'filename' => $fileName,
                'photoable_type' => Offer::class,
                'photoable_id' => $offer->id,
                'url' =>URL('images/offer/'.$fileName),
            ]);
         }
       }

         return response()->json(['message' => 'Offer created successfully']);
}
    // عرض جميع الرحلات
    public function show_all_trip(Request $request)
    {
     $id=auth()->user()->id;
     $comp=company::where('admin_id',$id)->first();
        $companie_id=$comp->id;
        $trip=offer::where('companie_id',$companie_id)->get();
        return $trip;
    }

    public function spec_det_trip_tourist(Request $request)
{
     $id=$request->id;
      $photo=photo::where('photoable_type','App\Models\offer')->where('photoable_id',$id )->pluck('url');
      $url=['url'=>$photo->toArray()];
      // return $url;
          $offer=offer::find($id);
            $trip_id= $offer->tourist_trips->id;
            $trip=tourist_trips::find($trip_id);
             $station=trip_station::where('tourist_trip_id',$trip_id)->get();

            return response()->json([ $offer,$trip,$station,$url ]);

}
    //  تعديل رحلة سياحية
    public function edite_trip_tourist(Request $request)
{
         $admin_id = auth()->user()->id;
         $comp = Company::where('admin_id', $admin_id)->first();

         if (!$comp || in_array($comp->status, ['pending', 'refuse'])) {
            return response()->json(['message' => 'You cannot update this offer because your company is not active'], 403);
         }
         $companie_id = $comp->id;

         // Validate request data
         $validatedData = $request->validate([
            'data' => 'required|json',
            'photos.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);

         $data = json_decode($validatedData['data'], true);
          // return $data['id'];
         $jsonValidator = Validator::make($data, [
            'trip_date' => 'required|date',
            'start_hour' => 'required|date_format:H:i',
            'source' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'trip_end_date' => 'required|date',
            'end_hour' => 'required|date_format:H:i',
            'cost' => 'required|numeric',
            'capacity' => 'required|integer',
            'station' => 'required|array',
            'station.*.descerp' => 'required|string|max:255',
            'station.*.stations_strat_houre' => 'required|date_format:H:i',
            'station.*.stations_end_houre' => 'required|date_format:H:i',
            'station.*.transportation' => 'required|string|max:255',
            'station.*.hotel' => 'nullable|string|max:255',
            'station.*.restaurant' => 'nullable|string|max:255',
            'photos.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);

         if ($jsonValidator->fails()) {
            return response()->json(['errors' => $jsonValidator->errors()], 422);
         }

         // العثور على العرض والرحلة السياحية المرتبطة به
         $offer = Offer::findOrFail($data['id']);
         $touristTrip = tourist_trips::where('offer_id', $offer->id)->firstOrFail();
         //return $offer->id;
         // تحديث بيانات العرض
         $offer->update([
            'trip_date' => $data['trip_date'],
            'start_hour' => $data['start_hour'],
            'source' => $data['source'],
            'destination' => $data['destination'],
            'companie_id' => $companie_id
         ]);

         // تحديث بيانات الرحلة السياحية
         $touristTrip->update([
            'trip_end_date' => $data['trip_end_date'],
            'end_hour' => $data['end_hour'],
            'cost' => $data['cost'],
            'capacity' => $data['capacity']
         ]);


         $touristTrip->trip_stations()->delete();
         foreach ($data['station'] as $station) {
            trip_station::create([
                'description' => $station['descerp'],
                'stations_strat_houre' => $station['stations_strat_houre'],
                'stations_end_houre' => $station['stations_end_houre'],
                'transportation' => $station['transportation'],
                'hotel' => $station['hotel'] ?? null,
                'restaurant' => $station['restaurant'] ?? null,
                'tourist_trip_id' => $touristTrip->id
            ]);
         }

         if ($request->hasFile('photos')) {

            Photo::where('photoable_id', $offer->id)
                ->where('photoable_type', Offer::class)
                ->delete();


            foreach ($request->file('photos') as $photoFile) {
                $fileName = $this->saveImage($photoFile, 'offer');
                Photo::create([
                    'filename' => $fileName,
                    'photoable_type' => Offer::class,
                    'photoable_id' => $offer->id,
                    'url' => URL('images/offer/' . $fileName),
                ]);
            }
        }


         return response()->json([
            'message' => 'Offer updated successfully',

        ]);
}
// حذف رحلة
    public function delete_offer(Request $request)
    {
            $id=$request->id;
            $photos=photo::where('photoable_type', 'App\Models\offer')->where('photoable_id', $id)->get();
            foreach($photos as $photo)
            {
                photo::findOrfail($photo->id)->delete();
            }

            offer::findOrfail($id)->delete();

            return response()->json(['delte done']);
    }

    public function show_all_user_booking_for_all_offer()
    {
        $id = auth()->user()->id;
        $company = Company::where('admin_id', $id)->first();
        $companyId=$company->id;

       $booking=booking::whereHas('offer.company',function($query) use ($companyId) {
        $query->where('id', $companyId);
     } )->get();
     $name =[];
     foreach($booking as $aa)
     {
        $user=User::where('id',$aa-> user_id)->first();
       // $name =$user->first_name;
        array_push( $name,implode(" ",[$user->first_name,$user->last_name])    );
        //echo $user->first_name  ;
        echo  "\n ";
     }
      return response()->json([$name]);
    }

    public function show_user_booking_in_offer(Request $request)
    {
        $id=$request->id;
        $booking=booking::where('offer_id', $id)->get();
        return $booking;
    }
    public function accept_bboking(Request $request)
{
            $id=$request->id;
           $booking=booking::where('id',$id)->first();
           $comp=offer::where('id',$booking->offer_id)->pluck('companie_id')->first();
          // return $comp;
         if($booking->status == 'active'){return response()->json(['message'=> 'الحجز متوافق عليه لا ضل تكبس']);}
         $aa=booking::where('offer_id',$booking->offer_id )->where('status','active')->get();
         $number=0;
         foreach($aa as $book)
         {
           $number = $number + ($book->number_of_people);
          }
         $capacity=tourist_trips::where('offer_id', $booking->offer_id)->first();
         $offer_capacity= $capacity->capacity;
         if($offer_capacity < ($number + $booking->number_of_people))
         {
            return response()->json(['message'=> "مافي محلات يا ابو الشباب"]);
         }
         $user_wallet = wallet::where('walletable_type', 'App\\User') ->where('walletable_id', $booking->user_id)->first();
         $company_wallet = wallet::where('walletable_type', 'App\Company') ->where('walletable_id', $comp)->first();
         // return $company_wallet->balance;
         if($user_wallet->balance < ( $booking->total_price))
         {
          return response()->json(['message'=> "مامعك مصاري يا ابو الشباب"]);
         }
         $booking=booking::findorFail($id);
         $booking->status='active';
         $booking->save();
         $user_wallet=wallet::findorFail($user_wallet->id);
         $user_wallet->balance -=$booking->total_price;
         $user_wallet->points +=$booking->total_price /10;
         $user_wallet->save();
         $company_wallet=wallet::findorFail($company_wallet->id);
         $company_wallet->balance +=$booking->total_price;
         $company_wallet->points +=$booking->total_price / 20;
         $company_wallet->save();
         return response()->json([
            'message' =>  'booking is active'
          ]);
}

    public function cancel_bboking(Request $request)
    {
              $id=$request->id;
              $booking=booking::findorFail($id);
              $booking->status='cancel';
              $booking->save();
              return response()->json([
                'message' =>  'booking is banned'
              ]);
    }

// حذف شركة
    public function delete_company(Request $request)
    {
            $id=auth()->user()->id;
            $companie_id=company::where('admin_id',$id)->pluck('id')->first();
            $company_wallet = wallet::where('walletable_type', 'App\Company') ->where('walletable_id', $companie_id)->first();
            $wallet_id= $company_wallet->id;
           // return $wallet_id;
           wallet::find( $wallet_id)->delete();
            company::where('admin_id',$id)->delete();
            return response()->json([['message'=>'delete done']]);
    }
    // تغير كلمة سر الادمنة
    public function change_password(Request $request)
    {
          $request->validate([
              'old_password' => 'required|string|min:6',
              'password' => 'required|string|min:6|confirmed',
          ]);
           $admin=auth()->user();
          if(Hash::check($request->old_password,$admin->password))
          {
              $hamdan=bcrypt($request->password);
              $new=Admin::findorfail($admin->id);
              $new->password=$hamdan;
              $new->save();
              return response()->json(['password is changed']);
          }
          else{
              return "password is not true";
          }
    }
/////////////////////////
public function add_image(Request $request)
{

      $id = Auth::id();
     $poto=photo::where('photoable_type','App\Models\company')->where('photoable_id', $id)->first();
     if($poto)
       {
     $filePath = public_path($poto->filename);

     $poto->delete();

     if (file_exists($filePath)) {
        unlink($filePath);
     }

       }
        $request->validate([
            'photo' => 'required|image|mimes:png,jpg,jpeg,gif',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $fileName = $this->saveImage($image, 'company');

            $photo = Photo::create([
                'filename' => 'images/company/' . $fileName,
                'photoable_type' => company::class,
                'photoable_id' => $id,
                 'url' =>  URL('images/company/' . $fileName),

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
   $photo = Photo::where('photoable_id', $id)->where('photoable_type', 'App\Models\company') ->first();
   if (!$photo) {
   return response()->json(['error' => 'Photo not found'], 404); }
     return response()->json([$photo->url]);
}


// عرض رصيد المحفظة
public function show_wallet(Request $request)
{
     $wallet_code = $request->wallet_code;
    $id = auth()->user()->id;
    $company = Company::where('admin_id', $id)->first();
    if (!$company) {
        return response()->json(['error' => 'شركة غير موجودة'], 404);
    }

    $wallet = Wallet::where('walletable_type','App\Company')->where('walletable_id', $id)->first();

    if (!$wallet || !Hash::check($wallet_code, optional($wallet)->wallet_code)) {
        return response()->json(['error' => 'الكود غير صحيح'], 404);
    }
    return response()->json([$wallet]);
}

            public function show_booking()
            {
                $id = auth()->user()->id;
                $company = Company::where('admin_id', $id)->first();
                $companyId=$company->id;

               $booking=booking::where('status','pending')->whereHas('offer.company',function($query) use ($companyId) {
                $query->where('id', $companyId);
             } )->get();
            // return "12";
                return $booking;
            }



  }


