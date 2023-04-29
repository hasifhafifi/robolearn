<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Group;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;

class ManageParticipantController extends Controller
{
    public function viewParticipant()
    {
        //get list of classrooms for dropdown class selection
        $classrooms = Classroom::where('isAvailable', 1)->get();
        
        $participant = Array();
        $emptyParticipant = [];
        $groupArray = [];

        //get participants for the first available class
        $classView = Classroom::where('isAvailable', 1)->first();

        if($classView){
            if(!empty($classView->classParticipant)){
                $arrayParticipant = json_decode($classView->classParticipant,true);
                foreach($arrayParticipant as $part){
                    $parti = User::find($part);
                    if(isset($parti->participants->participant_groupID)){
                        $group = Group::find($parti->participants->participant_groupID);
                        $parti->setAttribute('group', $group);
                    }
                    array_push($participant, $parti);
                }
                $classView->setAttribute('arrayParticipant', $participant);
                $participant = [];
            }else{
                $classView->setAttribute('arrayParticipant', $emptyParticipant);
            }
        }

        //  dd($classView);
        $isVerifiedMember = session('isVerifiedMember');
        return view('member.participant', compact('isVerifiedMember', 'classrooms', 'classView'));
    }

    public function viewParticipantbyClass(Request $request)
    {
        $participant = Array();
        $emptyParticipant = [];

        $classid = $request->input('classid');
        $classView = Classroom::find($classid);

        if(!empty($classView->classParticipant)){
            $arrayParticipant = json_decode($classView->classParticipant,true);
            foreach($arrayParticipant as $part){
                $parti = User::find($part);
                if(isset($parti->participants->participant_groupID)){
                    $group = Group::find($parti->participants->participant_groupID);
                    $parti->setAttribute('group', $group);
                }
                array_push($participant, $parti);
            }
        }

        return response()->json($participant);
    }

    public function getParticipantDetails($id)
    {
        $user = User::find($id);

        $isVerifiedMember = session('isVerifiedMember');
        return view('member.viewparticipantdetail', compact('isVerifiedMember', 'user'));
    }

    public function removeParticipant(Request $request)
    {   
        $id = $request->input('userID');
        $user = User::find($id);
        // dd($user);
        if (!$user) {
            return redirect()->back()->with('error', 'Participant not found.');
        }

        //remove from group
        if(isset($user->participants->participant_groupID)){
            $user->participants->participant_groupID = null;
        }

        //remove from array list in class
        $class = Classroom::where('classCode', $user->participants->participant_classcode)->first();
        $arrayParticipant = json_decode($class->classParticipant, true);

        if (($key = array_search($user->id, $arrayParticipant)) !== false) {
            array_splice($arrayParticipant, $key, 1);
        }

        $updatedArray = json_encode($arrayParticipant);

        $class->classParticipant = $updatedArray;
        $class->save();

        $user->participants->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Participant removed successfully.');
    }

    public function viewGroup()
    {   
        //get all active classrooms
        $classrooms = Classroom::where('isAvailable', 1)->get();

        $groups = [];
        $emptyGroup = [];

        //assign groups to the classrooms
        foreach($classrooms as $index => $class){
            $groups[$index] = Group::where('classroom_id', $class->id)->get();
            if(Group::where('classroom_id', $class->id)->first()){
                $class->setattribute('groups', $groups[$index]);
            }else{
                $class->setattribute('groups', $emptyGroup);
            }
        }
        
        $participantsArray = [];
        //getparticipantsbyclass
        foreach($classrooms as $class){
            if(Participant::where('participant_classcode', $class->classCode)->first()){
                $participants = Participant::where('participant_classcode', $class->classCode)->whereNull('participant_groupID')->get();
                foreach($participants as $participant){
                    $user = User::where('id', $participant->user_id)->first();
                    array_push($participantsArray, $user);
                }
                $class->setattribute('participants', $participantsArray);
                $participantsArray = [];
            }else{
                $class->setattribute('participants', $participantsArray);
            }
        }

        $groupView = [];
        $emptyGroup = [];

        //get first class for the first view page
        $classView = Classroom::where('isAvailable', 1)->first();

        //assign class to the first class
        if($classView){
            if(Group::where('classroom_id', $classView->id)->first()){
                $groupArray = Group::where('classroom_id', $classView->id)->get();
                foreach($groupArray as $group){
                    array_push($groupView, $group);
                }
                $classView->setattribute('groups', $groupView);
                $groupView = [];
            }else{
                $classView->setAttribute('groups', $emptyGroup);
            }
        }

        //  dd($classView);
        $isVerifiedMember = session('isVerifiedMember');
        return view('member.group', compact('isVerifiedMember', 'classrooms', 'classView'));
    }

