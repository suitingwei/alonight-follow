<?php
namespace Alonight\Follow\Traits;

use App\User;

trait CanBeFavorited
{

    public function favoriators()
    {
        return $this->morphToMany(User::class, 'favorable');
    }
}
