{{ bForm::open() }}
	<div class="row">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('/forum/admin/dashboard', 'Admin') }}</li>
				<li class="active">Edit Category</li>
			</ul>
		</small>
		<div class="col-md-offset-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Add new forum category</div>
					<div class="panel-body">
						{{ bForm::text('name', $category->name, array('placeholder' => 'Name'), 'Name') }}
						{{ bForm::select('forum_category_type_id', $types, $category->forum_category_type_id, array(), 'Type') }}
						{{ bForm::select('position', $categories, $category->position, array(), 'Position') }}
						{{ bForm::textarea('description', $category->description, array('placeholder' => 'Description'), 'Description') }}
						{{ bForm::submit('Update Category', array('class' => 'btn btn-sm btn-primary')) }}
					</div>
				</div>
			</div>
		</div>
	</div>
{{ bForm::close() }}