@if(count($menu->menu) > 0)
	<?php
		$rightItems = array();
	?>
	<div id="mainMenu">
		<ul id="utopian-navigation" class="black utopian">
			@foreach ($menu->menu as $item)
				<?php
					$class = null;
					$style = null;
					if (count($item->children) > 0) {
						$class .= 'dropdown ';
					}
					if ($item->link != null && (Request::is($item->link) || Request::segment(1) == $item->link)) {
						$class .= 'active';
					}
					if ($item->alignment == 'right') {
						$rightItems[] = $item;
						continue;
					}
					if ($class != null) {
						$class = ' class="'. $class .'"';
					}
					$item->link = (strlen($item->link) > 1 ? '/'. $item->link : $item->link);
				?>
				<li{{ $class }}{{ $style }}>
					@if ($item->link != null)
						{{ HTML::linkImage($item->link, $item->title) }}
					@else
						<a href="javascript: void(0);">{{ $item->title }}</a>
					@endif
					@if (count($item->children) > 0)
						<ul>
							@foreach ($item->children as $child)
								@if ($child->children->count() > 0)
									<li><a href="{{ (isset($child->link) ? '/'. $child->link : 'javascript: void(0);') }}">{{ $child->title }}</a>
										<ul>
											@foreach ($child->children as $subChild)
												<li>{{ HTML::linkImage('/'. $subChild->link, $subChild->title) }}</li>
											@endforeach
										</ul>
									</li>
								@else
									<li>{{ HTML::linkImage('/'. $child->link, $child->title) }}</li>
								@endif
							@endforeach
						</ul>
					@endif
				</li>
			@endforeach
			@foreach (array_reverse($rightItems) as $item)
				<?php
					$class = null;
					$style = null;
					if (count($item->children) > 0) {
						$class .= 'dropdown ';
					}
					if ($item->link != null && (Request::is($item->link) || Request::segment(1) == $item->link)) {
						$class .= 'active';
					}
					if ($class != null) {
						$class = ' class="'. $class .'"';
					}
					$item->link = (strlen($item->link) > 1 ? '/'. $item->link : $item->link);
				?>
				<li{{ $class }} style="float:right;">
					@if ($item->link != null)
						{{ HTML::linkImage($item->link, $item->title) }}
					@else
						<a href="javascript: void(0);">{{ $item->title }}</a>
					@endif
					@if (count($item->children) > 0)
						<ul>
							@foreach ($item->children as $child)
								@if ($child->children->count() > 0)
									<li><a href="{{ (isset($child->link) ? '/'. $child->link : 'javascript: void(0);') }}">{{ $child->title }}</a>
										<ul>
											@foreach ($child->children as $subChild)
												<li>{{ HTML::linkImage('/'. $subChild->link, $subChild->title) }}</li>
											@endforeach
										</ul>
									</li>
								@else
									<li>{{ HTML::linkImage('/'. $child->link, $child->title) }}</li>
								@endif
							@endforeach
						</ul>
					@endif
				</li>
			@endforeach
		</ul>
	</div>
	<br style="clear: both;" />
@endif