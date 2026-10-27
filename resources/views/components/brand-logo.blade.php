@props([
    'variant' => 'full', // 'full' (logo horizontal) | 'icon' (monogram TL)
    'height' => null,
    'width' => 'auto',
    'class' => '',
    'style' => '',
    'alt' => 'TaniLink',
    'pdf' => false,
])

@php
    $src = ($variant === 'icon')
        ? ($pdf ? public_path('images/tanilink-icon.png') : asset('images/tanilink-icon.png'))
        : ($pdf ? public_path('images/branding/tanilink-logo.png') : asset('images/branding/tanilink-logo.png'));

    $defaultHeight = ($variant === 'icon') ? '34px' : '38px';
    $finalHeight = $height ?? $defaultHeight;
@endphp

<img src="{{ $src }}"
     alt="{{ $alt }}"
     class="{{ $class }}"
     style="height:{{ $finalHeight }}; width:{{ $width }}; object-fit:contain; display:inline-block; vertical-align:middle; {{ $style }}"
     {{ $attributes }}>
