<?php

use App\Http\Controllers\CargoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\EscalaController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PontoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('empresas', EmpresaController::class);
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('cargos', CargoController::class);
    Route::resource('horarios', HorarioController::class);
    Route::resource('escalas', EscalaController::class);
    Route::resource('funcionarios', FuncionarioController::class);

    Route::get('ponto', [PontoController::class, 'index'])->name('ponto.index');
    Route::post('ponto/contingencia', [PontoController::class, 'registrarContingencia'])->name('ponto.contingencia');

    Route::post('equipamentos/importar', [EquipamentoController::class, 'importar'])->name('equipamentos.importar');
    Route::post('equipamentos/{equipamento}/testar-conexao', [EquipamentoController::class, 'testarConexao'])->name('equipamentos.testar-conexao');
    Route::post('equipamentos/{equipamento}/sincronizar', [EquipamentoController::class, 'sincronizar'])->name('equipamentos.sincronizar');
    Route::resource('equipamentos', EquipamentoController::class)->except('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
