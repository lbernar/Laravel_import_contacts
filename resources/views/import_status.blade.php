@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Report</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <a class="btn btn-primary" href="{{ route('home') }}" role="button">List Files</a>
                                        <a class="btn btn-primary" href="{{ route('import_status') }}" role="button">Imported Contacts</a>
                                        <a class="btn btn-primary" href="{{ route('import') }}" role="button">Import Contacts</a>
                                    </tr>
                                    <tr>
                                      <th scope="col">Name</th>
                                      <th scope="col">Birthday</th>
                                      <th scope="col">Phone</th>
                                      <th scope="col">Address</th>
                                      <th scope="col">Last Credit Card Numbers</th>
                                      <th scope="col">Credit Card Brand</th>
                                      <th scope="col">Email</th>
                                    </tr>
                                </thead>
                                @foreach ($contact_data as $row)
                                    <tr>
                                        <td>{{$row->name}}</td>
                                        <td>{{date("Y F d", strtotime($row->birthday))}}</td>
                                        <td>{{$row->phone}}</td>
                                        <td>{{$row->address}}</td>
                                        <td>{{$row->last_digits}}</td>
                                        <td>{{$row->franchise}}</td>
                                        <td>{{$row->email}}</td>
                                    </tr>
                                @endforeach
                                <div >
                                    {{ $contact_data->links() }}
                                </div>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
