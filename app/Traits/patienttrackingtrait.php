<?php

namespace App\Traits;

use App\models\patienttracking;
use Illuminate\Support\Facades\Auth;

trait patienttrackingtrait
{
    /**
     * تسجيل خطوة جديدة لتتبع المريض
     *
     * @param int $patientId
     * @param int $visitId
     * @param string $stepName
     * @param string $stepStatus
     * @param int|null $assignedTo
     * @param string|null $notes
     * @return patienttracking
     */
    public function logPatientStep($patientId, $visitId, $stepName, $stepStatus = 'pending', $assignedTo = null, $notes = null)
    {
        return patienttracking::create([
            'patient_id' => $patientId,
            'visit_id' => $visitId,
            'step_name' => $stepName,
            'step_status' => $stepStatus,
            'assigned_by' => Auth::id(),
            'assigned_to' => $assignedTo,
            'notes' => $notes,
            'start_time' => now(),
        ]);
    }

    /**
     * تحديث حالة الخطوة
     *
     * @param int $trackingId
     * @param string $stepStatus
     * @param string|null $notes
     * @return patienttracking
     */
    public function updatePatientStep($trackingId, $stepStatus, $notes = null)
    {
        $tracking = patienttracking::findOrFail($trackingId);

        $tracking->update([
            'step_status' => $stepStatus,
            'notes' => $notes,
            'end_time' => $stepStatus === 'completed' ? now() : null,
        ]);

        return $tracking;
    }

    /**
     * الحصول على جميع خطوات تتبع المريض
     *
     * @param int $patientId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPatientSteps($patientId)
    {
        return patienttracking::where('patient_id', $patientId)->get();
    }

    /**
     * الحصول على خطوات تتبع المريض لزيارة محددة
     *
     * @param int $visitId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPatientStepsByVisit($visitId)
    {
        return patienttracking::where('visit_id', $visitId)->get();
    }
}