<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['parent_id', 'key', 'name', 'route','active_route', 'icon', 'permission', 'sort_order', 'is_active'])]

class Menu extends Model
{
    protected function casts(){
        return [
            'is_active'=> 'boolean',
        ];
    }

    public function parent() {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function children(){
        return $this->hasMany(Menu::class, 'parent_id', 'id')->orderBy('sort_order');
    }
}
