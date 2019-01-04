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
                <div class="card-header">Add an expense</div>

                <form action="{{ route('save.expense') }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('post') }}
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <input type="text" name="desc" class="form-control" placeholder="Expense description" value="{{ old('desc')?old('desc'):'' }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-sm-6">
                                <input type="number" name="amt" class="form-control" placeholder="Amount" value="{{ old('amt')?old('amt'):'' }}" step="0.01">
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="date" name="date" class="form-control" placeholder="Date" value="{{ old('date')?old('date'): Carbon\Carbon::today()->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="btn-group btn-group-toggle col-sm-12" data-toggle="buttons">
                                <label class="btn btn-secondary{{ old('type')? (old('type') == 'daily'? ' active':'') : ' active' }}">
                                    <input type="radio" name="type" value="daily" autocomplete="off"{{ old('type')? (old('type') == 'daily'? ' checked':'') : ' checked' }}> Daily
                                </label>

                                <label class="btn btn-secondary{{ old('type') && old('type') == 'special'? ' active':'' }}">
                                    <input type="radio" name="type" value="special" autocomplete="off"{{ old('type') && old('type') == 'special'? ' checked':'' }}> Special
                                </label>

                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <input type="submit" value="Submit" class="btn btn-primary">
                        <a href="{{ route('home') }}" class="btn btn-light">Cancel</a>
                        <a class="float-right btn btn-light text-primary" target="_blank" href="https://docs.google.com/spreadsheets/d/{{$spreadsheetId}}">Spreadsheet</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



@push('styles')
<link href="{{asset('css/pnotify.custom.min.css')}}" media="all" rel="stylesheet" type="text/css" />
@endpush


@push('scripts')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{asset('js/pnotify.custom.min.js')}}"></script>
<script>
    $(function(){
        $('[name="desc"]').focus();
    });


    PNotify.prototype.options.styling = "bootstrap3";
    PNotify.prototype.options.styling = "fontawesome";
    @foreach (Alert::getMessages() as $type => $messages)
        @foreach ($messages as $message)

        $(function(){
            new PNotify({
                title: "{{ $type }}",
                text: "{{ $message }}",
                type: "{{ $type }}",
                icon: false
            });
        });

    @endforeach
    @endforeach

</script>

@endpush