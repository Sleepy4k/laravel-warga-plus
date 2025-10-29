<?php

namespace App\Models;

use App\Actions\SendEmailVerification;
use App\Casts\ProtectedCast;
use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Enums\UserOnlineStatus;
use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasUuid, Notifiable, HasRoles, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'phone',
        'identity_number',
        'password',
        'verified_at',
        'last_seen',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'identity_number',
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'phone' => ProtectedCast::class,
            'identity_number' => ProtectedCast::class,
            'verified_at' => 'datetime:Y-m-d',
            'password' => 'hashed',
            'last_seen' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Set the cache prefix.
     *
     * @return string
     */
    public function setCachePrefix(): string {
        return 'user.cache';
    }

    /**
     * Set the loggable fields.
     *
     * @return array<string>
     */
    public function setLoggableField(): array {
        return array_filter($this->fillable, function ($field) {
            return !in_array($field, ['last_seen', 'password']);
        });
    }

    /**
     * Get the user's online status based on the last seen timestamp.
     *
     * @return string
     *
     * @return UserOnlineStatus
     */
    public function getLastSeenStatusAttribute(): UserOnlineStatus
    {
        if (!$this->last_seen) {
            return UserOnlineStatus::OFFLINE;
        }

        $minutes = abs(now()->diffInMinutes($this->last_seen));

        return match (true) {
            $minutes < 2  => UserOnlineStatus::ONLINE,
            $minutes < 15 => UserOnlineStatus::AWAY,
            default       => UserOnlineStatus::OFFLINE,
        };
    }

    /**
     * Get the phone number where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->phone;
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $fullname = $this->personal->full_name;
        $url = url(route('password.reset', ['token' => $token, 'phone' => $this->phone], false));
        $this->notify(new ResetPassword($fullname, $url));
    }

    /**
     * Send an email verification notification to the user.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        (new SendEmailVerification())->execute($this);
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Deactivate the user and store their original role in a separate table.
     *
     * @return void
     */
    public function deactivate(): void
    {
        $currentRole = $this->roles()->first();

        if ($currentRole && $currentRole->name !== config('rbac.role.default')) {
            $this->temporaryRole()->create([
                'original_role' => $currentRole->name,
            ]);
        }

        $this->syncRoles(config('rbac.role.default'));

        $this->is_active = false;
        $this->save();
    }

    /**
     * Reactivate the user and restore their original role from the temporary table.
     *
     * @return void
     */
    public function activate(): void
    {
        $this->is_active = true;

        if ($this->temporaryRole) {
            $this->syncRoles($this->temporaryRole->original_role);
            $this->temporaryRole->delete();
        }

        $this->save();
    }

    /**
     * Define the relationship to the UserTemporaryRole model.
     */
    public function temporaryRole()
    {
        return $this->hasOne(UserTemporaryRole::class);
    }

    /**
     * Get the personal data associated with the user.
     */
    public function personal()
    {
        return $this->hasOne(UserPersonalData::class, 'user_id', 'id');
    }

    /**
     * Get the user agreement associated with the user.
     */
    public function agreement()
    {
        return $this->hasOne(UserAgreement::class, 'user_id', 'id');
    }

    /**
     * Get user avatar data from the personal data.
     */
    public function userAvatar()
    {
        return $this->personal ? $this->personal->userAvatar() : asset('img/avatars/silhouette.jpg'); // Fallback to a default avatar if personal data is not set
    }

    /**
     * Get the shortcuts that belong to the user from user has shortcuts table.
     */
    public function shortcuts()
    {
        return $this->belongsToMany(Shortcut::class, UserHasShortcut::class, 'user_id', 'shortcut_id');
    }
}
