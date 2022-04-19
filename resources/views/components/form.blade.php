@props(['method' => 'POST', 'type' => ''])

@php
	$method = strtoupper(trim($method));
	$type   = strtolower(trim($type));
@endphp

<form {{ $attributes }} method="{{ ($method !== 'GET') ? 'POST' : 'GET' }}"
	@if ($type === 'file') enctype="multipart/form-data" @endif>
	
	{{-- csrf --}}
	@unless (in_array($method, ['GET', 'HEAD', 'OPTIONS']))
		@csrf
	@endunless

	{{-- override method --}}
	@unless (in_array($method, ['GET', 'POST', 'HEAD', 'OPTIONS']))
		@method($method)
	@endunless

	{{ $slot }}
</form>