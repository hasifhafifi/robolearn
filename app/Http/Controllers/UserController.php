<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Member;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index()
    {
        //get the user model
        $userDetails = Auth::user();

        $isVerifiedMember = session('isVerifiedMember');

        return view('profile')
            ->with('userDetails', $userDetails)
            ->with('isVerifiedMember', $isVerifiedMember);
    }

    public function update(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
 
        // Validate the incoming request data
        $validatedData = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phonenum' => ['required', 'string', 'max:255'],
            'profilepic' => ['nullable', 'image', 'max:2048']
        ]);

        // Update the user's details
        $user->firstname = $validatedData['firstname'];
        $user->lastname = $validatedData['lastname'];
        $user->address = $validatedData['address'];
        $user->phonenum = $validatedData['phonenum'];

        // Handle uploaded avatar image, if present
        if ($request->hasFile('profilepic')) {
            $file = $request->file('profilepic');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('assets/img/profilepics/', $filename);
            $user->profilepic = $filename;
        }

        // Save changes to the user model
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function changepassword(Request $request)
    {
        //validate the input
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|string|min:8',
            'renewPassword_confirmation' => 'required|string|min:8|same:newPassword',
        ]);

        //save the new password
        $user = Auth::user();
        $current_password = $user->password;
        $inputCurrentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;

        if (Hash::check($inputCurrentPassword, $current_password)) {
            $user->password = Hash::make($newPassword);
            $user->save();
            return redirect()->back()->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->with(['error' => 'Invalid current password.']);
        }
    }

    public function deleteaccount(Request $request)
    {
        //validate the password
        $request->validate([
            'password' => 'required',
        ]);

        $user = auth()->user();
        $userpassword = $user->password;
        $enteredpassword = $request->password;

        //delete the user's details
        if (Hash::check($enteredpassword, $userpassword)) {
            if($user->usertype == 1){
                $participant = Participant::where('user_id', $user->id)->first();
                if(isset($participant->participant_groupID)){
                    $participant->participant_groupID = null;
                }

                //remove from array list in class
                $participantClass = Classroom::where('classcode', $participant->participant_classcode)->first();
                $arrayParticipant = json_decode($participantClass->classParticipant, true);

                if (($key = array_search($user->id, $arrayParticipant)) !== false) {
                    array_splice($arrayParticipant, $key, 1);
                }

                $updatedArray = json_encode($arrayParticipant);

                $participantClass->classParticipant = $updatedArray;
                $participantClass->save();
                $participant->delete();
            }else{
                $member = Member::where('user_id', $user->id)->first();
                $member->delete();
            }
            $user->delete();
            return redirect('login')->with('success', 'Your account has been deleted.');
        } else {
            return redirect()->back()->with(['error' => 'Invalid password.']);
        }
    }


}
