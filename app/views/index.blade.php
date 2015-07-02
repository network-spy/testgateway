@extends('layouts.default')

@section('content')
{{ HTML::script('js/validator.js') }}
<style>
    div.error {
        color: #a94442;
        font-size: 12px;
    }
</style>
<div class="wrapper" style="width: 800px; margin: 0 auto;">
    <div class="center-block" style="width:500px;">
        <h3 align="center">Make single payment</h3>
        <br/>
        <div style="text-align:center; margin-bottom:30px;">
            @if($errors->count())
                @foreach ($errors->all() as $error)
                    <p class="text-danger">{{ $error }}</p>
                @endforeach
            @endif
            @if(!empty($message))
                <p class="text-success">{{ $message }}</p>
            @endif
        </div>
        {{ Form::open(['class'=>'form-horizontal', 'id'=>'paymentForm', 'method' => 'post']) }}
            <h4 align="center">Order section</h4>
            <div class="form-group">
                {{ Form::label('amount', 'Price (amount)', ['class'=>'control-label']) }}
                {{ Form::text('amount', Input::old('amount'), ['class'=>'form-control', 'placeholder'=>'0.00']) }}
            </div>
            <div class="form-group">
                {{ Form::label('currency', 'Currency', ['class'=>'control-label']) }}
                {{ Form::select('currency', $currencyList, Input::old('currency'), ['class'=>'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('customer_full_name', 'Full name', ['class'=>'control-label']) }}
                {{ Form::text('customer_full_name', Input::old('customer_full_name'), ['class'=>'form-control']) }}
            </div>
            <h4 align="center">Payment section</h4>
            <div class="form-group">
                {{ Form::label('cc_holder_name', 'Credit card holder name', ['class'=>'control-label']) }}
                {{ Form::text('cc_holder_name', Input::old('cc_holder_name'), ['class'=>'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('cc_number', 'Credit card number', ['class'=>'control-label']) }}
                {{ Form::text('cc_number', Input::old('cc_number'), ['class'=>'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('cc_expiration', 'Credit card expiration', ['class'=>'control-label']) }}
                <div class="row">
                    <div class="col-xs-3">
                        {{ Form::text('cc_expiration', Input::old('cc_expiration'), ['class'=>'form-control', 'placeholder'=>'mm/yyyy']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('cc_ccv2', 'Credit card CCV', ['class'=>'control-label']) }}
                <div class="row">
                    <div class="col-xs-3">
                        {{ Form::text('cc_ccv2', Input::old('cc_ccv2'), ['class'=>'form-control', 'placeholder'=>'XXX']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4">
                    {{ Form::submit('Send', array('class' => 'btn btn-primary btn-block')) }}
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop