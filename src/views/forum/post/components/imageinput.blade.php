<div class="fileinput fileinput-new" data-provides="fileinput" id="imageInputTemplate">
	<span class="btn btn-sm btn-primary btn-file">
		<span class="fileinput-new">Select file</span>
		<span class="fileinput-exists">Change</span>
		<input type="file" name="image" {{ isset($post) ? 'required="required"' : null }}>
	</span>
	<span class="fileinput-filename"></span>
	<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
</div>
<br />