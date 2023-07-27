@extends ('layouts.app')
@section('content')
    <div class="pagetitle">
      <h1>Tournament Report</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">Report</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">
                  <!-- tabs for the page including ranking, group report and individual report -->
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ranking">Ranking</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#group">Group Report</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#individual">Individual Report</button>
                  </li>
                </ul>
                <div class="tab-content pt-2">
                    <!-- display the ranking table -->
                    <div class="tab-pane fade show active ranking" id="ranking">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-responsive">
                            <thead>
                                <th>No</th>
                                <th>Group/User Name</th>
                                <th>Total Marks</th>
                                <th>Total Checkpoints Passed</th>
                                <th>Objective</th>
                            </thead>
                            <tbody>
                                @if(!$reports->isEmpty())
                                @foreach($reports as $index=>$report)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    @if(isset($report->user))
                                    <td>{{ $report->user->username }}</td>
                                    @else
                                    <td>{{ $report->group->name }}</td>
                                    @endif
                                    <td>{{ $report->totalMarks }}</td>
                                    <td>{{ $report->totalCheckpoints }}</td>
                                    @if($report->passObjective == true)
                                    <td>Passed</td>
                                    @else
                                    <td>Failed</td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr><td colspan="5" class="text-center">No Data</td></tr>
                                @endif
                            </tbody>
                         </table>
                        </div>
                    </div>
                  
                  <!-- display the group report -->
                  <div class="tab-pane fade show group" id="group">
                    <table class="table table-striped table-bordered table-hover table-responsive">
                        @if(isset($reportGroup))
                        <tr>
                            <th style="width:30%">Group</th>
                            <td>{{ $group->name }}</td>
                        </tr>
                        <tr>
                          <th>Group Members</th>
                          <td>
                            @foreach($group->groupMembers as $count=>$member)
                            {{$count+1}}. {{$member->username}} <br>
                            @endforeach
                          </td>
                        </tr>
                        <tr>
                            <th>Class</th>
                            <td>{{ $class->className }}</td>
                        </tr>
                        <tr>
                            <th>Report Name</th>
                            <td>{{ $reportGroup->name }}</td>
                        </tr>
                        <tr>
                            <th>Total Marks</th>
                            <td>{{ $reportGroup->totalMarks }}</td>
                        </tr>
                        <tr>
                            <th>Total Checkpoints Passed</th>
                            <td>{{ $reportGroup->totalCheckpoints }}</td>
                        </tr>
                        <tr>
                            <th>Objective Achieved</th>
                            @if($reportGroup->passObjective == true)
                            <td>Passed</td>
                            @else
                            <td>Failed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Comment</th>
                            <td>{!! $reportGroup->comment !!}</td>
                        </tr>
                        @else
                        <tr>
                            No Data
                        </tr>
                        @endif
                     </table>
                  </div>

                  <!-- display the group report -->
                  <div class="tab-pane fade individual pt-3" id="individual">  
                    <table class="table table-striped table-bordered table-hover table-responsive">
                        @if(isset($reportUser))
                        <tr>
                            <th style="width:30%">Participant</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>Class</th>
                            <td>{{ $class->className }}</td>
                        </tr>
                        <tr>
                            <th>Report Name</th>
                            <td>{{ $reportUser->name }}</td>
                        </tr>
                        <tr>
                            <th>Total Marks</th>
                            <td>{{ $reportUser->totalMarks }}</td>
                        </tr>
                        <tr>
                            <th>Total Checkpoints Passed</th>
                            <td>{{ $reportUser->totalCheckpoints }}</td>
                        </tr>
                        <tr>
                            <th>Objective Achieved</th>
                            @if($reportUser->passObjective == true)
                            <td>Passed</td>
                            @else
                            <td>Failed</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Comment</th>
                            <td>{!! $reportUser->comment !!}</td>
                        </tr>
                        @else
                        <tr>
                            No Data
                        </tr>
                        @endif
                     </table>
                  </div>
                </div><!-- End Bordered Tabs -->
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection