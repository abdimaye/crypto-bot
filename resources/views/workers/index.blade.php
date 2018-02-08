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
                            <td><a href="/workers/{{ $worker->id }}">show</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>

    <hr>
    
    <div class="row" style="text-align: center">
        <div class="col-md-6 col-md-offset-3">
            <form action="/workers/store" method="post" class="form-group" id="new-worker-form">
                {{ csrf_field() }}
                <div class="form-group">
                    <select class="form-control" name="exchange" id="">
                        <option value="" disabled="" selected="">Select Exchange</option>
                        <option value="gdax">gdax</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="symbol" id="">
                        <option value="" disabled="" selected="">Select Pairing</option>
                        <option value="BTC/USD">BTC/USD</option>
                        <option value="BTC/EUR">BTC/EUR</option>
                        <option value="" disabled="">More Coming Soon</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <select name="coin" id="" style="background-color: transparent; border: none;">
                                <option value="USD">$</option>
                                <option value="EUR">$</option>
                            </select>
                        </div>
                        <input type="text" name="amount" class="form-control" id="" placeholder="Trading Amount">
                        <div class="input-group-addon">.00</div>
                    </div>
                </div>
                <div class="form-group">
                    <select class="form-control" name="type" id="">
                        <option value="" disabled="" selected="">Select Trade Type</option>
                        <option value="simulation">Simulation</option>
                        <option value="real" disabled="">Real trading coming soon</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Create</button>
            </form>
            <div class="row add-worker" style="text-align: center">
                <p>Add New Worker</p>
                <button type="submit" id="add-worker">+</button>
            </div>
        </div>

    </div>
</div>
@endsection

<style>
    .add-worker button {
        text-align: center;
        border-radius: 50%;
        font-size: 30px;
        width: 50px;
    }

    .add-worker {
        margin-top: 20px;
    }

</style>