    public function viewGroupbyClass(Request $request)
    {
        $groups = Array();
        $emptyParticipant = [];

        $classid = $request->input('classid');
        $classView = Classroom::find($classid);

        if(Group::where('classroom_id', $classView->id)->first()){
            $groupView = Group::where('classroom_id', $classView->id)->get();
            foreach($groupView as $group){
                array_push($groups, $group);
            }
        }

        return response()->json($groups);
    }

    public function createGroup(Request $request)
    {
        $initArray = [];
        $group = new Group();
        $group->name = $request->input('classname');
        $group->classroom_id = $request->input('classSelect');
        $group->member = json_encode($initArray);
        $group->save();

        return redirect()->back()->with('success', 'Group has successfully created.');
    }

    public function deleteGroup(Request $request)
    {   
        $id = $request->input('groupID');
        $group = Group::find($id);
        // dd($group);
        if (!$group) {
            return redirect()->back()->with('error', 'Group not found.');
        }

        //remove group from participant table
        $arrayParticipant = json_decode($group->member, true);
        if(!empty($arrayParticipant)){
            foreach($arrayParticipant as $participant){
                $user = User::find($participant);
                $user->participants->participant_groupID = null;
                $user->save();
            }
        }

        //delete group
        $group->delete();
        
        return redirect()->back()->with('success', 'Group deleted successfully.');
    }

    public function getGroupDetails($id)
    {
        $group = Group::find($id);
        $participants = Participant::where('participant_groupID', $id)->get();
        $arrayUser = [];

        if(Participant::where('participant_groupID', $id)->first()){
            foreach($participants as $participant){
                $user = User::where('id', $participant->user_id)->first();
                array_push($arrayUser, $user);
            }
            $group->setattribute('users', $arrayUser);
            $arrayUser = [];
        }else{
            $group->setattribute('users', $arrayUser);
        }
        
        //getting all participants from the classroom 
        //for the assign participant to the group
        $participantArray = [];
        $classroom = Classroom::where('id', $group->classroom_id)->first();
        $classParticipantArray = json_decode($classroom->classParticipant);
        foreach($classParticipantArray as $part){
            $classParticipant = User::find($part);
            if(!isset($classParticipant->participants->participant_groupID)){
                array_push($participantArray, $classParticipant);
            }
        }

        // dd($participantArray);
        $isVerifiedMember = session('isVerifiedMember');
        return view('member.viewgroup', compact('isVerifiedMember', 'group', 'participantArray'));
    }

    public function assignParticipantToGroup(Request $request)
    {
        $validatedData = $request->validate([
            'participant' => 'required',
            'groupID' => 'required',
        ]);
    
        // Check if the participant is already assigned to a group
        // $user = User::find($request->input('participant'));
        $participant = Participant::where('user_id', $request->input('participant'))->first();
        if ($participant->participant_groupID != null) {
            return redirect()->back()->with('error', 'Participant is already assigned to a group');
        }

        //assign participant to group
        $participant->participant_groupID = $request->input('groupID');
        $participant->save();

        //insert participant id into array in group
        $group = Group::find($request->input('groupID'));
        $arrayMember = json_decode($group->member, true);
        array_push($arrayMember, $participant->user_id);
        $convArraymember = json_encode($arrayMember);
        $group->member = $convArraymember;
        $group->save();

        // dd($request);
        return redirect()->back()->with('success', 'Participant added to group successfully.');
    }

    public function removeparticipantfromgroup(Request $request)
    {   
        $id = $request->input('userID');
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Participant not found.');
        }

        //remove from array list in group
        $group = Group::find($request->input('groupID'));
        $arrayMember = json_decode($group->member, true);

        if (($key = array_search($user->id, $arrayMember)) !== false) {
            array_splice($arrayMember, $key, 1);
        }

        $updatedArray = json_encode($arrayMember);
        $group->member = $updatedArray;
        $group->save();

        //remove from participant table
        $user->participants->participant_groupID = null;
        $user->participants->save();

        return redirect()->back()->with('success', 'Participant removed successfully.');
    }

}
