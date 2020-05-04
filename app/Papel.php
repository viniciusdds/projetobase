<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Papel extends Model implements Auditable
{
    use \OwenIt\Auditing\Audit;

    protected $table = 'papeis';

    protected $fillable = [
        'nome',
        'descricao'
    ];

    public function papeis()
    {
        return $this->belongsToMany(Papel::class);
    }
}
