<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Final Project #57</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta charset="UTF-8">

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">

	<!-- Stylesheets -->
	<link href={{ asset("/Bona/common-css/bootstrap.css")}} rel="stylesheet">
	<link href={{ asset("/Bona/common-css/ionicons.css")}} rel="stylesheet">

	{{-- <link href={{ asset("/Bona/layout-1/css/styles.css")}} rel="stylesheet">
	<link href={{ asset("/Bona/layout-1/css/responsive.css")}} rel="stylesheet"> --}}
	
	<link href={{ asset("/Bona/single-post-1/css/styles.css")}} rel="stylesheet">
	<link href={{ asset("/Bona/single-post-1/css/responsive.css")}} rel="stylesheet">
    
    
    <link rel="stylesheet" href= {{asset("/summernote-0.8.18-dist/summernote.min.css")}}> {{--WYSIWYG--}}

</head>
<body >
	@include('sweetalert::alert')
    @include('layouts.parts.header')

	{{-- <div class="slider"></div><!-- slider --> --}} {{--Abaikan saja--}}

	<section class="blog-area section">
		<div class="container">
            {{-- Content mulai dari sini --}}
            @yield('content')

		</div><!-- container -->
	</section><!-- section -->


	<footer>
		@include('layouts.parts.footer')
	</footer>
	<!-- SCRIPTS -->
	<script src={{ asset("/Bona/common-js/jquery-3.1.1.min.js")}}></script>
	<script src={{ asset("/Bona/common-js/tether.min.js")}}></script>
	<script src={{ asset("/Bona/common-js/bootstrap.js")}}></script>
    <script src={{ asset("/Bona/common-js/scripts.js")}}></script>
    
    <script src={{asset("/summernote-0.8.18-dist/summernote.min.js")}}></script> {{--WYSIWYG--}}

    @stack('scripts')
</body>
</html>
