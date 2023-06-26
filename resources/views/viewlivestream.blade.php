@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Livestream</h1>
  <nav>
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      @if(Auth::user()->usertype != '1')
      <li class="breadcrumb-item"><a href="{{ route('livestream') }}">List of Livestream</a></li>
      @else
      <li class="breadcrumb-item"><a href="{{ route('livestreambyclass') }}">List of Livestream</a></li>
      @endif
      <li class="breadcrumb-item active">Livestream</li>
  </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ $livestream->streamName }}</h5>
            <p>{{ $livestream->streamDesc }}</p>
            <div style="display: flex; justify-content: center;">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $livestream->yt_streamID }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            <div class="d-flex justify-content-center mt-2">
               {{-- <iframe width="560px" height="315px" src="https://www.youtube.com/live_chat?v={{ $livestream->yt_streamID }}&amp;embed_domain=http://127.0.0.1:8000" ></iframe> --}}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Ranking</h5>
            <div id="reportsContainer">
            <table class="table table-striped">
              <thead>
                <th>No</th>
                <th>Team Name</th>
                <th>Total Marks</th>
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
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="3" class="text-center">No Data</td>
                </tr>
                @endif
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Function to fetch and update the reports table
  function updateReportsTable(classroomId) {
    $.ajax({
      url: "{{ route('fetchReports') }}",
      type: "GET",
      data: { classroomId: classroomId }, // Pass the classroomId as a parameter
      dataType: "html",
      success: function (data) {
        // Replace the content of reportsContainer with the updated table
        $('#reportsContainer').html(data);
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      }
    });
  }

  // Call the updateReportsTable function initially with the classroomId
  var classroomId = "{{ $livestream->classroomID }}";
  updateReportsTable(classroomId);

  // Set an interval to call the updateReportsTable function every few seconds
  setInterval(function () {
    updateReportsTable(classroomId);
  }, 5000); // Refresh every 5 seconds (adjust the time interval as needed)
</script>


@endsection