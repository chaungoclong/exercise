@props(['for'])

<div {{ $attributes->class([
		'is-invalid' => $errors->has($for ?? ''),
		'input-group',
		'input-group-merge'
	]) }}>
	{{ $slot ?? '' }}
</div>