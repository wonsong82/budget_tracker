@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Quick look at your expenses</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Desc</th>
                                    <th>Type</th>
                                    <th class="text-right">Amt</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td>{{ (new \Carbon\Carbon($expense[0]))->format('n/j/y D') }}</td>
                                        <td>{{ $expense[1] }}</td>
                                        <td>{{ $expense[3] }}</td>
                                        <td class="text-right">{{ money_format('$%i', $expense[2]) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


