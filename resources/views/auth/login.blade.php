@extends('welcome')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Login</div>
					<div class="panel-body">
						@include('errors.list')
						@if(session()->has('message'))
							<div class="alert alert-info">{{session('message')}}</div>
						@endif
						<div class="row">
							<div class="col-md-7">
								<!-- Replacing the default auth login -->
								<!-- form class="form-horizontal" role="form" method="POST" action="/auth/login"-->
								<form class="form-horizontal" role="form" method="POST" action="/login">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
			
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
										<div class="col-md-8 col-md-offset-4">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="remember"> Remember Me
												</label>
											</div>
										</div>
									</div>
			
									<div class="form-group">
										<div class="col-md-8 col-md-offset-4">
											<button type="submit" class="btn btn-primary" style="margin-right: 15px;">
												Login
											</button>
			
											<a href="/password/email">Forgot Your Password?</a>
										</div>
									</div>
								</form>
							</div>
							<div class="col-md-1"><br><br><img alt="Or" src="/images/or.png"></div>
							<div class="col-md-3">
								<br><br>
								<a href="/loginWith/facebook"><img alt="Facebook" src="/images/loginUsingFacebook.png" height="50" width="230"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection