{{ HTML::style('vendor/jansyBootstrap/dist/extend/css/jasny-bootstrap.min.css') }}
{{ bForm::open(true) }}
	<div class="row">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('/forum', 'Forums') }}</li>
				<li>{{ HTML::link('/forum/category/view/'. $board->category->id, $board->category->name) }}</li>
				<li>{{ HTML::link('/forum/board/view/'. $board->id, $board->name) }}</li>
				<li class="active">Add Post</li>
			</ul>
		</small>
		<div class="col-md-offset-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">New Post</div>
					<div class="panel-body">
						@section('postForms')
							{{ bForm::select('forum_post_type_id', $types, array(1), array('onChange' => 'typeSwitch(this)'), 'Type') }}
						@show
						{{ bForm::text('name', Input::old('name'), array('placeholder' => 'Title', 'tabindex' => 1), 'Name') }}
						<?php $content =null; ?>
						@include('forum.post.components.quickreplybuttons')
						{{ bForm::submit('Post', array('class' => 'btn btn-sm btn-primary', 'tabindex' => 3)) }}
					</div>
					<div class="panel-footer">
						<div id="footer" style="display: none;">
							<div id="images">
								@include('forum.post.components.imageinput')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{{ bForm::close() }}

<script type="text/javascript">
	function typeSwitch(object) {
		if ($(object).val() == 9) {
			$('#footer').css('display', 'inline');
		} else {
			$('#footer').css('display', 'none');
		}
	}

	function addImageInput() {
		var imageInput = $('#imageInputTemplate');
		var clone      = imageInput.clone();

		$('#images').append(clone).append('<br />');
	}
</script>