<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Settle Up!</title>
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/settleUp.css">
	</head>
	<body>
		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>                        
		      </button>
		      <a class="navbar-brand" href="/">Settle Up!</a>
		    </div>
		    <div class="collapse navbar-collapse" id="myNavbar">
   		      <ul class="nav navbar-nav navbar-right">
   		      	<li><a href="/login">Login</a></li>
   		      	<li><a href="/register">Register</a></li>
   		      </ul>
		    </div>
		  </div>
		</nav>	
		<div class="container">
			@yield('content')
		</div>
		
		@yield('footer')
	</body>
</html>