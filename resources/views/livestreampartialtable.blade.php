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