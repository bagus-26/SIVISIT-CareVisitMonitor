<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'action', 'module',
        'description', 'ip_address', 'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aktivitas sistem secara statis.
     */
    public static function record(string $action, string $module, string $description = '', $request = null): void
    {
        $user = auth()->user();
        self::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name ?? 'Guest',
            'action'     => strtoupper($action),
            'module'     => $module,
            'description'=> $description,
            'ip_address' => $request?->ip() ?? request()->ip(),
            'user_agent' => $request?->userAgent() ?? request()->userAgent(),
        ]);
    }
}
