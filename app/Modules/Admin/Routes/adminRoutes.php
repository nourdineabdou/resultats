<?php
/*****************************************************
 toutes les routes consernant la gestion des utilisateur
 *
 ******************************************************/
Route::group(['prefix' => 'admin/users/', 'namespace' => 'App\Modules\Admin\Controllers','middleware'=>['web', 'auth', 'roles'],'roles' => [1]], function () {
    // Users
    Route::get('','UserController@index');
    Route::get('getDT/{etat}','UserController@getDT');
    Route::get('getDT/{etat}/{selected}','UserController@getDT');
    Route::get('get/{id}','UserController@get');
    Route::get('getTab/{id}/{tab}','UserController@getTab');
    Route::get('add','UserController@formAdd');
    Route::post('add','UserController@add');
    Route::post('edit','UserController@edit');
    Route::get('delete/{id}','UserController@delete');

    Route::get('resetpassword/{id}','UserController@ShowFormResetPasswordUser');
    Route::post('resetpassword','UserController@resetPasswordUser');
    Route::get('addProfileToUser/{id}','UserController@formAddProfileToUser');
    Route::get('deleteProfileFromUser/{id}','UserController@deleteProfileFromUser');
    Route::post('addProfileToUser','UserController@addProfileToUser');
});

Route::group(['prefix' => 'admin/profiles/', 'namespace' => 'App\Modules\Admin\Controllers','middleware'=>['web', 'auth', 'roles'],'roles' => [1]], function () {
    // Profiles
    Route::get('','ProfileController@index');
    Route::get('getDT','ProfileController@getDT');
    Route::get('getDT/{selected}','ProfileController@getDT');
    Route::get('get/{id}','ProfileController@get');
    Route::get('getTab/{id}/{tab}','ProfileController@getTab');
    Route::get('add','ProfileController@formAdd');
    Route::post('add','ProfileController@add');
    Route::post('edit','ProfileController@edit');
    Route::get('delete/{id}','ProfileController@delete');

    Route::get('getDroitsDT/{id}','ProfileController@getDroitsDT');
    Route::get('updatedroits/{list}/{id}','ProfileController@updateGrouping');
});

Route::group(['prefix' => 'admin/droits/', 'namespace' => 'App\Modules\Admin\Controllers','middleware'=>['web', 'auth', 'roles'],'roles' => [1]], function () {
    // Droits
    Route::get('','DroitController@index');
    Route::get('getDT','DroitController@getDT');
    Route::get('getDT/{selected}','DroitController@getDT');
    Route::get('get/{id}','DroitController@get');
    Route::get('getTab/{id}/{tab}','DroitController@getTab');
    Route::get('add','DroitController@formAdd');
    Route::post('add','DroitController@add');
    Route::post('edit','DroitController@edit');
    Route::get('delete/{id}','DroitController@delete');
});

// Route::group(['middleware' => 'roles','roles' => ['Administrateur','Validateur','Editeur','consultation']], function () {
    Route::get('users/resetmypassword','UserController@ShowFormResetMyPasswordUser');
    Route::Post('users/resetmypassword','UserController@resetMyPasswordUser');
// });

// cette route doit etre accecible par tous le monde
// Route::post('users/login','UserController@authenticate');
Route::post('users/login','UserController@authenticate');
