<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('home')->name('home.')->group(function(){
    Route::get('/wave_paths/{pmb_wave_path}/show', [App\Http\Controllers\HomeController::class, 'pmbWavePath'])->name('wave.path.show');
    Route::get('/registration/{pmb_wave_path}', [App\Http\Controllers\HomeController::class, 'registration'])->name('registration');
    Route::post('/registration', [App\Http\Controllers\HomeController::class, 'registration_store'])->name('registration.store');

});
Route::get('/daftar', [App\Http\Controllers\HomeController::class, 'daftar'])->name('daftar');
Route::get('/daftar', [App\Http\Controllers\HomeController::class, 'daftar'])->name('daftar.store');




Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginact'])->name('loginact');
Route::middleware('guest')->group(function(){
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');
    // masterdata
    Route::prefix('masterdata')->name('masterdata.')->group(function(){
        // fakultas
        Route::prefix('faculties')->name('faculty.')->group(function(){
            Route::middleware('permission:masterdata.faculty.view')->group(function(){
                Route::get('/', [App\Http\Controllers\Masterdata\FacultyController::class, 'index'])->name("index");
                Route::get('/{faculty}/show', [App\Http\Controllers\Masterdata\FacultyController::class, 'show'])->name("show");
            });
            Route::middleware('permission:masterdata.faculty.manage')->group(function(){
                Route::get('/create', [App\Http\Controllers\Masterdata\FacultyController::class, 'create'])->name("create");
                Route::post('/store', [App\Http\Controllers\Masterdata\FacultyController::class, 'store'])->name("store");
                Route::get('/{faculty}/edit', [App\Http\Controllers\Masterdata\FacultyController::class, 'edit'])->name("edit");
                Route::put('/{faculty}/update', [App\Http\Controllers\Masterdata\FacultyController::class, 'update'])->name("update");
                Route::delete('/{faculty}/destroy', [App\Http\Controllers\Masterdata\FacultyController::class, 'destroy'])->name("destroy");
            });
        });
        // program studi
        Route::prefix('study-programs')->name('study_program.')->group(function(){
            Route::middleware('permission:masterdata.study_program.view')->group(function(){
                Route::get('/', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'index'])->name("index");
                Route::get('/{study_program}/show', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'show'])->name("show");
            });
            Route::middleware('permission:masterdata.study_program.manage')->group(function(){
                Route::get('/create', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'create'])->name("create");
                Route::post('/store', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'store'])->name("store");
                Route::get('/{study_program}/edit', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'edit'])->name("edit");
                Route::put('/{study_program}/update', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'update'])->name("update");
                Route::delete('/{study_program}/destroy', [App\Http\Controllers\Masterdata\StudyProgramController::class, 'destroy'])->name("destroy");
            });
        });
    });
    // pengguna
    Route::prefix('user')->name('user.')->group(function(){
        Route::middleware('permission:user.view')->group(function(){
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name("index");
            Route::get('/{user}/show', [App\Http\Controllers\UserController::class, 'show'])->name("show");
        });
        Route::middleware('permission:user.manage')->group(function(){
            Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name("create");
            Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name("store");
            Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name("edit");
            Route::put('/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name("update");
            Route::delete('/{user}/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name("destroy");
        });
    });
    // akun pendaftar
    Route::prefix('registrant')->name('registrant.')->group(function(){
        Route::middleware('permission:registrant.view')->group(function(){
            Route::get('/', [App\Http\Controllers\RegistrantController::class, 'index'])->name("index");
            Route::get('/{user}/show', [App\Http\Controllers\RegistrantController::class, 'show'])->name("show");
        });
        Route::middleware('permission:registrant.manage')->group(function(){
            Route::get('/create', [App\Http\Controllers\RegistrantController::class, 'create'])->name("create");
            Route::post('/store', [App\Http\Controllers\RegistrantController::class, 'store'])->name("store");
            Route::get('/{user}/edit', [App\Http\Controllers\RegistrantController::class, 'edit'])->name("edit");
            Route::put('/{user}/update', [App\Http\Controllers\RegistrantController::class, 'update'])->name("update");
            Route::delete('/{user}/destroy', [App\Http\Controllers\RegistrantController::class, 'destroy'])->name("destroy");
        });
    });
    // pmb
    Route::prefix('pmb')->name('pmb.')->group(function(){
        // registration path
        Route::prefix('registration-paths')->name('registration_path.')->group(function(){
            Route::middleware('permission:pmb.registration_path.view')->group(function(){
                Route::get('/', [App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'index'])->name("index");
                Route::get('/{pmb_registration_path}/show', [App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'show'])->name("show");
            });
            Route::middleware('permission:pmb.registration_path.manage')->group(function(){
                Route::post('/store', [App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'store'])->name("store");
                Route::put('/{pmb_registration_path}/update', [App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'update'])->name("update");
                Route::delete('/{pmb_registration_path}/destroy', [App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'destroy'])->name("destroy");
                Route::prefix('/document')->name('document.')->group(function(){
                    Route::post('/store',[App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'registrationDocument_store'])->name('store');
                    Route::put('/{pmb_registration_document}/update',[App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'registrationDocument_update'])->name('update');
                    Route::delete('/{pmb_registration_document}/destroy',[App\Http\Controllers\Pmb\PmbRegistrationPathController::class, 'registrationDocument_destroy'])->name('destroy');
                });
            });
        });
        // periods
        Route::prefix('periods')->name('period.')->group(function(){
            Route::middleware('permission:pmb.period.view')->group(function(){
                Route::get('/', [App\Http\Controllers\Pmb\PmbPeriodController::class, 'index'])->name("index");
                Route::get('/{pmb_period}/show', [App\Http\Controllers\Pmb\PmbPeriodController::class, 'show'])->name("show");
            });
            Route::middleware('permission:pmb.period.manage')->group(function(){
                Route::post('/store', [App\Http\Controllers\Pmb\PmbPeriodController::class, 'store'])->name("store");
                Route::put('/{pmb_period}/update', [App\Http\Controllers\Pmb\PmbPeriodController::class, 'update'])->name("update");
                Route::delete('/{pmb_period}/destroy', [App\Http\Controllers\Pmb\PmbPeriodController::class, 'destroy'])->name("destroy");
            });
        });
        // waves
        Route::prefix('waves')->name('wave.')->group(function(){
            Route::middleware('permission:pmb.wave.view')->group(function(){
                Route::get('/', [App\Http\Controllers\Pmb\PmbWaveController::class, 'index'])->name("index");
                Route::get('/{pmb_wave}/show', [App\Http\Controllers\Pmb\PmbWaveController::class, 'show'])->name("show");
            });
            Route::middleware('permission:pmb.wave.manage')->group(function(){
                Route::get('/create',[App\Http\Controllers\Pmb\PmbWaveController::class, 'create'])->name('create');
                Route::post('/store', [App\Http\Controllers\Pmb\PmbWaveController::class, 'store'])->name("store");
                Route::get('/{pmb_wave}/edit',[App\Http\Controllers\Pmb\PmbWaveController::class, 'edit'])->name('edit');
                Route::put('/{pmb_wave}/update', [App\Http\Controllers\Pmb\PmbWaveController::class, 'update'])->name("update");
                Route::delete('/{pmb_wave}/destroy', [App\Http\Controllers\Pmb\PmbWaveController::class, 'destroy'])->name("destroy");
                // wave paths
                Route::prefix('wave_paths')->name('path.')->group(function(){
                    Route::get('{pmb_wave}/create',[App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePath_create'])->name('create');
                    Route::post('/store', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePath_store'])->name('store');
                    Route::get('{pmb_wave_path}/edit',[App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePath_edit'])->name('edit');
                    Route::put('/{pmb_wave_path}/update', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePath_update'])->name('update');
                    Route::delete('/{pmb_wave_path}/destroy', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePath_destroy'])->name("destroy");
                    Route::get('/{pmb_wave_path}/show', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePath_show'])->name('show');
                    Route::post('/stage/{pmb_wave_path}/store', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePathStage_store'])->name('stage.store');
                    Route::put('/stage/{pmb_wave_path_stage}/update', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePathStage_update'])->name('stage.update');
                    Route::delete('/stage/{pmb_wave_path_stage}/destroy', [App\Http\Controllers\Pmb\PmbWaveController::class, 'wavePathStage_destroy'])->name("stage.destroy");
                });
            });
        });
        // application
        Route::prefix('applications')->name('application.')->group(function(){
            Route::middleware('permission:pmb.application.view')->group(function(){
                Route::get('/',[\App\Http\Controllers\Pmb\PmbApplicationController::class, 'index'])->name('index');
                Route::get('/{application}/show', [\App\Http\Controllers\Pmb\PmbApplicationController::class, 'show'])->name('show');
            });
            Route::middleware('permission:pmb.application.manage')->group(function(){
                Route::get('/create', [\App\Http\Controllers\Pmb\PmbApplicationController::class, 'create'])->name('create');
                Route::post('/store', [\App\Http\Controllers\Pmb\PmbApplicationController::class, 'store'])->name('store');
                Route::put('/{payment}/payment/verify', [\App\Http\Controllers\Pmb\PmbApplicationController::class, 'payment_verify'])->name('payment.verify');
                Route::put('/{document}/document/verify', [\App\Http\Controllers\Pmb\PmbApplicationController::class, 'document_verify'])->name('document.verify');
                Route::put('/{application}/stage/set', [\App\Http\Controllers\Pmb\PmbApplicationController::class, 'stage_set'])->name('stage.set');
            });
        });
        // graduation
        Route::prefix('graduations')->name('graduation.')->group(function(){
            Route::middleware('permission:pmb.graduation.view')->group(function(){
                Route::get('/',[App\Http\Controllers\Pmb\PmbGraduationController::class, 'index'])->name('index');
                Route::get('/{application}/show', [\App\Http\Controllers\Pmb\pmbGraduationController::class, 'show'])->name('show');
            });
            Route::middleware('permission:pmb.graduation.manage')->group(function(){
                Route::post('/{application}/store', [\App\Http\Controllers\Pmb\PmbGraduationController::class, 'store'])->name('store');
            });
        });
    });
    // camaba
    Route::prefix('my')->name('my.')->group(function(){
        // applicant
        Route::middleware('permission:my.applicant')->prefix('applicant')->name('applicant.')->group(function(){
            Route::get('/', [\App\Http\Controllers\My\ApplicantController::class, 'index'])->name('index');
            Route::put('/{applicant}', [\App\Http\Controllers\My\ApplicantController::class, 'update'])->name('update');
        });
        // application
        Route::middleware('permission:my.application')->prefix('application')->name('application.')->group(function(){
            Route::get('/', [\App\Http\Controllers\My\ApplicationController::class, 'index'])->name('index');
            Route::get('/{application}/show', [\App\Http\Controllers\My\ApplicationController::class, 'show'])->name('show');
            Route::post('/documents/{application}/store', [\App\Http\Controllers\My\ApplicationController::class, 'document_store'])->name('documents.store');
            Route::post('/payments/{application}/store', [\App\Http\Controllers\My\ApplicationController::class, 'paymentform_store'])->name('payments.store');
        });
        // exam
        Route::middleware('permission:my.exam')->prefix('exam')->name('exam.')->group(function(){
            Route::get('/{application}/start', [\App\Http\Controllers\My\ExamController::class, 'start'])->name('start');
            Route::get('/{application}/show', [\App\Http\Controllers\My\ExamController::class, 'show'])->name('show');
            Route::post('/{application}/finish', [\App\Http\Controllers\My\ExamController::class, 'finish'])->name('finish');
            Route::post('/{application}/answer', [\App\Http\Controllers\My\ExamController::class, 'answer'])->name('answer');
        });
    });
});
