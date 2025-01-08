@extends('layouts.app')
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Visa Master</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Visa Master</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Visa Master</h3>
                        <div class="card-tools">
                            <a href="{{ route('visa-masters.create') }}" class="btn btn-sm btn-info">
                                <i class="fas fa-plus"></i> Create Visa
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="visaTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Visa Type</th>
                                    <th>Stay Validity</th>
                                    <th>Visa Validity</th>
                                    <th>Adult Fees</th>
                                    <th>Child Fees</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                                <tr>
                                    <form id="filterForm" method="get" action="{{ route('visa-masters.index') }}">
                                        <th><input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Name" /></th>
                                        <th><input type="text" name="visa_type" value="{{ request('visa_type') }}" class="form-control" placeholder="Visa Type" /></th>
                                        <th><input type="text" name="stay_validity" value="{{ request('stay_validity') }}" class="form-control" placeholder="Stay Validity" /></th>
                                        <th><input type="text" name="visa_validity" value="{{ request('visa_validity') }}" class="form-control" placeholder="Visa Validity" /></th>
                                        <th><input type="text" name="adult_fees" value="{{ request('adult_fees') }}" class="form-control" placeholder="Adult Fees" /></th>
                                        <th><input type="text" name="child_fees" value="{{ request('child_fees') }}" class="form-control" placeholder="Child Fees" /></th>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <button class="btn btn-info btn-sm" type="submit">Filter</button>
                                            <a class="btn btn-default btn-sm" href="{{ route('visa-masters.index') }}">Clear</a>
                                        </th>
                                    </form>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->visa_type }}</td>
                                        <td>{{ $record->stay_validity }}</td>
                                        <td>{{ $record->visa_validity }}</td>
                                        <td>{{ $record->adult_fees }}</td>
                                        <td>{{ $record->child_fees }}</td>
                                        <td>{{ $record->created_at ? $record->created_at->format('Y-m-d') : '-' }}</td>
                                        <td>{{ $record->updated_at ? $record->updated_at->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            
                                            <a class="btn btn-primary btn-sm" href="{{ route('visa-masters.edit', $record->id) }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form id="delete-form-{{ $record->id }}" method="post" action="{{ route('visa-masters.destroy', $record->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm hide" onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination mt-3 float-right">
                            {!! $records->appends(request()->query())->links() !!}
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function () {
       
    });
</script>
@endsection
