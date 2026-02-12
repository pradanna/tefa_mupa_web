<?php

namespace App\View\Components\backoffice\table;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

if (!class_exists('App\View\Components\backoffice\table\EmptyComponent')) {

    class EmptyComponent extends Component
    {
        /**
         * Create a new component instance.
         */
        public function __construct()
        {
            //
        }

        /**
         * Get the view / contents that represent the component.
         */
        public function render(): View|Closure|string
        {
            return view('components.backoffice.table.empty');
        }
    }
}
