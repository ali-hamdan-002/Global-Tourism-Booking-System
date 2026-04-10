<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\company;
use App\Models\offer;
use App\Models\User;
use Illuminate\Http\Request;

class SpuerAdmin extends Controller
{
    //
    public function show_user()
    {
        $user=User::get();
        return $user;
    }

    public function show_companies()
    {
        $companies=company::get();
        return $companies;
    }
    public function show_admin()
    {
        $Admin=Admin::where('role','admin')->get();
        return $Admin;
    }
    public function show_companies_pending()
    {
        $companies=company::where('status','pending')->get();
        return $companies;
    }
    public function show_companies_block()
    {
        $companies=company::where('status','block')->get();
        return $companies;
    }
    public function show_companies_active()
    {
        $companies=company::where('status','active')->get();
        return $companies;
    }
    public function show_companies_refuse()
    {
        $companies=company::where('status','refuse')->get();
        return $companies;
    }
    public function accept_company(Request $request)
    {
      $id=$request->id;
      $company=company::findOrFail($id);
      $company->status='active';
      $company->save();

      return response()->json(['active']);
    }
    public function refuse_company(Request $request)
    {
      $id=$request->id;
      $company=company::findOrFail($id);
      $company->status='refuse';
      $company->save();
      return response()->json(['refuse']);
    }
    public function block_company(Request $request)
    {
      $id=$request->id;
      $company=company::findOrFail($id);
      $company->status='block';
      $company->save();
      return response()->json(['block']);
    }

    public function show_offers()
    {
        $offers=offer::with('tourist_trips.trip_stations')->get();
        return $offers;
    }
    public function show_offers_pending()
    {
        $offers=offer::where('status','pending')->get();
        return $offers;
    }
    public function show_offers_accept()
    {
        $offers=offer::where('status','accept')->get();
        return $offers;
    }
    public function show_offers_refuse()
    {
        $offers=offer::where('status','refuse')->get();
        return $offers;
    }
    public function accept_offer(Request $request)
 {
    $id=$request->id;
    $offer=offer::findOrFail($id);
    $offer->status='accept';
    $offer->save();
    return response()->json(['accept']);
 }
    public function refuse_offer(Request $request)
   {
    $id=$request->id;
    $offer=offer::findOrFail($id);
    $offer->status='refuse';
    $offer->save();
    return response()->json(['refuse']);
   }

  public function block_user(Request $request )
  {
    $id=$request->id;
    $offer=User::findOrFail($id);
    $offer->status='block';
    $offer->save();
    return response()->json(['block']);
  }
  public function un_block_user(Request $request )
  {
    $id=$request->id;
    $offer=User::findOrFail($id);
    $offer->status='active';
    $offer->save();
    return response()->json(['active']);
  }
  public function show_user_blooked()
  {
    $companies=User::where('status','block')->get();
    return $companies;
  }
  public function show_user_active()
  {
    $companies=User::where('status','active')->get();
    return $companies;
  }


}
