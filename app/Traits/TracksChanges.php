<?php
namespace App\Traits;
use App\models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait TracksChanges
{
    public static function bootTracksChanges()
    {
        static::updated(function ($model) {
            foreach ($model->getChanges() as $field => $new_value) {
                if ($field !== 'updated_at') {
                    // تحويل أي مصفوفة إلى JSON
                    $old_value = is_array($model->getOriginal($field)) ? json_encode($model->getOriginal($field)) : $model->getOriginal($field);
                    $new_value = is_array($new_value) ? json_encode($new_value) : $new_value;
                    
                    Log::create([
                        'action_type' => 'update',
                        'table_name' => $model->getTable(),
                        'record_id' => $model->id,
                        'field_name' => $field,
                        'old_value' => $old_value,
                        'new_value' => $new_value,
                        'performed_by' => Auth::id(),
                        'performed_at' => now(),
                        'method' => Request::method(),
                        'full_url' => Request::fullUrl(),
                        'ip_address' => request()->ip(),
                        'mac_address' => substr(shell_exec('getmac'), 159, 20),
                        'user_agent' => Request::header('user-agent'),
                    ]);
                }
            }
        });

        static::created(function ($model) {
            Log::create([
                'action_type' => 'create',
                'table_name' => $model->getTable(),
                'record_id' => $model->id,
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'method' => Request::method(),
                'full_url' => Request::fullUrl(),
                'ip_address' => request()->ip(),
                'mac_address' => substr(shell_exec('getmac'), 159, 20),
                'user_agent' => Request::header('user-agent'),
            ]);
        });

        static::deleted(function ($model) {
            Log::create([
                'action_type' => 'delete',
                'table_name' => $model->getTable(),
                'record_id' => $model->id,
                'performed_by' => Auth::id(),
                'performed_at' => now(),
                'method' => Request::method(),
                'full_url' => Request::fullUrl(),
                'ip_address' => request()->ip(),
                'mac_address' => substr(shell_exec('getmac'), 159, 20),
                'user_agent' => Request::header('user-agent'),
            ]);
        });
    }
}