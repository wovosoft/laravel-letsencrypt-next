<?php

namespace App\Livewire\Pages;

use App\Models\Domain;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class Domains extends Component
{

    public function render(): Factory|Application|View|\Illuminate\View\View
    {
        $domains = Domain::query()
            ->whereUserId(auth()->id())
            ->paginate();

        return view('livewire.pages.domains.index', [
            "domains" => $domains
        ]);
    }
}
