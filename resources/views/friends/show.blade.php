@extends('app')

@section('content')

	<h3>Your Friends</h3>
	<hr>
	<div class="col-sm-6">
		@if(!session('addErrors'))
			<!-- If there were errors due to input in the modal, show it there instead of here -->
			<div class="row">@include('errors.list')</div>
		@endif
		@if(session()->has('message'))
			<div class="alert alert-info">{{session('message')}}</div>
		@endif
		{!! Form::open(['url' => 'friends/delete']) !!}
			<table class="table table-striped">
				<tr class="info">
					<th class="col-sm-1"></th>
					<th class="col-sm-5"><a href="/friends?sortBy=name&sortOrder={{$sortOrder}}">Name</th>
					<th class="col-sm-6">Email</th>
				</tr>
				@foreach($friends as $friend)
					<tr>
						<td>{!! Form::checkbox('friend_ids[]', $friend['id']) !!}</td>
						<td class="setWidth concat"><a href="{{ url('editFriend', $friend['id']) }}">{{ $friend['name']}}</a></td>
						<td>{{ $friend['email'] }}</td>
					</tr>
				@endforeach
			</table>
			<div class="row">
				<div class="col-sm-3 form-group">
					{!! Form::submit('Delete Friends', ['class' => 'btn btn-primary form-control']) !!}
				</div>
				<div class="col-sm-3 form-group">
					<a class="btn btn-primary form-control" role="button" data-toggle="modal" data-target="#addFriend" id="addFriendTrigger">Add a friend</a>
				</div>
				<div class="col-sm-6"><?php echo $friends->appends(['sortOrder' => ($sortOrder=='asc'?'desc':'asc')])->render(); ?></div>
			</div>
		{!! Form::close() !!}
		<br>
	</div>
	@include('friends._addFriendModal')
@stop
@section('footer')
	<script type="text/javascript">
		$(document).ready(function(){
			//If the add friend posting has returned with errors, open the modal window and show them
			str = {{ session('addErrors', '0') }};
			if(str == '1') {
				$('#addFriendTrigger').click();
			}
		});
	</script>
@stop