<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class AdminVerificationController extends Controller
{
    public function view()
    {
        $members = User::where('usertype', '2')->get();

        $newmembers = DB::table('users')
            ->join('members', 'users.id', '=', 'members.member_userid')
            ->where('users.usertype', '=', '2')
            ->where('members.verified', '=', '0')
            ->select('users.id', 'users.username', 'users.firstname', 'users.lastname', 'users.email', 'members.matricNum' ,'members.verified')
            ->get();

        // dd($newmembers);

        return view('admin.viewmember')
            ->with('listmembers', $members)
            ->with('newmembers', $newmembers);
    }

    public function verifyMember(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->members->verified = '1';
        $user->members->save();

        return redirect()->route('viewmember')->with('success', 'Member has been verified.');
    }

    public function getUserDetails($id)
    {
        $user = User::find($id);
        return view('admin.viewmemberdetail', compact('user'));
    }

    public function removeUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Member not found.');
        }
        $user->members->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Member removed successfully.');
    }
}
