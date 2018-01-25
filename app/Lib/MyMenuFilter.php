<?php
namespace App\Lib;


use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;
use Auth;

class MyMenuFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        $user = Auth::user();
        if (!isset($item['permission'])) {
            return $item;
        }
        if(is_array($item['permission'])){
            foreach ($item['permission'] as $permission) {
                if ($user->canDo($permission)) {
                    return $item;
                }
            }
        }else{
            if ($user->canDo($item['permission'])) {
                return $item;
            }
        }
        
        return false;

    }
}