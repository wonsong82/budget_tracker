@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger">
                        <h4>Please fix following errors</h4>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Settings</div>
                    <div class="card-body">
                        <form action="{{ route('save.settings') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('post') }}

                            <div class="form-group">
                                <label>Spreadsheet ID</label>
                                <input type="text" name="spreadsheet_id" class="form-control" placeholder="Enter Spreadsheet ID or URL" value="{{ old('spreadsheet_id')? old('spreadsheet_id') : ( $spreadsheetId? $spreadsheetId : '') }}">
                                <small class="form-text text-muted">Find spreadsheet and paste its ID or Full URL. For example, https://docs.google.com/spreadsheets/d/<b>1ErX_AxfjeErjdigSDf</b>/edit#gid=1</small>
                            </div>



                            <button type="submit" class="btn btn-primary">Save</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
<script>
    $(function(){
        $('[name="spreadsheet_id"]').focus();
    });
</script>
@endpush