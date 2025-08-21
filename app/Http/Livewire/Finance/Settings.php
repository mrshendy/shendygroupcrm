<?php

namespace App\Http\Livewire\Finance;

use Livewire\Component;

class Settings extends Component
{
    public bool $showFinanceSettings = false;

    public function toggleFinanceSettings(): void
    {
        $this->showFinanceSettings = !$this->showFinanceSettings;
    }

    public function render()
    {
        return view('livewire.finance.settings', [
            'showFinanceSettings' => $this->showFinanceSettings,
        ]);
    }
}
