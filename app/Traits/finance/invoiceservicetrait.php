<?php

namespace App\Traits\Finance;

use Illuminate\Support\Facades\DB;
use App\models\administrative\reservations;
use App\models\finance\invoices;
use App\models\finance\finservicevisit;

trait invoiceservicetrait
{
    /**
     * إضافة الخدمات إلى جدول finservicevisit وتحديث الفاتورة
     *
     * @param int   $reservationId
     * @param array $servicesList
     * @return void
     */
    public function addServicesToInvoice(int $reservationId, array $servicesList): void
    {
        DB::transaction(function () use ($reservationId, $servicesList) {
            // جلب بيانات الحجز والفاتورة
            $reservation = reservations::findOrFail($reservationId);
            $invoice     = invoices::where('reservation_id', $reservationId)->firstOrFail();

            // مجاميع الأسعار
            $sumTotal    = 0;
            $sumPatient  = 0;
            $sumContract = 0;

            foreach ($servicesList as $row) {
                // إنشاء سجل الخدمة
                finservicevisit::create([
                    'id_invoices'           => $invoice->id,
                    'id_visit'              => $reservation->visit_id,
                    'id_services'           => $row['service_id'],
                    'quantity'              => $row['quantity'],
                    'total_pricing'         => $row['total_price'],
                    'contract_contribution' => $row['contract_value'],
                    'patient_contribution'  => $row['patient_value'],
                    'discount'              => $row['unit_price'] - $row['patient_value'],
                    'currency'              => 'EGP',
                    'id_patient'            => $reservation->patient_id,
                ]);

                // تحديث المجاميع
                $sumTotal    += $row['total_price'];
                $sumPatient  += $row['patient_value'];
                $sumContract += $row['contract_value'];
            }

            // تحديث الفاتورة
            $invoice->increment('total', $sumTotal);
            $invoice->increment('patient_contribution', $sumPatient);
            $invoice->increment('contract_contribution', $sumContract);
            $invoice->decrement('residual', $sumPatient);

            // تحديث حالة الفاتورة
            if ($invoice->residual == 0) {
                $invoice->update(['status' => 'paid']);
            } elseif ($invoice->status !== 'partial') {
                $invoice->update(['status' => 'partial']);
            }
        });
    }

    /**
     * حذف خدمة من جدول finservicevisit وتحديث الفاتورة
     *
     * @param int   $reservationId
     * @param array $item يحتوي على record_id, total_price, contract_value, patient_value
     * @return void
     */
    public function removeServiceFromInvoice(int $reservationId, array $item): void
    {
        DB::transaction(function () use ($reservationId, $item) {
            // حذف السجل
            finservicevisit::destroy($item['record_id']);

            // جلب الفاتورة
            $invoice = invoices::where('reservation_id', $reservationId)->firstOrFail();

            // تحديث قيم الفاتورة
            $invoice->decrement('total', $item['total_price']);
            $invoice->decrement('contract_contribution', $item['contract_value']);
            $invoice->decrement('patient_contribution', $item['patient_value']);
            $invoice->increment('residual', $item['patient_value']);

            // تأكد من تعديل الحالة إذا كانت مدفوعة بالكامل
            if ($invoice->status === 'paid') {
                $invoice->update(['status' => 'partial']);
            }
        });
    }
}