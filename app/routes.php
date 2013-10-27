<?php

Route::get('/', function()
{
    return View::make('live');
});
Route::get('mock', function()
{
    return View::make('mockup');
});

Route::resource('lists', 'GSD\Controllers\ListController', array(
    'except' => array('create', 'edit')));
Route::post('lists/{lists}/archive', array(
    'as'   => 'lists.archive',
    'uses' => 'GSD\Controllers\ListController@archive',
));
Route::post('lists/{lists}/unarchive', array(
    'as'   => 'lists.unarchive',
    'uses' => 'GSD\Controllers\ListController@unarchive',
));
Route::post('lists/{source}/rename/{dest}', array(
    'as'   => 'lists.rename',
    'uses' => 'GSD\Controllers\ListController@rename',
));