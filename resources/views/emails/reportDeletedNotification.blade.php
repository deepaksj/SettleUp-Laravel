<!doctype html>
<html lang="en">
	<body>
		<div>Hey {{$user->name}},</div>
		<br>
		<div>{{$report->owner->name}} has deleted the report: {{$report->title}}.
		</div>
	</body>
</html>
