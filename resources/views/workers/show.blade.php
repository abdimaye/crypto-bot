@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-heading" style="border-bottom: 1px solid black;">Worker</div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Exchange</td>
                        <td>Symbol</td>
                        <td>Active</td>
                        <td>Change (All Time)</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $worker->id }}</td>
                        <td>{{ $worker->exchange }}</td>
                        <td>{{ $worker->symbol }}</td>
                        <td>{{ $worker->active }}</td>
                        <td>% up/down</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-heading" style="border-bottom: 1px solid black;">Trade History</div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Amount</td>
                        <td>Coin</td>
                        <td>Date time</td>
                        <td>Change (from previous)</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trades as $trade)
                    <tr>
                        <td>{{ $trade->id }}</td>
                        <td>{{ $trade->amount }}</td>
                        <td>{{ $trade->coin }}</td>
                        <td>{{ $trade->updated_at }}</td>
                        <td>% up/down</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
