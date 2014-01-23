@section('css')
	{{ HTML::style('vendor/Gallery/css/blueimp-gallery.min.css') }}
	{{ HTML::style('vendor/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css') }}
@stop
@section('jsInclude')
	{{ HTML::script('vendor/Gallery/js/jquery.blueimp-gallery.min.js') }}
	{{ HTML::script('vendor/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js') }}
@stop
	<div id="blueimp-gallery" class="blueimp-gallery">
		<div class="slides"></div>
		<h3 class="title"></h3>
		<a class="prev">‹</a>
		<a class="next">›</a>
		<a class="close">×</a>
		<a class="play-pause"></a>
		<ol class="indicator"></ol>
		<div class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" aria-hidden="true">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body next"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary pull-left prev">
							<i class="fa fa-chevron-left"></i>
							Previous
						</button>
						<button type="button" class="btn btn-primary next">
							Next
							<i class="fa fa-chevron-right"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<div class="row">
	<div class="col-md-1">
		<div id="links" class="well well-sm" style="width: 118px;">
			<a href="{{ $post->images[0] }}" data-gallery>{{ HTML::image($post->images[1]) }}</a>
		</div>
	</div>
	<div class="col-md-11">