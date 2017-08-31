<?php
namespace Alonight\Follow\Traits;

use App\User;

trait CanBeFollowed
{
    public function followers()
    {
        return $this->morphToMany(User::class, 'followable');
    }
}
