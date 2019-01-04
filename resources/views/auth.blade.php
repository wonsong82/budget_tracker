@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Auth Code</div>
                    <div class="card-body">
                        <form action="{{ route('auth') }}" method="post" class="form-inline">
                            {{ csrf_field() }}
                            {{ method_field('post') }}
                            <div class="form-group">

                                <input type="text" name="auth_code" class="form-control" placeholder="Enter auth code here">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')
<script>
    window.open('{!! $authUrl !!}', 'createPopup', 'width=1000,height=600,location=no,menubar=no,resizable,scrollbars,status=no');
</script>
@endpush