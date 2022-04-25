@props(['isChecked' => false])

<input 
	{{ $attributes->class(['error' => $errors->has($attributes['name'] ?? '')]) }}
	{{ $isChecked ? 'checked' : '' }}
>