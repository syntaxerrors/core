{{ bForm::open() }}
	<div class="row">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }}</li>
				<li>{{ HTML::link('/forum/admin/dashboard', 'Admin') }}</li>
				<li class="active">Add Category</li>
			</ul>
		</small>
		<div class="col-md-offset-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Add new forum category</div>
					<div class="panel-body">
						{{ bForm::text('name', Input::old('name'), array('placeholder' => 'Name'), 'Name') }}
						{{ bForm::select('forum_category_type_id', $types, Input::old('forum_category_type_id'), array(), 'Type') }}
						{{ bForm::select('position', $categories, Input::old('position'), array(), 'Position') }}
						{{ bForm::textarea('description', Input::old('description'), array('placeholder' => 'Description'), 'Description') }}
						{{ bForm::submit('Add Category', array('class' => 'btn btn-sm btn-primary')) }}
					</div>
				</div>
			</div>
		</div>
	</div>
{{ bForm::close() }}