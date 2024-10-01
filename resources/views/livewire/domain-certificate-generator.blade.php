<div>
    @if($this->showFirstStep)
        <x-flowbite.buttons.primary-button wire:click="handleFirstStep">
            <x-flowbite.spinner.medium/>
            Generate Free SSL
        </x-flowbite.buttons.primary-button>
    @endif
</div>
