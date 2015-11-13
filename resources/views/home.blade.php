@extends('welcome')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in! Hellz Yeah!
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer')
	<section class="bottom_last_ftoor clearfix">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<p>© Copyright 2015. All rights reserved | 
				<a href="/privacy.html">Privacy Policy</a> | 
				<a href="/terms.html">Terms &amp; Condition</a> | 
				<a href="mailto:info@app-portion.com">Contact Us</a></p>
		</div>
	</section>
@stop
