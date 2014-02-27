<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?=$pageTitle?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?php echo URL::to('/img/favicon.ico'); ?>" />

	<!-- Extra styles -->
	{{ HTML::style('/vendor/font-awesome/css/font-awesome.min.css') }}
	{{ HTML::style('/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}
	{{ HTML::style('/vendor/messenger/build/css/messenger.css') }}
	{{ HTML::style('/vendor/messenger/build/css/messenger-theme-future.css') }}
	{{ HTML::style('http://fonts.googleapis.com/css?family=Orbitron') }}

	<!-- Local styles -->
	@if (isset($activeUser) && File::exists($activeUser->theme))
		{{ HTML::style($activeUser->themeStyle) }}
	@else
		{{ HTML::style('/css/master.css') }}
	@endif
	{{ HTML::style('http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css') }}

	<!-- Css -->
	@section('css')
	@show
	<!-- Css Form -->
	@section('cssForm')
	@show
</head>
<body class="app">
	<div id="container">
		<div id="header">
			@if ($menu == 'utopian')
				@include('layouts.menu.utopian')
			@elseif ($menu == 'twitter')
				@include('layouts.menu.twitter')
			@endif
		</div>
		<hr />
		<div id="content">
			{{ $content }}
		</div>
	</div>
	<div id="modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h3 id="myModalLabel">Modal header</h3>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<div id="remoteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myRemoteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h3 id="myModalLabel">Modal header</h3>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<!-- javascript-->
	{{ HTML::script('/js/jquery-1.10.2.min.js') }}
	{{ HTML::script('/vendor/bootstrap3/dist/js/bootstrap.min.js') }}
	{{ HTML::script('/vendor/bootbox/bootbox.js') }}
	{{ HTML::script('/vendor/messenger/build/js/messenger.min.js') }}
	{{ HTML::script('/vendor/messenger/build/js/messenger-theme-future.js') }}
	{{ HTML::script('/js/master.js') }}

	<!-- JS Include -->
	@section('jsInclude')
	@show
	<!-- JS Include Form -->
	@section('jsIncludeForm')
	@show

	<script>
	$(document).ready(function() {
		// Minify CSS
		// var compressor = require('node-minify');
		// new compressor.minify({
		// 	type: 'gcc',
		// 	fileIn: ['public/css/master3/master.css'],
		// 	fileOut: 'public/css/master3/master.min.css',
		// 	callback: function(err, min){
		// 		console.log(err);
		// 	}
		// });

		$("a[rel=popover]").popover();
		$("a.confirm-remove").click(function(e) {
			e.preventDefault();
			var location = $(this).attr('href');
			bootbox.dialog({
				message: "Are you sure you want to remove this item?",
				buttons: {
					success: {
						label: "Yes",
						className: "btn-primary",
						callback: function() {
							window.location.replace(location);
						}
					},
					danger: {
						label: "No",
						className: "btn-primary"
					}
				}
			});
		});
		$("a.confirm-continue").click(function(e) {
			e.preventDefault();
			var location = $(this).attr('href');
			bootbox.dialog({
				message: "Are you sure you want to continue?",
				buttons: {
					danger: {
						label: "No",
						className: "btn-primary"
					},
					success: {
						label: "Yes",
						className: "btn-primary",
						callback: function() {
							window.location.replace(location);
						}
					},
				}
			});
		});
		// Work around for multi data toggle modal
		// http://stackoverflow.com/questions/12286332/twitter-bootstrap-remote-modal-shows-same-content-everytime
		$('body').on('hidden.bs.modal', '#modal', function () {
			$(this).removeData('modal');
		});
		$("div[id$='Modal']").on('hidden.bs.modal',
			function () {
				$(this).removeData('bs.modal');
			}
		);
		$("div[id$='modal']").on('hidden.bs.modal',
			function () {
				$(this).removeData('bs.modal');
			}
		);

		Messenger.options = {
			extraClasses: 'messenger-fixed {{ isset($activeUser) ? $activeUser->alertLocation : "messenger-on-top" }}',
			theme: 'future'
		}

		var mainErrors = {{ (Session::get('errors') != null ? json_encode(implode('<br />', Session::get('errors'))) : 0) }};
		var mainStatus = {{ (Session::get('message') != null ? json_encode(Session::get('message')) : 0) }};
		var mainLogins = {{ (Session::get('login_errors') != null ? json_encode(Session::get('login_errors')) : 0) }};

		if (mainLogins == true) {
			Messenger().post({
				message: 'Username or password incorrect.',
				type: 'error',
				showCloseButton: true
			});
		}
		if (mainErrors != 0) {
			Messenger().post({
				message: mainErrors,
				type: 'error',
				showCloseButton: true
			});
		}
		if (mainStatus != 0) {
			Messenger().post({
				message: mainStatus,
				showCloseButton: true
			});
		}

		// On Ready Js
		@section('onReadyJs')
		@show
		// On Ready Js Form
		@section('onReadyJsForm')
		@show
	});
	</script>

	<!-- JS -->
	@section('js')
	@show
	<!-- JS Form -->
	@section('jsForm')
	@show

</body>
</html>