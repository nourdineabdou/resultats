<?php
Route::group(['prefix' => 'matieres/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'MatiereController@index');
    Route::get('getDT', 'MatiereController@getDT');
    Route::get('getDT/{id}', 'MatiereController@getDT');
    Route::get('get/{id}','MatiereController@get');
    Route::get('getTab/{id}/{tab}','MatiereController@getTab');
    Route::get('add','MatiereController@formAdd');
    Route::post('add','MatiereController@add');
    Route::post('edit','MatiereController@edit');
    Route::get('delete/{id}','MatiereController@delete');
    Route::get('getmodulle/{id}', 'MatiereController@getmodulle');
});

Route::group(['prefix' => 'matieresPR/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'MatierePRController@index');
    Route::get('getDT', 'MatierePRController@getDT');
    Route::get('getDT/{id}', 'MatierePRController@getDT');
    Route::get('get/{id}','MatierePRController@get');
    Route::get('getTab/{id}/{tab}','MatierePRController@getTab');
    Route::get('add','MatierePRController@formAdd');
    Route::post('add','MatierePRController@add');
    Route::post('edit','MatierePRController@edit');
    Route::get('delete/{id}','MatierePRController@delete');
    Route::get('getmatieres/{id}', 'MatierePRController@getmatieres');
});

Route::group(['prefix' => 'profilGroupes/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'ProfilGroupeController@index');
    Route::get('getDT', 'ProfilGroupeController@getDT');
    Route::get('getDT/{id}', 'ProfilGroupeController@getDT');
    Route::get('get/{id}','ProfilGroupeController@get');
    Route::get('getTab/{id}/{tab}','ProfilGroupeController@getTab');
    Route::get('add','ProfilGroupeController@formAdd');
    Route::post('add','ProfilGroupeController@add');
    Route::post('edit','ProfilGroupeController@edit');
    Route::get('delete/{id}','ProfilGroupeController@delete');
    Route::get('getgroupeprofil/{id}','ProfilGroupeController@getgroupeprofil');
});

Route::group(['prefix' => 'plages/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'PlageController@index');
    Route::get('getDT', 'PlageController@getDT');
    Route::get('getDT/{id}', 'PlageController@getDT');
    Route::get('get/{id}','PlageController@get');
    Route::get('getTab/{id}/{tab}','PlageController@getTab');
    Route::get('add','PlageController@formAdd');
    Route::post('add','PlageController@add');
    Route::post('edit','PlageController@edit');
    Route::get('delete/{id}','PlageController@delete');
});

Route::group(['prefix' => 'modulles/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'ModulleController@index');
    Route::get('getDT', 'ModulleController@getDT');
    Route::get('getDT/{id}', 'ModulleController@getDT');
    Route::get('get/{id}','ModulleController@get');
    Route::get('getTab/{id}/{tab}','ModulleController@getTab');
    Route::get('add','ModulleController@formAdd');
    Route::post('add','ModulleController@add');
    Route::post('edit','ModulleController@edit');
    Route::get('delete/{id}','ModulleController@delete');
});
Route::group(['prefix' => 'profils/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'ProfilController@index');
    Route::get('getDT', 'ProfilController@getDT');
    Route::get('getDT/{id}', 'ProfilController@getDT');
    Route::get('get/{id}','ProfilController@get');
    Route::get('getTab/{id}/{tab}','ProfilController@getTab');
    Route::get('add','ProfilController@formAdd');
    Route::post('add','ProfilController@add');
    Route::post('edit','ProfilController@edit');
    Route::get('delete/{id}','ProfilController@delete');
});

Route::group(['prefix' => 'departements/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'DepartementController@index');
    Route::get('getDT', 'DepartementController@getDT');
    Route::get('getDT/{id}', 'DepartementController@getDT');
    Route::get('get/{id}','DepartementController@get');
    Route::get('getTab/{id}/{tab}','DepartementController@getTab');
    Route::get('add','DepartementController@formAdd');
    Route::post('add','DepartementController@add');
    Route::post('edit','DepartementController@edit');
    Route::get('delete/{id}','DepartementController@delete');
});

Route::group(['prefix' => 'salles/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'SalleController@index');
    Route::get('getDT', 'SalleController@getDT');
    Route::get('getDT/{id}', 'SalleController@getDT');
    Route::get('get/{id}','SalleController@get');
    Route::get('getTab/{id}/{tab}','SalleController@getTab');
    Route::get('add','SalleController@formAdd');
    Route::post('add','SalleController@add');
    Route::post('edit','SalleController@edit');
    Route::get('vesualisezSalle/{id}','SalleController@vesualisezSalle');
    Route::get('devesualisezSalle/{id}','SalleController@devesualisezSalle');
    Route::get('delete/{id}','SalleController@delete');
});

Route::group(['prefix' => 'facultes/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'FaculteController@index');
    Route::get('getDT', 'FaculteController@getDT');
    Route::get('getDT/{id}', 'FaculteController@getDT');
    Route::get('get/{id}','FaculteController@get');
    Route::get('getTab/{id}/{tab}','FaculteController@getTab');
    Route::get('add','FaculteController@formAdd');
    Route::post('add','FaculteController@add');
    Route::post('edit','FaculteController@edit');
    Route::get('delete/{id}','FaculteController@delete');
});

Route::group(['prefix' => 'universites/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'UniversiteController@index');
    Route::get('getDT', 'UniversiteController@getDT');
    Route::get('getDT/{id}', 'UniversiteController@getDT');
    Route::get('get/{id}','UniversiteController@get');
    Route::get('getTab/{id}/{tab}','UniversiteController@getTab');
    Route::get('add','UniversiteController@formAdd');
    Route::post('add','UniversiteController@add');
    Route::post('edit','UniversiteController@edit');
    Route::get('delete/{id}','UniversiteController@delete');
});

Route::group(['prefix' => 'etapes/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'EtapeController@index');
    Route::get('getDT', 'EtapeController@getDT');
    Route::get('getDT/{id}', 'EtapeController@getDT');
    Route::get('get/{id}','EtapeController@get');
    Route::get('getTab/{id}/{tab}','EtapeController@getTab');
    Route::get('add','EtapeController@formAdd');
    Route::post('add','EtapeController@add');
    Route::post('edit','EtapeController@edit');
    Route::get('delete/{id}','EtapeController@delete');
});

Route::group(['prefix' => 'parametres/', 'middleware' => 'roles','roles' => [11]], function () {
    Route::get('', 'ParametreController@index');
    Route::get('getDT', 'ParametreController@getDT');
    Route::get('get/{id}','ParametreController@get');
    Route::get('getTab/{id}/{tab}','ParametreController@getTab');
    Route::get('add','ParametreController@formAdd');
    Route::post('add','ParametreController@add');
    Route::post('edit','ParametreController@edit');
    Route::get('delete/{id}','ParametreController@delete');
});

Route::group(['prefix' => 'exemples/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'ExemplesController@index');
    Route::get('getDT', 'ExemplesController@getDT');
    Route::get('getDT/{id}', 'ExemplesController@getDT');
    Route::get('get/{id}','ExemplesController@get');
    Route::get('getTab/{id}/{tab}','ExemplesController@getTab');
    Route::get('add','ExemplesController@formAdd');
    Route::post('add','ExemplesController@add');
    Route::post('edit','ExemplesController@edit');
    Route::get('delete/{id}','ExemplesController@delete');
});
