@props(['for'])

@error($for)
	<span {{ $attributes->merge(['class' => 'error']) }}
		id="{{ str_replace('_', '-', $for) . '-error' }}">
		{{ $message }}
	</span>
@enderror