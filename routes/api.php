<?php

use App\Http\Controllers\V1\Auth\CompletedTaskController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\LogoutController;
use App\Http\Controllers\V1\Auth\MeController;
use App\Http\Controllers\V1\ExportTaskController;
use App\Http\Controllers\V1\State\CreateStateController;
use App\Http\Controllers\V1\State\GetAllStateController;
use App\Http\Controllers\V1\Task\CreateTaskController;
use App\Http\Controllers\V1\Task\DeleteTaskController;
use App\Http\Controllers\V1\Task\GetAllTaskController;
use App\Http\Controllers\V1\Task\GetAllTrashedTaskController;
use App\Http\Controllers\V1\Task\ReOrderTaskController;
use App\Http\Controllers\V1\Task\RestoreTrashedTaskController;
use App\Http\Controllers\V1\Task\ShowTaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * ------------------------------------------------------------------------
 * V1 Routes
 *
 * @endpoint /api/v1/*
 * ------------------------------------------------------------------------
 */
Route::prefix('v1')
    ->name('v1.')
    ->group(function () {
        /**
         * ------------------------------------------------------------------------
         * Auth Routes
         *
         * @endpoint /api/v1/auth/*
         * ------------------------------------------------------------------------
         */
        Route::prefix('auth')
            ->name('auth.')
            ->group(function () {
                // @endpoint /api/v1/auth/login
                Route::post('login', LoginController::class)->name('login');
                // @endpoint /api/v1/auth/logout
                Route::post('logout', LogoutController::class)->name('logout');
                // @endpoint /api/v1/auth/me
                Route::get('me', MeController::class)->name('me');
                // @endpoint /api/v1/auth/completed-tasks
                Route::get('completed-tasks', CompletedTaskController::class)->name('completedTasks');
            });

        /**
         * ------------------------------------------------------------------------
         * Tasks Routes
         *
         * @endpoint /api/v1/tasks/*
         * ------------------------------------------------------------------------
         */
        Route::prefix('tasks')
            ->name('tasks.')
            ->group(function () {
                // @endpoint /api/v1/tasks
                Route::get('/', GetAllTaskController::class)->name('all');
                // @endpoint /api/v1/tasks
                Route::post('/', CreateTaskController::class)->name('create');
                // @endpoint /api/v1/tasks/{task:uuid}
                Route::get('{task:uuid}', ShowTaskController::class)->name('show');
                // @endpoint /api/v1/tasks/{task:uuid}
                Route::delete('{task:uuid}', DeleteTaskController::class)->name('delete');
                // @endpoint /api/v1/tasks
                Route::patch('/', ReOrderTaskController::class)->name('reOrder');
                // @endpoint /api/v1/tasks/restore
                Route::post('restore', RestoreTrashedTaskController::class)->name('restore');
            });

        /**
         * ------------------------------------------------------------------------
         * States Routes
         *
         * @endpoint /api/v1/states/*
         * ------------------------------------------------------------------------
         */
        Route::prefix('states')
            ->name('states.')
            ->group(function () {
                // @endpoint /api/v1/states
                Route::get('/', GetAllStateController::class)->name('all');
                // @endpoint /api/v1/states
                Route::post('/', CreateStateController::class)->name('create');
            });

        /**
         * ------------------------------------------------------------------------
         * Exports Routes
         *
         * @endpoint /api/v1/exports/*
         * ------------------------------------------------------------------------
         */
        Route::prefix('exports')
            ->name('exports.')
            ->group(function () {
                // @endpoint /api/v1/exports/tasks
                Route::get('tasks', ExportTaskController::class)->name('tasks');
            });
    });
