<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\UserController;



Route::post('user/login' ,[Authcontroller::class,'user_login'])->name('userlogin');
Route::post('user/registar' ,[Authcontroller::class,'user_registar'])->name('user_registar');
Route::post('user/verify_code' ,[Authcontroller::class,'user_verify_code']);


Route::post('user/password/email' ,[Authcontroller::class,'user_forget_password']);
Route::post('user/password/code/check' ,[Authcontroller::class,'user_check_code']);
Route::post('user/password/reset' ,[Authcontroller::class,'user_reset_password']);




Route::group(['prefix' =>'user' ,'middleware'=>['auth:user-api','scope:user']],function(){
         Route::post('logout',[Authcontroller::class ,'user_logout']);
         //عرض البروفايل
         Route::post('show_profail',[UserController::class ,'show_profile']);
         //تعديل البروفايل
         Route::post('edite_profail',[UserController::class ,'edite_profile']);
         //اضافة صورة
         Route::post('add_image',[UserController::class ,'add_image']);
         //عرض الصورة
         Route::post('show_image',[UserController::class ,'show_image']);

         Route::post('show_details_of_company',[UserController::class ,'show_details_of_company']);


 Route::get('show_all_offer',[UserController::class ,'show_all_offer']);
         Route::post('change_password',[UserController::class ,'change_password']);
         Route::get('delete_account',[UserController::class ,'delete_account']);



///هبدات ابو محمد شلمونة الملقب بالزيبق
Route::get('show_all_tourist_trip',[UserController::class ,'show_all_tourist_trip']);
Route::post('show_details_of_tourist_trip',[UserController::class ,'show_details_of_tourist_trip']);
Route::get('show_tourist_trip_trends',[UserController::class ,'show_tourist_trip_trends']);
Route::get('show_tourist_trip_recommended',[UserController::class ,'show_tourist_trip_recommended']);
Route::get('show_all_company',[UserController::class ,'show_all_company']);
Route::get('show_all_company_trend',[UserController::class ,'show_all_company_trend']);


Route::post('user_booking',[UserController::class ,'user_booking']);
Route::post('user_rate_an_offer',[UserController::class ,'user_rate_an_offer']);

Route::post('user_rate_an_company',[UserController::class ,'user_rate_an_company']);
//delete and favourite
//////////
Route::post('add_to_favourite',[UserController::class ,'add_to_favourite']);
Route::get('show_favourite',[UserController::class ,'show_favourite']);
Route::post('delete_from_favourite',[UserController::class ,'delete_from_favourite']);

Route::post('add_to_delete',[UserController::class ,'add_to_delete']);
Route::get('show_delete',[UserController::class ,'show_delete']);
Route::post('delete_from_delete',[UserController::class ,'delete_from_delete']);
//////////show_level
Route::get('show_level',[UserController::class ,'show_level']);
// add_card //add_balane //show_card
Route::post('add_card',[UserController::class ,'add_card']);
Route::post('add_balane',[UserController::class ,'add_balane']);
Route::post('show_card',[UserController::class ,'show_card']);
Route::post('delete_card',[UserController::class ,'delete_card']);
Route::post('filter_trip',[UserController::class ,'filter_trip']);
Route::post('follow_trips',[UserController::class ,'follow_trips']);
});

