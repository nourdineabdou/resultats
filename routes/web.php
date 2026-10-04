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

use App\Http\Controllers\AdminBachelierController;
// Liste administrative des bacheliers
Route::get(
    'admin/bacheliers',
    [AdminBachelierController::class, 'index']
)->name('admin.bacheliers.index');

// Afficher le dossier d'un bachelier
Route::get(
    'admin/bacheliers/{id}',
    [AdminBachelierController::class, 'show']
)->name('admin.bacheliers.show');

// Valider l'inscription
Route::post(
    'admin/bacheliers/{id}/valider',
    [AdminBachelierController::class, 'valider']
)->name('admin.bacheliers.valider');

// Demander des compléments
Route::post(
    'admin/bacheliers/{id}/complement',
    [AdminBachelierController::class, 'complement']
)->name('admin.bacheliers.complement');




Route::get('/', 'HomeController@dashboard');
Route::get('candidature/formulaire', 'HomeController@formulaire')
    ->name('candidature.formulaire');
Route::get('candidature/attente1', 'HomeController@attente1')
    ->name('candidature.attente1');

Route::post('candidature/store', 'HomeController@store')
    ->name('candidature.store');
Route::post('authentification1', 'HomeController@authenticate1');
Route::post('authentification2', 'HomeController@authenticate2');
Route::get('home', 'HomeController@dashboard')->name('home');
Route::get('sorties', 'HomeController@sorties');
Route::get('selectModule/{id}', 'HomeController@selectModule');


Route::get('getbiltinsFondamental', 'editionsRoute@getbiltinsFondamental')->name('home');
Route::get('dashboard/{id}', 'HomeController@dashboard')->name('dashboard');

+
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
