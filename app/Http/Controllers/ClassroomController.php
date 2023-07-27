<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        //get all classrooms
        $classrooms = Classroom::all();

        $isVerifiedMember = session('isVerifiedMember');
        return view('member.Classroom', compact('isVerifiedMember', 'classrooms'));
    }
    
    public function createClassroom(Request $request)
    {
        // Validate the request data
        $request->validate([
            'classname' => 'required|string|max:255',
            'classcode' => 'required|unique:classrooms|string|size:6',
        ]);

        // Create a new Classroom instance
        $classroom = new Classroom;

        // Set the classroom's properties
        $classroom->classname = $request->input('classname');
        $classroom->classcode = $request->input('classcode');

        // Save the classroom to the database
        $classroom->save();

        // Redirect the user to the classroom's detail page
        return redirect()->back()->with('success', 'Class has successfully created.');
    }

    public function changeClassStatus(Request $request)
    {
        //find the classroom
        $class = Classroom::find($request->input('classroom_id'));

        $class->isAvailable = $request->input('isAvailable');

        //save new data
        $class->save();

        return redirect()->back()->with('success', 'Class status has successfully changed.');
    }

    public function updateRegistration(Request $request)
    {
        //find the classroom
        $class = Classroom::find($request->input('classID'));

        $class->isFull = $request->input('isFull');

        //save the data
        $class->save();

        return redirect()->back();
    }
}
