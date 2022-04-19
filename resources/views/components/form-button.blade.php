<form action="{{ $action ?? '' }}" method="POST">
   @csrf
   @method($method ?? 'POST')
   <button class="{{ $class ?? '' }}">
      {{ $slot }}
   </button>
</form>