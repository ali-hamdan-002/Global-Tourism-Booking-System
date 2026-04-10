<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\AdminController;



Route::post('admin/login' ,[Authcontroller::class,'admin_login'])->name('admin_login');
Route::post('admin/registar' ,[Authcontroller::class,'admin_registar'])->name('admin_registar');

Route::post('admin/verify_code' ,[Authcontroller::class,'admin_verify_code']);


Route::post('admin/password/email' ,[Authcontroller::class,'admin_forget_password']);
Route::post('admin/password/code/check' ,[Authcontroller::class,'admin_check_code']);
Route::post('admin/password/reset' ,[Authcontroller::class,'admin_reset_password']);

Route::group(['prefix' =>'admin' ,'middleware'=>['auth:admin-api','scopes:admin']],function(){
         Route::post('logout',[Authcontroller::class ,'admin_logout']);
              //اضافة شركة
         Route::post('add_company',[AdminController::class ,'add_company']);
              //الادمن يشوف تفاصيل شركتو
         Route::post('show_company',[AdminController::class ,'show_company']);
              //الادمن يضيف   رحلة سياحية
         Route::post('make_trip' ,[AdminController::class,'make_trip'])->name('make_trip');
             //الادمن يعرض الرحلات يلي عندو
         Route::post('show_all_trip' ,[AdminController::class,'show_all_trip'])->name('show_all_trip');
         //
         Route::post('spec_det_trip' ,[AdminController::class,'spec_det_trip'])->name('spec_det_trip');


         Route::post('change_password' ,[AdminController::class,'change_password']);
         Route::post('show_profail' ,[AdminController::class,'show_profail']);
         Route::post('edite_profail' ,[AdminController::class,'edite_profail']);
         Route::post('add_offer_trip_tourist' ,[AdminController::class,'add_offer_trip_tourist']);
         Route::post('spec_det_trip_tourist' ,[AdminController::class,'spec_det_trip_tourist']);
         Route::post('delete_offer' ,[AdminController::class,'delete_offer']);
         Route::post('edite_trip_tourist' ,[AdminController::class,'edite_trip_tourist']);
         Route::post('delete_company' ,[AdminController::class,'delete_company']);
         Route::post('accept_bboking' ,[AdminController::class,'accept_bboking']);
         Route::post('cancel_bboking' ,[AdminController::class,'cancel_bboking']);

         Route::post('add_photo_to_company' ,[AdminController::class,'add_image']);
         Route::post('show_image' ,[AdminController::class,'show_image']);
         //show_wallet
         Route::post('show_wallet' ,[AdminController::class,'show_wallet']);
         //cancel_bboking
        //  edite_company
        // show_user_booking_in_offer
        Route::post('edite_company' ,[AdminController::class,'edite_company']);
        Route::post('show_user_booking_in_offer' ,[AdminController::class,'show_user_booking_in_offer']);
         Route::post('show_all_user_booking_for_all_offer' ,[AdminController::class,'show_all_user_booking_for_all_offer']);




         Route::post('show_booking' ,[AdminController::class,'show_booking']);
});

//Route::post('spec_det_trip_tourist' ,[AdminController::class,'spec_det_trip_tourist']);




