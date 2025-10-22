<?php

namespace App\View\Components\Account;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PasswordLockerComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $passwords)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.account.password-locker-component');
    }
}
