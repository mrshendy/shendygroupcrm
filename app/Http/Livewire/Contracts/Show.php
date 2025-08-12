<?php

namespace App\Http\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;

class Show extends Component
{
    public Contract $contract;

    public function mount(Contract $contract): void
    {
        $this->contract = $contract->load(['client','project','offer','items','payments']);
    }

    public function markPaymentPaid(int $paymentId): void
    {
        $p = $this->contract->payments()->findOrFail($paymentId);
        $p->update(['is_paid' => true]);
        $this->contract->load('payments');
        session()->flash('success','تم تحديد الدفعة كمدفوعة.');
    }

    public function render()
    {
        return view('livewire.contracts.show', [
            'contract' => $this->contract,
            'types'    => \App\Models\Contract::TYPES ?? [],
            'stages'   => \App\Models\ContractPayment::STAGES ?? [],
        ])->layout('layouts.master', ['title' => 'عرض عقد']);
    }
}
