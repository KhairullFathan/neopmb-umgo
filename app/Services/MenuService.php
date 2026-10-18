<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Collection;

/**
 * Class MenuService.
 */
class MenuService
{
    public function getMenus(): Collection{
        $user = auth()->user();
        if(!$user) return collect();
        $menus = Menu::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return $this->buildTree(
            $menus, null, $user
        );
    }

    private function buildTree(Collection &$menus, ?int $parentId, $user): Collection{
        return $menus
            ->where('parent_id', $parentId)
            ->sortBy('sort_order')
            ->map(function (Menu $menu) use ($menus, $user){
                if($menu->permission && !($user->can($menu->permission))){
                    return null;
                }
                $children = $this->buildTree($menus, $menu->id, $user);
                $menu->setRelation('children', $children);
                if(!($menu->route) && $children->isEmpty()) {
                    return null;
                }
                return $menu;
            })
            ->filter()
            ->values();
    }
}
