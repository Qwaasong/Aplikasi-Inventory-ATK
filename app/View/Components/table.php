<?php

namespace App\View\Components;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public array $dataTable;
    public string $dataUrl;
    public bool $showAction;
    public string $primaryKey;

    public function __construct(
        array $dataTable,
        string $dataUrl,
        bool $showAction = true,
        string $primaryKey = 'id'
    ) {
        $this->dataTable = $dataTable;
        $this->dataUrl = $dataUrl;
        $this->showAction = $showAction;
        $this->primaryKey = $primaryKey;
    }

    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}