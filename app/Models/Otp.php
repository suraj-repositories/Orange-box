<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'used_at',
    ];

    protected $dates = [
        'expires_at',
        'used_at',
    ];

    /**
     * Generate a new OTP for a user.
     */
    public static function generate($userId,  $length = 6, $ttlMinutes = 5)
    {
        $otp = rand(pow(10, $length-1), pow(10, $length)-1);
        return self::create([
            'user_id' => $userId,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes($ttlMinutes),
        ]);
    }

    /**
     * Verify the OTP for a user.
     */
    public static function verify($userId, $otp)
    {
        $record = self::where('user_id', $userId)
                      ->where('otp', $otp)
                      ->whereNull('used_at')
                      ->where('expires_at', '>=', Carbon::now())
                      ->first();

        if ($record) {
            $record->used_at = Carbon::now();
            $record->save();
            return true;
        }

        return false;
    }

    /**
     * Relation to user (assuming a User model exists)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
