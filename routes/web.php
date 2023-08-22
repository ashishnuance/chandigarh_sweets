<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CssController;
use App\Http\Controllers\BasicUiController;
use App\Http\Controllers\AdvanceUiController;
use App\Http\Controllers\ExtraComponentsController;
use App\Http\Controllers\BasicTableController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ChartController;
/** new controllers **/
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductCompanyMapping;


use App\Http\Controllers\SubCategoryController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

// Route::prefix('superadmin')->group(function () { 
    Route::get('/superadmin-login', [LoginController::class, 'showLoginFormSuperadmin']);
    Route::post('/superadmin-login', [LoginController::class, 'postLoginFormSuperadmin'])->name('superadmin-login');
// });



Route::group(['middleware' => ['auth']], function () { 


    Route::get('/logout', [LoginController::class, 'logout']);
    /** country state city **/
    Route::post('api/fetch-states', [CompanyController::class, 'fetchState']);
    Route::post('api/fetch-cities', [CompanyController::class, 'fetchCity']);
    Route::get('image/{filename}', [DashboardController::class,'displayImage'])->name('image.displayImage');
    /** new routes start **/
    Route::prefix('superadmin')->middleware(['superadmin'])->group(function () { 
        Route::get('/', [DashboardController::class, 'dashboardSuperadminModern']);
        Route::get('/login', [LoginController::class, 'showLoginForm']);
        /** company route */
        Route::resource('/company', CompanyController::class);
        Route::post('/company-import', [CompanyController::class,'companyImport'])->name('company-import');
        Route::get('/company-export', [CompanyController::class,'companyExport']);
        Route::get('/company/delete/{id}', [CompanyController::class,'destroy'])->name('company.destroy');
        
        /** company admin **/
        Route::get('/company-admin-create', [UserController::class, 'create']);
        Route::get('/company-admin-edit/{id}', [UserController::class, 'create'])->name('company-admin-edit');
        Route::post('/company-admin-update/{id}', [UserController::class, 'update'])->name('company-admin-update');
        Route::get('/company-admin-list', [UserController::class, 'index'])->name('company-admin-list');
        Route::post('/company-admin-create', [UserController::class, 'store'])->name('company-admin-create');

        Route::get('/company-user-create', [UserController::class, 'usersCreate']);
        Route::get('/company-user-edit/{id}', [UserController::class, 'usersCreate'])->name('company-user-edit');
        Route::post('/company-user-update/{id}', [UserController::class, 'usersUpdate'])->name('company-user-update');
        Route::get('/company-user-list', [UserController::class, 'usersList'])->name('company-user-list');
        Route::post('/company-user-create', [UserController::class, 'userStore'])->name('company-user-create');

        /** new buyer routes start **/
        Route::resource('/buyer-type', BuyerController::class);
        Route::get('/buyer-type/delete/{id}', [BuyerController::class,'destroy'])->name('buyer-type.destroy');
        
        /** new products routes **/
        Route::resource('/product',ProductsController::class);
        Route::get('/product',[ProductsController::class,'index'])->name('superadmin.product.index');
        Route::get('/product/{$id}/edit',[ProductsController::class,'edit'])->name('superadmin.product.edit');
        Route::post('/product',[ProductsController::class,'store'])->name('superadmin.product.store');
        Route::patch('/product/{$id}',[ProductsController::class,'update'])->name('superadmin.product.update');
        Route::get('/product/destroy/{id}',[ProductsController::class,'destroy'])->name('superadmin.product.delete');
        
        /** product category **/
        Route::resource('/product-category',ProductCategoryController::class);
        Route::get('/product-category/destroy/{id}',[ProductCategoryController::class,'destroy'])->name('product-category.delete');

        /** product company mapping **/
        Route::resource('/product-mapping',ProductCompanyMapping::class);

          /** sub category **/
          Route::resource('/sub-category',SubCategoryController::class);
          Route::get('/sub-category/destroy/{id}',[SubCategoryController::class,'destroy'])->name('sub-category.delete');
        
    });

    
    /** product import and export **/
    Route::post('/product-import', [ProductsController::class,'productImport']);
    Route::get('/product-export/{type?}', [ProductsController::class,'productExport'])->name('product-export');
    
    Route::post('/company-user-import', [UserController::class,'companyUserImport']);
    Route::get('/company-user-export/{type?}', [UserController::class,'companyUserExport'])->name('company-user-export');
    Route::get('/company-user-import', [UserController::class,'companyUserExport'])->name('company-user-import');

    Route::middleware(['companyadmin'])->group(function () {
        Route::get('/', [DashboardController::class, 'dashboardModern']);
        /** company user **/
        Route::get('/company-user-create', [UserController::class, 'usersCreate'])->name('company-user-create');
        Route::get('/company-user-edit/{id}', [UserController::class, 'usersCreate'])->name('company-user-edit');
        Route::post('/company-user-update/{id}', [UserController::class, 'usersUpdate'])->name('company-user-update');
        Route::get('/company-user-list', [UserController::class, 'usersList'])->name('companyadmin.company-user-list');
        Route::post('/company-user-create', [UserController::class, 'userStore'])->name('company-user-create');

        /** new products routes **/
        //Route::resource('/product',ProductsController::class);
        /** new products routes **/
        Route::resource('/product',ProductsController::class);
        Route::get('/product/destroy/{id}',[ProductsController::class,'destroy'])->name('product.delete');
        
    });
    /** state and city **/
    Route::post('api/user-fetch-states', [DashboardController::class, 'user_fetchState']);
    Route::post('api/user-fetch-cities', [DashboardController::class, 'user_fetchCity']);
    Route::post('api/fetch-subcategory', [DashboardController::class, 'product_fetchSubcategory']);
    

    /** new routes end **/



    // Dashboard Route
    // Route::get('/', [DashboardController::class, 'dashboardModern'])->middleware('verified');
    Route::get('/', [DashboardController::class, 'dashboardModern']);

    Route::get('/modern', [DashboardController::class, 'dashboardModern']);
    Route::get('/ecommerce', [DashboardController::class, 'dashboardEcommerce']);
    Route::get('/analytics', [DashboardController::class, 'dashboardAnalytics']);

    // Application Route
    Route::get('/app-email', [ApplicationController::class, 'emailApp']);
    Route::get('/app-email/content', [ApplicationController::class, 'emailContentApp']);
    Route::get('/app-chat', [ApplicationController::class, 'chatApp']);
    Route::get('/app-todo', [ApplicationController::class, 'todoApp']);
    Route::get('/app-kanban', [ApplicationController::class, 'kanbanApp']);
    Route::get('/app-file-manager', [ApplicationController::class, 'fileManagerApp']);
    Route::get('/app-contacts', [ApplicationController::class, 'contactApp']);
    Route::get('/app-calendar', [ApplicationController::class, 'calendarApp']);
    Route::get('/app-invoice-list', [ApplicationController::class, 'invoiceList']);
    Route::get('/app-invoice-view', [ApplicationController::class, 'invoiceView']);
    Route::get('/app-invoice-edit', [ApplicationController::class, 'invoiceEdit']);
    Route::get('/app-invoice-add', [ApplicationController::class, 'invoiceAdd']);
    Route::get('/eCommerce-products-page', [ApplicationController::class, 'ecommerceProduct']);
    Route::get('/eCommerce-pricing', [ApplicationController::class, 'eCommercePricing']);

    // User profile Route
    Route::get('/user-profile-page', [UserProfileController::class, 'userProfile']);

    // Page Route
    Route::get('/page-contact', [PageController::class, 'contactPage']);
    Route::get('/page-blog-list', [PageController::class, 'pageBlogList']);
    Route::get('/page-search', [PageController::class, 'searchPage']);
    Route::get('/page-knowledge', [PageController::class, 'knowledgePage']);
    Route::get('/page-knowledge/licensing', [PageController::class, 'knowledgeLicensingPage']);
    Route::get('/page-knowledge/licensing/detail', [PageController::class, 'knowledgeLicensingPageDetails']);
    Route::get('/page-timeline', [PageController::class, 'timelinePage']);
    Route::get('/page-faq', [PageController::class, 'faqPage']);
    Route::get('/page-faq-detail', [PageController::class, 'faqDetailsPage']);
    Route::get('/page-account-settings', [PageController::class, 'accountSetting']);
    Route::get('/page-blank', [PageController::class, 'blankPage']);
    Route::get('/page-collapse', [PageController::class, 'collapsePage']);

    // Media Route
    Route::get('/media-gallery-page', [MediaController::class, 'mediaGallery']);
    Route::get('/media-hover-effects', [MediaController::class, 'hoverEffect']);

    // User Route
    Route::get('/page-users-list', [UserController::class, 'usersList']);
    Route::get('/page-users-view', [UserController::class, 'usersView']);
    Route::get('/page-users-edit', [UserController::class, 'usersEdit']);



    // Card Route
    Route::get('/cards-basic', [CardController::class, 'cardBasic']);
    Route::get('/cards-advance', [CardController::class, 'cardAdvance']);
    Route::get('/cards-extended', [CardController::class, 'cardsExtended']);

    // Css Route
    Route::get('/css-typography', [CssController::class, 'typographyCss']);
    Route::get('/css-color', [CssController::class, 'colorCss']);
    Route::get('/css-grid', [CssController::class, 'gridCss']);
    Route::get('/css-helpers', [CssController::class, 'helpersCss']);
    Route::get('/css-media', [CssController::class, 'mediaCss']);
    Route::get('/css-pulse', [CssController::class, 'pulseCss']);
    Route::get('/css-sass', [CssController::class, 'sassCss']);
    Route::get('/css-shadow', [CssController::class, 'shadowCss']);
    Route::get('/css-animations', [CssController::class, 'animationCss']);
    Route::get('/css-transitions', [CssController::class, 'transitionCss']);

    // Basic Ui Route
    Route::get('/ui-basic-buttons', [BasicUiController::class, 'basicButtons']);
    Route::get('/ui-extended-buttons', [BasicUiController::class, 'extendedButtons']);
    Route::get('/ui-icons', [BasicUiController::class, 'iconsUI']);
    Route::get('/ui-alerts', [BasicUiController::class, 'alertsUI']);
    Route::get('/ui-badges', [BasicUiController::class, 'badgesUI']);
    Route::get('/ui-breadcrumbs', [BasicUiController::class, 'breadcrumbsUI']);
    Route::get('/ui-chips', [BasicUiController::class, 'chipsUI']);
    Route::get('/ui-chips', [BasicUiController::class, 'chipsUI']);
    Route::get('/ui-collections', [BasicUiController::class, 'collectionsUI']);
    Route::get('/ui-navbar', [BasicUiController::class, 'navbarUI']);
    Route::get('/ui-pagination', [BasicUiController::class, 'paginationUI']);
    Route::get('/ui-preloader', [BasicUiController::class, 'preloaderUI']);

    // Advance UI Route
    Route::get('/advance-ui-carousel', [AdvanceUiController::class, 'carouselUI']);
    Route::get('/advance-ui-collapsibles', [AdvanceUiController::class, 'collapsibleUI']);
    Route::get('/advance-ui-toasts', [AdvanceUiController::class, 'toastUI']);
    Route::get('/advance-ui-tooltip', [AdvanceUiController::class, 'tooltipUI']);
    Route::get('/advance-ui-dropdown', [AdvanceUiController::class, 'dropdownUI']);
    Route::get('/advance-ui-feature-discovery', [AdvanceUiController::class, 'discoveryFeature']);
    Route::get('/advance-ui-media', [AdvanceUiController::class, 'mediaUI']);
    Route::get('/advance-ui-modals', [AdvanceUiController::class, 'modalUI']);
    Route::get('/advance-ui-scrollspy', [AdvanceUiController::class, 'scrollspyUI']);
    Route::get('/advance-ui-tabs', [AdvanceUiController::class, 'tabsUI']);
    Route::get('/advance-ui-waves', [AdvanceUiController::class, 'wavesUI']);
    Route::get('/fullscreen-slider-demo', [AdvanceUiController::class, 'fullscreenSlider']);

    // Extra components Route
    Route::get('/extra-components-range-slider', [ExtraComponentsController::class, 'rangeSlider']);
    Route::get('/extra-components-sweetalert', [ExtraComponentsController::class, 'sweetAlert']);
    Route::get('/extra-components-nestable', [ExtraComponentsController::class, 'nestAble']);
    Route::get('/extra-components-treeview', [ExtraComponentsController::class, 'treeView']);
    Route::get('/extra-components-ratings', [ExtraComponentsController::class, 'ratings']);
    Route::get('/extra-components-tour', [ExtraComponentsController::class, 'tour']);
    Route::get('/extra-components-i18n', [ExtraComponentsController::class, 'i18n']);
    Route::get('/extra-components-highlight', [ExtraComponentsController::class, 'highlight']);

    // Basic Tables Route
    Route::get('/table-basic', [BasicTableController::class, 'tableBasic']);

    // Data Table Route
    Route::get('/table-data-table', [DataTableController::class, 'dataTable']);

    // Form Route
    Route::get('/form-elements', [FormController::class, 'formElement']);
    Route::get('/form-select2', [FormController::class, 'formSelect2']);
    Route::get('/form-validation', [FormController::class, 'formValidation']);
    Route::get('/form-masks', [FormController::class, 'masksForm']);
    Route::get('/form-editor', [FormController::class, 'formEditor']);
    Route::get('/form-file-uploads', [FormController::class, 'fileUploads']);
    Route::get('/form-layouts', [FormController::class, 'formLayouts']);
    Route::get('/form-wizard', [FormController::class, 'formWizard']);

    // Charts Route
    Route::get('/charts-chartjs', [ChartController::class, 'chartJs']);
    Route::get('/charts-chartist', [ChartController::class, 'chartist']);
    Route::get('/charts-sparklines', [ChartController::class, 'sparklines']);


    // locale route
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);
});

// Authentication Route
Route::get('/user-login', [AuthenticationController::class, 'userLogin']);
Route::get('/user-register', [AuthenticationController::class, 'userRegister']);
Route::get('/user-forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::get('/user-lock-screen', [AuthenticationController::class, 'lockScreen']);

// Misc Route
Route::get('/page-404', [MiscController::class, 'page404']);
Route::get('/page-maintenance', [MiscController::class, 'maintenancePage']);
Route::get('/page-500', [MiscController::class, 'page500']);