<?php
Route::get('documents/getDocuments/{id_objet}/{type_objet}','GEDController@getDocuments');
Route::get('documents/getDocuments/{id_objet}/{type_objet}/{selected}','GEDController@getDocuments');
Route::get('documents/get_document/{id_objet}/{type_objet}','GEDController@get_document');
Route::post('documents/add', 'GEDController@addDocument');
Route::get('documents/deleteDocument/{id}', 'GEDController@deleteDocument');
