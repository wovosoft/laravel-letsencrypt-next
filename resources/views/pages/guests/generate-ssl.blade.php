<?php
use function Laravel\Folio\name;
use Illuminate\Support\Facades\Storage;

name("guests.generate-ssl");

?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate SSL') }}
        </h2>
    </x-slot>

    <div class="container m-auto max-w-[800px]">
        <livewire:guest-certificate-generator/>
    </div>
</x-app-layout>
