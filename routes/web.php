<?php

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


Route::get('/', function () {
    return view('welcome');
});

Route::get('/error', function () {
    return view('error');
});


Auth::routes(['verify' => true]);

//HOME CONTROLLER
Route::get('/home ', function() {
    return redirect()->route('home');
});


Route::get('/dashboard', 'HomeController@index')->name('home');
Route::get('/dashboard/{form_id}/{form_type}', 'HomeController@show_form')->name('show-form');
Route::get('/dashboard/get_current_request', "HomeController@current_request")->name('get-current-request');
Route::get('/dashboard/get_form_setup_status', "HomeController@get_form_setup_status")->name('get-form-setup-status');
Route::post('/dashboard/dl_enhance_request', 'HomeController@submit_form_request')->name('submit-form-request');
Route::post('/dashboard/vpn_wifi_request', 'HomeController@submit_vpn_wifi')->name('submit-vpn-wifi');

// FINANCE
Route::post('/dashboard/finance_form_request', 'FinanceRequestController@finance_form_request')->name('finance_form-request');


Route::get('/admin', 'HomeController@admin')->name('admin');
Route::get("/admin/approvals", 'HomeController@approval_dashboard')->name('approval-dashboard');
Route::get("/admin/{type}/{query}", "HomeController@get_all_requests")->name('form-requests');
Route::get("/admin/settings", 'HomeController@settings_dashboard')->name('settings-dashboard');

// displays root admin dashboard
Route::get("/root", "RootController@root_dashboard")->name('root-dashboard');

/*
*  Root admin: Handles create, update, delete and fetch actions for category
*/
Route::post('/root/add_update_category', "RootController@add_update_category")->name('root-add-update-category');
Route::get('/root/fetch_categories', "RootController@fetch_categories" )->name('root-fetch-categories');
Route::post('/root/delete_category', "RootController@delete_category" )->name('root-delete-category');

/*
*  Root admin: Handles create, update, delete and fetch actions for forms
*/
Route::post('/root/add_update_form', "RootController@add_update_form")->name('root-add-update-form');
Route::get('/root/fetch_forms', "RootController@fetch_forms" )->name('root-fetch-forms');
Route::post('/root/delete_form', "RootController@delete_form" )->name('root-delete-form');


/*
*  Root admin: Role setting
*/
Route::post('/admin/add_update_role', "SettingController@add_update_role" )->name('admin-add-update-role');
Route::get('/admin/fetch_roles', "SettingController@fetch_roles" )->name('admin-fetch-roles');
Route::post('/delete_role', "SettingController@delete_roles" )->name('admin-delete-role');

/*
* Admin: Approver setting
*/
Route::post('/admin/add_update_approver', "SettingController@add_update_approver" )->name('admin-add-update-approver');
Route::get('/admin/fetch_approvers', "SettingController@fetch_approvers" )->name('admin-fetch-approver');
Route::post('/admin/delete_approver', "SettingController@delete_approver" )->name('admin-delete-approver');


/*
* Api calls initiated from the admin approval dashboard
*/
Route::get("/admin/approvals/get_all_staff_approval_roles/{approver_email}", "ApprovalActionsController@get_all_roles_by_staff");//
Route::get("/admin/requests/{form_type}/{request_status}", "ApprovalActionsController@get_all_requests_to_staff");
Route::get('/admin/all_roles', "ApprovalActionsController@assigned_form_roles")->name('form-roles');
Route::post('/admin/delete_role', "ApprovalActionsController@delete_role")->name('delete-role');
Route::get('/admin/get_all_requests_for_owned_forms', "ApprovalActionsController@get_all_requests_for_owned_forms");//
Route::post('/admin/reset_request', "ApprovalActionsController@reset_request")->name('reset-requests');
Route::post('/admin/send_reminder', "ApprovalActionsController@send_reminder")->name('send-reminder');
Route::post('/admin/create_new_role', "ApprovalActionsController@create_role")->name('create-new-role');

Route::get('/reminder/{id}', 'HomeController@reminder')->name('reminder');
Route::post('/download_report', 'HomeController@download_report')->name('download-report');
Route::post('/home/role_sort', 'HomeController@role_sort');


//EMPLOYEE CONTROLLER
Route::get('/home/profile', 'EmployeeController@profile')->name('profile');
Route::get('/home/profile/{staff_id}', 'EmployeeController@get_staff_profile_details');
Route::get('/change_role/{new_role?}/{access_level?}', 'EmployeeController@change_role')->name('change-role');


//REQUEST CONTROLLER ROUTES
Route::post('/get_staff_details', 'RequestController@get_staff_details');
Route::post('/vue_submit', 'RequestController@vue_submit');
Route::get('/root_setup', 'RequestController@root_setup');
Route::post('create_admin', 'RequestController@create_admin')->name('new_admin');


//DLEnhance
Route::get("requests/{type}/{id}/{staff_id}", "AccessApprovalController@dl_enhance_approval")->name('dl-enhance-approval');
Route::post("requests/approval", "AccessApprovalController@requests_approval_action")->name('dl-enhance-approval-action');
Route::get("requests/get_current_approvals/{request_id}/{form_id}/{request_type?}", "AccessApprovalController@get_current_approvals")->name('get_current_approvals');
Route::post("requests/bulk_approval", "AccessApprovalController@bulk_approval")->name('bulk_approval');
