@props(['name', 'label' => null, 'required' => false, 'placeholder' => 'Pilih opsi...', 'options' => [], 'value' => ''])

<div @class(['space-y-1' => $label])>
    @if($label)
        <x-label :for="$name" :value="$label" :class="$required ? 'required' : ''" />
    @endif
    <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
            @class([
                'block w-full appearance-none rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset transition duration-200 focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100',
                'ring-slate-300 dark:ring-slate-700' => !$errors->has($name),
                'ring-rose-400 focus:ring-rose-500' => $errors->has($name),
            ])>
        @if($placeholder)
            <option value="" disabled @selected(old($name, $value) === '')>{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
    @enderror
</div>
