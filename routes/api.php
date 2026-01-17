<?php
use App\Http\Controllers\Api\BarangApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('barang', BarangApiController::class);
;