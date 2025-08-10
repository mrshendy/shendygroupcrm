<?php

namespace App\Traits\web;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

trait file_storage
{
    function file_storage($files, $get_directory)
    {
        $paths = [];

        // تجهيز المسار العام بدون / في البداية
        $time = Carbon::now();
        $directory = $get_directory . '/' . $time->format('Y') . '/' . $time->format('m');

        // دعم ملف واحد أو عدة ملفات
        $isMultiple = is_array($files);
        $files = $isMultiple ? $files : [$files];

        foreach ($files as $file) {
            if ($file) {
                $file_name = $time->format('His') . rand(1, 9) . '.' . $file->extension();

                // تخزين الملف داخل public
                Storage::disk('public')->putFileAs($directory, $file, $file_name);

                // إضافة المسار بدون "/"
                $paths[] = $directory . '/' . $file_name;
            }
        }

        return $isMultiple ? $paths : ($paths[0] ?? null);
    }
}
