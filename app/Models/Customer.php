<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'identifier',
        'name',
        'last_name',
        'telephone',
        'email',
        'date_of_birth',
        'note'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->last_name}");
    }
}
