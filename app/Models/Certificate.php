<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * 
 *
 * @property int $id
 * @property int $domain_id
 * @property string $issue_date
 * @property string $expiry_date
 * @property string $certificate
 * @property string $private_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereDomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUpdatedAt($value)
 * @property-read \App\Models\Domain $domain
 * @property-read \App\Models\User|null $user
 * @mixin \Eloquent
 */
class Certificate extends Model
{
    protected function casts(): array
    {
        return [
            'issue_date'  => 'date',
            'expiry_date' => 'date'
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Domain::class
        );
    }
}
