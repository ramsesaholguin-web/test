<?php

use Illuminate\Support\Facades\Route;

// Redirect the root URL directly to the Filament admin
Route::redirect('/', '/admin');
