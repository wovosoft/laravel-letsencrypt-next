<?php
\Laravel\Folio\name('domains.index');
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Domains') }}
        </h2>
    </x-slot>

    <livewire:pages.domains/>
</x-app-layout>
