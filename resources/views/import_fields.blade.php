@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">CSV Import</div>
                    <div class="panel-body">
                        <a class="btn btn-primary" href="{{ route('home') }}" role="button">List Files</a>
                        <a class="btn btn-primary" href="{{ route('import_status') }}" role="button">Imported Contacts</a>
                        <a class="btn btn-primary" href="{{ route('import') }}" role="button">Import Contacts</a>
                        <form class="form-horizontal" method="POST" action="{{ route('import_process') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    @if (isset($csv_header_fields))
                                    <tr>
                                        @foreach ($csv_header_fields as $csv_header_field)
                                            <th>{{ $csv_header_field }}</th>
                                        @endforeach
                                    </tr>
                                    @endif
                                    @foreach ($csv_data as $row)
                                        <tr>
                                        @foreach ($row as $key => $value)
                                            <td>{{ $value }}</td>
                                        @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @foreach ($csv_data[0] as $key => $value)
                                            <td>
                                                <select name="fields[{{ $key }}]">
                                                    @foreach (config('app.db_fields') as $db_field)
                                                        <option value="{{ (\Request::has('header')) ? $db_field : $loop->index }}"
                                                            @if ($key === $db_field) selected @endif>{{ $db_field }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Process Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
