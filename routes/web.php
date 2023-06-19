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
    //account
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/profile', [App\Http\Controllers\UserController::class, 'index'])->name('profile');
    Route::post('/home/profile/edit', [App\Http\Controllers\UserController::class, 'update'])->name('editprofile');
    Route::post('/home/profile/editpassword', [App\Http\Controllers\UserController::class, 'changepassword'])->name('changepassword');
    Route::post('/home/profile/deleteaccount', [App\Http\Controllers\UserController::class, 'deleteaccount'])->name('deleteaccount');

    //participant
    //livestream
    Route::get('/livestreambyclass', [App\Http\Controllers\LivestreamController::class, 'indexParticipant'])->name('livestreambyclass');
    Route::get('/livestream/view/{id}', [App\Http\Controllers\LivestreamController::class, 'viewLivestream'])->name('viewlivestream');

    //module
    Route::get('/module', [App\Http\Controllers\ModuleController::class, 'index'])->name('module');

    //chat
    Route::get('/chat', [App\Http\Controllers\vendor\Chatify\MessagesController::class, 'index'])->name('chat');
    
    //module
    Route::get('/module/viewmodule/{id}', [App\Http\Controllers\ModuleController::class, 'viewModule'])->name('viewmodule');
    Route::get('/module/viewmoduleparticipant/{id}', [App\Http\Controllers\ModuleController::class, 'viewModuleParticipant'])->name('viewmoduleparticipant');
    Route::post('/module/viewmodule/createsection', [App\Http\Controllers\ModuleController::class, 'createSection'])->name('createSection');
    Route::post('/module/viewmodule/deletesection', [App\Http\Controllers\ModuleController::class, 'deleteSection'])->name('deleteSection');
    Route::get('/module/section/edit/{id}', [App\Http\Controllers\ModuleController::class, 'viewSection'])->name('viewSection');
    Route::post('/module/section/edit', [App\Http\Controllers\ModuleController::class, 'editSection'])->name('editSection');

    Route::post('/module/viewmodule/addcontent', [App\Http\Controllers\ModuleController::class, 'addContent'])->name('addContent');
    Route::get('/module/content/edit/{id}', [App\Http\Controllers\ModuleController::class, 'viewContent'])->name('viewContent');
    Route::post('/module/content/edit', [App\Http\Controllers\ModuleController::class, 'editContent'])->name('editContent');
    Route::post('/module/content/deletecontent', [App\Http\Controllers\ModuleController::class, 'deleteContent'])->name('deleteContent');

    Route::post('/module/viewmodule/addfile', [App\Http\Controllers\ModuleController::class, 'addFile'])->name('addFile');
    Route::get('/module/viewmodule/viewfile/{id}', [App\Http\Controllers\ModuleController::class, 'viewFile'])->name('viewFile');
    Route::get('/module/viewmodule/viewfile/viewfilepdf/{id}', [App\Http\Controllers\ModuleController::class, 'viewFilePDF'])->name('viewFilePDF');
    Route::get('/module/viewmodule/viewfile/viewfilezip/{id}', [App\Http\Controllers\ModuleController::class, 'viewFileZip'])->name('viewFileZip');
    Route::get('/module/viewmodule/viewfile/viewfileword/{id}', [App\Http\Controllers\ModuleController::class, 'viewFileWord'])->name('viewFileWord');
    Route::post('/module/viewmodule/deletefile', [App\Http\Controllers\ModuleController::class, 'deleteFile'])->name('deleteFile');
    Route::post('/module/viewmodule/viewfile/edit', [App\Http\Controllers\ModuleController::class, 'viewFileforEdit'])->name('viewFileforEdit');
    Route::post('/module/viewmodule/viewfile/edit/content', [App\Http\Controllers\ModuleController::class, 'editFile'])->name('editFile');
    Route::post('/module/viewmodule/viewfile/edit/image', [App\Http\Controllers\ModuleController::class, 'editFileImage'])->name('editFileImage');
    Route::post('/module/viewmodule/viewfile/edit/url', [App\Http\Controllers\ModuleController::class, 'editFileURL'])->name('editFileURL');
    Route::post('/module/viewmodule/viewfile/edit/file', [App\Http\Controllers\ModuleController::class, 'editFileAllType'])->name('editFileAllType');

    //mark as done
    Route::post('/module/viewmodule/viewfile/done', [App\Http\Controllers\ModuleController::class, 'markAsDone'])->name('markAsDone');
    Route::post('/module/viewmodule/viewfile/undone', [App\Http\Controllers\ModuleController::class, 'unmarkAsDone'])->name('unmarkAsDone');

    //submission
    Route::get('/submission', [App\Http\Controllers\ModuleController::class, 'submissionIndex'])->name('submissionIndex');
    Route::get('/submission/viewsubmissionbyid/{id}', [App\Http\Controllers\ModuleController::class, 'viewSubmissionById'])->name('viewSubmissionById');
    Route::get('/submission/viewsubmission/{id}', [App\Http\Controllers\ModuleController::class, 'viewSubmission'])->name('viewSubmission');
    Route::post('/submission/viewsubmission/add', [App\Http\Controllers\ModuleController::class, 'addSubmission'])->name('addSubmission');
    Route::post('/submission/viewsubmission/add/submit', [App\Http\Controllers\ModuleController::class, 'submitFile'])->name('submitFile');
    Route::post('/submission/viewsubmission/edit', [App\Http\Controllers\ModuleController::class, 'editSubmission'])->name('editSubmission');
    Route::post('/submission/viewsubmission/edit/submit', [App\Http\Controllers\ModuleController::class, 'editSubmittedFile'])->name('editSubmittedFile');
    Route::post('/submission/viewsubmission/delete', [App\Http\Controllers\ModuleController::class, 'removeSubmission'])->name('removeSubmission');
    Route::get('/submission/viewsubmission/get/{id}', [App\Http\Controllers\ModuleController::class, 'getSubmission'])->name('getSubmission');

    //report
    Route::get('/report', [App\Http\Controllers\ReportController::class, 'viewReportParticipant'])->name('viewReportParticipant');
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
    Route::get('/home/manageparticipant/group', [App\Http\Controllers\ManageParticipantController::class, 'viewGroup'])->name('grouplist');
    Route::post('/home/manageparticipant/group/create', [App\Http\Controllers\ManageParticipantController::class, 'createGroup'])->name('createGroup');
    Route::get('/home/manageparticipant/class/groupbyclass', [App\Http\Controllers\ManageParticipantController::class, 'viewGroupbyClass'])->name('groupbyclass');
    Route::post('/home/manageparticipant/class/group/remove', [App\Http\Controllers\ManageParticipantController::class, 'deleteGroup'])->name('deleteGroup');
    Route::get('/home/manageparticipant/class/group/detail/{id}', [App\Http\Controllers\ManageParticipantController::class, 'getGroupDetails'])->name('viewgroupdetail');
    Route::post('/home/manageparticipant/class/group/assignparticipant', [App\Http\Controllers\ManageParticipantController::class, 'assignParticipantToGroup'])->name('assignParticipantToGroup');
    Route::post('/home/manageparticipant/class/group/removeparticipant', [App\Http\Controllers\ManageParticipantController::class, 'removeparticipantfromgroup'])->name('removeparticipantfromgroup');

    //livestream
    Route::get('/livestream', [App\Http\Controllers\LivestreamController::class, 'index'])->name('livestream');
    Route::post('/livestream/add', [App\Http\Controllers\LivestreamController::class, 'addLivestream'])->name('addlivestream');
    Route::get('/livestream/edit/{id}', [App\Http\Controllers\LivestreamController::class, 'viewLivestreamforEdit'])->name('viewlivestreamforEdit');
    Route::post('/livestream/edit', [App\Http\Controllers\LivestreamController::class, 'editLivestream'])->name('editLivestream');
    Route::post('/livestream/delete/{id}', [App\Http\Controllers\LivestreamController::class, 'deleteLivestream'])->name('deleteLivestream');

    //module
    Route::get('/module/listofclasses', [App\Http\Controllers\ModuleController::class, 'activeclasslist'])->name('activeclasslist');
    Route::get('/module/viewmodulebyid/{id}', [App\Http\Controllers\ModuleController::class, 'viewModuleById'])->name('viewModuleById');
    Route::post('/module/addmodule', [App\Http\Controllers\ModuleController::class, 'createModule'])->name('createModule');
    Route::post('/module/deletemodule', [App\Http\Controllers\ModuleController::class, 'deleteModule'])->name('deleteModule');
    Route::get('/module/edit/{id}', [App\Http\Controllers\ModuleController::class, 'viewModuleforEdit'])->name('viewModuleforEdit');
    Route::post('/module/edit', [App\Http\Controllers\ModuleController::class, 'editModule'])->name('editModule');

    //submission
    Route::post('/submission/addfile', [App\Http\Controllers\ModuleController::class, 'createSubmission'])->name('createSubmission');
    Route::get('/submission/listofclasses', [App\Http\Controllers\ModuleController::class, 'activeclasslistsub'])->name('activeclasslistsub');
    Route::get('/submission/managesubmission/{id}', [App\Http\Controllers\ModuleController::class, 'manageSubmission'])->name('manageSubmission');
    Route::post('/submission/viewsubmissiondetail', [App\Http\Controllers\ModuleController::class, 'viewSubmissionDetail'])->name('viewSubmissionDetail');
    Route::post('/submission/editsubmissiondetail', [App\Http\Controllers\ModuleController::class, 'editSubmissionDetail'])->name('editSubmissionDetail');
    Route::post('/submission/deletesubmissiondetail', [App\Http\Controllers\ModuleController::class, 'deleteSubmissionDetail'])->name('deleteSubmissionDetail');
    Route::post('/submission/getallsubmission', [App\Http\Controllers\ModuleController::class, 'getAllSubmission'])->name('getAllSubmission');

    //report
    Route::get('/progress/listofclasses', [App\Http\Controllers\ReportController::class, 'activeclasslist'])->name('activeclassprogressreport');
    Route::get('/progress/{id}', [App\Http\Controllers\ReportController::class, 'viewProgressbyClass'])->name('viewProgressbyClass');
    Route::get('/progress/participant/{id}', [App\Http\Controllers\ReportController::class, 'viewParticipantModule'])->name('viewParticipantModule');
    
    //tournament reporting
    Route::get('/tournament/listofclasses', [App\Http\Controllers\ReportController::class, 'activeclasstournamentlist'])->name('activeclasstournamentreport');
    Route::get('/tournament/{id}', [App\Http\Controllers\ReportController::class, 'viewTournamentbyClass'])->name('viewTournamentbyClass');
    Route::get('/tournament/participant/{id}', [App\Http\Controllers\ReportController::class, 'viewTournamentbyClassParticipant'])->name('viewTournamentbyClassParticipant');
    Route::get('/tournament/group/{id}', [App\Http\Controllers\ReportController::class, 'viewTournamentbyClassGroup'])->name('viewTournamentbyClassGroup');
    Route::get('/tournament/participant/create-report/{id}', [App\Http\Controllers\ReportController::class, 'reportForm'])->name('reportForm');
    Route::post('/tournament/participant/create-report/save', [App\Http\Controllers\ReportController::class, 'createReport'])->name('createReport');
    Route::get('/tournament/participant/view-report/{id}', [App\Http\Controllers\ReportController::class, 'viewReport'])->name('viewReport');
    Route::get('/tournament/participant/view-report/edit/{id}', [App\Http\Controllers\ReportController::class, 'editReport'])->name('editReport');
    Route::post('/tournament/participant/view-report/edit/save', [App\Http\Controllers\ReportController::class, 'updateReport'])->name('updateReport');
    Route::get('/tournament/group/create-report/{id}', [App\Http\Controllers\ReportController::class, 'reportFormGroup'])->name('reportFormGroup');
    Route::get('/tournament/group/view-report/{id}', [App\Http\Controllers\ReportController::class, 'viewReportGroup'])->name('viewReportGroup');
    Route::get('/tournament/group/view-report/edit/{id}', [App\Http\Controllers\ReportController::class, 'editReportGroup'])->name('editReportGroup');
    Route::post('/tournament/participant/view-report/delete', [App\Http\Controllers\ReportController::class, 'deleteReport'])->name('deleteReport');
});

//check if user is admin and authenticated (admin only)
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/home/viewmembers', [App\Http\Controllers\AdminVerificationController::class, 'view'])->name('viewmember');
    Route::post('/home/viewmembers/verify/{id}', [App\Http\Controllers\AdminVerificationController::class, 'verifyMember'])->name('verifymember');
    Route::get('/home/viewmembers/detail/{id}', [App\Http\Controllers\AdminVerificationController::class, 'getUserDetails'])->name('viewmemberdetail');
    Route::post('/home/viewmembers/remove/{id}', [App\Http\Controllers\AdminVerificationController::class, 'removeUser'])->name('removemember');

    //report template
    Route::get('/home/createtemplate', [App\Http\Controllers\ReportController::class, 'createReportTemplate'])->name('adminCreateTemplate');
    Route::post('/home/createtemplate/save', [App\Http\Controllers\ReportController::class, 'saveReportTemplate'])->name('saveReportTemplate');
});

