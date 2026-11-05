@props(['name', 'label', 'type' => 'text', 'value' => null])
<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $type === 'password' ? '' : old($name, $value) }}"
        {{ $attributes->class(['invalid' => $errors->has($name)]) }}
    >
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
