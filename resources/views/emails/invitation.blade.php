<!doctype html>
<html lang="en">
	<body>
		<div>Hey {{$toUser->name}},</div>
		<br>
		<div>Your friend {{$fromUser->name}} has invited you to sign up for SettleUp, a cool tool to help you split expenses with
		your friends and keep track of them till they are settled. Come check us out, you can confirm your registration by clicking 
		on this <a href='register/confirm/{{$toUser->token}}'>link!</a>. Its free to use and really easy to sign and up and get started.
		</div>
	</body>
</html>
