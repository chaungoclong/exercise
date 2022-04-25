@props([
   'action' => '',
   'method' => 'POST', 
   'button' => true
])

<form action="{{ $action }}" method="{{ $method }}">
   @csrf
   @method($method)
  
   @if ($button)
      <button {{ $attributes }}>
         {{ $slot }}
      </button>
   @endif
</form>