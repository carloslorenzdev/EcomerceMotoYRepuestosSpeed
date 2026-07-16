@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="MotosSpeed" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
            <x-store.logo class="size-6" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="MotosSpeed" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
            <x-store.logo class="size-6" />
        </x-slot>
    </flux:brand>
@endif
