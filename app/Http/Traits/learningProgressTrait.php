<?php
namespace App\http\traits;
use App\Models\Classroom;
use App\Models\Document;
use App\Models\File;
use App\Models\Group;
use App\Models\Module;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait learningProgressTrait{
    public function checkIfFileCompletionExist($participant){
        // $user = Auth::user();
        // $participant = Participant::where('user_id', $user->id)->first();
        $class = Classroom::where('classCode', $participant->participant_classcode)->first();
        $allModules = Module::where('classroomID', $class->id)->get();
        $allModulesIDs = $allModules->pluck('id');
        $allDocuments = Document::whereIn('moduleID', $allModulesIDs)->get();
        $allDocumentIDs = $allDocuments->pluck('id');
        $allFiles = File::whereIn('documentID', $allDocumentIDs)->get();

        $arrayFile = [];
        // dd('masuk bos');
        //update fileCompletion column
        if(!isset($participant->fileCompletion)){
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
            $existingFiles = json_decode($participant->fileCompletion, true);
            
             // Compare the count of existing files with the count of files set by the member
            if (count($existingFiles) !== count($allFiles)) {

                // Update the array with the new files
                $updatedFiles = [];
                foreach ($allFiles as $file) {
                    // Check if the file already exists in the array
                    $existingFile = array_filter($existingFiles, function ($item) use ($file) {
                        return $item['fileID'] === $file->id;
                    });

                    if (!empty($existingFile)) {
                        // Use the existing file status
                        $updatedFiles[] = reset($existingFile);
                    } else {
                        // Add new file with status 0
                        $updatedFiles[] = [
                            'modID' => $file->moduleID,
                            'fileID' => $file->id,
                            'status' => 0
                        ];
                    }
                }

                $participant->fileCompletion = json_encode($updatedFiles);
                $participant->save();
            }
        }
        return json_decode($participant->fileCompletion, true);
    }

    public function checkIfModuleCompletionExist($participant){
        //get list of active classrooms
        $classroom = Classroom::where('classCode', $participant->participant_classcode)->first();
        
        // $modules = Module::where('classroomID', $classroom->id)->where('isHidden', false)->get();
        $modules = Module::where('classroomID', $classroom->id)->get();

        // $user = Auth::user();
        // $participant = Participant::where('user_id', $user->id)->first();

        $arrayModule = [];

        //get module completion status
        if(!isset($participant->moduleCompletion)){
            foreach($modules as $module){
                $arrayModule[] = [
                    'modID' => $module->id,
                    'percentage' => 0.00
                ];
            }
            $arrayModule = json_encode($arrayModule);
            $participant->moduleCompletion = $arrayModule;
            $participant->save();
        }else{
            // Get the existing module completion status from the participant
            $existingModules = json_decode($participant->moduleCompletion, true);

            // Check if the number of existing modules is different from the total number of modules
            if (count($existingModules) !== count($modules)) {
                // Create an array to store the updated module completion status
                $updatedModules = [];

                // Loop through all the modules
                foreach ($modules as $module) {
                    // Check if the module already exists in the existing module completion status
                    $existingModule = array_filter($existingModules, function ($item) use ($module) {
                        return $item['modID'] == $module->id;
                    });

                    // If the module exists, add it to the updated module completion status
                    if (!empty($existingModule)) {
                        $updatedModules[] = array_shift($existingModule);
                    } else {
                        // If the module does not exist, add it with a percentage of 0
                        $updatedModules[] = [
                            'modID' => $module->id,
                            'percentage' => 0.00
                        ];
                    }
                }

                // Update the module completion status in the participant
                $participant->moduleCompletion = json_encode($updatedModules);
                $participant->save();
            }
        }

        return json_decode($participant->moduleCompletion, true);
    }

    public function checkIfFileCompletionExistAdmin($participant){
        // dd($participant);
        // $user = Auth::user();
        // $participant = Participant::where('user_id', $user->id)->first();
        $class = Classroom::where('classCode', $participant->participant_classcode)->first();
        $allModules = Module::where('classroomID', $class->id)->get();
        $allModulesIDs = $allModules->pluck('id');
        $allDocuments = Document::whereIn('moduleID', $allModulesIDs)->get();
        $allDocumentIDs = $allDocuments->pluck('id');
        $allFiles = File::whereIn('documentID', $allDocumentIDs)->get();

        $arrayFile = [];
        // dd('masuk bos');
        //update fileCompletion column
        if(!isset($participant->fileCompletion)){
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
        }

        $existingFiles = json_decode($participant->fileCompletion, true);
        
            // Compare the count of existing files with the count of files set by the member
        if (count($existingFiles) !== count($allFiles)) {

            // Update the array with the new files
            $updatedFiles = [];
            foreach ($allFiles as $file) {
                // Check if the file already exists in the array
                $existingFile = array_filter($existingFiles, function ($item) use ($file) {
                    return $item['fileID'] === $file->id;
                });

                if (!empty($existingFile)) {
                    // Use the existing file status
                    $updatedFiles[] = reset($existingFile);
                } else {
                    // Add new file with status 0
                    $updatedFiles[] = [
                        'modID' => $file->moduleID,
                        'fileID' => $file->id,
                        'status' => 0
                    ];
                }
            }

            $participant->fileCompletion = json_encode($updatedFiles);
            $participant->save();
        }
        return json_decode($participant->fileCompletion, true);
    }

    public function updateAfterAddModule($module)
    {
        $class = Classroom::where('id', $module->classroomID)->first();

        // Get all participants of that class
        $participants = Participant::where('participant_classcode', $class->classCode)->get();

        // Update for each participant
        foreach ($participants as $participant) {
            // Update module for each participant
            $moduleCompletion = json_decode($participant->moduleCompletion, true);

            $moduleCompletion[] = [
                'modID' => $module->id,
                'percentage' => 0.00
            ];

            $participant->moduleCompletion = json_encode($moduleCompletion);
            $participant->save();
        }
    }

    public function updateAfterDeleteFile($file){
        $module = Module::where('id', $file->moduleID)->first();
        $class = Classroom::where('id', $module->classroomID)->first();

        //get all participants of that class
        $participants = Participant::where('participant_classcode', $class->classCode)->get();
        
        //update for each participant
        foreach($participants as $participant){
            // Remove from fileCompletion
            $fileCompletion = json_decode($participant->fileCompletion, true);
            $fileID = $file->id;

            // Check if the fileID exists in fileCompletion and remove it
            $fileCompletion = array_filter($fileCompletion, function ($arr) use ($fileID) {
                return $arr['fileID'] != $fileID;
            });

            // Update the fileCompletion column
            $participant->fileCompletion = json_encode(array_values($fileCompletion));

            // Update moduleCompletion
            $moduleCompletion = json_decode($participant->moduleCompletion, true);
            $moduleID = $module->id;

            // Calculate the new percentage
            $countCompleted = 0;
            $countTotal = 0;

            foreach ($moduleCompletion as &$arr) {
                if ($arr['modID'] == $moduleID) {
                    foreach($fileCompletion as $arrFile){
                        if($arrFile['modID'] == $moduleID){
                            if ($arrFile['status'] == 1) {
                            $countCompleted++;
                            }
                            $countTotal++;
                        } 
                    }
                    break;
                }
            }

            // Calculate the percentage
            $percentage = $countTotal > 0 ? ($countCompleted / $countTotal) * 100 : 0;

            // Update the percentage in moduleCompletion
            foreach ($moduleCompletion as &$arr) {
                if ($arr['modID'] == $moduleID) {
                    $arr['percentage'] = number_format($percentage, 2); // Format the percentage to 2 decimal places
                }
            }

            // Update the moduleCompletion column
            $participant->moduleCompletion = json_encode($moduleCompletion);

            // Save the participant model
            $participant->save();
        }
    }

    public function updateAfterDeleteModule($module)
    {
        $class = Classroom::where('id', $module->classroomID)->first();
        $moduleID = $module->id;

        // Get all participants of that class
        $participants = Participant::where('participant_classcode', $class->classCode)->get();

        // Update for each participant
        foreach ($participants as $participant) {
            // Remove every fileCompletion with the same moduleID
            $fileCompletion = json_decode($participant->fileCompletion, true);
            $updatedFileCompletion = [];

            if(isset($fileCompletion)){
            foreach ($fileCompletion as $file) {
                if ($file['modID'] != $moduleID) {
                    $updatedFileCompletion[] = $file;
                }
            }
            }

            $participant->fileCompletion = json_encode($updatedFileCompletion);

            // Remove from moduleCompletion
            $moduleCompletion = json_decode($participant->moduleCompletion, true);
            $updatedModuleCompletion = [];

            foreach ($moduleCompletion as $moduleCompletionItem) {
                if ($moduleCompletionItem['modID'] != $moduleID) {
                    $updatedModuleCompletion[] = $moduleCompletionItem;
                }
            }

            $participant->moduleCompletion = json_encode($updatedModuleCompletion);

            $participant->save();
        }
    }
}