@extends('master')

@section('title', 'Flutter Apps')
@section('description', 'An open list of apps built with Google Flutter')
@section('image_url', asset('images/logo.png'))

@section('content')

	<div id="app">
		<div class="columns is-multiline is-4 is-variable">

			<div v-for="app in apps" class="column is-one-third">
				<div onclick="location.href = '@{{ url('flutter-app/'. $app->slug) }}';" style="cursor:pointer">
					<div class="card is-hover-elevated">
						<header class="card-header">
							<p class="card-header-title is-2">
								@{{ app.title }}
							</p>
							<a href="@{{ app.facebook_url }}" class="card-header-icon" target="_blank">
								<i style="font-size: 20px; color: #888" class="fab fa-facebook"></i>
							</a>
							<a href="@{{ app.twitter_url }}" class="card-header-icon" target="_blank">
								<i style="font-size: 20px; color: #888" class="fab fa-twitter"></i>
							</a>
							<a href="@{{ app.repo_url }}" class="card-header-icon" target="_blank">
								<i style="font-size: 20px; color: #888" class="fab fa-github"></i>
							</a>
						</header>

						<div class="card-content">
							<div class="content">
								<div class="subtitle is-6" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" v-bind:title="app.short_description">
									@{{ app.short_description }}
								</div>
								<div class="columns">
									<div class="column is-one-half">
										<div onclick="openStoreUrl('@{{ $app->google_url }}')" target="_blank" style="visibility:@{{ $app->google_url ? 'visible' : 'hidden' }}">
											<div class="card-image is-slightly-elevated">
												<img src="{{ asset('images/google.png') }}"/>
											</div>
										</div>
										<div class="card-image is-slightly-elevated">
											<img src="{{ asset('images/google.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
										</div>
									</div>
									<div class="column is-one-half">
										<div onclick="openStoreUrl('@{{ $app->apple_url }}')" target="_blank" style="visibility:@{{ $app->apple_url ? 'visible' : 'hidden' }}">
											<div class="card-image is-slightly-elevated">
												<img src="{{ asset('images/apple.png') }}"/>
											</div>
										</div>
										<div class="card-image is-slightly-elevated">
											<img src="{{ asset('images/apple.png') }}" style="opacity: 0.1; filter: grayscale(100%);"/>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-image">
							<img v-bind:src="app.screenshot1_url" width="1080" height="1920"/>
						</div>
					</div>
				</div>
		    </div>

			<p>&nbsp;</p>

		</div>

	</div>

	<script>

	var app = new Vue({
		el: '#app',
		data: {
			apps: {!! $apps !!}
		}
	})

	function openStoreUrl(url) {
		window.open(url, '_blank');

		// https://stackoverflow.com/a/2385180/497368
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	}

	</script>


@stop
