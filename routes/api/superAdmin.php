<?php

use Illuminate\Http\Request;
use App\Http\Controllers\SpuerAdmin;
use Illuminate\Support\Facades\Route;








Route::post('show_offers_spec_companie' ,[SpuerAdmin::class,'show_offers_spec_companie']);
//////////////////////////////
Route::post('show_companies' ,[SpuerAdmin::class,'show_companies']);

Route::post('show_offers' ,[SpuerAdmin::class,'show_offers']);
Route::group(['prefix' =>'super' ,'middleware'=>['auth:admin-api','scopes:admin','super']],function(){

Route::get('show_user' ,[SpuerAdmin::class,'show_user']);
Route::get('show_companies' ,[SpuerAdmin::class,'show_companies']);
Route::get('show_admin' ,[SpuerAdmin::class,'show_admin']);
Route::get('show_companies_pending' ,[SpuerAdmin::class,'show_companies_pending']);
Route::get('show_companies_block' ,[SpuerAdmin::class,'show_companies_block']);
Route::get('show_companies_active' ,[SpuerAdmin::class,'show_companies_active']);
Route::get('show_companies_refuse' ,[SpuerAdmin::class,'show_companies_refuse']);
Route::post('accept_company' ,[SpuerAdmin::class,'accept_company']);
Route::post('refuse_company' ,[SpuerAdmin::class,'refuse_company']);
Route::post('block_company' ,[SpuerAdmin::class,'block_company']);
Route::get('show_offers' ,[SpuerAdmin::class,'show_offers']);
Route::get('show_offers_pending' ,[SpuerAdmin::class,'show_offers_pending']);
Route::get('show_offers_accept' ,[SpuerAdmin::class,'show_offers_accept']);
Route::get('show_offers_refuse' ,[SpuerAdmin::class,'show_offers_refuse']);
Route::post('accept_offer' ,[SpuerAdmin::class,'accept_offer']);
Route::post('refuse_offer' ,[SpuerAdmin::class,'refuse_offer']);
Route::post('block_user' ,[SpuerAdmin::class,'block_user']);
Route::post('un_block_user' ,[SpuerAdmin::class,'un_block_user']);

Route::get('show_user_blooked' ,[SpuerAdmin::class,'show_user_blooked']);
Route::get('show_user_active' ,[SpuerAdmin::class,'show_user_active']);
});
