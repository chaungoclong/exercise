@props(['for'])

@error($for)
	<span class="error">
		{{ $message }}
	</span>
@enderror