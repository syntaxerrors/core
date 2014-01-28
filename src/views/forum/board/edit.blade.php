{{ bForm::open() }}
	<div class="row">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('forum/category/view/'. $board->category->id, $board->category->name) }}</li>
				<li class="active">Edit Board</li>
			</ul>
		</small>
		<div class="col-md-offset-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Add new forum board</div>
					<div class="panel-body">
						{{ bForm::select('forum_category_id', $categories, $board->forum_category_id, array(), 'Category') }}
						{{ bForm::text('name', $board->name, array('placeholder' => 'Name'), 'Name') }}
						{{ bForm::select('forum_board_type_id', $types, $board->forum_board_type_id, array('onChange' => 'isChild(this)'), 'Type') }}
						{{ bForm::select('parent_id', $boards, $board->parent_id, array('id' => 'child', 'style' => 'display: none;'), 'Parent') }}
						{{ bForm::textarea('description', $board->description, array('placeholder' => 'Description'), 'Description') }}
						{{ bForm::submit('Update Board') }}
					</div>
				</div>
			</div>
		</div>
	</div>
{{ bForm::close() }}
<script type="text/javascript">
	function isChild(object) {
		if ($(object).val() == 2) {
			$('#child').css('display', 'inline');
		} else {
			$('#child').css('display', 'none');
		}
	}
</script>