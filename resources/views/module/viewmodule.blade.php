@extends('layouts.app')
@section('content')
@if ($errors->any())
  <!-- display error message -->
    <div class="alert alert-danger">
      Failed to add add new module:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
  .custom-cursor {
  cursor: pointer;
}
</style>

<div class="pagetitle">
    <h1>{{ $module->moduleName }}</h1>
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if(Auth::user()->usertype != '1')
          <li class="breadcrumb-item"><a href="{{ route('activeclasslist') }}">Class</a></li>
        @endif
        @if(Auth::user()->usertype != '1')
          <li class="breadcrumb-item"><a href="{{ route('viewModuleById', ['id' => $module->classroomID]) }}">Module</a></li>
        @else
          <li class="breadcrumb-item"><a href="{{ route('module') }}">Module</a></li>
        @endif
        <li class="breadcrumb-item active">{{ $module->moduleName }}</li>
    </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card border border-dark">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                <h5 class="card-title">Module Content</h5>
              </div>
              <div class="col-lg-6 d-flex align-items-center justify-content-end">
                <!-- if not participant, display the add section button -->
                @if(Auth::user()->usertype != '1')
                    <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                        <i class="bi bi-plus-circle"></i><span>&nbspAdd Section</span>
                    </button>
                @endif
              </div>
            </div>

            <div class="row">
              <!-- check if the contents is empty -->
              @if($sections->isEmpty())
              <div class="card shadow lg p-3 mb-5 bg-white rounded border border-dark">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title">No Data</div>
                  </div>
                  @if(Auth::user()->usertype != '1')
                  Click Add Section to start adding content.
                  @else
                  Content will be added later.
                  @endif
                </div>
              </div>
              @else
              <!-- display each sections -->
              @foreach($sections as $index=>$section)
              <div class="card shadow lg p-3 mb-5 bg-white rounded border border-dark">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <div>
                      {{ $index+1 }}. {{ $section->secTitle }}
                      @if(Auth::user()->usertype != '1' && $section->isHidden == true)
                        <i class="bi bi-eye-slash"></i>
                      @endif
                    </div>
                    
                    <div class="dropdown ms-auto">
                      <!-- if not participant, display button to add/edit/delete the section -->
                      @if(Auth::user()->usertype != '1')
                      <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                      <ul class="dropdown-menu">
                        <li>
                          <span class="dropdown-item">
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#addContentModal" onclick="getSectionID('{{ $section->id }}')"><i class="bi bi-plus-circle mx-2"></i><span> Add Content</span></button>
                          </span>
                        </li>
                        <li>
                          <span class="dropdown-item">
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#editSectionModal" onclick="getSection('{{ $section->id }}')"><i class="bi bi-pencil-square mx-2"></i> Edit</button>
                          </span>
                        </li>
                        <li>
                          <span class="dropdown-item">
                          <form action="{{ route('deleteSection') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete section: {{ $section->secTitle }} and all its contents?');">
                            @csrf
                            <input type="hidden" name="sectionid" id="sectionid" value="{{ $section->id }}">
                            <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                          </form>
                          </span>
                        </li>
                      </ul>
                      @endif
                    </div>
                  </div>

                  @php $indexDoc = 0; @endphp
                  <!-- display all the documents in that section -->
                  @foreach($documents as $document)
                    @if($document->sectionID == $section->id)
                    <div class="card shadow p-3 mb-5 bg-white rounded border border-dark">
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="card-title">
                            {{ $indexDoc+1 }}.  {{ $document->docTitle }}
                            @php $indexDoc++; @endphp
                            @if(Auth::user()->usertype != '1' && $document->isHidden == true)
                              <i class="bi bi-eye-slash"></i>
                            @endif
                          </div>
                          <!-- if not participant, display button to edit or delete the content -->
                          @if(Auth::user()->usertype != '1')
                          <div class="dropdown ms-auto">
                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">
                              <li>
                                <span class="dropdown-item">
                                  <form action="{{ route('viewContent', ['id' => $document->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Edit</button>
                                  </form>
                                </span>
                              </li>
                              <li>
                                <span class="dropdown-item">
                                  <form action="{{ route('deleteContent') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete content: {{ $document->docTitle }}?');">
                                    @csrf
                                    <input type="hidden" name="contentid" id="contentid" value="{{ $document->id }}">
                                    <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                  </form>
                                </span>
                              </li>
                            </ul>
                          </div>
                          @endif
                        </div>
                          {!! $document->docDesc !!}
                          
                          @if (empty($files))
                              <p>No files available.</p>
                          @else
                          <!-- loop all files in that document -->
                            @foreach($files as $file)
                              @if($file->documentID == $document->id)
                              <div class="p-3 mb-2 border border-dark bg-light rounded">
                                <!-- display for text type of file -->
                                @if($file->fileType == 'text')
                                <div class="row">
                                  <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                      <i class="bi bi-file-earmark-text"></i>
                                      <a href="{{ route('viewFile', ['id' => $file->id]) }}">{{ $file->fileName }}</a>
                                      @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                        <i class="bi bi-eye-slash"></i>
                                      @endif
                                    </div>
                                    <!-- mark as done button for participant -->
                                    @if(Auth::user()->usertype == '1')
                                    <div>
                                      @if($file->status == '0')
                                      <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                      </form>
                                      @else
                                      <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                      </form>
                                      @endif
                                    </div>
                                    @else
                                    <!-- update/delete button for the member/admin -->
                                    <div>
                                      <div class="dropdown ms-auto">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu">
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                              </form>
                                            </span>
                                          </li>
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                              </form>
                                            </span>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    @endif
                                  </div>
                                  <div>
                                    <!-- display the text content -->
                                    {!! $file->fileContent !!}
                                  </div> 
                                </div>
                                <!-- for the image file type -->
                                @elseif($file->fileType == 'image')
                                  <div class="row">
                                    <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                        <i class="bi bi-file-earmark-image"></i>
                                        <a href="{{ route('viewFile', ['id' => $file->id]) }}">{{ $file->fileName }}</a>
                                        @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                          <i class="bi bi-eye-slash"></i>
                                        @endif
                                      </div>
                                      @if(Auth::user()->usertype == '1')
                                      <div>
                                        <!-- marks as done button for participant -->
                                        @if($file->status == '0')
                                        <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                          @csrf
                                          <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                          <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                        </form>
                                        @else
                                        <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                          @csrf
                                          <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                          <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                        </form>
                                        @endif
                                      </div>
                                      @else
                                      <!-- update/delete button for admin/member -->
                                      <div>
                                        <div class="dropdown ms-auto">
                                          <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                          <ul class="dropdown-menu">
                                            <li>
                                              <span class="dropdown-item">
                                                <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                  @csrf
                                                  <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                  <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                                </form>
                                              </span>
                                            </li>
                                            <li>
                                              <span class="dropdown-item">
                                                <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                  @csrf
                                                  <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                  <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                                </form>
                                              </span>
                                            </li>
                                          </ul>
                                        </div>
                                      </div>
                                      @endif
                                    </div>
                                    <!-- display the image -->
                                    <div class="d-flex justify-content-center align-items-center mt-2" style="height: 100%;">
                                      <img src="{{ asset('assets/img/filepics/' . $file->fileContent) }}" alt="Gambar" style="max-width: 50%; height: auto;" class="text-middle">
                                    </div> 
                                  </div>
                                  <!-- for pdf file type -->
                                @elseif($file->fileType == 'pdf')
                                  <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                      <i class="bi bi-file-earmark-pdf"></i>
                                      <!-- display the pdf file name and open in newtab when clicked -->
                                      <a href="{{ route('viewFilePDF', ['id' => $file->id]) }}" target="_blank">{{ $file->fileName }}</a>
                                      @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                        <i class="bi bi-eye-slash"></i>
                                      @endif
                                    </div>
                                    <!-- mark as done button for participant -->
                                    @if(Auth::user()->usertype == '1')
                                    <div>
                                      @if($file->status == '0')
                                      <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                      </form>
                                        @else
                                        <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                          @csrf
                                          <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                          <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                        </form>
                                        @endif
                                    </div>
                                    @else
                                    <!-- update/delete button for member/admin -->
                                    <div>
                                      <div class="dropdown ms-auto">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu">
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                              </form>
                                            </span>
                                          </li>
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                              </form>
                                            </span>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    @endif
                                  </div>
                                  <!-- display for zip file type -->
                                @elseif($file->fileType == 'zip')
                                  <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                      <i class="bi bi-file-earmark-zip"></i>
                                      <!-- display the file and automatically download in new tab when clicked -->
                                      <a href="{{ route('viewFileZip', ['id' => $file->id]) }}" target="_blank">{{ $file->fileName }}</a>
                                      @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                        <i class="bi bi-eye-slash"></i>
                                      @endif
                                    </div>
                                    <!-- mark as done button for participant -->
                                    @if(Auth::user()->usertype == '1')
                                    <div>
                                      @if($file->status == '0')
                                      <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                      </form>
                                      @else
                                      <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                      </form>
                                      @endif
                                    </div>
                                    @else
                                    <!-- update/delete button for member/admin -->
                                    <div>
                                      <div class="dropdown ms-auto">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu">
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                              </form>
                                            </span>
                                          </li>
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                              </form>
                                            </span>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    @endif
                                  </div>
                                  <!-- display for document file type -->
                                @elseif($file->fileType == 'docx')
                                  <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                      <i class="bi bi-file-earmark-word"></i>
                                      <!-- display the file name and automatically download in new tab when clicked -->
                                      <a href="{{ route('viewFileWord', ['id' => $file->id]) }}" target="_blank">{{ $file->fileName }}</a>
                                      @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                        <i class="bi bi-eye-slash"></i>
                                      @endif
                                    </div>
                                    <!-- mark as done button for participant -->
                                    @if(Auth::user()->usertype == '1')
                                    <div>
                                      @if($file->status == '0')
                                      <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                      </form>
                                      @else
                                      <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                      </form>
                                      @endif
                                    </div>
                                    @else
                                    <!-- update/delete button for member/admin -->
                                    <div>
                                      <div class="dropdown ms-auto">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu">
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                              </form>
                                            </span>
                                          </li>
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                              </form>
                                            </span>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    @endif
                                  </div>
                                  <!-- display for url file type -->
                                @elseif($file->fileType == 'url')
                                  <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                      <i class="bi bi-globe"></i>
                                      <!-- display the file name -->
                                      <a href="{{ route('viewFile', ['id' => $file->id]) }}">{{ $file->fileName }}</a>
                                      @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                        <i class="bi bi-eye-slash"></i>
                                      @endif
                                    </div>
                                    <!-- mark as done button for participant -->
                                    @if(Auth::user()->usertype == '1')
                                    <div>
                                      @if($file->status == '0')
                                      <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                      </form>
                                      @else
                                      <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                      </form>
                                      @endif
                                    </div>
                                    @else
                                    <!-- update/delete button for member/admin -->
                                    <div>
                                      <div class="dropdown ms-auto">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu">
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                              </form>
                                            </span>
                                          </li>
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                              </form>
                                            </span>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    @endif
                                  </div>
                                  <!-- display for youtube video url file type -->
                                @elseif($file->fileType == 'yturl')
                                <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                    <i class="bi bi-file-earmark-easel"></i>
                                    <!-- display the file name -->
                                    <a href="{{ route('viewFile', ['id' => $file->id]) }}">{{ $file->fileName }}</a>
                                    @if(Auth::user()->usertype != '1' && $file->ishidden == true)
                                        <i class="bi bi-eye-slash"></i>
                                      @endif
                                  </div>
                                  <!-- mark as done button for participant -->
                                  @if(Auth::user()->usertype == '1')
                                    <div>
                                      @if($file->status == '0')
                                      <form id="markAsDoneForm" action="{{ route('markAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-primary" type="submit" onclick="submitForm()">Mark as Done</button>
                                      </form>
                                      @else
                                      <form id="unmarkAsDoneForm" action="{{ route('unmarkAsDone') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="fileID" id="fileID" value="{{$file->id}}">
                                        <button class="btn btn-success" type="submit" onclick="submitFormUnmark()">Done</button>
                                      </form>
                                      @endif
                                    </div>
                                    @else
                                    <!-- edit/delete button for admin/member -->
                                    <div>
                                      <div class="dropdown ms-auto">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu">
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('viewFileforEdit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-pencil-square mx-2"></i> Update</button>
                                              </form>
                                            </span>
                                          </li>
                                          <li>
                                            <span class="dropdown-item">
                                              <form action="{{ route('deleteFile') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete file: {{ $file->fileName }}?');">
                                                @csrf
                                                <input type="hidden" name="fileid" id="fileid" value="{{ $file->id }}">
                                                <button type="submit" class="btn"><i class="bi bi-trash mx-2"></i> Delete</button>
                                              </form>
                                            </span>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    @endif
                                </div>      
                                @endif
                              </div>
                              @endif
                            @endforeach
                          @endif
                          <!-- add file button for member/admin -->
                          @if(Auth::user()->usertype != '1')
                          <div class="card-footer bg-light border border-dark custom-cursor rounded" data-bs-toggle="modal" data-bs-target="#addFileModal" onclick="getDocumentID('{{ $document->id }}')">
                            <i class="bi bi-plus-circle"></i><span>&nbspAdd File</span>
                          </div>
                          @endif
                      </div>
                    </div>
                    @endif
                  @endforeach
                </div>
              </div>
              @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add section -->
    <div class="modal fade" id="addSectionModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Section</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form method="POST" action="{{ route('createSection') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                  <label for="sectionname">Section Title:</label>
                  <input type="text" class="form-control" id="sectionname" name="sectionname" value="{{ old('sectionname') }}">
                </div>
                <input type="hidden" id="moduleid" name="moduleid" value="{{$module->id}}">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div><!-- End add section Modal-->

    <!-- modal edit section -->
    <div class="modal fade" id="editSectionModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Section</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form method="POST" action="{{ route('editSection') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                  <label for="editsectionname">Section Title:</label>
                  <input type="text" class="form-control" id="editsectionname" name="editsectionname" value="{{ old('editsectionname') }}">
                </div>
                <div class="form-group mb-3">
                  <input type="checkbox" id="isHidden" name="isHidden">Hide Section
                </div>
                <input type="hidden" id="editsectionid" name="editsectionid">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Edit</button>
            </div>
          </form>
        </div>
      </div>
    </div><!-- End edit section Modal-->

    <!-- modal add content -->
    <div class="modal fade" id="addContentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('addContent') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="contentname">Content Title:</label>
                    <input type="text" class="form-control" id="contentname" name="contentname" value="{{ old('contentname') }}">
                  </div>

                  <div class="form-group mb-3">
                    <label for="contentdesc">Content Description:</label>
                    <textarea name="contentdetail" id="contentdetail" class="form-control" value="{{ old('contentdesc') }}" style="display:none"></textarea>
                    <div id="contentdesc" style="height:100px">

                    </div>
                  </div>

                  <input type="hidden" name="moduleid" id="moduleid" value="{{$module->id}}">
                  <input type="hidden" name="consectionid" id="consectionid">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End add content Modal-->

    <!-- modal add file -->
    <div class="modal fade" id="addFileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('addFile') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="filetype">File Type:</label>
                    <select name="filetype" id="filetype" class="form-select">
                      <option value="text">Text</option>
                      <option value="zip">Zip File</option>
                      <option value="pdf">PDF</option>
                      <option value="docx">Document</option>
                      <option value="image">Image</option>
                      <option value="url">URL</option>
                      <option value="yturl">Youtube Video URL</option>
                    </select>
                  </div>

                  <div class="form-group mb-3">
                    <label for="filename">File Title:</label>
                    <input type="text" class="form-control" id="filename" name="filename" value="{{ old('filename') }}">
                  </div>

                  <div class="form-group mb-3" id="textthing">
                    <label for="filetext">Text:</label>
                    <textarea name="filetext" id="filetext" class="form-control" value="{{ old('filetext') }}" style="display:none"></textarea>
                    <div id="filedesc" style="height:100px">

                    </div>
                  </div>

                  <div class="form-group mb-3" id="uploadthing">
                    <label for="filecontent">Upload File:</label>
                    <input type="file" class="form-control" id="filecontent" name="filecontent" value="{{ old('filecontent') }}">
                  </div>

                  <div class="form-group mb-3" id="uploadpdf">
                    <label for="filepdf">Upload File:</label>
                    <input type="file" class="form-control" id="filepdf" name="filepdf" value="{{ old('filepdf') }}">
                  </div>

                  <div class="form-group mb-3" id="uploadzip">
                    <label for="filezip">Upload File:</label>
                    <input type="file" class="form-control" id="filezip" name="filezip" value="{{ old('filezip') }}">
                  </div>

                  <div class="form-group mb-3" id="uploaddoc">
                    <label for="filedoc">Upload File:</label>
                    <input type="file" class="form-control" id="filedoc" name="filedoc" value="{{ old('filedoc') }}">
                  </div>

                  <div class="form-group mb-3" id="uploadurl">
                    <label for="fileurl">URL Link:</label>
                    <input type="text" class="form-control" id="fileurl" name="fileurl" value="{{ old('fileurl') }}">
                  </div>

                  <div class="form-group mb-3" id="uploadyturl">
                    <label for="fileyturl">Youtube Video Link:</label>
                    <input type="text" class="form-control" id="fileyturl" name="fileyturl" value="{{ old('fileyturl') }}">
                  </div>

                  <input type="hidden" id="documentid" name="documentid">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End add file/submission Modal-->
</section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Include the Quill library -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
      //to get the placeholder for editing section form
      function getSection(id) {
        $.ajax({
          url: '/module/section/edit/' + id,
          method: 'GET',
          success: function(response) {
            // Handle the response here
            console.log(response);
            // Update the modal with the retrieved section data
            $('#editsectionname').val(response[0].secTitle);
            $('#editsectionid').val(response[0].id);

            // Check or uncheck the checkbox based on the value of isHidden
            if (response[0].isHidden === 1) {
              $('#isHidden').prop('checked', true);
            } else {
              $('#isHidden').prop('checked', false);
            }
          },
          error: function(xhr, status, error) {
            // Handle the error here
            console.log(error);
          }
        });
      }

      function getSectionID(id) {
        $('#consectionid').val(id);
      }

      function getDocumentID(id) {
        $('#documentid').val(id);
      }
    </script>

  <!-- Initialize Quill editor -->
  <script>
    //for the text area using quill editor
    $(document).ready(function(){
      var quill = new Quill('#contentdesc', {
      theme: 'snow'
      });

      quill.on('text-change', function(){
        $('#contentdetail').text($(".ql-editor").html());
      });
    });
  </script>

<script>
  $(document).ready(function(){
    var quill2 = new Quill('#filedesc', {
    theme: 'snow'
    });

    quill2.on('text-change', function(){
      var htmlContent = quill2.root.innerHTML;
      $('#filetext').val(htmlContent);
    });
  });
</script>

<script>
  $(document).ready(function() {
  // Hide the initial file input field
  $("#uploadthing").hide();
  $("#uploadpdf").hide();
  $("#uploaddoc").hide();
  $("#uploadzip").hide();
  $("#uploadurl").hide();
  $("#uploadyturl").hide();

  // Handle the change event of the file type select field
  $("#filetype").change(function() {
    var selectedType = $(this).val();

    // Show/hide the corresponding input field based on the selected type
    if (selectedType === "text") {
      $("#uploadthing").hide();
      $("#uploadpdf").hide();
      $("#uploaddoc").hide();
      $("#uploadzip").hide();
      $("#uploadurl").hide();
      $("#uploadyturl").hide();
      $("#uploadthing input").attr("type", "text");
      $("#textthing").show();
    } else if (selectedType === "image"){
      $("#uploadthing").show();
      $("#uploadthing input").attr("type", "file");
      $("#textthing").hide();
      $("#uploadpdf").hide();
      $("#uploaddoc").hide();
      $("#uploadzip").hide();
      $("#uploadurl").hide();
      $("#uploadyturl").hide();
    } else if (selectedType === "pdf"){
      $("#uploadpdf").show();
      $("#uploadpdf input").attr("type", "file");
      $("#textthing").hide();
      $("#uploadthing").hide();
      $("#uploaddoc").hide();
      $("#uploadzip").hide();
      $("#uploadurl").hide();
      $("#uploadyturl").hide();
    } else if (selectedType === "zip"){
      $("#uploadzip").show();
      $("#uploadzip input").attr("type", "file");
      $("#textthing").hide();
      $("#uploadthing").hide();
      $("#uploaddoc").hide();
      $("#uploadpdf").hide();
      $("#uploadurl").hide();
      $("#uploadyturl").hide();
    } else if (selectedType === "docx"){
      $("#uploaddoc").show();
      $("#uploaddoc input").attr("type", "file");
      $("#textthing").hide();
      $("#uploadthing").hide();
      $("#uploadpdf").hide();
      $("#uploadzip").hide();
      $("#uploadurl").hide();
      $("#uploadyturl").hide();
    } else if (selectedType === "url"){
      $("#uploadurl").show();
      $("#uploadurl input").attr("type", "text");
      $("#textthing").hide();
      $("#uploadthing").hide();
      $("#uploadpdf").hide();
      $("#uploadzip").hide();
      $("#uploaddoc").hide();
      $("#uploadyturl").hide();
    } else if (selectedType === "yturl"){
      $("#uploadyturl").show();
      $("#uploadyturl input").attr("type", "text");
      $("#textthing").hide();
      $("#uploadthing").hide();
      $("#uploadpdf").hide();
      $("#uploadzip").hide();
      $("#uploaddoc").hide();
      $("#uploadurl").hide();
    } 
  });
});

</script>

<script>
  //automatically submit the form for mark as done button
  function submitForm() {
      // Submit the form
      document.getElementById('markAsDoneForm').submit();
  }

  function submitFormUnmark() {
      // Submit the form
      document.getElementById('unmarkAsDoneForm').submit();
  }
</script>
@endsection