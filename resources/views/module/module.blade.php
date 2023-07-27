@extends('layouts.app')
@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
      <!-- display error message -->
      Failed to add add new module:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="pagetitle">
        <h1>Module</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            @if(Auth::user()->usertype != '1')
            <li class="breadcrumb-item"><a href="{{ route('activeclasslist') }}">Class</a></li>
            @endif
            <li class="breadcrumb-item active">Module</li>
        </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <h5 class="card-title">{{ $classroom->className }}</h5>
                  </div>
                  <div class="col-lg-6 mt-3">
                    <div class="col-md-12 d-flex justify-content-end mb-2">
                      <!-- check if admin/member display add module button -->
                      @if(Auth::user()->usertype != '1')
                        <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                           <i class="bi bi-plus-circle"></i><span>&nbspAdd Module</span>
                        </button>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- list all modules -->
                <div class="row">
                @foreach($modules as $module)
                    <div class="col-md-3">
                        @if(Auth::user()->usertype != '1')
                        <a href="{{ route('viewmodule', ['id' => $module->id]) }}">
                        @else
                        <a href="{{ route('viewmoduleparticipant', ['id' => $module->id]) }}">
                        @endif
                        <div class="card shadow">
                            <img class="card-img-top" src="{{ asset('assets/img/modulepics/' . $module->modulePic) }}" alt="Card image cap" width="30em" height="170em"> 
                            <div class="card-body">
                            <h5 class="card-title">
                              {{ $module->moduleName }}
                              @if(Auth::user()->usertype != '1' && $module->isHidden == true)
                                <i class="bi bi-eye-slash"></i>
                              @endif
                            </h5>
                            <p class="card-text">{{ $module->moduleDesc }}</p>
                            <!-- if participant, display learning percentage -->
                            @if(Auth::user()->usertype == '1')
                            <div class="card-footer text-white text-center">
                              <span class="percentage-badge badge {{ $module->percentage == 100 ? 'bg-success' : 'bg-primary' }}">
                                {{ $module->percentage }}%
                            </span>
                            </div>
                            @endif
                          </a>
                          <!-- if admin/member, display edit and delete button -->
                            @if(Auth::user()->usertype != '1')
                              <div class="d-flex justify-content-between align-items-center">
                                <button  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModuleModal" onclick="getModule('{{ $module->id }}')">
                                  Edit
                               </button>

                                <form action="{{ route('deleteModule') }}" method="POST" id="removeForm" onsubmit="return confirm('Are you sure you want to delete Module: {{ $module->moduleName }}?');">
                                  @csrf
                                  <input type="hidden" name="moduleID" id="moduleID" value="{{ $module->id }}">
                                  <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                              </div>
                            @endif
                            </div>
                        </div>
                    </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
    </div>

     <!-- modal add module -->
     <div class="modal fade" id="addModuleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
  
              <div class="modal-body">
                <form method="POST" action="{{ route('createModule') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="modulename">Module Name:</label>
                    <input type="text" class="form-control" id="modulename" name="modulename" value="{{ old('modulename') }}">
                  </div>
  
                  <div class="form-group mb-3">
                    <label for="moduledesc">Module Description:</label>
                    <input type="text" class="form-control" id="moduledesc" name="moduledesc" value="{{ old('moduledesc') }}">
                  </div>

                  <input name="classSelect" id="classSelect" class="form-select" type="hidden" value="{{$id}}">
              </div>
  
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End add module Modal-->

    <!-- modal edit module -->
      <div class="modal fade" id="editModuleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form method="POST" action="{{ route('editModule') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group mb-3">
                    <label for="editmodulename">Module Name:</label>
                    <input type="text" class="form-control" id="editmodulename" name="editmodulename" value="{{ old('editmodulename') }}">
                  </div>

                  <div class="form-group mb-3">
                    <input type="checkbox" id="isHidden" name="isHidden">Hide Module
                  </div>

                  <div class="form-group mb-3">
                    <label for="editmoduledesc">Module Description:</label>
                    <input type="text" class="form-control" id="editmoduledesc" name="editmoduledesc" value="{{ old('editmoduledesc') }}">
                  </div>

                  <input name="editmoduleid" id="editmoduleid" class="form-select" type="hidden">
                  <input name="editclassSelect" id="editclassSelect" class="form-select" type="hidden" value="{{$id}}">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End edit module Modal-->
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      //to get the placeholder for the edit modal form
        function getModule(id) {
      $.ajax({
        url: '/module/edit/' + id,
        method: 'GET',
        success: function(response) {
          // Handle the response here
          console.log(response);
          // Update the modal with the retrieved module data
          $('#editmoduleid').val(response.id);
          $('#editmodulename').val(response.moduleName);
          $('#editmoduledesc').val(response.moduleDesc);
          $('#editclassSelect').val(response.classroomID);
          $('#moduleImage').attr('src', response.image_url);

           // Check or uncheck the checkbox based on the value of isHidden
          if (response.isHidden === 1) {
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
    </script>
@endsection