<!doctype html>
<html lang="en">
	<body>
		<div>Hey {{$user->name}},</div>
		<br>
		<div>{{$report->owner->name}} has determined the following settlements for report: 
				<a href="/expenseReports/{{$report->id}}">{{$report->title}}</a>.
		</div>
		<div>
			@foreach($settlementMessages as $message)
				<li>{{$message}}</li>
			@endforeach
		</div>
	</body>
</html>
