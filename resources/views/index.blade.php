@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading" style="border-bottom: 1px solid black;">Workers</div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Exchange</td>
                            <td>Symbol</td>
                            <td>Active</td>
                            <td>Trades</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workers as $worker)
                        <tr>
                            <td>{{ $worker->id }}</td>
                            <td>{{ $worker->exchange }}</td>
                            <td>{{ $worker->symbol }}</td>
                            <td>{{ $worker->active }}</td>
                            <td><a href="/worker/{{ $worker->id }}/trades">trades</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>
@endsection
