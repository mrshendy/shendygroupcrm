<?php

namespace App\Http\Livewire\Finance;

use Livewire\Component;

class Settings extends Component
{
      public $showFinanceSettings = false;

    public function toggleFinanceSettings()
    {
        $this->showFinanceSettings = !$this->showFinanceSettings;
    }
    public function render()
    {
        return view('livewire.finance.settings');
    }
}
