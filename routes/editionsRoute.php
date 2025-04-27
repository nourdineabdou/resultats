<?php
Route::group(['prefix' => 'editions/', 'middleware' => 'roles','roles' => [1,4,5,11,7]], function () {
    Route::get('', 'editionController@index');
    Route::get('getDT/{profil}/{groupe}', 'editionController@getDT');
    Route::get('getDT/{profil}/{groupe}/{id}', 'editionController@getDT');
    Route::get('get/{id}','editionController@get');
    Route::get('getTab/{id}/{tab}','editionController@getTab');
    Route::get('add','editionController@formAdd');
    Route::post('add','editionController@add');
    Route::post('UpdateProfil','editionController@UpdateProfil');
    Route::post('UpdateProfil1','editionController@UpdateProfil1');
    Route::post('UpdateNumero','editionController@UpdateNumero');
    Route::post('edit','editionController@edit');
    Route::get('delete/{id}','editionController@delete');
    Route::get('chagerProfil/{id}','editionController@chagerProfil');
    Route::get('chagerProfil1/{id}','editionController@chagerProfil1');
    Route::get('chanerNumero/{id}','editionController@chanerNumero');
    Route::get('corrigerAttestation/{id}','editionController@corrigerAttestation');

    Route::get('SupprimeerMatierDejaProgrammee/{id}','editionController@SupprimeerMatierDejaProgrammee');
    Route::get('bloqueEtudiant/{id}','editionController@bloqueEtudiant');
    Route::get('bloqueEtudiant1/{id}','editionController@bloqueEtudiant1');
    Route::get('listeConcours','editionController@listeConcours');
    Route::get('supprimerReinscription/{id}','editionController@supprimerReinscription');
    Route::get('exporteattestationPDF/{id}','editionController@exporteattestationPDF');
    Route::get('inserteTemp/{id}','editionController@inserteTemp');
    Route::get('DeleteTemp/{id}','editionController@DeleteTemp');
    Route::get('pdfListeEtudiant/{id}','editionController@pdfListeEtudiant');
   Route::get('pdfstatiNSEtudiant/{id}','editionController@pdfstatiNSEtudiant');
    Route::get('pdfListeRenvoyer','editionController@pdfListeRenvoyer');
    Route::get('DeleteAll','editionController@DeleteAll');
     Route::get('pdfattestationColl/{profil}/{groupe}','editionController@pdfattestationColl');
});
