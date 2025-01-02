@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tickets CSV Upload</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tickets</a></li>
              <li class="breadcrumb-item active">Tickets CSV Upload</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <form action="{{ route('tickets.csv.upload') }}" method="post" class="form" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Tickets CSV Upload</h3>
            </div>
            <div class="card-body">
			<div class="row">
			 <div class="form-group col-md-6">
                <label for="inputName">Ticket For: <span class="red">*</span></label>
                <select name="ticket_for" required id="ticket_for" class="form-control">
				<option value="" >Select</option>
				<option value="Adult" @if(old('ticket_for') =='Adult') {{'selected="selected"'}} @endif>Adult</option>
				<option value="Child" @if(old('ticket_for') =='Child') {{'selected="selected"'}} @endif >Child</option>
				<option value="Both" @if(old('ticket_for') =='Both') {{'selected="selected"'}} @endif >Both</option>
                 </select>
				  @if ($errors->has('ticket_for'))
                    <span class="text-danger">{{ $errors->first('ticket_for') }}</span>
				
                @endif
				</div>
				  <div class="form-group col-md-6">
                <label for="inputName">Type Of Ticket: <span class="red">*</span></label>
                <select name="type_of_ticket" required id="type_of_ticket" class="form-control">
				<option value="" >Select</option>
				<option value="Media-Code" @if(old('type_of_ticket') =='Media-Code') {{'selected="selected"'}} @endif>Media-Code</option>
				<option value="QR-Code" @if(old('type_of_ticket') =='QR-Code') {{'selected="selected"'}} @endif >QR-Code</option>
				<option value="PDF" @if(old('type_of_ticket') =='PDF') {{'selected="selected"'}} @endif >PDF</option>
                 </select>
				  @if ($errors->has('type_of_ticket'))
                    <span class="text-danger">{{ $errors->first('type_of_ticket') }}</span>
				
                @endif
				</div>
				 
			  <div class="form-group col-md-6">
                <label for="inputName">Activity: <span class="red">*</span></label>
                <select name="activity_id" required id="activity_id" class="form-control">
				<option value="">--select--</option>
				@foreach($activities as $activity)
                    <option value="{{$activity->id}}" @if(old('activity_id') == $activity->id) {{'selected="selected"'}} @endif>{{$activity->title}}</option>
				@endforeach
                 </select>
				 @if ($errors->has('activity_id'))
                    <span class="text-danger">{{ $errors->first('activity_id') }}</span>
                @endif
              </div>
			  <div class="form-group col-md-6">
                <label for="inputName">Activity Variant: <span class="red">*</span></label>
                <select name="activity_variant" required id="activity_variant" class="form-control">
				<option value="">--select--</option>
				</select>
				 @if ($errors->has('activity_variant'))
                    <span class="text-danger">{{ $errors->first('activity_variant') }}</span>
                @endif
              </div>
			    <div class="form-group col-md-6">
                <label for="inputName">Serial Number: <span class="red">*</span></label>
                <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" class="form-control"  placeholder="Serial Number" />
                @if ($errors->has('serial_number'))
                    <span class="text-danger">{{ $errors->first('serial_number') }}</span>
                @endif
              </div>
			    <div class="form-group col-md-3">
                <label for="inputName">Valid From: <span class="red">*</span></label>
                <input type="text" id="valid_from" name="valid_from" value="{{ old('valid_from') }}" class="form-control datepickerdmy"  placeholder="Valid From" />
                @if ($errors->has('valid_from'))
                    <span class="text-danger">{{ $errors->first('valid_from') }}</span>
                @endif
              </div>
			    <div class="form-group col-md-3">
                <label for="inputName">Valid Till: <span class="red">*</span></label>
                <input type="text" id="valid_till" name="valid_till" value="{{ old('valid_till') }}" class="form-control datepickerdmy"  placeholder="Name" />
                @if ($errors->has('valid_till'))
                    <span class="text-danger">{{ $errors->first('valid_till') }}</span>
                @endif
              </div>
			  <div class="form-group col-md-12">
                <label for="inputName">Terms And Conditions: <span class="red">*</span></label>
				
                <textarea placeholder="Terms And Conditions" name="terms_and_conditions" required cols="50" rows="10" id="content" class="form-control box-size text-editor">{{ old('terms_and_conditions') }}</textarea>
                @if ($errors->has('terms_and_conditions'))
                    <span class="text-danger">{{ $errors->first('terms_and_conditions') }}</span>
                @endif
              </div>
               </div>
			   <div class="form-group col-md-12">
                <label for="inputName">CSV File : <span class="red">*</span></label>
                <input type="file" id="uploaded_file_csv" required name="uploaded_file_csv"  class="form-control"  />
                @if ($errors->has('uploaded_file_csv'))
                    <span class="text-danger">{{ $errors->first('uploaded_file_csv') }}</span>
                @endif
				
              </div>
			   <div class="col-md-6">
                <label for="inputName">CSV File Sample: <span class="red">*</span></label>
               <a href="{{ asset('images/ticket-csv-sample.csv') }}" class="">
                      
                      Download <i class="fas fa-download"></i>
                  </a> 
				
              </div>
            </div>
			
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-success float-right">Upload</button>
        </div>
      </div>
    </form>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
@include('inc.ckeditor')
	<script type="text/javascript">
 $(document).ready(function(){
	var activity_id = "{{old('activity_id')}}";
	var oldactivity_variant = "{{old('activity_variant')}}";
	
	$("body #activity_id").on("change", function () {
            var activity_id = $(this).val();
			$("#activity_variant").prop("disabled",true);
            $.ajax({
                type: "POST",
                url: '{{ route("variantByActivity") }}',
                data: {'activity_id': activity_id, '_token': '{{ csrf_token() }}'},
                success: function (data) {
					 $('#activity_variant').html('<option value="">--select--</option>');
					$.each(data, function (key, value) {
                            $("#activity_variant").append('<option value="' + value
                                .u_code + '">' + value.variant_name + '</option>');
                        });
					$('#activity_variant').val(oldactivity_variant).prop('selected', true);
					$("#activity_variant").prop("disabled",false);
					
					
                }
            });
        });
		if(oldactivity_variant){
					$("body #activity_id").trigger("change");
					}
		});
			</script>  
@endsection
