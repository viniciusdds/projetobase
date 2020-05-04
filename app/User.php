<?php

namespace App;

use App\Papel;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use Notifiable;

    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function eAdmin()
    {
        //return $this->id == 1;
        return $this->existePapel('Admin');
    }

    public function papeis()
    {
        return $this->belongsToMany(Papel::class);
    }

    public function adicionaPapel($papel)
    {
        if (is_string($papel)) {
            $papel = Papel::where('nome', '=', $papel)->firstOrFail();
        }

        if ($this->existePapel($papel)) {
            return;
        }

        return $this->papeis()->attach($papel);
    }
    
    public function existePapel($papel)
    {
        if (is_string($papel)) {
            $papel = Papel::where('nome', '=', $papel)->firstOrFail();
        }

        //Verificar se esse papel já existe para esse usuario
        return (bool) $this->papeis()->find($papel->id);
    }

    public function removePapel($papel)
    {

        if (is_string($papel->nome)) {
            $papel = Papel::where('nome', '=', $papel->nome)->get();
        }


        return $this->papeis()->detach($papel);
    }

    public function temUmPapelDestes($papeis)
    {
        //se um dos papeis tiver para esse usuario ele vai retornar verdadeiro
        $userPapeis = $this->papeis;

        //vai trazer o numero de intersect das duas listas
        return $papeis->intersect($userPapeis)->count();
    }
}
