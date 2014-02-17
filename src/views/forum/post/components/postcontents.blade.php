								<!-- Start Title and Details -->
								{{ $post->icon }}
								@if (getRootClass($post) == 'Forum_Post')
									<strong>{{ HTML::link('forum/post/view/'. $post->id .'/'. $details->currentPage, $post->name, array('name' => 'reply:'. $post->id, 'rel' => 'nofollow')) }}</strong>
								@else
									<strong>{{ HTML::link('forum/post/view/'. $post->post->id .'/'. $details->currentPage .'#reply:'. $post->id, $post->name, array('name' => 'reply:'. $post->id, 'rel' => 'nofollow')) }}</strong>
								@endif
								@if ($post->forum_post_type_id == Forum_Post::TYPE_APPLICATION && $post->approvedFlag == 0)
									<small class="label label-important">Unapproved</small>
								@endif
								<div class="pull-right text-bottom text-right">
									<small>
										@if ($post->frontPageFlag == 1)
											<small class="label label-primary pull-right">Front Page Post</small>
											<div class="clearfix"></div>
										@endif
										@if ($post->adminReviewFlag == 1 || $post->moderatorLockedFlag == 1)
											<span class="text-error">Under review <i class="fa fa-gavel" style="width: 15px;"></i></span>
										@else
											<a href="#reportToModerator" onClick="addResourcetoReport(this, 'report')" data-resource-id="{{ $post->id }}" data-resource-name="{{ $post->forumType }}" role="button" data-toggle="modal">
												Report to Moderator <i class="fa fa-gavel" style="width: 15px;"></i>
											</a>
										@endif
									</small>
								</div>
								<br />
								<small><small>On {{ $post->created_at }}</small></small>
								<!-- End Title and Details -->
								<hr />
								<!-- Start quote -->
								@if ($post->quote_id != null)
									<small>
										@if ($post->quote instanceof Forum_Reply || $post->quote instanceof Core\Forum_Reply)
											{{ HTML::link('forum/post/view/'. $post->quote->post->id .'#reply:'. $post->quote->id, 'Quote from: '. $post->quote->displayName .' on '. $post->quote->created_at) }}
										@else
											{{ HTML::link('forum/post/view/'. $post->quote->id, 'Quote from: '. $post->quote->displayName .' on '. $post->quote->created_at) }}
										@endif
									</small><br />
										<?php $newQuote = $post->quote; ?>
										@while ($newQuote != null)
											<blockquote>
												@if ($newQuote->forum_reply_type_id == Forum_Reply::TYPE_ACTION)
													@if ($newQuote->roll->roll != 9999)
														{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
														@if ($newQuote->roll->roll == 42 || $newQuote->roll->roll == 69)
															<span class="text-warning">
														@else
															<span>
														@endif
															{{ $newQuote->displayName }} rolled a {{ $newQuote->roll->roll }}
														</span>
													@else
														<span class="text-warning">Story Action</span>
													@endif
													<br />
													<br />
												@endif
												<i class="fa fa-quote-left"></i> {{ BBCode::parse(e($newQuote->content)) }}
												<?php $newQuote = $newQuote->quote; ?>
											</blockquote>
										@endwhile
									<hr />
								@endif
								<!-- End quote -->
								<!-- Start post content -->
								@if ($post->forumType == 'reply')
									@if ($post->forum_reply_type_id == Forum_Reply::TYPE_ACTION)
										@if ($post->roll->roll != 9999)
											{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
											@if ($post->roll->roll == 42 || $post->roll->roll == 69)
												<span class="text-warning">
											@else
												<span>
											@endif
												{{ $post->displayName }} rolled a {{ $post->roll->roll }}
											</span>
										@else
											<span class="text-warning">Story Action</span>
										@endif
										<br />
										<br />
										{{ BBCode::parse(e($post->content)) }}
									@elseif ($post->forum_reply_type_id == Forum_Reply::TYPE_INNER_THOUGHT)
										<span class="text-info">
											{{ $post->icon }} This is an inner-thought post.  Anything detailed here is only known to the character and is used for role-playing purposes. {{ $post->icon }}
										</span>
										<br />
										<br />
										{{ BBCode::parse(e($post->content)) }}
									@elseif ($post->forum_reply_type_id == Forum_Reply::TYPE_CONVERSATION)
										<i class="fa fa-quote-left" style="padding-right: 5px;"></i>
										{{ str_replace('<br /><br />', '<br /><br /><i class="fa fa-quote-left" style="padding-right: 5px;"></i>', BBCode::parse(e($post->content))) }}
									@else
										{{ BBCode::parse(e($post->content)) }}
									@endif
								@else
									@if ($post->adminReviewFlag == 0)
										{{ BBCode::parse(e($post->content)) }}
									@else
										<span class="text-error" style="font-weight: bold; font-size: 1.1em;">This post is under admin review.  It may be restored or deleted in the near future.</span>
									@endif
								@endif
								<!-- End post content -->