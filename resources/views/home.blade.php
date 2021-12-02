@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">CSV Files List</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <a class="btn btn-primary" href="{{ route('home') }}" role="button">List Files</a>
                                    <a class="btn btn-primary" href="{{ route('import_status') }}" role="button">Imported Contacts</a>
                                    <a class="btn btn-primary" href="{{ route('import') }}" role="button">Import Contacts</a>
                                  </tr>
                                <tr>
                                  <th scope="col">File Name</th>
                                  <th scope="col">File Status</th>
                                </tr>
                            </thead>
                            @foreach ($csv_data as $row)
                                <tr>
                                @foreach ($row as $key => $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
