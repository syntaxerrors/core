					<style type="text/css">
						.btn-group i {
							font-size: 14px;
						}
					</style>
					<div class="text-center">
						<div class="btn-group">
							<a href="javascript: void()" onClick="showPreview();" class="btn btn-sm btn-primary" title="Preview" id="previewBtn">
								<i class="fa fa-share-square-o fa-inverse"></i>
							</a>
						</div>
						<div class="btn-group">
							<a href="javascript: void()" onClick="addStyle('italic');" class="btn btn-sm btn-primary" title="Italic"><i class="fa fa-italic"></i></a>
							<a href="javascript: void()" onClick="addStyle('bold');" class="btn btn-sm btn-primary" title="Bold"><i class="fa fa-bold"></i></a>
							<a href="javascript: void()" onClick="addStyle('underline');" class="btn btn-sm btn-primary" title="Underline"><i class="fa fa-underline"></i></a>
							<a href="javascript: void()" onClick="addStyle('strike');" class="btn btn-sm btn-primary" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
							<a href="javascript: void()" onClick="addStyle('code');" class="btn btn-sm btn-primary" title="Code"><i class="fa fa-bars"></i></a>
							<a href="javascript: void()" onClick="addStyle('center');" class="btn btn-sm btn-primary" title="Align-Center"><i class="fa fa-align-center"></i></a>
							<a href="javascript: void()" onClick="addStyle('paragraph');" class="btn btn-sm btn-primary" title="Paragraph"><i class="fa fa-outdent"></i></a>
							<a href="javascript: void()" onClick="addStyle('size');" class="btn btn-sm btn-primary" title="Font Size"><i class="fa fa-text-width"></i></a>
							<a href="javascript: void()" onClick="addStyle('color');" class="btn btn-sm btn-primary" title="Font Color"><i class="fa fa-font"></i></a>
							<a href="javascript: void()" onClick="addStyle('url');" class="btn btn-sm btn-primary" title="URL"><i class="fa fa-link"></i></a>
							<a href="javascript: void()" onClick="addStyle('list');" class="btn btn-sm btn-primary" title="List"><i class="fa fa-list"></i></a>
							<a href="javascript: void()" onClick="addStyle('image');" class="btn btn-sm btn-primary" title="Image"><i class="fa fa-picture-o"></i></a>
							<a href="javascript: void()" onClick="addStyle('youtube');" class="btn btn-sm btn-primary" title="YouTube"><i class="fa fa-film"></i></a>
						</div>
						<div class="btn-group">
							<a href="javascript: void()" class="btn btn-sm btn-primary dropdown-toggle" title="Icons" data-toggle="dropdown">
								<i class="fa fa-tag"></i> <span class="caret"></span>
							</a>
							<ul class="dropdown-menu text-left">
								<li><a href="javascript: void()" onClick="addStyle('icon', 'heart');"><i class="fa fa-heart"></i> Heart</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'star');"><i class="fa fa-star"></i> Star</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'music');"><i class="fa fa-music"></i> Music</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'comment');"><i class="fa fa-comment"></i> Comment</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'comments');"><i class="fa fa-comments"></i> Comments</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'quote-left');"><i class="fa fa-quote-left"></i> Left Quote</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'quote-right');"><i class="fa fa-quote-right"></i> Right Quote</a></li>
								<li><a href="javascript: void()" onClick="addStyle('icon', 'lightbulb');"><i class="fa fa-lightbulb-o"></i> Lightbulb</a></li>
								<li><a href="javascript: void()" onClick="addStyle('dice');">{{ HTML::image('img/dice.png', null, array('style' => 'width: 14px;')) }} Dice</a></li>
							</ul>
						</div>
					</div>
					<br />
					<div class="form-group">
						<label class="col-md-2 control-label" for="content">Content</label>
						<div class="col-md-10" id="contentField">
							{{ Form::textarea('content', (Input::old('content') != null ? Input::old('content') : $content), array('class' => 'form-control', 'placeholder' => 'Body', 'id' => 'contentData', 'required' => 'required', 'tabindex' => 2)) }}
						</div>
						<div class="text-left well col-md-10" id="contentPreview" style="display: none;"></div>
					</div>
					<script type="text/javascript">
						function addStyle(type, icon) {
							// This should be type: [openTag, closeTag].  It should match what is searched in BBCode
							var tags = {
								'italic': ['[i]', '[/i]'],
								'bold': ['[b]', '[/b]'],
								'code': ['[code]', '[/code]'],
								'size': ['[size=100]', '[/size]'],
								'color': ['[color=#ffffff]', '[/color]'],
								'strike': ['[s]', '[/s]'],
								'underline': ['[u]', '[/u]'],
								'center': ['[center]', '[/center]'],
								'paragraph': ['[paragraph]', '[/paragraph]'],
								'url': ['[url=]', '[/url]'],
								'image': ['[img]', '[/img]'],
								'list': ['[list]', '[/list]'],
								'youtube': ['[youtube]', '[/youtube]'],
								// In drop down
								'icon': ['[icon='+ icon +']', ''],
								'dice': ['[dice]', ''],
							};

							var openTag = tags[type][0];
							var closeTag = tags[type][1];

							wrapText('contentData', openTag, closeTag);
						}
						function wrapText(elementID, openTag, closeTag) {
							var textArea = $('#' + elementID);
							var len = textArea.val().length;
							var start = textArea[0].selectionStart;
							var end = textArea[0].selectionEnd;
							var selectedText = textArea.val().substring(start, end);
							var replacement = openTag + selectedText + closeTag;
							textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
						}
						function showPreview() {
							if ($('#contentPreview').css('display') == 'none') {
								$.post("/forum/preview", { update: $('#contentData').val() }).done(function(data) {
									$('#contentPreview').height('auto').width($('#contentData').width());
									$('#contentField').hide();
									$('#contentPreview').empty().append(data).show();
									$('#previewBtn i').removeClass('fa-share-square-o').addClass('fa-reply');
									$('#previewBtn').attr('title', 'Edit');
								});
							} else {
								$('#contentField').show();
								$('#contentPreview').hide();
								$('#previewBtn i').addClass('fa-share-square-o').removeClass('fa-reply');
								$('#previewBtn').attr('title', 'Preview');
							}
						}
					</script>