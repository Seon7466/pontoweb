<?php

use App\Http\Controllers\Api\V1\AgentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/agent')
    ->middleware(['agent.token', 'throttle:120,1'])
    ->group(function () {
        Route::post('heartbeat', [AgentController::class, 'heartbeat']);
        Route::get('configuration', [AgentController::class, 'configuration']);
        Route::post('markings', [AgentController::class, 'markings']);
    });
