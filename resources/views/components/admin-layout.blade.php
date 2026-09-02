@props(['title' => 'Dashboard', 'header' => null, 'actions' => null])

@php
    $layoutSlot = $slot;
@endphp

@include('layouts.admin', [
    'title' => $title,
    'header' => $header,
    'actions' => $actions,
    'slot' => $layoutSlot,
])
