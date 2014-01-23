<div class="row">
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Search Options</div>
			<div class="panel-body">
				{{ bForm::setSizes(3)->setType('basic')->open(false, array('id' => 'searchForm')) }}
					{{ bForm::text('keyword', Input::get('keyword'), array('placeholder' => 'Search Term'), 'Search Term') }}
					{{ bForm::select('type', $typesArray, Input::get('type'), array(), 'Post Type') }}
					{{ bForm::select('user', $users, Input::get('user'), array(), 'User') }}
					{{ bForm::submit('Search') }}
				{{ bForm::close() }}
			</div>
		</div>
	</div>
	<div class="col-md-10" id="ajaxContent">
		@if ( (isset($posts) && count($posts) > 0) || (isset($replies) && count($replies) > 0) )
			@include('forum.searchresults')
		@endif
	</div>
</div>

<script>
	@section('onReadyJs')
		// Make twitter paginator ajax
		$('.pagination a').on('click', function (event) {
			event.preventDefault();
			if ( $(this).attr('href') != '#') {
				$('#ajaxContent').load($(this).attr('href'));
			}
		});
	@stop
</script>
@section('js')
	<script>
		$('#searchForm').submit(function (e) {
			e.preventDefault();

			$('#ajaxContent').empty().html('<i class="fa fa-spinner fa-spin"></i>');

			$.get('/forum/search-results', $(this).serialize(), function (data) {
				$('#ajaxContent').empty().html(data);
			});
		});
	</script>
@stop