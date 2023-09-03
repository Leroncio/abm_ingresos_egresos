<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableRow extends Component
{

    public $id;
    public $detail;
    public $amount;
    public $selected;
    public $created;

    /**
     * Create a new component instance.
     */
    public function __construct(string $id  = null, string $detail  = null, string $amount  = null, string $selected  = null, string $created = null)
    {
        $this->id = $id;
        $this->detail = $detail;
        $this->amount = $amount;
        $this->selected = $selected;
        $this->created = $created;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-row');
    }
}
