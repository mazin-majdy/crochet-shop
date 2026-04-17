@props(['name', 'value' => '', 'label' => null])
@if ($label)
    <label for="">{{ $label }}</label>
@endif
<textarea name="{{ $name }}" {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>


@error($name)
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
