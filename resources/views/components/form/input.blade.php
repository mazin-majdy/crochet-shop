@props([
    'type' => 'text',
    'name',
    'value' => '',
    'label' => null,
])
@if ($label)
    <label for="">{{ $label }}</label>
@endif
<input type="{{ $type ?? 'text' }}" name="{{ $name }}" value = "{{ old($name, $value) }}"
    {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }} />


@error($name)
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
