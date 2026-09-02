@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-rose-600 space-y-1 dark:text-rose-400']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5">
                <x-icon name="alert" class="h-4 w-4 shrink-0" />
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
