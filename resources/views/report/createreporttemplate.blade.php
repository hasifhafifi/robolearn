@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Report Template</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Create Template</li>
        </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">
                            <h5 class="card-title">Report Template</h5>
                        </div>
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReportModal">
                                <i class="bi bi-plus-circle"></i><span>&nbspAdd Report Template</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSck2cUh66u0Cr-MbGW2madYuy1AYwu6fYOnTyyo_z2g2ZynGA/viewform?embedded=true" width="700" height="520" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
                </div>
                <div class="">
                    <iframe src="https://docs.google.com/spreadsheets/d/e/2PACX-1vRaGM0shieAFuVzaRn8OgvPO-Bvc7mOilYvhHHkes1cNmZdfFS6bjT0fTo_bcHhDwp8I4VSDujLRQMR/pubhtml?gid=826926955&amp;single=true&amp;widget=true&amp;headers=false"></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- modal add module -->
     <div class="modal fade" id="addReportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add New Report Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
  
              <div class="modal-body">
                <form method="POST" action="{{ route('saveReportTemplate') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="modulename">Report Name:</label>
                        <input type="text" class="form-control" id="modulename" name="reportname" value="{{ old('reportname') }}">
                      </div>
                  
                    <div id="fieldsContainer">
                      <!-- Initial field -->
                      <div class="form-group row mb-3">
                        <div class="col-md-4">
                          <label for="field1Type">Field 1 Type:</label>
                          <select name="fieldTypes[]" class="form-control" id="field1Type">
                            <option value="text">Text</option>
                            <option value="option">Option</option>
                            <option value="select">Select</option>
                            <!-- Add more options as needed -->
                          </select>
                        </div>
                        <div class="col-md-5">
                          <label for="field1Name">Field 1 Name:</label>
                          <input type="text" name="fieldNames[]" class="form-control" id="field1Name">
                        </div>
                        <div class="col-md-3 d-flex align-items-center mt-3">
                          <button type="button" class="removeFieldButton btn btn-danger">Remove</button>
                        </div>
                      </div>
                    </div>
                  
                    <button type="button" id="addFieldButton" class="btn btn-primary mt-2">Add Field</button>                                 
              </div>
  
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
    </div><!-- End add module Modal-->
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
          var fieldCount = 1; // Initial field count
      
          // Add Field button click event
          $('#addFieldButton').click(function() {
            fieldCount++; // Increment field count
      
            var newField = `
              <div class="form-group row mb-3" id="field${fieldCount}">
                <div class="col-md-4">
                  <label for="field${fieldCount}Type">Field ${fieldCount} Type:</label>
                  <select name="fieldTypes[]" class="form-control field-type" id="field${fieldCount}Type">
                    <option value="text">Text</option>
                    <option value="option">Option</option>
                    <option value="select">Select</option>
                    <!-- Add more options as needed -->
                  </select>
                </div>
                <div class="col-md-5">
                  <label for="field${fieldCount}Name">Field ${fieldCount} Name:</label>
                  <input type="text" name="fieldNames[]" class="form-control field-name" id="field${fieldCount}Name">
                </div>
                <div class="col-md-3 d-flex align-items-center mt-3">
                  <button type="button" class="removeFieldButton btn btn-danger" data-field="${fieldCount}">Remove</button>
                </div>
              </div>
            `;
      
            $('#fieldsContainer').append(newField);
          });
      
          // Remove Field button click event
          $(document).on('click', '.removeFieldButton', function() {
            var fieldNumber = $(this).data('field');
            $('#field' + fieldNumber).remove();
          });
      
          // Change event for field type selection
          $(document).on('change', '.field-type', function() {
            var selectedType = $(this).val();
            var fieldContainer = $(this).closest('.form-group.row');
            var optionsField = fieldContainer.find('.field-options');
      
            if (selectedType === 'option') {
              if (optionsField.length === 0) {
                var optionsFieldHtml = `
                  <div class="col-md-3 field-options">
                    <label for="field${fieldCount}Options">Options:</label>
                    <input type="text" name="fieldOptions[]" class="form-control field-options-input" id="field${fieldCount}Options">
                  </div>
                `;
                fieldContainer.append(optionsFieldHtml);
              }
            } else {
              optionsField.remove();
            }
          });
        });
      </script>
      
        
@endsection