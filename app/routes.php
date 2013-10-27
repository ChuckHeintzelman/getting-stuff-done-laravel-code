<?php

Route::get('/', function()
{
    return View::make('live');
});
Route::get('mock', function()
{
    return View::make('mockup');
});