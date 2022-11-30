<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\operacion\viaje;
Route::get('Operaciones/viaje/detalles', [viaje::class,'getDetalles']);