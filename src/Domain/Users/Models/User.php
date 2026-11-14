<?php

declare(strict_types=1);

namespace Domain\Users\Models;

use Database\Factories\UserFactory;
use Domain\Shared\Casts\AsDateTime;
use Domain\Shared\Concerns\HasUlidRouteKey;
use Domain\Users\Collections\UserCollection;
use Domain\Users\QueryBuilders\UserQueryBuilder;
use Domain\Users\States\UserState;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\ModelStates\HasStates;
use Spatie\Permission\Traits\HasRoles;

#[CollectedBy(UserCollection::class)]
#[UseEloquentBuilder(UserQueryBuilder::class)]
class User extends Authenticatable implements HasMedia, MustVerifyEmail, PasskeyUser
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use HasStates;
    use HasUlidRouteKey;
    use InteractsWithMedia;
    use Notifiable;
    use PasskeyAuthenticatable;
    use Searchable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'state',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'state' => UserState::class,
            'password' => 'hashed',
            'email_verified_at' => AsDateTime::class,
            'two_factor_confirmed_at' => AsDateTime::class,
            'created_at' => AsDateTime::class,
            'updated_at' => AsDateTime::class,
            'deleted_at' => AsDateTime::class,
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * @return list<string>
     */
    public function guardName(): array
    {
        return ['api', 'web'];
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/avif',
                'image/gif',
                'image/jpeg',
                'image/png',
                'image/webp',
            ]);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->nonQueued()
            ->fit(Fit::Crop, 256, 256);
    }

    /**
     * The database engine searches these columns; other engines index them.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole('admin', 'super-admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * @return Attribute<?string, never>
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->getFirstMediaUrl('avatar', 'thumb') ?: null,
        )->shouldCache();
    }

    /**
     * @return Attribute<Collection<int, string>, never>
     */
    protected function assignedRoles(): Attribute
    {
        return Attribute::make(
            get: fn (): Collection => $this->getRoleNames(),
        )->shouldCache();
    }

    /**
     * @return Attribute<Collection<int, string>, never>
     */
    protected function assignedPermissions(): Attribute
    {
        return Attribute::make(
            get: fn (): Collection => $this->getAllPermissions()->pluck('name'),
        )->shouldCache();
    }
}
