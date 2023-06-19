@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Participant Progress</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('activeclassprogressreport') }}">Class</a></li>
        <li class="breadcrumb-item"><a href="{{ route('viewProgressbyClass', ['id' => $class->id]) }}">{{$class->className}}</a></li>
        <li class="breadcrumb-item active">View Participant Progress</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{$participant->username}}</h5>
            @foreach($modules as $module)
              <div class="card shadow lg p-3 mb-5 bg-white rounded">
                <div class="card-body">
                <h5 class="card-title">
                    {{$module->moduleName}}
                    @foreach($participant->moduleCompletionArr as $moduleCompletion)
                    @if($moduleCompletion['modID'] == $module->id)
                    ({{$moduleCompletion['percentage']}}%)
                    @endif
                    @endforeach
                </h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File Name</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody>
                @foreach($files as $index=>$file)
                    @if($file->moduleID == $module->id)
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>{{$file->fileName}}</td>
                            @foreach($participant->fileCompletionArr as $arr)
                                @if($arr['fileID'] == $file->id)
                                    @if($arr['status'] == '1')    
                                        <td>
                                        <span class="percentage-badge badge bg-success">
                                            Done
                                        </span>
                                        </td>
                                    @else
                                        <td>
                                        <span class="percentage-badge badge bg-primary">
                                            No Action
                                        </span>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endif
                 @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
  
@endsection
