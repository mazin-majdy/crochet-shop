@props([
    'options',
    'name',
    'checked' => false,
    'label' => '',

])

<label for="">{{ $label }}</label>
@foreach ($options as $value => $text)
    <div class="form-check">

        <input
            id="{{ $value }}"
            type="radio"
            class="form-check-input"
            name="{{ $name }}"
            value="{{ $value }}"
            @checked(old($name, $checked) == $value)
            {{ $attributes->class([
                'form-check-input',
                'is-invallid' => $errors->has($name)
                ]) }}
        />
        <label
            for="{{ $value }}"
            {{ $attributes->class(['form-check-label']) }}
        >{{ $text }}</label>
    </div>
@endforeach
@error($name)
    <span class="invalid-feedback">{{ $message }}</span>
@enderror
