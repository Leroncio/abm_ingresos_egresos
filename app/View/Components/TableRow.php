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
    public $type;
    public $updated;

    /**
     * Create a new component instance.
     */
    public function __construct(string $id  = null, string $detail  = null, string $amount  = null, string $type  = null, string $updated = null)
    {
        $this->id = $id;
        $this->detail = $detail;
        $this->amount = $amount;
        $this->type = $type;
        $this->updated = $updated;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-row');
    }
}
