@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('login.shopify') }}" aria-label="{{ __('Register') }}">
                        <div class="form-group">
                            <label for="domain">Domain</label>

                            <div class="input-group mb-3">
                                <input id="domain" type="text" class="form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}" name="domain" value="{{ old('domain') }}" placeholder="yourshop" aria-describedby="myshopify" required autofocus>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="myshopify">myshopify.com</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
