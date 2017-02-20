<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipe';

    public function hasManyMaterials() {
        return $this->hasMany('App\Material', 'recipe_id', 'id');
    }

    public function hasManySteps() {
        return $this->hasMany('App\Step', 'recipe_id', 'id')->orderBy('steps.sorts', 'asc');
    }
}
