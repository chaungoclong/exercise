@props(['name', 'value'])

<input type="hidden"
    id="passing_data_from_PHP_to_JavaScript_{{ $name ?? '' }}"
    value={{ $value }}
    disabled>
