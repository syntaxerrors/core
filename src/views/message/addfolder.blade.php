{{ bForm::open(false, array('id' => 'composeMessage')) }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h3 id="myRemoteModalLabel">Add Folder</h3>
	</div>
	<div class="modal-body">
		{{ bForm::select('parent_id', $folders, $inbox, array('id' => 'parent_id'), 'Parent Folder') }}
		{{ bForm::text('name', null, array('id' => 'name', 'placeholder' => 'Name'), 'Name') }}
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" id="folderSubmit" aria-hidden="true">Submit</button>
		<div id="folderStatusMessage"></div>
	</div>
{{ bForm::close() }}

<script>
	$('#folderSubmit').on('click', function(event) {
		event.preventDefault();
		$('#folderSubmit').attr('disabled', 'disabled');

		$('.error').removeClass('error');
		$('#folderStatusMessage').empty().append('<i class="fa fa-spinner fa-spin"></i>');

		var data = $('#addFolder').serialize();

		$.post('/messages/add-folder', data, function(response) {

			if (response.status == 'success') {
				$('#folderStatusMessage').empty().append('Folder created.');

				// Add the new node
				var newFolderParentNode = $tree.tree('getNodeById', '{{ $inbox }}');

				$tree.tree(
					'appendNode',
					response.data.folder,
					newFolderParentNode
				);

				// Make the modal go away
				window.setTimeout(function () {
					$('#addFolderModal').modal('hide');
					$('#addFolderModal').removeData('modal');
					$('#folderSubmit').removeAttr('disabled');
					$('#folderStatusMessage').empty();
				}, 2000);
			}
			if (response.status == 'error') {
				$('#folderStatusMessage').empty();
				$.each(response.errors, function (key, value) {
					$('#' + key).addClass('error');
					$('#folderStatusMessage').append('<span class="text-error">'+ value +'</span><br />');
				});
			}
		});
	});
</script>