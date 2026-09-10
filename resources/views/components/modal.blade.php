@props([
    'show'      => 'showModal',
    'title'     => null,
    'subtitle'  => null,
    'maxWidth'  => 'md',
    'closeable' => true,
])

@php
// ponytail: explicit class maps ensure Tailwind JIT discovers every literal class name
$maxWidthMap = [
    'sm'  => 'max-w-sm',
    'md'  => 'max-w-md',
    'lg'  => 'max-w-lg',
    'xl'  => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];

$maxWidthClass = $maxWidthMap[$maxWidth] ?? 'max-w-md';
@endphp

<div
    x-show="{{ $show }}"
    style="display: none; margin: 0 !important;"
    class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 !m-0"
    @if($closeable)
        @keydown.escape.window="{{ $show }} = false"
    @endif
>
    <div
        @if($closeable)
            @click.away="{{ $show }} = false"
        @endif
        {{ $attributes->merge(['class' => "bg-white rounded-2xl shadow-xl w-full {$maxWidthClass} overflow-hidden border border-slate-200 text-left"]) }}
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        @if($title || isset($header))
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                @if(isset($header))
                    {{ $header }}
                @else
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">{{ $title }}</h3>
                        @if($subtitle)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                        @endif
                    </div>
                @endif

                @if($closeable)
                    <button
                        type="button"
                        @click="{{ $show }} = false"
                        class="text-slate-400 hover:text-slate-600 text-lg leading-none transition ml-3"
                        title="Tutup"
                    >&times;</button>
                @endif
            </div>
        @endif

        {{ $slot }}

        @if(isset($footer))
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end space-x-2">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
