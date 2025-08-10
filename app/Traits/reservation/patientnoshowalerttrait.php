<?php

namespace App\Traits\reservation;

use App\models\administrative\reservations;
use Carbon\Carbon;

trait patientnoshowalerttrait
{
    /**
     * إرجاع نص تحذيري لو المريض عنده no_show خلال آخر 3 شهور
     *
     * @param int $patientId
     * @param int|null $clinicId
     * @param int|null $doctorId
     * @return string|null
     */
    public function getPatientNoShowWarning($patientId, $clinicId = null, $doctorId = null)
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $messages = [];

        // المستشفى بالكامل
        $hospitalNoShow = reservations::where('id_patient', $patientId)
            ->where('status', 'unconfirmed')
            ->whereDate('date_visit', '>=', $threeMonthsAgo)
            ->count();

        if ($hospitalNoShow > 0) {
            $messages[] = trans('alerts.no_show_hospital', ['count' => $hospitalNoShow]);
        }

        // حسب العيادة
        if ($clinicId) {
            $clinicNoShow = reservations::where('id_patient', $patientId)
                ->where('clinic_id', $clinicId)
                ->where('status', 'unconfirmed')
                ->whereDate('date_visit', '>=', $threeMonthsAgo)
                ->count();

            if ($clinicNoShow > 0) {
                $messages[] = trans('alerts.no_show_clinic', ['count' => $clinicNoShow]);
            }
        }

        // حسب الطبيب
        if ($doctorId) {
            $doctorNoShow = reservations::where('id_patient', $patientId)
                ->where('id_doctor', $doctorId)
                ->where('status', 'unconfirmed')
                ->whereDate('date_visit', '>=', $threeMonthsAgo)
                ->count();

            if ($doctorNoShow > 0) {
                $messages[] = trans('alerts.no_show_doctor', ['count' => $doctorNoShow]);
            }
        }

        return count($messages) > 0 ? implode("\n", $messages) : null;
    }
}