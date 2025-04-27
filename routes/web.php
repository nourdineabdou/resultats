<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require_once 'parametreRoute.php';
require_once 'concours.php';
require_once 'editionsRoute.php';
require_once 'reinscriptionRoute.php';
require_once 'examenCnsRoute.php';
require_once 'inscriptionRoute.php';
require_once 'examenRoute.php';
Route::get('/', 'HomeController@dashboard');
Route::post('authentification1', 'HomeController@authenticate1');
Route::get('sorties', 'HomeController@sorties');
Route::get('selectModule/{id}', 'HomeController@selectModule');
Route::get('home', 'HomeController@dashboard')->name('home');
Route::get('dashboard/{id}', 'HomeController@dashboard')->name('dashboard');

Auth::routes();

// exemple de routes pour familles
Route::group(['prefix' => 'familles/', 'middleware' => 'roles','roles' => [1]], function () {
	Route::get('', 'FamilleController@index');
	Route::get('getDT', 'FamilleController@getDT');
	Route::get('get/{id}','FamilleController@get');
	Route::get('getTab/{id}/{tab}','FamilleController@getTab');
	Route::get('add','FamilleController@formAdd');
	Route::post('add','FamilleController@add');
	Route::post('edit','FamilleController@edit');
	Route::get('delete/{id}','FamilleController@delete');
});
Route::get('/lang/{n}', function ($n) {
    Session::put('applocale', $n);
    return redirect('/');
});
