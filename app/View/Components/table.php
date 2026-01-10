<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class table extends Component
{
    /**
     * Columns displayed in the table component.
     *
     * @var array
     */
    public array $dataTable;

    /**
     * Endpoint URL used by the component to fetch data.
     *
     * @var string
     */
    public string $dataUrl;
    
    /**
     * Whether to show action column or not.
     *
     * @var bool
     */
    public bool $showAction;
    
    /**
     * Create a new component instance.
     */
    public function __construct(array $dataTable, string $dataUrl, bool $showAction = true)
    {
        $this->dataTable = $dataTable;
        $this->dataUrl = $dataUrl;
        $this->showAction = $showAction;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}
