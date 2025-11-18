<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use phpseclib3\Crypt\DH\PublicKey;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, HasRoles, HasPermissions, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'role_id',
        'deleted_at',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'avatar_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRouteKeyName()
    {
        return 'username';
    }


    public function name()
    {
        return $this->details?->first_name ?? $this->username;
    }
    public function fullname()
    {
        return empty($this->details) ? '' : $this->details->first_name . " " . $this->details->last_name;
    }

    public function profilePicture()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return url('storage/' . $this->avatar);
        } else if (preg_match('/^https?:\/\//', $this->avatar ?? '')) {
            return $this->avatar;
        }

        return "https://placehold.co/100/FF8600/ffffff?text=" . strtoupper(substr($this->username, 0, 1));
    }
    public function getAvatarUrlAttribute()
    {
        $value = $this->avatar;

        if ($value && Storage::disk('public')->exists($value)) {

            return url('storage/' . $value);
        } else if (preg_match('/^https?:\/\//', $value ?? '')) {
            return $value;
        }
        return "https://placehold.co/100/FF8600/ffffff?text=" . strtoupper(substr($this->username, 0, 1));
    }

    public function masterKey()
    {
        return $this->hasOne(UserMasterKey::class);
    }
    public function activeMasterKey()
    {
        return $this->masterKey()->where('status', 'active');
    }
    public function screenLockPin()
    {
        return $this->hasOne(UserScreenLockPin::class);
    }
    public function activeScreenLockPin()
    {
        return $this->screenLockPin()->where('status', 'active');
    }
    public function publicKey()
    {
        return $this->hasOne(PublicKey::class);
    }

    public function activePublicKey()
    {
        return $this->publicKey()->where('status', 'active');
    }

    public function screenLocks()
    {
        return $this->hasMany(ScreenLock::class);
    }

    public function isLocked()
    {
        $lock = $this->screenLocks()->latest()->first();
        return $lock && !$lock->unlocked && (!$lock->expires_at || $lock->expires_at >= now());
    }

    public function details()
    {
        return $this->hasOne(UserDetails::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function primaryAddress()
    {
        return $this->addresses()->where('is_primary', true)->first();
    }

    public function themeSetting()
    {
        return $this->hasOne(UserSetting::class, 'user_id')
            ->whereHas('setting', function ($q) {
                $q->where('key', 'app_theme');
            });
    }

    public function theme()
    {
        $relation = $this->hasOneThrough(
            AppTheme::class,
            UserSetting::class,
            'user_id',
            'id',
            'id',
            'value'
        );

        $relation->getQuery()->join('settings', 'settings.id', '=', 'user_settings.setting_id')
            ->where('settings.key', 'app_theme')
            ->select('app_themes.*');

        return $relation;
    }
}
