@props([
    'color' => 'gray',
    'active' => true
])

<button {{ $attributes->merge([
    'type' => 'submit', 
    'style' => match($color) {
        'red' => 'background-color: #e61e1e; --tw-bg-opacity: 1;',
        'green' => 'background-color: #166534; --tw-bg-opacity: 1;',
        'blue' => 'background-color: #1e40af; --tw-bg-opacity: 1;',
        default => 'background-color: #1f2937; --tw-bg-opacity: 1;',
    } . (!$active ? ' opacity: 0.5; cursor: not-allowed;' : ''),
    'class' => "inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
]) }}>
    {{ $slot }}
</button>
