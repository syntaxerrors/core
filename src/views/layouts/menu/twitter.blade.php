@if(count($menu->menu) > 0)
	<?php $rightSwitch = false; ?>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="javascript: void(0);">
				@if (Config::get('app.siteIcon') != null)
					<i class="fa fa-{{ Config::get('app.siteIcon') }}"></i>
				@endif
				{{ Config::get('app.siteName') }}
			</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				@foreach ($menu->menu as $item)
					<?php
						$class   = array();
						$liClass = null;
						$style   = null;
						if (count($item->children) > 0) {
							$class[] = 'dropdown ';
						}
						if ($item->link != null && (Request::is($item->link) || Request::segment(1) == $item->link)) {
							$class[] = 'active';
						}
						if (count($class) > 0) {
							$liClass = ' class="'. implode(' ', $class) .'"';
						}
						$item->link = (strlen($item->link) > 1 ? '/'. $item->link : $item->link);
					?>
					@if ($item->alignment == 'right' && $rightSwitch == false)
						</ul>
						<ul class="nav navbar-nav navbar-right">
						<?php $rightSwitch = true; ?>
					@endif
					@if (count($item->children) == 0)
						@if ($item->link != null)
							<li{{ $liClass }}>{{ HTML::linkImage($item->link, $item->title) }}</li>
						@else
							<li{{ $liClass }}><a href="javascript: void(0);">{{ $item->title }}</a></li>
						@endif
					@else
						<li{{ $liClass }}>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $item->title }} <b class="caret"></b></a>
							<ul class="dropdown-menu">
								@if ($item->link != null)
									<li>{{ HTML::linkImage($item->link, $item->title) }}</li>
								@endif
								@foreach ($item->children as $child)
									@if ($child->children->count() > 0)
										@if ($child->link != null)
											<li class="nav-header">{{ HTML::linkImage('/'. $child->link, $child->title) }}</li>
										@else
											<li class="nav-header"><a href="javascript: void(0);">{{ $child->title }}</a></li>
										@endif
										@foreach ($child->children as $subChild)
											<li>{{ HTML::linkImage('/'. $subChild->link, $subChild->title) }}</li>
										@endforeach
									@else
										<li>{{ HTML::linkImage('/'. $child->link, $child->title) }}</li>
									@endif
								@endforeach
							</ul>
						</li>
					@endif
				@endforeach
			</ul>
		</div>
	</div>
@endif
<br style="clear: both;" />