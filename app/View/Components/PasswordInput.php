<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PasswordInput extends Component
{
    public $inputName;
    public $id;
    public $iconName;
    public $iconId;
    public $class;
    public $label;

    public function __construct($inputName, $iconName, $class, $label = null, $id = null, $iconId = null)
    {
        $this->inputName = $inputName;
        $this->id = $id ?: $inputName;

        $this->iconName = $iconName;
        $this->iconId = $iconId ?: $iconName;
        
        $this->class = $class;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.password-input');
    }
}
