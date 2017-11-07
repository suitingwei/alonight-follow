<?php
namespace Alonight\Follow\Traits;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait CanBeTasted
{

    /**
     * @return  MorphTo
     */
    public function tasted()
    {
       return $this->morphTo();
    }
}
