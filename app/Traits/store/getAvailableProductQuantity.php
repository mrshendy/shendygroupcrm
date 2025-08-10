<?php

namespace App\Traits\store;

use App\Models\supply_chain\productstorebalance;
use App\Models\supply_chain\productunit;
use App\Models\supply_chain\unit;

trait GetAvailableProductQuantity
{
    /**
     * إرجاع الرصيد الكلي للمنتج بناءً على المخزن والوحدة (اختياري)
     */
    public function getProductStockDetails($product_id, $store_id = null, $unit_id = null)
    {
        $query = productstorebalance::where('product_id', $product_id);

        if ($store_id) {
            $query->where('store_id', $store_id);
        }

        $balances = $query->get();

        if ($balances->isEmpty()) {
            return [
                'message' => 'لا يوجد رصيد متاح لهذا المنتج',
                'data' => [],
            ];
        }

        $unitConversions = productunit::where('product_id', $product_id)->get();
        $result = [];

        foreach ($balances->groupBy('store_id') as $store => $storeBalances) {
            $total_in_base = 0;

            foreach ($storeBalances as $balance) {
                $rate = $this->convertToBaseUnit($balance->units_id, $unitConversions);
                $total_in_base += $balance->quantity * $rate;
            }

            $converted = $total_in_base;

            if ($unit_id) {
                $rate_to_unit = $this->convertFromBaseUnit($unit_id, $unitConversions);
                if ($rate_to_unit != 0) {
                    $converted = $total_in_base / $rate_to_unit;
                }
            }

            $result[$store] = [
                'store_id' => $store,
                'quantity' => round($converted, 2),
                'quantity_in_base_unit' => $total_in_base,
                'formatted' => $this->formatQuantityBreakdown($product_id, $total_in_base),
            ];
        }

        return [
            'message' => 'تم حساب الرصيد بنجاح',
            'data' => $result,
        ];
    }

    /**
     * تحويل من أي وحدة إلى الوحدة الأساسية الصغرى (يدعم سلاسل متعددة)
     */
    private function convertToBaseUnit($unit_id, $unitConversions)
    {
        $rate = 1;

        while (true) {
            $step = $unitConversions->firstWhere('unit_large_id', $unit_id);
            if (!$step) break;
            $rate *= $step->conversion_rate;
            $unit_id = $step->unit_small_id;
        }

        return $rate;
    }

    /**
     * تحويل من الوحدة الصغرى إلى أي وحدة أعلى (لعرض الرصيد بها)
     */
    private function convertFromBaseUnit($unit_id, $unitConversions)
    {
        $rate = 1;

        while (true) {
            $step = $unitConversions->firstWhere('unit_small_id', $unit_id);
            if (!$step) break;
            $rate *= $step->conversion_rate;
            $unit_id = $step->unit_large_id;
        }

        return $rate;
    }

    /**
     * عرض الرصيد بطريقة مفصلة: "2 كرتونة و1 عبوة و3 حقنة"
     */
    public function formatQuantityBreakdown($product_id, $quantity_in_base_unit)
    {
        $breakdown = [];

        $unitConversions = productunit::where('product_id', $product_id)->get();

        // تحديد أصغر وحدة (الوحدة الأساسية)
        $currentUnit = null;
        foreach ($unitConversions as $unit) {
            if (!$unitConversions->firstWhere('unit_large_id', $unit->unit_small_id)) {
                $currentUnit = $unit->unit_small_id;
                break;
            }
        }

        if (!$currentUnit) {
            return 'لم يتم تحديد وحدة صغرى للمنتج';
        }

        // بناء سلسلة التحويل من الأصغر إلى الأعلى
        $units_chain = [];
        while ($step = $unitConversions->firstWhere('unit_small_id', $currentUnit)) {
            $units_chain[] = [
                'unit_id' => $step->unit_large_id,
                'conversion_rate' => $step->conversion_rate,
            ];
            $currentUnit = $step->unit_large_id;
        }

        // حساب الكميات من الأعلى إلى الأصغر
        $remaining = $quantity_in_base_unit;

        foreach (array_reverse($units_chain) as $unit_step) {
            $count = floor($remaining / $unit_step['conversion_rate']);
            $remaining = $remaining % $unit_step['conversion_rate'];

            if ($count > 0) {
                $unit_name = unit::find($unit_step['unit_id'])->name_ar ?? 'وحدة';
                $breakdown[] = $count . ' ' . $unit_name;
            }
        }

        // الباقي بوحدة الصنف الأساسية
        if ($remaining > 0) {
            $unit_name = unit::find($currentUnit)->name_ar ?? 'وحدة';
            $breakdown[] = $remaining . ' ' . $unit_name;
        }

        return implode(' و ', $breakdown);
    }
}