<?php

use App\Http\Controllers\Backend\ActualProgressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\ForgotPasswordController;
use App\Http\Controllers\Backend\ConfirmablePasswordController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\FaqController;
use App\Http\Controllers\Backend\AmenityController;
use App\Http\Controllers\Backend\TermsConditionController;
use App\Http\Controllers\Backend\PrivacyPolicyController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\CommonController;
use App\Http\Controllers\Backend\BuilderController;
use App\Http\Controllers\Backend\CmsPagesController;
use App\Http\Controllers\Backend\DownloadBrochureDataController;
use App\Http\Controllers\Backend\InsightReportDataController;
use App\Http\Controllers\Backend\LocalityController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\ReraProgressController;
use App\Http\Controllers\Backend\ProjectBadgeController;
use App\Http\Controllers\Backend\SubscriberController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect(route('admin.login'));
    });

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.post');

        Route::get('forgot', [ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot');
        Route::post('forgot', [ForgotPasswordController::class, 'sendResetLink'])->name('password.forgot.post');
        Route::get('reset/{token}/{email}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset', [ForgotPasswordController::class, 'reset'])->name('password.reset.post');
    });

    Route::middleware(['auth', 'adminOrEmployee'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [UserController::class, 'profile'])->name('profile');
            Route::post('/profileupdate', [UserController::class, 'profileupdate'])->name('profile.profileupdate');
            Route::post('/updatepassword', [UserController::class, 'updatepassword'])->name('profile.updatepassword');
        });

        Route::group(['prefix' => 'builders'], function () {
            Route::get('/', [BuilderController::class, 'index'])->name('builder');
            Route::get('/get', [BuilderController::class, 'get'])->name('builder.list');
            Route::get('/detail', [BuilderController::class, 'detail'])->name('builder.detail');
            Route::post('/delete', [BuilderController::class, 'delete'])->name('builder.delete');
            Route::post('/addupdate', [BuilderController::class, 'addupdate'])->name('builder.addupdate');
        });

        Route::group(['prefix' => 'projects'], function () {
            Route::get('/', [ProjectController::class, 'index'])->name('project');
            Route::get('/get', [ProjectController::class, 'get'])->name('project.list');
            Route::get('/detail', [ProjectController::class, 'detail'])->name('project.detail');
            Route::post('/delete', [ProjectController::class, 'delete'])->name('project.delete');
            Route::post('/addupdate', [ProjectController::class, 'addupdate'])->name('project.addupdate');
            Route::get('/add', [ProjectController::class, 'add'])->name('project.add');
            Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
            Route::get('/get-property-sub-types', [ProjectController::class, 'getPropertySubTypes'])->name('project.get-property-sub-types');
            Route::get('/{id}', [ProjectController::class, 'view'])->name('project.view');
        });
    });

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        // Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.confirm.post');

        // Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/upload-image', [DashboardController::class, 'ckEditorimageUpload'])->name('ckeditor.image.upload');

        // Route::group(['prefix' => 'profile'], function () {
        //     Route::get('/', [UserController::class, 'profile'])->name('profile');
        //     Route::post('/profileupdate', [UserController::class, 'profileupdate'])->name('profile.profileupdate');
        //     Route::post('/updatepassword', [UserController::class, 'updatepassword'])->name('profile.updatepassword');
        // });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])->name('user');
            Route::get('/get', [UserController::class, 'get'])->name('user.list');
            Route::get('/detail', [UserController::class, 'detail'])->name('user.detail');
            Route::post('/addupdate', [UserController::class, 'addupdate'])->name('user.addupdate');
            Route::post('/delete', [UserController::class, 'delete'])->name('user.delete');
        });

        Route::group(['prefix' => 'faqs'], function () {
            Route::get('/', [FaqController::class, 'index'])->name('faq');
            Route::get('/get', [FaqController::class, 'get'])->name('faq.list');
            Route::get('/detail', [FaqController::class, 'detail'])->name('faq.detail');
            Route::post('/addupdate', [FaqController::class, 'addupdate'])->name('faq.addupdate');
            Route::post('/delete', [FaqController::class, 'delete'])->name('faq.delete');
            Route::get('/getall', [FaqController::class, 'getall'])->name('faq.getall');
            Route::post('/saveorder', [FaqController::class, 'saveorder'])->name('faq.saveorder');
        });

        Route::group(['prefix' => 'cms-page'], function () {
            Route::get('/{page_name}', [CmsPagesController::class, 'index'])->name('page');
            Route::post('/update', [CmsPagesController::class, 'update'])->name('page.update');
        });


        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', [SettingController::class, 'index'])->name('setting');
            Route::get('/get', [SettingController::class, 'get'])->name('setting.list');
            Route::get('/detail', [SettingController::class, 'detail'])->name('setting.detail');
            Route::post('/addupdate', [SettingController::class, 'addupdate'])->name('setting.addupdate');
        });

        Route::group(['prefix' => 'cities'], function () {
            Route::get('/', [CityController::class, 'index'])->name('city');
            Route::get('/get', [CityController::class, 'get'])->name('city.list');
            Route::get('/detail', [CityController::class, 'detail'])->name('city.detail');
            Route::post('/delete', [CityController::class, 'delete'])->name('city.delete');
            Route::post('/addupdate', [CityController::class, 'addupdate'])->name('city.addupdate');
        });

        Route::group(['prefix' => 'locations'], function () {
            Route::get('/', [LocationController::class, 'index'])->name('location');
            Route::get('/get', [LocationController::class, 'get'])->name('location.list');
            Route::get('/detail', [LocationController::class, 'detail'])->name('location.detail');
            Route::post('/delete', [LocationController::class, 'delete'])->name('location.delete');
            Route::post('/addupdate', [LocationController::class, 'addupdate'])->name('location.addupdate');
        });

        Route::group(['prefix' => 'common'], function () {
            Route::get('/cities', [CommonController::class, 'getAllCities'])->name('common.cities.all');
        });

        // Route::group(['prefix' => 'builders'], function () {
        //     Route::get('/', [BuilderController::class, 'index'])->name('builder');
        //     Route::get('/get', [BuilderController::class, 'get'])->name('builder.list');
        //     Route::get('/detail', [BuilderController::class, 'detail'])->name('builder.detail');
        //     Route::post('/delete', [BuilderController::class, 'delete'])->name('builder.delete');
        //     Route::post('/addupdate', [BuilderController::class, 'addupdate'])->name('builder.addupdate');
        // });

        Route::group(['prefix' => 'amenities'], function () {
            Route::get('/', [AmenityController::class, 'index'])->name('amenity');
            Route::get('/get', [AmenityController::class, 'get'])->name('amenity.list');
            Route::get('/detail', [AmenityController::class, 'detail'])->name('amenity.detail');
            Route::post('/delete', [AmenityController::class, 'delete'])->name('amenity.delete');
            Route::post('/addupdate', [AmenityController::class, 'addupdate'])->name('amenity.addupdate');
        });

        Route::group(['prefix' => 'localities'], function () {
            Route::get('/', [LocalityController::class, 'index'])->name('locality');
            Route::get('/get', [LocalityController::class, 'get'])->name('locality.list');
            Route::get('/detail', [LocalityController::class, 'detail'])->name('locality.detail');
            Route::post('/delete', [LocalityController::class, 'delete'])->name('locality.delete');
            Route::post('/addupdate', [LocalityController::class, 'addupdate'])->name('locality.addupdate');
        });

        // Route::group(['prefix' => 'projects'], function () {
        //     Route::get('/', [ProjectController::class, 'index'])->name('project');
        //     Route::get('/get', [ProjectController::class, 'get'])->name('project.list');
        //     Route::get('/detail', [ProjectController::class, 'detail'])->name('project.detail');
        //     Route::post('/delete', [ProjectController::class, 'delete'])->name('project.delete');
        //     Route::post('/addupdate', [ProjectController::class, 'addupdate'])->name('project.addupdate');
        //     Route::get('/add', [ProjectController::class, 'add'])->name('project.add');
        //     Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
        //     Route::get('/get-property-sub-types', [ProjectController::class, 'getPropertySubTypes'])->name('project.get-property-sub-types');
        //     Route::get('/{id}', [ProjectController::class, 'view'])->name('project.view');
        // });

        Route::group(['prefix' => 'rera-progress'], function () {
            Route::get('/get', [ReraProgressController::class, 'get'])->name('rera_progress.list');
            Route::get('/detail', [ReraProgressController::class, 'detail'])->name('rera_progress.detail');
            Route::post('/delete', [ReraProgressController::class, 'delete'])->name('rera_progress.delete');
            Route::post('/addupdate', [ReraProgressController::class, 'addupdate'])->name('rera_progress.addupdate');
            Route::get('/{project_id}', [ReraProgressController::class, 'index'])->name('rera_progress');
        });

        Route::group(['prefix' => 'actual-progress'], function () {
            Route::get('/get', [ActualProgressController::class, 'get'])->name('actual_progress.list');
            Route::get('/detail', [ActualProgressController::class, 'detail'])->name('actual_progress.detail');
            Route::post('/delete', [ActualProgressController::class, 'delete'])->name('actual_progress.delete');
            Route::post('/addupdate', [ActualProgressController::class, 'addupdate'])->name('actual_progress.addupdate');
            Route::get('/get-images', [ActualProgressController::class, 'getImages'])->name('actual_progress.get-images');
            Route::get('/{project_id}', [ActualProgressController::class, 'index'])->name('actual_progress');
        });

        Route::group(['prefix' => 'news'], function () {
            Route::get('/', [NewsController::class, 'index'])->name('news');
            Route::get('/get', [NewsController::class, 'get'])->name('news.list');
            Route::get('/add', [NewsController::class, 'add'])->name('news.add');
            Route::get('/edit/{news_id}', [NewsController::class, 'edit'])->name('news.edit');
            Route::post('/delete', [NewsController::class, 'delete'])->name('news.delete');
            Route::post('/addupdate', [NewsController::class, 'addupdate'])->name('news.addupdate');
        });

        Route::group(['prefix' => 'project-badges'], function () {
            Route::get('/', [ProjectBadgeController::class, 'index'])->name('project-badge');
            Route::get('/get', [ProjectBadgeController::class, 'get'])->name('project-badge.list');
            Route::get('/detail', [ProjectBadgeController::class, 'detail'])->name('project-badge.detail');
            Route::post('/delete', [ProjectBadgeController::class, 'delete'])->name('project-badge.delete');
            Route::post('/addupdate', [ProjectBadgeController::class, 'addupdate'])->name('project-badge.addupdate');
        });

        Route::group(['prefix' => 'download-brochure'], function () {
            Route::get('/', [DownloadBrochureDataController::class, 'index'])->name('download-brochure');
            Route::get('/get', [DownloadBrochureDataController::class, 'get'])->name('download-brochure.list');
        });

        Route::group(['prefix' => 'insight-reports'], function () {
            Route::get('/', [InsightReportDataController::class, 'index'])->name('insight-reports');
            Route::get('/get', [InsightReportDataController::class, 'get'])->name('insight-reports.list');
        });

        Route::group(['prefix' => 'subscriber'], function () {
            Route::get('/', [SubscriberController::class, 'index'])->name('subscriber');
            Route::get('/get', [SubscriberController::class, 'get'])->name('subscriber.list');
        });
    });
});
