<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Module;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\http\traits\learningProgressTrait;

class HomeController extends Controller
{
    use learningProgressTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if(Auth::user()->usertype == '1'){
            $class = Classroom::where('classCode', $user->participants->participant_classcode)->first();
            $modules = Module::where('classroomID', $class->id)->take(3)->get();

            //get module completion for that user
            $moduleCompletion = $this->checkIfModuleCompletionExist();
            
            foreach ($moduleCompletion as $arr) {
                foreach ($modules as $module) {
                    if ($module->id == $arr['modID']) {
                        $module->setAttribute('percentage', $arr['percentage']);
                        break;
                    }
                }
            }
            return view('home', compact('modules', 'user'));
        }
        $isVerifiedMember = session('isVerifiedMember');
        return view('home', compact('isVerifiedMember', 'user'));
    }
}
