@extends('layouts.app')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Visa Master Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('visa-masters.index') }}">Visa Master</a></li>
                    <li class="breadcrumb-item active">Add Visa</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">
    <form action="{{ route('visa-masters.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add Visa Master</h3>
                    </div>
                    <div class="card-body row">

                        <!-- Name -->
                        <div class="form-group col-md-6">
                            <label for="name">Visa Name: <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Enter visa name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="form-group col-md-6">
                            <label for="image">Visa Image:</label>
                            <input type="file" id="image" name="image" class="form-control">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Visa Type -->
                        <div class="form-group col-md-6">
                            <label for="visa_type">Visa Type: <span class="text-danger">*</span></label>
                            <select id="visa_type" name="visa_type" class="form-control">
                                <option value="">-- Select Visa Type --</option>
                                <option value="Tourist" {{ old('visa_type') == 'Tourist' ? 'selected' : '' }}>Tourist</option>
                                <option value="Work" {{ old('visa_type') == 'Work' ? 'selected' : '' }}>Work</option>
                                <option value="Student" {{ old('visa_type') == 'Student' ? 'selected' : '' }}>Student</option>
                                <option value="Business" {{ old('visa_type') == 'Business' ? 'selected' : '' }}>Business</option>
                            </select>
                            @error('visa_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Insurance Mandate -->
                        <div class="form-group col-md-6">
                            <label for="insurance_mandate">Insurance Requirement:</label>
                            <select id="insurance_mandate" name="insurance_mandate" class="form-control">
                                <option value="">-- Select Insurance Requirement --</option>
                                <option value="Optional" {{ old('insurance_mandate') == 'Optional' ? 'selected' : '' }}>Optional</option>
                                <option value="Mandate" {{ old('insurance_mandate') == 'Mandate' ? 'selected' : '' }}>Mandate</option>
                            </select>
                            @error('insurance_mandate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Insurance Information -->
                        <div class="form-group col-md-12">
                            <label for="insurance_information">Insurance Information:</label>
                            <textarea id="insurance_information" name="insurance_information" rows="2" class="form-control"
                                placeholder="Enter insurance information">{{ old('insurance_information') }}</textarea>
                            @error('insurance_information')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stay Validity -->
                        <div class="form-group col-md-6">
                            <label for="stay_validity">Stay Validity:</label>
                            <input type="text" id="stay_validity" name="stay_validity" value="{{ old('stay_validity') }}"
                                class="form-control " placeholder="Enter stay validity">
                            @error('stay_validity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Visa Validity -->
                        <div class="form-group col-md-6">
                            <label for="visa_validity">Visa Validity:</label>
                            <input type="text" id="visa_validity" name="visa_validity" value="{{ old('visa_validity') }}"
                                class="form-control" placeholder="Enter visa validity">
                            @error('visa_validity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Adult Fees -->
                        <div class="form-group col-md-6">
                            <label for="adult_fees">Adult Fees:</label>
                            <input type="number" id="adult_fees" name="adult_fees" value="{{ old('adult_fees') }}"
                                class="form-control" placeholder="Enter adult fees">
                            @error('adult_fees')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Child Fees -->
                        <div class="form-group col-md-6">
                            <label for="child_fees">Child Fees:</label>
                            <input type="number" id="child_fees" name="child_fees" value="{{ old('child_fees') }}"
                                class="form-control" placeholder="Enter child fees">
                            @error('child_fees')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Express Charges -->
                        <div class="form-group col-md-6">
                            <label for="express_charges">Express Charges:</label>
                            <input type="number" id="express_charges" name="express_charges"
                                value="{{ old('express_charges') }}" class="form-control"
                                placeholder="Enter express charges">
                            @error('express_charges')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Super Express Charges -->
                        <div class="form-group col-md-6">
                            <label for="super_express_charges">Super Express Charges:</label>
                            <input type="number" id="super_express_charges" name="super_express_charges"
                                value="{{ old('super_express_charges') }}" class="form-control"
                                placeholder="Enter super express charges">
                            @error('super_express_charges')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group col-md-12">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="3" class="form-control"
                                placeholder="Enter description">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Documents Checklist -->
                        <div class="form-group col-md-12">
                            <label for="documents_checklist">Documents Checklist:</label>
                            <textarea id="documents_checklist" name="documents_checklist" rows="3" class="form-control"
                                placeholder="Enter documents checklist">{{ old('documents_checklist') }}</textarea>
                            @error('documents_checklist')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
<!-- Normal Processing Timeline -->
<div class="form-group col-md-6">
  <label for="normal_processing_timeline">Normal Processing Timeline:</label>
  <input type="text" id="normal_processing_timeline" name="normal_processing_timeline" value="{{ old('normal_processing_timeline') }}" class="form-control" placeholder="Enter normal processing timeline">
  @error('normal_processing_timeline')
      <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<!-- Express Processing Timeline -->
<div class="form-group col-md-6">
  <label for="express_processing_timeline">Express Processing Timeline:</label>
  <input type="text" id="express_processing_timeline" name="express_processing_timeline" value="{{ old('express_processing_timeline') }}" class="form-control" placeholder="Enter express processing timeline">
  @error('express_processing_timeline')
      <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<!-- Super Express Processing Timeline -->
<div class="form-group col-md-6">
  <label for="super_express_processing_timeline">Super Express Processing Timeline:</label>
  <input type="text" id="super_express_processing_timeline" name="super_express_processing_timeline" value="{{ old('super_express_processing_timeline') }}" class="form-control" placeholder="Enter super express processing timeline">
  @error('super_express_processing_timeline')
      <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Visa</button>
                        <a href="{{ route('visa-masters.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

@endsection
