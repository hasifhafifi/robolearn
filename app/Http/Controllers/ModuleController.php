<?php

namespace App\Http\Controllers;

use App\http\traits\learningProgressTrait;
use App\Models\Classroom;
use App\Models\Document;
use App\Models\File;
use App\Models\Group;
use App\Models\Module;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

use function GuzzleHttp\Promise\all;

class ModuleController extends Controller
{
    use learningProgressTrait;

    public function index()
    {
        //get list of active classrooms
        $classroom = Classroom::where('classCode', Auth::user()->participants->participant_classcode)->first();
        
        $modules = Module::where('classroomID', $classroom->id)->where('isHidden', false)->get();

        $id = $classroom->id;

        $user = Auth::user();
        $participant = Participant::where('user_id', $user->id)->first();

        $arrayModule = [];

        //get module completion status
        // if(!isset($participant->moduleCompletion)){
        //     foreach($modules as $module){
        //         $arrayModule[] = [
        //             'modID' => $module->id,
        //             'percentage' => 0
        //         ];
        //     }
        //     $arrayModule = json_encode($arrayModule);
        //     $participant->moduleCompletion = $arrayModule;
        //     $participant->save();
        // }else{
        //     // Get the existing module completion status from the participant
        //     $existingModules = json_decode($participant->moduleCompletion, true);

        //     // Check if the number of existing modules is different from the total number of modules
        //     if (count($existingModules) !== count($modules)) {
        //         // Create an array to store the updated module completion status
        //         $updatedModules = [];

        //         // Loop through all the modules
        //         foreach ($modules as $module) {
        //             // Check if the module already exists in the existing module completion status
        //             $existingModule = array_filter($existingModules, function ($item) use ($module) {
        //                 return $item['modID'] == $module->id;
        //             });

        //             // If the module exists, add it to the updated module completion status
        //             if (!empty($existingModule)) {
        //                 $updatedModules[] = array_shift($existingModule);
        //             } else {
        //                 // If the module does not exist, add it with a percentage of 0
        //                 $updatedModules[] = [
        //                     'modID' => $module->id,
        //                     'percentage' => 0
        //                 ];
        //             }
        //         }

        //         // Update the module completion status in the participant
        //         $participant->moduleCompletion = json_encode($updatedModules);
        //         $participant->save();
        //     }
        // }

        $arr = $this->checkIfModuleCompletionExist();
        
        // $arr = json_decode($participant->moduleCompletion, true);
        foreach($arr as $a){
            foreach($modules as $module){
                if($module->id == $a['modID']){
                    $percentage = $a['percentage'];
                    $module->setAttribute('percentage', $percentage);
                }
            }
        }

        return view('module.module', compact('classroom', 'modules', 'id'));
    }

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

