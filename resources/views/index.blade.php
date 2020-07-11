@extends('layouts.master')

@section('content')
<div class="jumbotron">
    @if (Route::has('login'))
        @auth
            <h1 class="display-4">Selamat Datang {{ ucfirst(Auth()->user()->name) }}!</h1>
        @else
            <h1 class="display-4">Selamat Datang!</h1>
        @endauth
    @endif

    <p><br></p>
    <p class="lead">Ini adalah Final Project Laravel dari Kelompok 57. Jangan lupa atur file .env untuk set database. 
        Agar fitur bisa dirasakan semua, buatlah akun sendiri pada link <a href="/register">register</a></p>
    <hr class="my-4">

    @if (Route::has('login'))
        @auth
            <p>Untuk mencoba, silahkan klik tombol di bawah ini.</p>
            <p><br></p>
            <p class="lead">
              <a class="btn btn-primary btn-lg" href="/pertanyaan" role="button">Menuju Artikel</a>
            </p>
        @else
            <p>Untuk mencoba, silahkan klik tombol di bawah ini. Loginlah terlebih dahulu</p>
            <p><br></p>
            <p class="lead">
              <a class="btn btn-primary btn-lg" href="/pertanyaan" role="button">Menuju Forum</a>
              <a class="btn btn-primary btn-lg" href="/login" role="button">Login dulu</a>
            </p>
        @endauth
    @endif

</div>
 
@endsection