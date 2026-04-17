@props(['name', 'options' => [], 'selected' => null, 'label'])

<label for="">{{ $label }}</label>
<select name="{{ $name }}"
    {{ $attributes->class(['form-control', 'form-select', 'is-invalid' => $errors->has($name)]) }}>
    @foreach ($options as $value => $text)
        <option value="{{ $value }}" @selected($value == $selected)>{{ $text }}</option>
    @endforeach
</select>

@error($name)
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
