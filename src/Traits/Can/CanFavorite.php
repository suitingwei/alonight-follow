<?php

namespace Alonight\Follow\Traits\Can;

use Alonight\Follow\Models\Favorable;
use Alonight\Follow\Traits\ParameterNormalize;
use App\User;

trait CanFavorite
{
    /**
     * A user may favorite many resources.
     *
     * @param string $className
     */
    public function favoritings($className = User::class)
    {
        return $this->morphedByMany($className, 'favorable');
    }

    /**
     * Clear all favoriting things.
     *
     * @param string $className
     * @return bool
     */
    public function clearFavoritings($className = User::class)
    {
        Favorable::query()->where(['user_id' => $this->id, 'favorable_type' => $className])->delete();

        return true;
    }

    /**
     * favorite the user.
     *
     * @param string $favorableType
     * @param        $favorableId
     * @throws \Exception
     * @internal param $idolId
     */
    public function favorite($favorableType = User::class, $favorableId = null)
    {
        list($favorableType, $favorableId) = $this->normalizeFavoriteParameters($favorableType, $favorableId);

        if ($this->hadFavorited($favorableType, $favorableId)) {
            throw new \Exception('You have already favorited the ' . $favorableType . ' with id: ' . $favorableId);
        }

        Favorable::query()->create([
            'user_id'        => $this->id,
            'favorable_id'   => $favorableId,
            'favorable_type' => $favorableType,
        ]);
    }

    /**
     * Unfavorite the user.
     *
     * @param string $favorableType
     * @param        $favorableId
     * @internal param $idolId
     */
    public function unfavorite($favorableType = User::class, $favorableId = null)
    {
        list($favorableType, $favorableId) = $this->normalizeFavoriteParameters($favorableType, $favorableId);
        Favorable::query()->where([
            'user_id'        => $this->id,
            'favorable_id'   => $favorableId,
            'favorable_type' => $favorableType,
        ])->delete();
    }

    /**
     * @param string $favorableType
     * @param        $favorableId
     * @return bool
     * @internal param User|int $user
     */
    public function hadFavorited($favorableType = User::class, $favorableId = null)
    {
        list($favorableType, $favorableId) = $this->normalizeFavoriteParameters($favorableType, $favorableId);

        return Favorable::query()->where([
            'user_id'        => $this->id,
            'favorable_id'   => $favorableId,
            'favorable_type' => $favorableType,
        ])->exists();
    }

    /**
     * Normalize the input parameters,for we'll give the user convince to emit the favoriteType to user::class
     *
     * @param string $handledModelType
     * @param null   $handledModelId
     * @return array
     * @throws \Exception
     */
    protected function normalizeFavoriteParameters($handledModelType = User::class, $handledModelId = null)
    {
        if (!is_null($handledModelId)) {
            return [$handledModelType, $handledModelId];
        }

        if (is_int($handledModelType)) {
            $handledModelId   = $handledModelType;
            $handledModelType = User::class;
            return [$handledModelType, $handledModelId];
        }

        throw new \Exception('Input parameters incorccet.');
    }

}
