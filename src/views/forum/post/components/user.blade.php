							<!-- Start Author/Character Name -->
							{{ HTML::link('/user/view/'. $post->author->id, $post->author->username, array('class' => 'lead')) }}
							<!-- End Author/Character Name -->
							<br />
							<!-- Start Avatar and Post Count -->
							<small>{{ $post->author->getHighestRole('Forum') }}</small>
							<br />
							{{ HTML::image($post->author->image, null, array('class'=> 'img-polaroid', 'style' => 'width: 100px;')) }}
							<br />
							<small>
								Posts: {{-- $post->author->postsCount --}}
							<!-- End Avatar and Post Count -->
							<!-- Start Online Status -->
								<br />
								{{ ($post->author->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes'))
									? HTML::image('img/icons/online.png', 'Online', array('title' => 'Online')) .' Online'
									: HTML::image('img/icons/offline.png', 'Offline', array('title' => 'Offline')) .' Offline'
								) }}
							</small>
							<!-- End Online Status -->