<link rel="stylesheet" type="text/css" href={{ asset('style.css')}}> {{--Khusus Auth--}}

@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-3"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h3 class="login-heading mb-4">Login Succesfull!</h3>
              <div class="card">
                  <div class="card-body text-center">
                    Welcome <h3 class="text-success">{{ ucfirst(Auth()->user()->name) }}</h3>
                  </div>
                  <div class="card-body text-center">
                    <a class="small" href="{{url('forum')}}"><h5 class="text-warning">Forum</h5></a>
                    <a class="small" href="{{url('logout')}}"><h5 class="text-danger">Logout</h5></a>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 
@endsection

@push('scripts')

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> {{--Khusus Auth--}}

@endpush