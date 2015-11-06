<div class="row">
	@foreach($links as $link)
		<a href="{{$link[0]}}">{{$link[1]}}</a> >>
	@endforeach
</div>