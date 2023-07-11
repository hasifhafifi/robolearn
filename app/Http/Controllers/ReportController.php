<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Module;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\http\traits\learningProgressTrait;
use App\Models\File;
use App\Models\Group;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class ReportController extends Controller
{
    use learningProgressTrait;

    public function activeclasslist()
    {
         //get list of active classrooms
         $classrooms = Classroom::where('isAvailable', 1)->get();

         if(!empty($classrooms)){
             foreach($classrooms as $classroom){
                 $modules = Module::where('classroomID', $classroom->id)->get();
                 $classroom->setAttribute('modulesCount', count($modules));
             }
         }

        return view('report.activeclassprogressreport', compact('classrooms'));
    }

    public function viewProgressbyClass($id)
    {
        //get list of active classrooms
        $class = Classroom::where('id', $id)->first();
        $modules = Module::where('classroomID', $class->id)->get();
        $participants = Participant::where('participant_classcode', $class->classCode)->get();
        $arrPercentage = [];

        foreach($participants as $participant){
            $user = User::where('id', $participant->user_id)->first();
            $moduleCompletion = $this->checkIfModuleCompletionExist($participant);
            $participant->setAttribute('username', $user->username);
            $participant->setAttribute('arrPercentage', $moduleCompletion);
        }

        return view('report.learningprogress', compact('modules', 'participants', 'class'));
    }

    public function viewParticipantModule($id)
    {
        $participant = Participant::where('id', $id)->first();
        $class = Classroom::where('classCode', $participant->participant_classcode)->first();
        $modules = Module::where('classroomID', $class->id)->get();
        $moduleIDs = $modules->pluck('id');
        $files = File::whereIn('moduleID', $moduleIDs)->get();
        $fileCompletion = $this->checkIfFileCompletionExist($participant);
        $moduleCompletion = $this->checkIfModuleCompletionExist($participant);
        $participant->setAttribute('fileCompletionArr', $fileCompletion);
        $participant->setAttribute('moduleCompletionArr', $moduleCompletion);
        $user = User::where('id', $participant->user_id)->first();
        $participant->setAttribute('username', $user->username);

        return view('report.participantProgress', compact('modules', 'participant', 'class', 'files'));
    }

    public function activeclasstournamentlist()
    {
         //get list of active classrooms
         $classrooms = Classroom::where('isAvailable', 1)->get();

         if(!empty($classrooms)){
             foreach($classrooms as $classroom){
                $groups = Group::where('classroom_id', $classroom->id)->get();
                $classroom->setAttribute('groupsCount', count($groups));
             }
         }

        return view('report.activeclasstournamentreport', compact('classrooms'));
    }

    // public function viewTournamentbyClass($id)
    // {
    //     //get list of active classrooms
    //     $class = Classroom::where('id', $id)->first();
    //     $reports = Report::where('classID', $class->id)->orderBy('totalMarks', 'desc')->get();
    //     $participants = Participant::where('participant_classcode', $class->classCode)->get();
    //     $groups = Group::where('classroom_id', $class->id)->get();

    //     foreach($participants as $participant){
    //         if(isset($participant->participant_groupID)){
    //             $group = Group::where('id', $participant->participant_groupID)->first();
    //             $participant->setAttribute('groupName', $group->name);
    //         }else{
    //             $participant->setAttribute('groupName', 'Not in a group');
    //         }
    //     }

    //     // dd($reports);
        
    //     return view('report.learningprogress', compact('modules', 'participants', 'class'));
    // }

    public function viewTournamentbyClass($id)
    {
        //get list of active classrooms
        $class = Classroom::where('id', $id)->first();
        $reports = Report::where('classID', $class->id)->orderBy('totalMarks', 'desc')->get();

        foreach($reports as $report){
            if(isset($report->userID)){
                $user = User::where('id', $report->userID)->first();
                $report->setAttribute('user', $user);
            }else{
                $group = Group::where('id', $report->groupID)->first();
                $participants = Participant::where('participant_groupID', $group->id)->get();
                $participantIDs = $participants->pluck('user_id');
                $users = User::whereIn('id', $participantIDs)->get();
                $group->setAttribute('groupMembers', $users);
                $report->setAttribute('group', $group);
            }
        }

        return view('report.viewranking', compact('reports', 'class'));
    }

    public function viewTournamentbyClassParticipant($id)
    {
        $class = Classroom::where('id', $id)->first();
        $participants = Participant::where('participant_classcode', $class->classCode)->get();
        $participantIDs = $participants->pluck('user_id');
        $users = User::whereIn('id', $participantIDs)->get();

        foreach($users as $user){
            if(isset($user->participants->participant_groupID)){
                $group = Group::where('id', $user->participants->participant_groupID)->first();
                $user->setAttribute('groupName', $group->name);
            }else{
                $user->setAttribute('groupName', 'Not in a group');
            }

            //one person one report
            if(Report::where('userID', $user->id)->first()){
                $report = Report::where('userID', $user->id)->first();
                $user->setAttribute('reportID', $report->id);
            }
        }
        
        return view('report.tournamentparticipant', compact('users', 'class'));
    }

    public function viewTournamentbyClassGroup($id)
    {
        $class = Classroom::where('id', $id)->first();
        $groups = Group::where('classroom_id', $class->id)->get();

        foreach($groups as $group){
            //find the group member in the Participants table and assign it into groupmember array
            $participants = Participant::where('participant_groupID', $group->id)->get();
            $participantIDs = $participants->pluck('user_id');
            $users = User::whereIn('id', $participantIDs)->get();
            $group->setAttribute('groupMembers', $users);

            //one person one report
            if(Report::where('groupID', $group->id)->first()){
                $report = Report::where('groupID', $group->id)->first();
                $group->setAttribute('reportID', $report->id);
            }
        }
        
        return view('report.tournamentgroup', compact('groups', 'class'));
    }

    public function reportForm($id)
    {
        $user = User::where('id', $id)->first();
        $class = Classroom::where('classCode', $user->participants->participant_classcode)->first();
        return view('report.makereport', compact('user', 'class'));
    }

    public function reportFormGroup($id)
    {
        $group = Group::where('id', $id)->first();
        $class = Classroom::where('id', $group->classroom_id)->first();
        $participants = Participant::where('participant_groupID', $group->id)->get();
        $participantIDs = $participants->pluck('user_id');
        $users = User::whereIn('id', $participantIDs)->get();
        $group->setAttribute('groupMembers', $users);

        return view('report.makereport', compact('group', 'class'));
    }

    public function createReport(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'reportname' => 'required|max:255',
            'tourname' => 'required|max:255',
            'totalmarks' => 'required|numeric',
            'checkpoints' => 'required|numeric',
            'objective' => 'required|in:1,0',
            'commentdetail' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // $user = User::where('id', $request->input('userID'))->first();
        $class = Classroom::where('id', $request->input('classID'))->first();
        $report = new Report();
    
        // Assign the form data to the model properties
        $report->name = $request->input('reportname');
        $report->tournamentName = $request->input('tourname');
        $report->totalmarks = $request->input('totalmarks');
        $report->totalCheckpoints = $request->input('checkpoints');
        $report->passObjective = $request->input('objective');
        $report->comment = $request->input('commentdetail');
        $report->classID = $class->id;

        //check whether its for group or individual
        if($request->input('userID') != null){
            $report->userID = $request->input('userID');
        }else{
            $report->groupID = $request->input('groupID');
        }

        // Save the report
        $report->save();

        //return to its respective page
        if($request->input('userID') != null){
            return redirect()->route('viewTournamentbyClassParticipant', ['id' => $class->id])->with('success', 'Report added succesfully');
        }else{
            return redirect()->route('viewTournamentbyClassGroup', ['id' => $class->id])->with('success', 'Report added succesfully');
        }
    }

    public function viewReport($id)
    {
        $report = Report::where('id', $id)->first();
        $user = User::where('id', $report->userID)->first();
        $class = Classroom::where('classCode', $user->participants->participant_classcode)->first();
        return view('report.viewreport', compact('report', 'user', 'class'));
    }

    public function viewReportGroup($id)
    {
        $report = Report::where('id', $id)->first();
        $group = Group::where('id', $report->groupID)->first();
        $class = Classroom::where('id', $group->classroom_id)->first();
        $participants = Participant::where('participant_groupID', $group->id)->get();
        $participantIDs = $participants->pluck('user_id');
        $users = User::whereIn('id', $participantIDs)->get();
        $group->setAttribute('groupMembers', $users);
        $class = Classroom::where('id', $report->classID)->first();
        return view('report.viewreport', compact('report', 'group', 'class'));
    }

    public function editReport($id)
    {
        $report = Report::where('id', $id)->first();
        $user = User::where('id', $report->userID)->first();
        $class = Classroom::where('classCode', $user->participants->participant_classcode)->first();
        return view('report.editreport', compact('report', 'user', 'class'));
    }

    public function editReportGroup($id)
    {
        $report = Report::where('id', $id)->first();
        $group = Group::where('id', $report->groupID)->first();
        $class = Classroom::where('id', $group->classroom_id)->first();
        $participants = Participant::where('participant_groupID', $group->id)->get();
        $participantIDs = $participants->pluck('user_id');
        $users = User::whereIn('id', $participantIDs)->get();
        $group->setAttribute('groupMembers', $users);
        return view('report.editreport', compact('report', 'group', 'class'));
    }

    public function updateReport(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'reportname' => 'required|max:255',
            'tourname' => 'required|max:255',
            'totalmarks' => 'required|numeric',
            'checkpoints' => 'required|numeric',
            'objective' => 'required|in:1,0',
            'commentdetail' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $class = Classroom::where('id', $request->input('classID'))->first();
        $report = Report::where('id', $request->input('reportID'))->first();
    
        // Assign the form data to the model properties
        $report->name = $request->input('reportname');
        $report->tournamentName = $request->input('tourname');
        $report->totalmarks = $request->input('totalmarks');
        $report->totalCheckpoints = $request->input('checkpoints');
        $report->passObjective = $request->input('objective');
        $report->comment = $request->input('commentdetail');

        // Save the report
        $report->save();

        //return to its respective page
        if($request->input('userID') != null){
            return redirect()->route('viewReport', ['id' => $report->id])->with('success', 'Report Edited succesfully');
        }else{
            return redirect()->route('viewReportGroup', ['id' => $report->id])->with('success', 'Report Edited succesfully');
        }
    }

    public function deleteReport(Request $request)
    {
        $report = Report::where('id', $request->input('reportID'))->first();
        $report->delete();

        $class = Classroom::where('id', $request->input('classID'))->first();
        //return to its respective page
        if($request->input('userID') != null){
            return redirect()->route('viewTournamentbyClassParticipant', ['id' => $class->id])->with('success', 'Report deleted succesfully');
        }else{
            return redirect()->route('viewTournamentbyClassGroup', ['id' => $class->id])->with('success', 'Report deleted succesfully');
        }
    }

    public function viewReportParticipant()
    {
        $user = Auth::user();
        $class = Classroom::where('classCode', $user->participants->participant_classcode)->first();

        //get report for group
        $group = Group::where('id', $user->participants->participant_groupID)->first();
        // $reportGroup = new Report();
        if(isset($group) && Report::where('groupID', $group->id)->first()){
            $reportGroup = Report::where('groupID', $group->id)->first();
            $participants = Participant::where('participant_groupID', $group->id)->get();
            $participantIDs = $participants->pluck('user_id');
            $users = User::whereIn('id', $participantIDs)->get();
            $group->setAttribute('groupMembers', $users);
        }
        
        //ranking
        $reports = Report::where('classID', $class->id)->orderBy('totalMarks', 'desc')->get();

        foreach($reports as $report){
            if(isset($report->userID)){
                $userDetail = User::where('id', $report->userID)->first();
                $report->setAttribute('user', $userDetail);
            }else{
                $group = Group::where('id', $report->groupID)->first();
                $participants = Participant::where('participant_groupID', $group->id)->get();
                $participantIDs = $participants->pluck('user_id');
                $users = User::whereIn('id', $participantIDs)->get();
                $group->setAttribute('groupMembers', $users);
                $report->setAttribute('group', $group);
            }
        }
 
        //get report for individual
        $reportUser = Report::where('userID', $user->id)->first();
        if(isset($reportGroup)){
            // dd($user);
            return view('report.viewreportparticipant', compact('user', 'group', 'reportGroup', 'reportUser', 'reports', 'class'));
        }else{
            return view('report.viewreportparticipant', compact('user', 'group', 'reportUser', 'reports', 'class'));
        }
    }

    public function createReportTemplate()
    {
        return view('report.createreporttemplate');
    }

    public function saveReportTemplate(Request $request)
    {
        dd($request->all());
        return view('report.createreporttemplate');
    }
}