        return view('member.activeclasslist', compact('classrooms'));
    }

    public function viewModuleById($id)
    {
        //get classroom based on id
        $classroom = Classroom::where('id', $id)->first();

        $modules = Module::where('classroomID', $id)->get();

        return view('module.module', compact('modules', 'id', 'classroom'));
    }

    public function viewModule($id)
    {
        $module = Module::where('id', $id)->first();

        $sections = DB::table('sections')
        ->select('*')
        ->where('moduleID', $id)
        ->orderBy('id', 'asc')
        ->get();

        $documents = Document::where('moduleID', $id)->get();

        $documentIDs = $documents->pluck('id');
        $files = File::whereIn('documentID', $documentIDs)->get();
        $submissions = Submission::whereIn('documentID', $documentIDs)->get();

        return view('module.viewmodule', compact('module', 'sections', 'documents', 'files', 'submissions'));
    }

    public function viewModuleParticipant($id)
    {
        $module = Module::where('id', $id)->first();

        $sections = DB::table('sections')
        ->select('*')
        ->where('moduleID', $id)
        ->where('isHidden', false)
        ->orderBy('id', 'asc')
        ->get();

        $documents = Document::where('moduleID', $id)->where('isHidden', false)->get();

        $documentIDs = $documents->pluck('id');
        $files = File::whereIn('documentID', $documentIDs)->where('isHidden', false)->get();
        $submissions = Submission::whereIn('documentID', $documentIDs)->get();

        $user = Auth::user();
        $participant = Participant::where('user_id', $user->id)->first();
        // $allModules = Module::where('classroomID', $module->classroomID)->get();
        // $allModulesIDs = $allModules->pluck('id');
        // $allDocuments = Document::whereIn('moduleID', $allModulesIDs)->get();
        // $allDocumentIDs = $allDocuments->pluck('id');
        // $allFiles = File::whereIn('documentID', $allDocumentIDs)->get();

        // $arrayFile = [];
        $arr = $this->checkIfFileCompletionExist();
        //update fileCompletion column
        // if(!isset($participant->fileCompletion)){
        //     foreach($allFiles as $file){
        //         $arrayFile[] = [
        //             'modID' => $file->moduleID,
        //             'fileID' => $file->id,
        //             'status' => 0
        //         ];
        //     }
        //     $arrayFile = json_encode($arrayFile);
        //     $participant->fileCompletion = $arrayFile;
        //     $participant->save();
        // }else{
        //     $existingFiles = json_decode($participant->fileCompletion, true);
            
        //      // Compare the count of existing files with the count of files set by the member
        //     if (count($existingFiles) !== count($allFiles)) {

        //         // Update the array with the new files
        //         $updatedFiles = [];
        //         foreach ($allFiles as $file) {
        //             // Check if the file already exists in the array
        //             $existingFile = array_filter($existingFiles, function ($item) use ($file) {
        //                 return $item['fileID'] === $file->id;
        //             });

        //             if (!empty($existingFile)) {
        //                 // Use the existing file status
        //                 $updatedFiles[] = reset($existingFile);
        //             } else {
        //                 // Add new file with status 0
        //                 $updatedFiles[] = [
        //                     'modID' => $file->moduleID,
        //                     'fileID' => $file->id,
        //                     'status' => 0
        //                 ];
        //             }
        //         }

        //         $participant->fileCompletion = json_encode($updatedFiles);
        //         $participant->save();
        //     }
        // }

        //set attribute to file
        // $arr = json_decode($participant->fileCompletion, true);
        foreach($arr as $a){
            foreach($files as $file){
                if($file->id == $a['fileID']){
                    $status = $a['status'];
                    $file->setAttribute('status', $status);
                }
            }
        }

        return view('module.viewmodule', compact('module', 'sections', 'documents', 'files', 'submissions'));
    }

    public function createModule(Request $request)
    {
        // Validate the request data
        $validator = request()->validate([
            'modulename' => 'required|max:255',
            'moduledesc' => 'required',
            'modulepic' => 'image|mimes:jpeg,png,jpg|max:2048',
            'classSelect' => 'required',
        ]);

        // Store the module data
        $module = new Module();
        $module->moduleName = $request->input('modulename');
        $module->moduleDesc = $request->input('moduledesc');
        $module->classroomID = $request->input('classSelect');

        // Upload the module picture
        if ($request->hasFile('modulepic')) {
            $image = $request->file('modulepic');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move('assets/img/modulepics/', $imageName);
            $module->modulePic = $imageName;
        }

        $module->save();

        $this->updateAfterAddModule($module);

        // Redirect with success message
        return redirect()->back()->with('success', 'Module created successfully.');
    }

    public function deleteModule(Request $request)
    {
        // Find the module by ID
        $module = Module::find($request->input('moduleID'));

        // Check if the module exists
        if ($module) {
            // Delete the module
            $this->updateAfterDeleteModule($module);
            $module->delete();
            
            // Redirect with a success message
            return redirect()->back()->with('success', 'Module deleted successfully.');
        }

        // Redirect with an error message if the module was not found
        return redirect()->back()->with('error', 'Module not found.');
    }

    public function viewModuleforEdit($id)
    {
        $module = Module::findOrFail($id);

        // Prepend the directory to the image URL
        $imageUrl = asset('assets/img/modulepics/' . $module->modulePic);

        // Add the image URL to the module data
        $module->image_url = $imageUrl;

        return response()->json($module);
    }

    public function editModule(Request $request)
    {
        $module = Module::findOrFail($request->input('editmoduleid'));

        $validatedData = $request->validate([
            'editmodulename' => 'required',
            'editmoduledesc' => 'required',
        ]);

        $module->moduleName = $request->input('editmodulename');
        $module->moduleDesc = $request->input('editmoduledesc');
        $module->isHidden = $request->has('isHidden') && $request->filled('isHidden');

        // Upload the module picture
        if ($request->hasFile('editmodulepic')) {
            $image = $request->file('editmodulepic');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move('assets/img/modulepics/', $imageName);
            $module->modulePic = $imageName;
        }

        $module->save();

        return redirect()->back()->with('success', 'Module edited successfully.');
    }

    public function markAsDone(Request $request){
        // dd($request->all());
        $user = Auth::user();
        $file = File::where('id', $request->fileID)->first();
        $arrayFile = json_decode($user->participants->fileCompletion, true);

        //update fileCompletion
        foreach($arrayFile as &$arr){
            if($file->id == $arr['fileID']){
                $arr['status'] = 1;
                break;
            }
        }
        $user->participants->fileCompletion = json_encode($arrayFile);
        $user->participants->save();

        //update moduleCompletion to change the percentage
        $arrayFileNew = json_decode($user->participants->fileCompletion, true);
        $document = Document::where('id', $file->documentID)->first();
        $module = Module::where('id', $document->moduleID)->first();
        $count = 0;
        $countFile = 0;

        foreach($arrayFileNew as $arr){
            if($module->id == $arr['modID']){
                if($arr['status'] == '1'){
                    $count++;
                }
                $countFile++;
            }
        }
        
        //calculate the percentage
        $percentage = $countFile > 0 ? ($count / $countFile) * 100 : 0;
        $percentage = number_format($percentage, 2);

        $arrayModule = json_decode($user->participants->moduleCompletion, true);

        foreach($arrayModule as &$arr){
            if($module->id == $arr['modID']){
                $arr['percentage'] = $percentage;
                break;
            }
        }
        $user->participants->moduleCompletion = json_encode($arrayModule);
        $user->participants->save();

        return redirect()->back();
    }

    public function unmarkAsDone(Request $request)
    {
        $user = Auth::user();
        $file = File::findOrFail($request->fileID);
        $arrayFile = json_decode($user->participants->fileCompletion, true);

        // Reverse the status in fileCompletion
        foreach ($arrayFile as &$arr) {
            if ($file->id == $arr['fileID']) {
                $arr['status'] = 0;
                break;
            }
        }

        $user->participants->fileCompletion = json_encode($arrayFile);
        $user->participants->save();

        // Update moduleCompletion to change the percentage
        $arrayFileNew = json_decode($user->participants->fileCompletion, true);
        $arrayModule = json_decode($user->participants->moduleCompletion, true);
        $document = Document::findOrFail($file->documentID);
        $module = Module::findOrFail($document->moduleID);
        $count = 0;
        $countFile = 0;

        foreach ($arrayFileNew as $arr) {
            if ($module->id == $arr['modID']) {
                if ($arr['status'] == 1) {
                    $count++;
                }
                $countFile++;
            }
        }
        
        // Calculate the percentage
        $percentage = $countFile > 0 ? ($count / $countFile) * 100 : 0;
        $percentage = number_format($percentage, 2);

        foreach ($arrayModule as &$arr) {
            if ($module->id == $arr['modID']) {
                $arr['percentage'] = $percentage;
                break;
            }
        }
        $user->participants->moduleCompletion = json_encode($arrayModule);
        $user->participants->save();

        return redirect()->back();
    }

    public function createSection(Request $request)
    {
        // Validate the request data
        $validator = request()->validate([
            'sectionname' => 'required|max:255',
        ]);

        $now = Carbon::now();

        // Store the section data
        DB::table('sections')->insert([
            'secTitle' => $request->input('sectionname'),
            'moduleID' => $request->input('moduleid'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Redirect with success message
        return redirect()->back()->with('success', 'Section created successfully.');
    }

    public function deleteSection(Request $request)
    {
        DB::table('sections')->where('id', $request->input('sectionid'))->delete();

        $documents = Document::where('sectionID', $request->input('sectionid'))->get();
        
        foreach ($documents as $document) {
            $files = File::where('documentID', $document->id)->get();

            if (!$files->isEmpty()) {
                foreach ($files as $file) {
                    $file->delete();
                }
            }

            $document->delete();
        }
        // Redirect with a success message
        return redirect()->back()->with('success', 'Section deleted successfully.');
    }

    public function viewSection($id)
    {
        $sections = DB::table('sections')
        ->select('*')
        ->where('id', $id)
        ->get();

        return response()->json($sections);
    }

    public function editSection(Request $request)
    {
        $hidden = $request->has('isHidden') && $request->filled('isHidden');

        DB::table('sections')
        ->where('id', $request->input('editsectionid')) // Specify the condition for the update
        ->update([
            'secTitle' => $request->input('editsectionname'),
            'isHidden' => $hidden,
        ]);

        return redirect()->back()->with('success', 'Section edited successfully.');
    }

    public function addContent(Request $request)
    {
        // Validate the request data
        $validator = request()->validate([
            'contentname' => 'required|max:255',
            'contentdetail' => 'required|max:255',
        ]);

        $document = new Document();
        $document->docTitle = $request->input('contentname');

        $contentDetail = $request->input('contentdetail');

        // Check if the content detail has any anchor tags
        if (strpos($contentDetail, '<a href="') !== false) {
            // Regular expression pattern to match anchor tags
            $pattern = '/<a\s+([^>]*href=(["\'])(.*?)\2[^>]*)>/i';

            // Update anchor tags that don't have https://
            $contentDetail = preg_replace_callback($pattern, function ($matches) {
                $attributes = $matches[1];
                $href = $matches[3];

                // Check if https:// or http:// exists in the link
                if (!preg_match('#https?://#', $href)) {
                    // Prepend https:// to the link
                    $href = 'https://' . $href;
                    // Update the href attribute in the anchor tag
                    $attributes = preg_replace('#href=(["\']).*?\1#', 'href="' . $href . '"', $attributes);
                }

                // Construct the updated anchor tag
                return '<a ' . $attributes . '>';
            }, $contentDetail);
        }

        $document->docDesc = $contentDetail;
        $document->moduleID = $request->input('moduleid');
        $document->sectionID = $request->input('consectionid');

        $document->save();

        return redirect()->back()->with('success', 'Content added successfully.');
    }

    public function viewContent($id)
    {
        $document = Document::where('id', $id)->first();
        $module = Module::where('id', $document->moduleID)->first();

        return view('module.viewcontent', compact('document', 'module'));
    }

    public function editContent(Request $request)
    {
        $hidden = $request->has('isHidden') && $request->filled('isHidden');

        $document = Document::where('id', $request->input('editcontentid'))->first();

        $document->docTitle = $request->input('editcontentname');
        // $document->docDesc = $request->input('editcontentdetail');

        $contentDetail = $request->input('editcontentdetail');

        // Check if the content detail has any anchor tags
        if (strpos($contentDetail, '<a href="') !== false) {
            // Regular expression pattern to match anchor tags
            $pattern = '/<a\s+([^>]*href=(["\'])(.*?)\2[^>]*)>/i';

            // Update anchor tags that don't have https://
            $contentDetail = preg_replace_callback($pattern, function ($matches) {
                $attributes = $matches[1];
                $href = $matches[3];

                // Check if https:// or http:// exists in the link
                if (!preg_match('#https?://#', $href)) {
                    // Prepend https:// to the link
                    $href = 'https://' . $href;
                    // Update the href attribute in the anchor tag
                    $attributes = preg_replace('#href=(["\']).*?\1#', 'href="' . $href . '"', $attributes);
                }

                // Construct the updated anchor tag
                return '<a ' . $attributes . '>';
            }, $contentDetail);
        }

        $document->docDesc = $contentDetail;

        $document->isHidden = $hidden;
        $document->save();

        return redirect()->route('viewmodule', ['id' => $document->moduleID])->with('success', 'Content successfully updated!');
    }

    public function deleteContent(Request $request)
    {
        $document = Document::where('id', $request->input('contentid'))->first();
        $files = File::where('documentID', $document->id)->get();

        foreach($files as $file){
            $file->delete();
        }

        $document->delete();
        // Redirect with a success message
        return redirect()->back()->with('success', 'Content deleted successfully.');
    }

    public function addFile(Request $request)
    {
        // dd($request->all());
        $validator = request()->validate([
            'filename' => 'required|max:255',
            'filetype' => 'required',
        ]);

        $document = Document::where('id', $request->input('documentid'))->first();

        $file = new File();
        $file->fileName = $request->input('filename');
        $file->fileType = $request->input('filetype');
        $file->documentID = $document->id;
        $file->moduleID = $document->moduleID;

        if($request->input('filetype') == 'text'){
            $file->fileContent = $request->input('filetext');
        } elseif ($request->input('filetype') == 'image') {
            if ($request->hasFile('filecontent')) {
                $imageFile = $request->file('filecontent');
                $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
                
                $validImageExtensions = ['jpg', 'jpeg', 'png', 'gif']; // List of supported image extensions
                
                // Check if the file extension is in the list of supported image extensions
                if (!in_array(strtolower($imageFile->getClientOriginalExtension()), $validImageExtensions)) {
                    return redirect()->back()->with('error', 'Invalid image file. Please select a valid image file.');
                }

                $imageFile->move('assets/img/filepics/', $imageName);
                $file->fileContent = $imageName;
            }
        } elseif ($request->input('filetype') == 'pdf') {
            if ($request->hasFile('filepdf')) {
                // Check if the file type is different from the current file type
                $newFile = $request->file('filepdf');
                $newFileType = $newFile->getClientOriginalExtension();
                if ($file->fileType !== $newFileType) {
                    return redirect()->back()->with('error', 'Invalid file type. Please select a valid file type.');
                } 

                $filepdf = $request->file('filepdf');
                $fileName = time() . '.' . $filepdf->getClientOriginalExtension();
                $filepdf->move('assets/files/', $fileName);
                $file->fileContent = $fileName;
            }
        } elseif ($request->input('filetype') == 'zip') {
            if ($request->hasFile('filezip')) {
                // Check if the file type is different from the current file type
                $newFile = $request->file('filezip');
                $newFileType = $newFile->getClientOriginalExtension();
                if ($file->fileType !== $newFileType) {
                    return redirect()->back()->with('error', 'Invalid file type. Please select a valid file type.');
                } 

                $filezip = $request->file('filezip');
                $fileName = time() . '.' . $filezip->getClientOriginalExtension();
                $filezip->move('assets/files/', $fileName);
                $file->fileContent = $fileName;
            }
        } elseif ($request->input('filetype') == 'docx') {
            if ($request->hasFile('filedoc')) {
                // Check if the file type is different from the current file type
                $newFile = $request->file('filedoc');
                $newFileType = $newFile->getClientOriginalExtension();
                if ($file->fileType !== $newFileType) {
                    return redirect()->back()->with('error', 'Invalid file type. Please select a valid file type.');
                } 

                $fileDoc = $request->file('filedoc');
                $fileName = time() . '.' . $fileDoc->getClientOriginalExtension();
                $fileDoc->move('assets/files/', $fileName);
                $file->fileContent = $fileName;
            }
        } elseif ($request->input('filetype') == 'url') {
            $file->fileContent = $request->input('fileurl');
        } elseif ($request->input('filetype') == 'yturl') {
            $videoLink = $request->input('fileyturl');
            $videoId = '';

            // Extract video ID from the link
            $parsedUrl = parse_url($videoLink);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query);
                if (isset($query['v'])) {
                    $videoId = $query['v'];
                }
            }

            $file->fileContent = $videoId;
        } 

        if($request->input('filetype') != 'submission'){
            $file->save();
        }

        //update all the participants' fileCompletion and moduleCompletion for that class
        $module = Module::where('id', $document->moduleID)->first();
        $class = Classroom::where('id', $module->classroomID)->first();
        $participants = Participant::where('participant_classcode', $class->classCode)->get();
        $allModules = Module::where('classroomID', $module->classroomID)->get();
        $allModulesIDs = $allModules->pluck('id');
        $allDocuments = Document::whereIn('moduleID', $allModulesIDs)->get();
        $allDocumentIDs = $allDocuments->pluck('id');
        $allFiles = File::whereIn('documentID', $allDocumentIDs)->get();
        // dd($allFiles);
    
        foreach ($participants as $participant) {
            if(!isset($participant->fileCompletion)){
                $arrayFile = [];
                foreach($allFiles as $file){
                    $arrayFile[] = [
                        'modID' => $file->moduleID,
                        'fileID' => $file->id,
                        'status' => 0
                    ];
                }
                $arrayFile = json_encode($arrayFile);
                $participant->fileCompletion = $arrayFile;
                $participant->save();
            }else{
                $arrayFile = json_decode($participant->fileCompletion, true);

                // Update fileCompletion for the participant
                $arrayFile[] = [
                    'modID' => $file->moduleID,
                    'fileID' => $file->id,
                    'status' => 0
                ];

                $participant->fileCompletion = json_encode($arrayFile);
                $participant->save();

                // Update moduleCompletion for the participant
                
                //check if moduleCompletion is set
                if(!isset($participant->moduleCompletion)){
                    $arrayModule = [];
                    foreach($allModules as $module){
                        $arrayModule[] = [
                            'modID' => $module->id,
                            'percentage' => 0
                        ];
                    }
                    $arrayModule = json_encode($arrayModule);
                    $participant->moduleCompletion = $arrayModule;
                    $participant->save();
                }else{
                    $arrayModule = json_decode($participant->moduleCompletion, true);
                    $arrayFileNew = json_decode($participant->fileCompletion, true);

                    foreach ($allModules as $module) {
                        $count = 0;
                        $countFile = 0;

                        foreach ($arrayFileNew as $arr) {
                            if ($module->id == $arr['modID']) {
                                if ($arr['status'] == 1) {
                                    $count++;
                                }
                                $countFile++;
                            }
                        }

                        // Calculate the percentage and format it to two decimal places
                        $percentage = $countFile > 0 ? number_format(($count / $countFile) * 100, 2) : 0;

                        // Update the percentage in moduleCompletion for the module
                        foreach ($arrayModule as &$arrM) {
                            if ($module->id == $arrM['modID']) {
                                $arrM['percentage'] = $percentage;
                                break;
                            }
                        }
                    }
                    // dd($arrayModule);
                    $arrayModule = json_encode($arrayModule);
                    $participant->moduleCompletion = $arrayModule;
                    $participant->save();
                }
            }
        }
        return redirect()->back()->with('success', 'File added successfully.');
    }

    public function viewFile($id)
    {
        // dd($id);
        $file = File::where('id', $id)->first();
        $document = Document::where('id', $file->documentID)->first();
        $module = Module::where('id', $document->moduleID)->first();
        
        return view('module.viewfile', compact('file', 'document', 'module'));
    }

    public function viewFilePDF($id)
    {
        // dd($id);
        $file = File::where('id', $id)->first();
        $filePath = public_path('assets/files/' . $file->fileContent);

        // Make sure the file exists
        if (file_exists($filePath)) {
            // Set the appropriate headers for PDF display
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file->fileName . '.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filePath));
            header('Accept-Ranges: bytes');

            // Output the file content
            readfile($filePath);
        } else {
            // Handle file not found
            abort(404);
        }
    }

    public function viewFileZip($id)
    {
        // dd($id);    
        $file = File::where('id', $id)->first();
        $filePath = public_path('assets/files/' . $file->fileContent);

        // Make sure the file exists
        if (file_exists($filePath)) {
            $headers = ['Content-Type: application/zip'];
            $newName = $file->fileName.'.zip';

            return response()->download($filePath, $newName, $headers);
        } else {
            // Handle file not found
            abort(404);
        }
    }

    public function viewFileWord($id)
    {
        // dd($id);    
        $file = File::where('id', $id)->first();
        $filePath = public_path('assets/files/' . $file->fileContent);

        // Make sure the file exists
        if (file_exists($filePath)) {
            $headers = ['Content-Type: application/msword'];
            $newName = $file->fileName.'.docx';

            return response()->download($filePath, $newName, $headers);
        } else {
            // Handle file not found
            abort(404);
        }
    }

    public function deleteFile(Request $request)
    {
        // dd($request);
        // Find the file by ID
        $file = File::where('id', $request->input('fileid'))->first();
        $this->updateAfterDeleteFile($file);

        // Get the file path
        if($file->fileType == 'image'){
            $filePath = public_path('assets/img/filepics/' . $file->fileContent);
        }else{
            $filePath = public_path('assets/files/' . $file->fileContent);
        }
        
        // Check if the file exists
        if (file_exists($filePath)) {
            // Delete the file from the directory
            unlink($filePath);
        }

        // Delete the file record from the database
        $file->delete();

        //update fileCompletion and moduleCompletion


        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    public function viewFileforEdit(Request $request)
    {
        // dd($request);
        $file = File::where('id', $request->input('fileid'))->first();
        $document = Document::where('id', $file->documentID)->first();
        $module = Module::where('id', $document->moduleID)->first();
        
        if($file->fileType == 'text'){
            return view('module.editfiletext', compact('file', 'document', 'module'));
        }else if($file->fileType == 'image'){
            return view('module.editfileimage', compact('file', 'document', 'module'));
        } else if($file->fileType == 'url' || $file->fileType == 'yturl'){
            return view('module.editfileurl', compact('file', 'document', 'module'));
        } else{
            return view('module.editfileall', compact('file', 'document', 'module'));
        } 
        
    }

    public function editFile(Request $request)
    {
        $hidden = $request->has('isHidden') && $request->filled('isHidden');

        $file = File::where('id', $request->input('editfileid'))->first();
        
        $file->fileName = $request->input('editfilename');

        $contentDetail = $request->input('editfilecontent');

        // Check if the content detail has any anchor tags
        if (strpos($contentDetail, '<a href="') !== false) {
            // Regular expression pattern to match anchor tags
            $pattern = '/<a\s+([^>]*href=(["\'])(.*?)\2[^>]*)>/i';

            // Update anchor tags that don't have https://
            $contentDetail = preg_replace_callback($pattern, function ($matches) {
                $attributes = $matches[1];
                $href = $matches[3];

                // Check if https:// or http:// exists in the link
                if (!preg_match('#https?://#', $href)) {
                    // Prepend https:// to the link
                    $href = 'https://' . $href;
                    // Update the href attribute in the anchor tag
                    $attributes = preg_replace('#href=(["\']).*?\1#', 'href="' . $href . '"', $attributes);
                }

                // Construct the updated anchor tag
                return '<a ' . $attributes . '>';
            }, $contentDetail);
        }

        $file->fileContent = $contentDetail;
        $file->ishidden = $hidden;
        $file->save();

        $document = Document::where('id', $file->documentID)->first();

        return redirect()->route('viewmodule', ['id' => $document->moduleID])->with('success', 'File successfully updated!');
    }

    public function editFileImage(Request $request)
    {
        $hidden = $request->has('isHidden') && $request->filled('isHidden');
        
        $file = File::where('id', $request->input('editfileid'))->first();
        
        $file->fileName = $request->input('editfilename');
        
        // Check if a new photo was uploaded
        if ($request->hasFile('editfilecontent')) {
            $newPhotoFile = $request->file('editfilecontent');
            
            // Validate file type
            if (!$newPhotoFile->isValid() || !in_array($newPhotoFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $document = Document::where('id', $file->documentID)->first();
                $module = Module::where('id', $document->moduleID)->first();
                $errorMessage = 'Invalid file type. Please select a valid file type.';
                return view('module.editfileimage', compact('file', 'module', 'document', 'errorMessage'));
                // return redirect()->back()->with('error', 'Invalid image file. Please select a valid image file.');
            }
            
            // Delete the old photo
            if ($file->fileContent !== null) {
                $oldPhotoPath = public_path('assets/img/filepics/' . $file->fileContent);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            
            // Upload and save the new photo
            $newPhotoName = time() . '.' . $newPhotoFile->getClientOriginalExtension();
            $newPhotoFile->move('assets/img/filepics/', $newPhotoName);
            $file->fileContent = $newPhotoName;
        }
        
        $file->ishidden = $hidden;
        $file->save();
        
        $document = Document::where('id', $file->documentID)->first();
        
        return redirect()->route('viewmodule', ['id' => $document->moduleID])->with('success', 'File successfully updated!');
    }

    public function editFileURL(Request $request)
    {
        $hidden = $request->has('isHidden') && $request->filled('isHidden');
        
        $file = File::where('id', $request->input('editfileid'))->first();
        
        $file->fileName = $request->input('editfilename');

        if($file->fileType == 'url'){
            $file->fileContent = $request->input('editfilecontent');
        }else{
            $videoLink = $request->input('editfilecontent');
            $videoId = '';

            // Extract video ID from the link
            $parsedUrl = parse_url($videoLink);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query);
                if (isset($query['v'])) {
                    $videoId = $query['v'];
                }
            }

            $file->fileContent = $videoId;
        }
        
        $file->ishidden = $hidden;
        $file->save();
        
        $document = Document::where('id', $file->documentID)->first();
        
        return redirect()->route('viewmodule', ['id' => $document->moduleID])->with('success', 'File successfully updated!');
    }

    public function editFileAllType(Request $request)
    {
        $hidden = $request->has('isHidden') && $request->filled('isHidden');
        
        $file = File::where('id', $request->input('editfileid'))->first();
        
        // Check if a new file was uploaded
        if ($request->hasFile('editfilecontent')) {
            $newFile = $request->file('editfilecontent');
            $newFileType = $newFile->getClientOriginalExtension();
            
            // Check if the file type is different from the current file type
            if ($file->fileType !== $newFileType) {
                $document = Document::where('id', $file->documentID)->first();
                $module = Module::where('id', $document->moduleID)->first();
                $errorMessage = 'Invalid file type. Please select a valid file type.';
                return view('module.editfileall', compact('file', 'module', 'document', 'errorMessage'));
            }            
            
            // Delete the previous file
            if ($file->fileContent !== null) {
                $previousFilePath = storage_path('assets/files/' . $file->fileContent);
                if (file_exists($previousFilePath)) {
                    unlink($previousFilePath);
                }
            }
            
            // Upload and save the new file
            $newFileName = time() . '.' . $newFileType;
            $newFile->move('assets/files/', $newFileName);
            $file->fileContent = $newFileName;
        }
        
        $file->fileName = $request->input('editfilename');
        $file->ishidden = $hidden;
        $file->save();
        
        $documentback = Document::where('id', $file->documentID)->first();
        
        return redirect()->route('viewmodule', ['id' => $documentback->moduleID])->with('success', 'File successfully updated!');
    }

    public function createSubmission(Request $request)
    {
        // Validate the submitted data
        $validatedData = $request->validate([
            'filename' => 'required|max:255',
            'submissiondesc' => 'required',
            'submissiontype' => 'required',
            'duedate' => 'required|date|after_or_equal:today',
            'duetime' => 'required',
            'classid' => 'required',
        ]);

        // Save submission
        $submission = new Submission();
        $submission->submissionName = $validatedData['filename'];
        $submission->submissionDesc = $validatedData['submissiondesc'];
        $submission->submissionType = $validatedData['submissiontype'];
        $submission->duedate = $validatedData['duedate'];
        $submission->duetime = $validatedData['duetime'];
        $submission->classID = $validatedData['classid'];
        $submission->save();

        return redirect()->back()->with('success', 'Submission created successfully.');
    }

    public function viewSubmission($id)
    {
        // dd($id);
        $submission = Submission::where('id', $id)->first();
        $user = User::where('id', Auth::user()->id)->first();
        $submissionFile = SubmissionFile::where('userID', $user->id)->where('submissionID', $submission->id)->first();

        //calculate time left
        $carbonTime = Carbon::createFromFormat('H:i:s', $submission->duetime);
        $formattedTime = $carbonTime->format('h:i A');
        $dueDateTime = Carbon::createFromFormat('Y-m-d h:i A', $submission->duedate . ' ' . $formattedTime, 'Asia/Kuala_Lumpur');
        $currentDateTime = Carbon::now()->timezone('Asia/Kuala_Lumpur');

        $timeLeft = $currentDateTime->diff($dueDateTime);
        $daysLeft = $timeLeft->d;
        $hoursLeft = $timeLeft->h;
        $minutesLeft = $timeLeft->i;
        $secondsLeft = $timeLeft->s;

        $timeLeftString = $daysLeft . ' days, ' . $hoursLeft . ' hours, ' . $minutesLeft . ' minutes, ' . $secondsLeft . ' seconds';

        if ($currentDateTime->greaterThan($dueDateTime)) {
            $timeSign = 'negative';
        } elseif ($currentDateTime->lessThan($dueDateTime)) {
            $timeSign = 'positive';
        }

        // dd($timeLeftString);
        $submission->setAttribute('timeLeft', $timeLeftString);
        $submission->setAttribute('timeSign', $timeSign);

        // Convert duedate string to Carbon object
        $dueDate = Carbon::parse($submission->duedate);

        // Convert duetime string to Carbon object
        $dueTime = Carbon::parse($submission->duetime);

        // Format the date
        $formattedDate = $dueDate->format('j F Y');

        // Format the time
        $formattedTime = $dueTime->format('h:i A');

        $submission->setAttribute('formattedDate', $formattedDate);
        $submission->setAttribute('formattedTime', $formattedTime);
        
        return view('submission.viewsubmission', compact('submission', 'submissionFile'));
    }

    public function addSubmission(Request $request)
    {
        // dd($id);
        $submission = Submission::where('id', $request->input('submissionID'))->first();
        
        return view('submission.addsubmission', compact('submission'));
    }

    public function submitFile(Request $request)
    {
        // dd($request->all());
        $user = User::where('id', Auth::user()->id)->first();
        $submission = Submission::where('id', $request->input('submissionID'))->first();
        $errorMessage = 'Invalid file type. Please select a valid file type.';
        $successMessage = 'File has been submitted succesfully.';
        $submissionFile = SubmissionFile::where('userID', $user->id)->where('submissionID', $submission->id)->first();
        
        //calculate time left
        $carbonTime = Carbon::createFromFormat('H:i:s', $submission->duetime);
        $formattedTime = $carbonTime->format('h:i A');
        $dueDateTime = Carbon::createFromFormat('Y-m-d h:i A', $submission->duedate . ' ' . $formattedTime, 'Asia/Kuala_Lumpur');
        $currentDateTime = Carbon::now()->timezone('Asia/Kuala_Lumpur');

        $timeLeft = $currentDateTime->diff($dueDateTime);
        $daysLeft = $timeLeft->d;
        $hoursLeft = $timeLeft->h;
        $minutesLeft = $timeLeft->i;
        $secondsLeft = $timeLeft->s;

        $timeLeftString = $daysLeft . ' days, ' . $hoursLeft . ' hours, ' . $minutesLeft . ' minutes, ' . $secondsLeft . ' seconds';

        if ($currentDateTime->greaterThan($dueDateTime)) {
            $timeSign = 'negative';
        } elseif ($currentDateTime->lessThan($dueDateTime)) {
            $timeSign = 'positive';
        }

        // dd($timeLeftString);
        $submission->setAttribute('timeLeft', $timeLeftString);
        $submission->setAttribute('timeSign', $timeSign);

        if ($request->hasFile('filesubmit')) {
            $file = $request->file('filesubmit');
            $extension = $file->getClientOriginalExtension();

            if($submission->submissionType != 'allfile'){
                if($submission->submissionType != $extension){
                    return view('module.addsubmission', compact('submission', 'document', 'module', 'errorMessage'));
                } 
            }
            
            $submitFile = new SubmissionFile();
            $submitFile->submissionID = $submission->id;
            $submitFile->userID = $user->id;
            if(isset($user->participants->participant_groupID)){
                $submitFile->groupID = $user->participants->participant_groupID;
            }

            $orifilename = $file->getClientOriginalName();
            $submitFile->submittedFileName = $orifilename;

            $filenameWithoutExtension = pathinfo($orifilename, PATHINFO_FILENAME); // Get the filename without extension
            $fileName = $filenameWithoutExtension . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/submissions/', $fileName);
            $submitFile->submittedFileContent = $fileName;
            $submitFile->save();
            $submissionFile = $submitFile;
            
            // return view('module.viewsubmission', compact('submission', 'document', 'module', 'submissionFile', 'successMessage'));
            return redirect()->route('viewSubmission', ['id' => $submission->id])->with('success', $successMessage);
        } else {
            // return view('module.addsubmission', compact('submission', 'document', 'module', 'errorMessage'));
            return redirect()->route('viewSubmission', ['id' => $submission->id])->with('error', $errorMessage);
        }
    }

    public function editSubmission(Request $request)
    {
        // dd($id);
        $submission = Submission::where('id', $request->input('submissionID'))->first();
        $user = User::where('id', Auth::user()->id)->first();
        $submissionFile = SubmissionFile::where('userID', $user->id)->where('submissionID', $submission->id)->first();
        
        return view('submission.editsubmission', compact('submission', 'submissionFile'));
    }

    public function viewSubmissionDetail(Request $request)
    {
        // dd($request);
        $submission = Submission::where('id', $request->input('submissionid'))->first();
        
        return view('submission.viewsubmissiondetail', compact('submission'));
    }

    public function editSubmissionDetail(Request $request)
    {
        // dd($request);
        $submission = Submission::where('id', $request->input('editsubid'))->first();
        
        //edit submission
        $submission->submissionName = $request->input('editsubname');
        $submission->submissionDesc = $request->input('editsubcontent');
        $submission->submissionType = $request->input('submissiontype');
        $submission->duedate = $request->input('duedate');
        $submission->duetime = $request->input('duetime');
        $hidden = $request->has('isHidden') && $request->filled('isHidden');
        $submission->ishidden = $hidden;
        $submission->save();
        
        return redirect()->route('viewSubmissionById', ['id' => $submission->classID])->with('success', 'Submission successfully updated!');
    }

    public function deleteSubmissionDetail(Request $request)
    {
        // dd($request);
        $submission = Submission::where('id', $request->input('submissionid'))->first();
        $documentback = Document::where('id', $submission->documentID)->first();
        $submittedFiles = SubmissionFile::where('submissionID', $submission->id)->get();

        //delete all submitted files
        foreach($submittedFiles as $submittedFile){
            // Delete the submitted file record from the database
            $submittedFile->delete();

            // Remove the file from the file directory
            $filePath = 'assets/submissions/' . $submittedFile->submittedFileContent;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the submission
        $submission->delete();
        
        return redirect()->route('viewSubmissionById', ['id' => $submission->classID])->with('success', 'Submission deleted successfully!');
    }


    public function editSubmittedFile(Request $request)
    {
        // dd($request->all());
        $user = User::where('id', Auth::user()->id)->first();
        $submission = Submission::where('id', $request->input('submissionID'))->first();
        $errorMessage = 'Invalid file type. Please select a valid file type.';
        $successMessage = 'File has been submitted succesfully.';
        $submissionFile = SubmissionFile::where('userID', $user->id)->where('submissionID', $submission->id)->first();

        //calculate time left
        $carbonTime = Carbon::createFromFormat('H:i:s', $submission->duetime);
        $formattedTime = $carbonTime->format('h:i A');
        $dueDateTime = Carbon::createFromFormat('Y-m-d h:i A', $submission->duedate . ' ' . $formattedTime, 'Asia/Kuala_Lumpur');
        $currentDateTime = Carbon::now()->timezone('Asia/Kuala_Lumpur');

        $timeLeft = $currentDateTime->diff($dueDateTime);
        $daysLeft = $timeLeft->d;
        $hoursLeft = $timeLeft->h;
        $minutesLeft = $timeLeft->i;
        $secondsLeft = $timeLeft->s;

        $timeLeftString = $daysLeft . ' days, ' . $hoursLeft . ' hours, ' . $minutesLeft . ' minutes, ' . $secondsLeft . ' seconds';

        if ($currentDateTime->greaterThan($dueDateTime)) {
            $timeSign = 'negative';
        } elseif ($currentDateTime->lessThan($dueDateTime)) {
            $timeSign = 'positive';
        }

        // dd($timeLeftString);
        $submission->setAttribute('timeLeft', $timeLeftString);
        $submission->setAttribute('timeSign', $timeSign);

        if ($request->hasFile('filesubmit')) {
            $file = $request->file('filesubmit');
            $extension = $file->getClientOriginalExtension();

            if($submission->submissionType != 'allfile'){
                if($submission->submissionType != $extension){
                    return view('submission.addsubmission', compact('submission', 'errorMessage'));
                } 
            }
            
            $submitFile = SubmissionFile::where('userID', $user->id)->where('submissionID', $submission->id)->first();
            $submitFile->submissionID = $submission->id;
            $submitFile->userID = $user->id;
            if(isset($user->participants->participant_groupID)){
                $submitFile->submissionID = $user->participants->participant_groupID;
            }

            $orifilename = $file->getClientOriginalName();
            $submitFile->submittedFileName = $orifilename;

            $filenameWithoutExtension = pathinfo($orifilename, PATHINFO_FILENAME); // Get the filename without extension
            $fileName = $filenameWithoutExtension . time() . '.' . $file->getClientOriginalExtension();
            
            // Delete the submitted file from the file system
            if ($submissionFile->submittedFileContent !== null) {
                $filePath = public_path('assets/submissions/' . $submissionFile->submittedFileContent);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $file->move('assets/submissions/', $fileName);
            $submitFile->submittedFileContent = $fileName;
            $submitFile->save();
            $submissionFile = $submitFile;
            
            return redirect()->route('viewSubmission', ['id' => $submission->id])->with('success', $successMessage);
            // return view('module.viewsubmission', compact('submission', 'document', 'module', 'submissionFile', 'successMessage'));
        } else {
            return view('submission.addsubmission', compact('submission', 'document', 'module', 'errorMessage'));
            // return redirect()->route('viewSubmission', ['id' => $submission->id])->with('error', $errorMessage);
        }
    }

    public function removeSubmission(Request $request)
    {
        $submissionFile = SubmissionFile::findOrFail($request->submittedFileID);

        // Delete the submitted file from the file system
        if ($submissionFile->submittedFileContent !== null) {
            $filePath = public_path('assets/submissions/' . $submissionFile->submittedFileContent);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the SubmissionFile record
        $submissionFile->delete();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Submission deleted successfully.');
    }

    public function manageSubmission($id)
    {
        // dd($id);
        $submission = Submission::where('id', $id)->first();
        $classroom = Classroom::where('id', $submission->classID)->first();
        $participants = Participant::where('participant_classcode', $classroom->classCode)->get();
        $submissionFiles = SubmissionFile::where('submissionID', $submission->id)->get();
        $userIds = $submissionFiles->pluck('userID');
        $users = User::whereIn('id', $userIds)->get();

        foreach($participants as $participant){
            $user = User::where('id', $participant->user_id)->first();
            
            if(SubmissionFile::where('userID', $user->id)->first()){
                $file = SubmissionFile::where('userID', $user->id)->where('submissionID', $submission->id)->first();
                // Format the time
                if(isset($file)){
                    $datetime = $file->updated_at;
                    $formattedDateTime = $datetime->format('j F Y, h:i A');
                    $file->setAttribute('formattedDateTime', $formattedDateTime);
                }
                
                $participant->setAttribute('file', $file);
            }

            if(Group::where('id', $participant->participant_groupID)->first()){
                $group = Group::where('id', $participant->participant_groupID)->first();
                $participant->setAttribute('groupName', $group->name);
            }
            
            $participant->setAttribute('userdetail', $user);

        }

        //calculate time left
        $carbonTime = Carbon::createFromFormat('H:i:s', $submission->duetime);
        $formattedTime = $carbonTime->format('h:i A');
        $dueDateTime = Carbon::createFromFormat('Y-m-d h:i A', $submission->duedate . ' ' . $formattedTime, 'Asia/Kuala_Lumpur');
        $currentDateTime = Carbon::now()->timezone('Asia/Kuala_Lumpur');

        $timeLeft = $currentDateTime->diff($dueDateTime);
        $daysLeft = $timeLeft->d;
        $hoursLeft = $timeLeft->h;
        $minutesLeft = $timeLeft->i;
        $secondsLeft = $timeLeft->s;

        $timeLeftString = $daysLeft . ' days, ' . $hoursLeft . ' hours, ' . $minutesLeft . ' minutes, ' . $secondsLeft . ' seconds';

        if ($currentDateTime->greaterThan($dueDateTime)) {
            $timeSign = 'negative';
        } elseif ($currentDateTime->lessThan($dueDateTime)) {
            $timeSign = 'positive';
        }

        $submission->setAttribute('timeLeft', $timeLeftString);
        $submission->setAttribute('timeSign', $timeSign);
        //end calculate time

        // Convert duedate string to Carbon object
        $dueDate = Carbon::parse($submission->duedate);

        // Convert duetime string to Carbon object
        $dueTime = Carbon::parse($submission->duetime);

        // Format the date
        $formattedDate = $dueDate->format('j F Y');

        // Format the time
        $formattedTime = $dueTime->format('h:i A');

        $submission->setAttribute('formattedDate', $formattedDate);
        $submission->setAttribute('formattedTime', $formattedTime);

        return view('submission.managesubmission', compact('submission', 'submissionFiles', 'participants', 'users'));
    }

    public function getSubmission($id)
    {
        $submissionFile = SubmissionFile::find($id);

        if (!$submissionFile) {
            // SubmissionFile not found
            return redirect()->back()->with('error', 'Submission file not found.');
        }

        $filePath = 'assets/submissions/' . $submissionFile->submittedFileContent;

        if (!file_exists($filePath)) {
            // File not found
            return redirect()->back()->with('error', 'File not found.');
        }

        // Generate a response to force the file download
        return response()->download($filePath, $submissionFile->submittedFileName);
    }

    public function getAllSubmission(Request $request)
    {
        $submissionID = $request->input('submissionID');
        $submission = Submission::where('id', $submissionID)->first();
        $submissionName = $submission->submissionName;
        $submissionFiles = SubmissionFile::where('submissionID', $submissionID)->get();

        if ($submissionFiles->isEmpty()) {
            // No submission files found
            return redirect()->back()->with('error', 'No submission files found.');
        }

        $zipFileName = 'submission_files_' . $submissionName . '.zip';
        $zipFilePath = public_path('assets/tempzip/' . $zipFileName);

        // Create a new ZipArchive instance
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            // Failed to create zip archive
            return redirect()->back()->with('error', 'Failed to create zip archive.');
        }

        // Add each submission file to the zip archive
        foreach ($submissionFiles as $submissionFile) {
            $filePath = 'assets/submissions/' . $submissionFile->submittedFileContent;

            if (!file_exists($filePath)) {
                // File not found
                continue;
            }

            $user = User::find($submissionFile->userID);
            $username = $user->username;

            $fileContent = file_get_contents($filePath);
            $newFileName = $username . '_' . $submissionFile->submittedFileName;
            $zip->addFromString($newFileName, $fileContent);
        }

        // Close the zip archive
        $zip->close();

        // Download the zip file
        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend();
    }

    public function submissionIndex()
    {
        //get classroom based on id
        $classroom = Classroom::where('classCode', Auth::user()->participants->participant_classcode)->first();

        $submissions = Submission::where('classID', $classroom->id)->where('ishidden', false)->get();

        if(!$submissions->isEmpty()){
            foreach($submissions as $submission){
                // Convert duedate string to Carbon object
                $dueDate = Carbon::parse($submission->duedate);

                // Convert duetime string to Carbon object
                $dueTime = Carbon::parse($submission->duetime);

                // Format the date
                $formattedDate = $dueDate->format('j F Y');

                // Format the time
                $formattedTime = $dueTime->format('h:i A');

                $submission->setAttribute('formattedDate', $formattedDate);
                $submission->setAttribute('formattedTime', $formattedTime);
            }
        }

        $id = $classroom->id;

        return view('submission.submission', compact('submissions', 'id', 'classroom'));
    }

    public function activeclasslistsub()
    {
        //get list of active classrooms
        $classrooms = Classroom::where('isAvailable', 1)->get();

        if(!empty($classrooms)){
            foreach($classrooms as $classroom){
                $submissions = Submission::where('classID', $classroom->id)->get();
                $classroom->setAttribute('submissionCount', count($submissions));
            }
        }

        return view('member.activeclasslistsub', compact('classrooms'));
    }

    public function viewSubmissionById($id)
    {
        //get classroom based on id
        $classroom = Classroom::where('id', $id)->first();

        $submissions = Submission::where('classID', $id)->get();

        if(!$submissions->isEmpty()){
            foreach($submissions as $submission){
                // Convert duedate string to Carbon object
                $dueDate = Carbon::parse($submission->duedate);

                // Convert duetime string to Carbon object
                $dueTime = Carbon::parse($submission->duetime);

                // Format the date
                $formattedDate = $dueDate->format('j F Y');

                // Format the time
                $formattedTime = $dueTime->format('h:i A');

                $submission->setAttribute('formattedDate', $formattedDate);
                $submission->setAttribute('formattedTime', $formattedTime);
            }
        }

        return view('submission.submission', compact('submissions', 'id', 'classroom'));
    }
}
