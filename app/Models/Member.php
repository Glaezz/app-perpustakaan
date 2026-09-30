<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Member extends Model
{
    public function loans(): hasMany
    {
        return $this->hasMany(Loan::class);
    }

    protected $fillable = [
        'nama',
        'nim',
        'email',
        'nomor_telepon',
        'alamat',
        'status',
    ];
}
