<?php
namespace App\Traits\Web;

use App\models\finance\contract_last_level;
use App\models\finance\contract_frist_level;
use App\models\finance\contract_second_level;
use App\models\finance\contracts_details_last_level;

trait get_contract
{
    public function getcontractname($contract_id)
    {
        // العثور على العقد الأخير
        $contract_last_level = contract_last_level::find($contract_id);
        if (!$contract_last_level) {
            return 'العقد المحدد غير موجود.'; // التأكد من وجود العقد الأخير
        }

        // العثور على تفاصيل العقد الأخير
        $contract_details_last_level = contracts_details_last_level::where('last_level_id', $contract_id)->first();
        if (!$contract_details_last_level) {
            return 'تفاصيل العقد غير موجودة.'; // التأكد من وجود تفاصيل العقد
        }

        // العثور على المستوى الثاني
        $contract_second_level = contract_second_level::find($contract_last_level->second_level_id);
        if (!$contract_second_level) {
            return 'المستوى الثاني من العقد غير موجود.'; // التأكد من وجود المستوى الثاني
        }

        // العثور على المستوى الأول
        $contract_frist_level = contract_frist_level::find($contract_second_level->frist_level_id);
        if (!$contract_frist_level) {
            return 'المستوى الأول من العقد غير موجود.'; // التأكد من وجود المستوى الأول
        }

        // جمع أسماء المستويات الثلاثة
        $result = $contract_frist_level->name . ' - ' . $contract_second_level->name . ' - ' . $contract_last_level->name;

        return $result; // إرجاع النتيجة
    }
}