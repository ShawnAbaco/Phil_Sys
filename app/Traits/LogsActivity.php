<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        $module = class_basename($model);
        
        ActivityLog::log(
            $action,
            $module,
            "{$module} was {$action}",
            $action !== 'created' ? $model->getOriginal() : null,
            $action !== 'deleted' ? $model->getAttributes() : null
        );
    }
}