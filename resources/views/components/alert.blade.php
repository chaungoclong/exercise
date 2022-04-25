@props([
	'type' => 'info',
	'for' => '', 
	'dismiss' => false,
])

@error($for)
<div 
	{{ 
		$attributes->class([
			'alert alert-' . trim(strtolower($type)),
			'alert-dismissible fade show' => $dismiss
		]); 
	}}

	role="alert"
>
  <div class="alert-body">
    {{ $message }}

    @if ($dismiss)
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	@endif
  </div>
</div>
@enderror