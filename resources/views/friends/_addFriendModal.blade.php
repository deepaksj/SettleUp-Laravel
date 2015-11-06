<div id="addFriend" class="modal" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a new friend</h4>
      </div>
      <div class="modal-body">
			@if(session('addErrors'))
				<!-- Show only this form related errors -->
				<div id="errorsDiv" class="col-sm-11 col-sm-offset-0.5">@include('errors.list')</div>
			@else
				<div id="errorsDiv" class="col-sm-11 col-sm-offset-0.5"></div>
			@endif
   			{!! Form::open(['url' => 'addFriend', 'id' => 'addFriendForm']) !!}
			<div class="form-group">
				{!! Form::label('name', 'Name:') !!}
				{!! Form::text('name', null, ['class' => 'form-control', 'id' => 'addFriendName']) !!}
			</div>
			<div class="form-group">
				{!! Form::label('email', 'Email:') !!}
				{!! Form::text('email', null, ['class' => 'form-control', 'id' => 'addFriendEmail']) !!}
			</div>
			<div class="form-group">
				{!! Form::submit('Invite Friend', ['class' => 'btn btn-primary form-control', 'id' => 'addFriendBtn']) !!}
			</div>
		{!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="modalCloseBtn">Close</button>
      </div>
    </div>
  </div>
</div>
