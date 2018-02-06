@extends('layouts.app')

@section('content')
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
                        <td>Trades</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trades as $trade)
                    <tr>
                        <td>{{ $trade->id }}</td>
                        <td>{{ $trade->amount }}</td>
                        <td>{{ $trade->coin }}</td>
                        <td>{{ $trade->updated_at }}</td>
                        <td><a href="/worker/{{ $trade->id }}/trades">show</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection