<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Group;
use App\Models\Livestream;
use App\Models\Module;
use App\Models\Participant;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LivestreamController extends Controller
{
    public function index()
    {
        //get list of active classrooms
        $classrooms = Classroom::where('isAvailable', 1)->get();

        //get list of livestreams
        $livestreams = Livestream::all();

        if(!($livestreams->isEmpty())){
            foreach($livestreams as $livestream){
                $classname = Classroom::where('id', $livestream->classroomID)->first();
                $livestream->setAttribute('classname', $classname->className);

                // Convert date string to Carbon object
                $date = Carbon::parse($livestream->streamDate);

                // Convert time string to Carbon object
                $time = Carbon::parse($livestream->streamTime);

                // Format the date
                $formattedDate = $date->format('j F Y');

                // Format the time
                $formattedTime = $time->format('h:i A');

                $livestream->setAttribute('formattedDate', $formattedDate);
                $livestream->setAttribute('formattedTime', $formattedTime);
            }
        }

        $isVerifiedMember = session('isVerifiedMember');
        return view('member.livestream', compact('isVerifiedMember', 'classrooms', 'livestreams'));
    }

    public function viewLivestream($id)
    {
        //get livestream based on id
        $livestream = Livestream::where('id', $id)->first();

        //ranking
        $reports = Report::where('classID', $livestream->classroomID)->orderBy('totalMarks', 'desc')->get();

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

        return view('viewlivestream', compact('livestream', 'reports'));
    }

    public function fetchReports(Request $request)
    {
        //ranking
        $reports = Report::where('classID', $request->input('classroomId'))->orderBy('totalMarks', 'desc')->take(10)->get();

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

        return view('livestreampartialtable', compact('reports'));
    }

    public function addLivestream(Request $request)
    {
        // Validate the form data
        $validatedData = request()->validate([
            'streamname' => 'required|string|max:255',
            'streamdesc' => 'string|max:255',
            'streamdate' => 'required|date',
            'streamtime' => 'required',
            'ytstreamid' => 'required|string|max:255',
            'classSelect' => 'required|integer',
        ], [
            'streamname.required' => 'The livestream name field is required.',
            'streamdesc.required' => 'The livestream description field is required.',
            'streamdate.required' => 'The livestream date field is required.',
            'streamtime.required' => 'The livestream time field is required.',
        ]);

        // Extract the form data
        $streamName = $validatedData['streamname'];
        $streamDesc = $validatedData['streamdesc'];
        $streamDate = $validatedData['streamdate'];
        $streamTime = $validatedData['streamtime'];
        // $ytStreamId = $validatedData['ytstreamid'];
        $classroomId = $validatedData['classSelect'];

        $videoLink = $request->input('ytstreamid');
        $videoId = '';

        // Extract video ID from the link
        $parsedUrl = parse_url($videoLink);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
            if (isset($query['v'])) {
                $videoId = $query['v'];
            }
        }

        $ytStreamId = $videoId;

        // Create a new Livestream instance
        $livestream = new Livestream();
        $livestream->streamName = $streamName;
        $livestream->streamDesc = $streamDesc;
        $livestream->streamDate = $streamDate;
        $livestream->streamTime = $streamTime;
        $livestream->yt_streamID = $ytStreamId;
        $livestream->classroomID = $classroomId;

        // Save the livestream to the database
        $livestream->save();

        //redirect the user after saving the data
        return redirect()->back()->with('success', 'Livestream saved successfully.');
    }

    public function viewLivestreamforEdit($id)
    {
        $livestream = Livestream::findOrFail($id);

        return response()->json($livestream);
    }

    public function editLivestream(Request $request)
    {
        // Validate the form data
        $validatedData = request()->validate([
            'editstreamname' => 'required|string|max:255',
            'editstreamdesc' => 'string|max:255',
            'editstreamdate' => 'required|date',
            'editstreamtime' => 'required',
            'editytstreamid' => 'required|string|max:255',
            'editclassSelect' => 'required|integer',
        ]);

        $livestream = Livestream::findOrFail($request->input('livestreamID'));

        // Update the livestream attributes with the new values
        $livestream->streamName = $request->input('editstreamname');
        $livestream->streamDesc = $request->input('editstreamdesc');
        $livestream->streamDate = $request->input('editstreamdate');
        $livestream->streamTime = $request->input('editstreamtime');
        $livestream->classroomID = $request->input('editclassSelect');

        $videoLink = $request->input('editytstreamid');
        $videoId = '';

        // Extract video ID from the link
        $parsedUrl = parse_url($videoLink);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
            if (isset($query['v'])) {
                $videoId = $query['v'];
            }
        }

        $livestream->yt_streamID = $videoId;
        
        // Save the changes
        $livestream->save();
    
        // Return a response indicating success
        return redirect()->back()->with('success', 'Livestream edited successfully.');
    }

    public function deleteLivestream($id)
    {
        $livestream = Livestream::findOrFail($id);

        // Delete the livestream
        $livestream->delete();

        // Redirect back to the previous page or any other desired route
        return redirect()->back()->with('success', 'Livestream deleted successfully');
    }

    public function indexParticipant()
    {
        //get user classroom id
        $participant = Participant::where('user_id', Auth::user()->id)->first();

        //get classroom
        $classroom = Classroom::where('classCode', $participant->participant_classcode)->first();

        //get list of livestreams for that class
        $livestreams = Livestream::where('classroomID', $classroom->id)->get();

        if(!($livestreams->isEmpty())){
            foreach($livestreams as $livestream){
                // $classname = Classroom::where('id', $livestream->classroomID)->first();
                // $livestream->setAttribute('classname', $classname->className);

                // Convert date string to Carbon object
                $date = Carbon::parse($livestream->streamDate);

                // Convert time string to Carbon object
                $time = Carbon::parse($livestream->streamTime);

                // Format the date
                $formattedDate = $date->format('j F Y');

                // Format the time
                $formattedTime = $time->format('h:i A');

                $livestream->setAttribute('formattedDate', $formattedDate);
                $livestream->setAttribute('formattedTime', $formattedTime);
            }
        }

        return view('participants.livestreambyclass', compact('livestreams'));
    }
}
