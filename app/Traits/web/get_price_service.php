<?php
namespace App\Traits\Web;

use App\models\finance\contract_last_level;
use App\models\finance\contract_frist_level;
use App\models\finance\contract_second_level;
use App\models\finance\contracts_details_last_level;
use App\models\finance\price_lists;
use App\models\finance\pricelistservice;

trait get_price_service
{
    public function getPrice($contract_id, $service_id)
    {
        $contract_last_level = contract_last_level::find($contract_id);
        if (!$contract_last_level) {
            return 'العقد المحدد غير موجود.'; // التأكد من وجود العقد الأخير
        }

        $contract_details_last_level = contracts_details_last_level::where('last_level_id', $contract_id)->first();
        if (!$contract_details_last_level) {
            return 'تفاصيل العقد غير موجودة.'; // التأكد من وجود تفاصيل العقد
        }

        $contract_second_level = contract_second_level::find($contract_last_level->second_level_id);
        if (!$contract_second_level) {
            return 'المستوى الثاني من العقد غير موجود.'; // التأكد من وجود المستوى الثاني
        }

        $contract_frist_level = contract_frist_level::find($contract_second_level->frist_level_id);
        if (!$contract_frist_level) {
            return 'المستوى الأول من العقد غير موجود.'; // التأكد من وجود المستوى الأول
        }

        $price_lists = price_lists::find($contract_frist_level->price_list_id);
        if (!$price_lists) {
            return 'قائمة الأسعار المرتبطة بالعقد غير موجودة.'; // التأكد من وجود قائمة الأسعار
        }

        $hospital_price = pricelistservice::where('service_id', $service_id)
            ->where('price_list_id', $price_lists->id)
            ->value('hospital_price');

        if (!$hospital_price) {
            return 'لا يوجد تسعير لهذه الخدمة في قائمة الأسعار المحددة.'; // التأكد من وجود سعر الخدمة
        }
        $original_price = $hospital_price;
        // تحقق من وجود نسبة خصم وخدمات العيادات
        $discount_percentage = $contract_details_last_level->discount_percentage_clinic_services;
        $patient_contribution = $contract_details_last_level->patient_contribution_clinic_services;

        // إذا كانت نسبة الخصم موجودة، يتم تطبيقها أولاً على الخدمة
        if ($discount_percentage !== null) {
            $hospital_price *= 1 - $discount_percentage / 100;
        }

        // إذا كانت نسبة التحمل للمريض موجودة، يتم حسابها على النحو التالي
        if ($patient_contribution !== null) {
            // إذا كانت نسبة التحمل 100%، يتم دفع كامل سعر الخدمة
            if ($patient_contribution == 100) {
                // إنشاء مصفوفة لنتيجة الحساب
                $result = [
                    'original_price' => $original_price, // السعر قبل الخصم
                    'amount_to_pay' => $hospital_price, // السعر بعد الخصم
                ];
            }
            // إذا كانت نسبة التحمل أقل من 100%، يتم دفع النسبة المحددة
            else {
                $amount_to_pay = $hospital_price * ($patient_contribution / 100);
                  $result = [
                    'original_price' => $original_price, // السعر قبل الخصم
                    'amount_to_pay' => $amount_to_pay, // السعر بعد الخصم
                ];
            }
        }
         return $result;
    }
}