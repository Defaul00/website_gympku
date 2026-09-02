@props(['name', 'label' => null, 'required' => false, 'placeholder' => '', 'value' => '', 'rows' => 4])

<div @class(['space-y-1' => $label])>
    @if($label)
        <x-label :for="$name" :value="$label" :class="$required ? 'required' : ''" />
    @endif
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
              @class([
                  'block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset transition duration-200 placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500',
                  'ring-slate-300 dark:ring-slate-700' => !$errors->has($name),
                  'ring-rose-400 focus:ring-rose-500' => $errors->has($name),
              ])>{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
    @enderror
</div>
