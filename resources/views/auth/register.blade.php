@extends('welcome')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@include('errors.list')					
					@if(session()->has('message'))
						<div class="alert alert-info">{{session('message')}}</div>
					@endif
					<!-- Replacing the default auth register with one for email validation -->
					<!-- form class="form-horizontal" role="form" method="POST" action="/auth/register" -->
					<div class="row">
						<div class="col-md-7">
							<form class="form-horizontal" role="form" method="POST" action="/register">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
		
								<div class="form-group">
									<label class="col-md-4 control-label">Name</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="name" value="{{ old('name') }}">
									</div>
								</div>
		
								<div class="form-group">
									<label class="col-md-4 control-label">E-Mail Address</label>
									<div class="col-md-8">
										<input type="email" class="form-control" name="email" value="{{ old('email') }}">
									</div>
								</div>
		
								<div class="form-group">
									<label class="col-md-4 control-label">Password</label>
									<div class="col-md-8">
										<input type="password" class="form-control" name="password">
									</div>
								</div>
		
								<div class="form-group">
									<label class="col-md-4 control-label">Confirm Password</label>
									<div class="col-md-8">
										<input type="password" class="form-control" name="password_confirmation">
									</div>
								</div>
		
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Register
										</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-1"><br><br><br><img alt="Or" src="/images/or.png"></div>
						<div class="col-md-4">
							<br><br><br>
							<a href="/register/facebook"><img alt="Facebook" src="/images/signUpUsingFacebook.jpg" height="50" width="230"></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
