<?php

namespace App\View\Components\Flowbite\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PrimaryButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $type = 'button',
        public ?bool   $isLink = false,
        public ?string $href = null
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->isLink) {
            return <<<'blade'
<a {{$attributes->merge(['href'=>$href, 'class'=>'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'])}}>
{{$slot}}
</a>
blade;
        }

        return <<<'blade'
<button {{$attributes->merge(['type' => $type,'class'=>'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'])}}>
{{$slot}}
</button>
blade;
    }
}
