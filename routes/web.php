<?php

use Illuminate\Support\Facades\Route;

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

//ori
Route::get('/', function () {
    return view('auth.login');
});


//template
Route::get('/template', function () {
    return view('template');
});

Route::get('/dashboard', function () {
    return view('participants/dashboard');
});

Route::get('/signin', function () {
    return view('signin');
});

Route::get('/signup', function () {
    return view('signup');
});

Auth::routes();

//check if user is authenticated (general user)
Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/profile', [App\Http\Controllers\UserController::class, 'index'])->name('profile');
    Route::post('/home/profile/edit', [App\Http\Controllers\UserController::class, 'update'])->name('editprofile');
    Route::post('/home/profile/editpassword', [App\Http\Controllers\UserController::class, 'changepassword'])->name('changepassword');
    Route::post('/home/profile/deleteaccount', [App\Http\Controllers\UserController::class, 'deleteaccount'])->name('deleteaccount');
});

//check if user is member/admin and authenticated
Route::group(['middleware' => ['auth', 'member']], function () {
    //classroom
    Route::get('/home/manageparticipant/class', [App\Http\Controllers\ClassroomController::class, 'index'])->name('classroom');
    Route::post('/home/manageparticipant/class/create', [App\Http\Controllers\ClassroomController::class, 'createClassroom'])->name('createClassroom');
    Route::post('/home/manageparticipant/class/changeAvailability', [App\Http\Controllers\ClassroomController::class, 'changeClassStatus'])->name('changeClassroomAvailability');
    Route::post('/home/manageparticipant/class/updateRegistration', [App\Http\Controllers\ClassroomController::class, 'updateRegistration'])->name('updateRegistration');
    
    //participant
    Route::get('/home/manageparticipant/class/participant', [App\Http\Controllers\ManageParticipantController::class, 'viewParticipant'])->name('participant');
    Route::get('/home/manageparticipant/class/participantbyclass', [App\Http\Controllers\ManageParticipantController::class, 'viewParticipantbyClass'])->name('participantbyclass');
    Route::get('/home/manageparticipant/class/participant/detail/{id}', [App\Http\Controllers\ManageParticipantController::class, 'getParticipantDetails'])->name('viewparticipantdetail');
    Route::post('/home/manageparticipant/class/participant/remove', [App\Http\Controllers\ManageParticipantController::class, 'removeParticipant'])->name('removeparticipant');

    //group
    Route::get('/home/manageparticipant/group', [App\Http\Controllers\ManageParticipantController::class, 'viewGroup'])->name('group');
    Route::post('/home/manageparticipant/group/create', [App\Http\Controllers\ManageParticipantController::class, 'createGroup'])->name('createGroup');
    Route::get('/home/manageparticipant/class/groupbyclass', [App\Http\Controllers\ManageParticipantController::class, 'viewGroupbyClass'])->name('groupbyclass');
    Route::post('/home/manageparticipant/class/group/remove', [App\Http\Controllers\ManageParticipantController::class, 'deleteGroup'])->name('deleteGroup');
    Route::get('/home/manageparticipant/class/group/detail/{id}', [App\Http\Controllers\ManageParticipantController::class, 'getGroupDetails'])->name('viewgroupdetail');
    Route::post('/home/manageparticipant/class/group/assignparticipant', [App\Http\Controllers\ManageParticipantController::class, 'assignParticipantToGroup'])->name('assignParticipantToGroup');
    Route::post('/home/manageparticipant/class/group/removeparticipant', [App\Http\Controllers\ManageParticipantController::class, 'removeparticipantfromgroup'])->name('removeparticipantfromgroup');
});

//check if user is admin and authenticated (admin only)
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/home/viewmembers', [App\Http\Controllers\AdminVerificationController::class, 'view'])->name('viewmember');
    Route::post('/home/viewmembers/verify/{id}', [App\Http\Controllers\AdminVerificationController::class, 'verifyMember'])->name('verifymember');
    Route::get('/home/viewmembers/detail/{id}', [App\Http\Controllers\AdminVerificationController::class, 'getUserDetails'])->name('viewmemberdetail');
    Route::post('/home/viewmembers/remove/{id}', [App\Http\Controllers\AdminVerificationController::class, 'removeUser'])->name('removemember');
});

