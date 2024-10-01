<?php

use App\Models\Domain;
use function Laravel\Folio\{name, render};
use Illuminate\View\View;

name("domains.show");

render(function (View $view, Domain $domain) {
    return $view->with([

    ]);
});


/**
 * @var Domain $domain
 */
?>


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $domain->domain }}
        </h2>
    </x-slot>
    <div class="container m-auto mt-3">
        <div
            class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">

            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{$domain->domain}}
            </h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">
                Email: {{$domain->email}}, <br/>
                Created {{$domain->created_at->diffForHumans()}}
            </p>
            <br/>
            <br/>
            <livewire:domain-certificate-generator :domain="$domain"/>
        </div>
    </div>
</x-app-layout>
