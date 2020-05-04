<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Permissao extends Model implements Auditable
{
    use \OwenIt\Auditing\Audit;

    protected $table = 'permissoes';

    protected $fillable = [
        'nome',
        'descricao'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissoes()
    {        
        return $this->belongsToMany(Permissao::class);
    }


    public function adicionaPermissao($permissao)
    {
        
        if (is_string($permissao)) {
            $permissao = Permissao::where('nome','=',$permissao)->firstOrFail();
        }

        if($this->existePermissao($permissao)){
            return;
        }

        return $this->permissoes()->attach($permissao);

    }

    public function existePermissao($permissao)
    {
        
        if (is_string($permissao)) {
            $permissao = Permissao::where('nome','=',$permissao)->firstOrFail();
        }

        //Verificar se esse papel já existe para esse usuario
        return (boolean) $this->permissoes()->find($permissao->id);

    }
    
    public function removePermissao($permissao)
    {
        
        if (is_string($permissao->nome)) {            
            $permissao = Permissao::where('nome','=',$permissao->nome)->get();
        }
        
        
        return $this->permissoes()->detach($permissao);
    }
}
