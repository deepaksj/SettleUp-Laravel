<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Settle Up!</title>
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/settleUp.css">
		<link rel="stylesheet" href="/css/select2.min.css">
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
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
		      <ul class="nav navbar-nav">
		        <li class="active"><a href="#">Home</a></li>
		        <li class="dropdown">
		        	<a class="dropdown-toggle" data-toggle="dropdown" href="#">Expense Reports<span class="caret"></span></a>
          			<ul class="dropdown-menu">
			            <li><a href="/expenseReports/create">Create New Report</a></li>
			            <li><a href="/expenseReports">Your Active Reports</a></li>
			            <li><a href="/settledExpenseReports">Archived Reports</a></li>
			        </ul>
		        </li>
		        <li><a href="/settlements">Settlements</a></li>
		        <li><a href="/friends">Friends</a></li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		        <li class="active"><a href="#">Your Total: $1,200</a></li>
		        <li class="dropdown">
          			<a class="dropdown-toggle" data-toggle="dropdown" href="#">{{ (\Auth::user() != null)?(\Auth::user()->name):"" }}<span class="caret"></span></a>
          			<ul class="dropdown-menu">
          				<li><a href="/myAccount">My Account</a></li>
			            <li><a href="/logout">Logout</a></li>
          			</ul>
        		</li>
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