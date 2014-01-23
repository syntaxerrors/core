<div class="row">
	<div class="col-md-offset-4 col-md-4">
		<div class="well text-center">
			<div class="well-title">
				An error has occurred.
			</div>
			Please go back and try again. <br />
			@if (Session::has('errorMessage'))
				Error Message: {{ Session::get('errorMessage') }}
			@endif
		</div>
	</div>
</div>