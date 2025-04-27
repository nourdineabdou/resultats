<?php
/*****************************************************
 toutes les routes consernant la gestion des droits d'acces
 *
 ******************************************************/
Route::group(['prefix' => 'admin', 'namespace' => 'App\Modules\Admin\Controllers','middleware'=>['web', 'auth', 'roles'],'roles' => [1]], function () {
    
    Route::get('droitsAcces','droitsAccesController@droitsAcces');
    Route::get('droitsAcces/getDroitsAccesDT/{selected}','droitsAccesController@getDroitsAcces');
    Route::get('droitsAcces/getDroit/{id}','droitsAccesController@getDroit');
    Route::get('droitsAcces/selectUnite','droitsAccesController@getUniteLabo');
    Route::get('droitsAcces/selectUnite/{id}','droitsAccesController@getUniteLabor');
    Route::get('droitsAcces/selectDepot','droitsAccesController@getDepot');
    Route::get('droitsAcces/selectDepot/{id}','droitsAccesController@getDepott');
    Route::post('droitsAcces/add','droitsAccesController@addDroitsAcces');
    Route::get('droitsAcces/delete/{id}','droitsAccesController@deleteDroitsAcces');
    Route::post('droitsAcces/edit','droitsAccesController@editDroitAccess');

    Route::get('profilsAcces','profilsAccesController@profilsAcces');
    Route::get('profilsAcces/getProfilsAccesDT/{selected}','profilsAccesController@getProfilsAcces');
    Route::post('profilsAcces/add','profilsAccesController@addProfilsAcces');
    Route::get('profilsAcces/delete/{id}','profilsAccesController@deleteProfilsAcces');

    Route::get('profilsAcces/droitsAcces/{id}','profilsAccesController@droitsAcces');
    Route::get('profilsAcces/droitsAcces/getDroitsAcces/{id}','profilsAccesController@getDroitsAcces');
    Route::get('profilsAcces/droitsAcces/{id}/add','profilsAccesController@newDroitsAcces');
    Route::post('profilsAcces/droitsAcces/add','profilsAccesController@addDroitsAcces');

    Route::get('profilsAcces/droitsAcces/{list}/{id}','profilsAccesController@updateGrouping');

    Route::get('profilsAcces/selectProfilUser/{id}','profilsAccesController@selectProfilUser');
    
    Route::get('profilsAcces/getProfil/{id}/{showall}','profilsAccesController@getProfil');
    Route::get('profilsAcces/getProfilTab/{id}/{tab}','profilsAccesController@getProfilTab');

    Route::post('profilsAcces/update/','profilsAccesController@updateProfil');
    
});
