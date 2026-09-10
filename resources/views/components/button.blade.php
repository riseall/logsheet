@props([
    'variant' => 'default', // default/brand, primary/teal, secondary, tertiary, success, danger, warning, dark, ghost
    'size'    => null,      // xs, sm, base/md, lg, xl
    'pill'    => false,     // true => rounded-full (.btn-pill)
    'outline' => false,     // true => outline style (.btn-outline-*)
    'light'   => false,     // true => light pastel style (.btn-light-*)
    'icon'    => false,     // true => square icon button (.btn-icon)
    'sr'      => null,      // screen-reader accessibility text (<span class="sr-only">)
    'type'    => 'button',  // button, submit, reset
    'href'    => null,      // if provided, renders as <a>
])

@php
// ponytail: explicit class maps ensure Tailwind JIT discovers every literal class name
$solidVariants = [
    'default'         => 'btn-default',
    'brand'           => 'btn-brand',
    'primary'         => 'btn-primary',
    'teal'            => 'btn-teal',
    'secondary'       => 'btn-secondary',
    'tertiary'        => 'btn-tertiary',
    'success'         => 'btn-success',
    'danger'          => 'btn-danger',
    'warning'         => 'btn-warning',
    'dark'            => 'btn-dark',
    'ghost'           => 'btn-ghost',
    'light'           => 'btn-light',
    'light-default'   => 'btn-light-default',
    'light-brand'     => 'btn-light-brand',
    'light-primary'   => 'btn-light-primary',
    'light-teal'      => 'btn-light-teal',
    'light-secondary' => 'btn-light-secondary',
    'light-tertiary'  => 'btn-light-tertiary',
    'light-success'   => 'btn-light-success',
    'light-danger'    => 'btn-light-danger',
    'light-warning'   => 'btn-light-warning',
    'light-dark'      => 'btn-light-dark',
];

$outlineVariants = [
    'default'   => 'btn-outline-default',
    'brand'     => 'btn-outline-brand',
    'primary'   => 'btn-outline-primary',
    'teal'      => 'btn-outline-teal',
    'secondary' => 'btn-outline-secondary',
    'tertiary'  => 'btn-outline-tertiary',
    'success'   => 'btn-outline-success',
    'danger'    => 'btn-outline-danger',
    'warning'   => 'btn-outline-warning',
    'dark'      => 'btn-outline-dark',
    'ghost'     => 'btn-ghost',
];

$lightVariants = [
    'default'   => 'btn-light-default',
    'brand'     => 'btn-light-brand',
    'primary'   => 'btn-light-primary',
    'teal'      => 'btn-light-teal',
    'secondary' => 'btn-light-secondary',
    'tertiary'  => 'btn-light-tertiary',
    'success'   => 'btn-light-success',
    'danger'    => 'btn-light-danger',
    'warning'   => 'btn-light-warning',
    'dark'      => 'btn-light-dark',
    'ghost'     => 'btn-light-secondary',
];

$regularSizes = [
    'xs'   => 'btn-xs',
    'sm'   => 'btn-sm',
    'base' => 'btn-base',
    'md'   => 'btn-base',
    'lg'   => 'btn-lg',
    'xl'   => 'btn-xl',
];

$iconSizes = [
    'xs'   => 'btn-icon-xs',
    'sm'   => 'btn-icon-sm',
    'base' => 'btn-icon-base',
    'md'   => 'btn-icon-base',
    'lg'   => 'btn-icon-lg',
    'xl'   => 'btn-icon-xl',
];

$isOutline = filter_var($outline, FILTER_VALIDATE_BOOLEAN);
$isLight   = filter_var($light, FILTER_VALIDATE_BOOLEAN);
$isPill    = filter_var($pill, FILTER_VALIDATE_BOOLEAN);
$isIcon    = filter_var($icon, FILTER_VALIDATE_BOOLEAN);

if ($isLight) {
    $varClass = $lightVariants[$variant] ?? 'btn-light-default';
} elseif ($isOutline) {
    $varClass = $outlineVariants[$variant] ?? 'btn-outline-default';
} else {
    $varClass = $solidVariants[$variant] ?? 'btn-default';
}

if ($isIcon) {
    $sizeClass = $size && isset($iconSizes[$size]) ? $iconSizes[$size] : 'btn-icon';
} else {
    $sizeClass = $size && isset($regularSizes[$size]) ? $regularSizes[$size] : '';
}

$pillClass = $isPill ? 'btn-pill' : '';

$classes = trim("btn {$varClass} {$sizeClass} {$pillClass}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
        @if ($sr)<span class="sr-only">{{ $sr }}</span>@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
        @if ($sr)<span class="sr-only">{{ $sr }}</span>@endif
    </button>
@endif

