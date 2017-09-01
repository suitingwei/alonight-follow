<?php
namespace Alonight\Follow\Traits;

use App\Models\Followable;
use App\User;

/**
 * Trait CanFollow
 * Not only can user follow users, also can they follow posts, or movies, channels, etc.
 *
 * @package Alonight\Follow\Traits
 */
trait CanFollow
{
    /**
     * A user may follow many resources.
     *
     * @param string $className
     */
    public function followings($className = User::class)
    {
        return $this->morphedByMany($className, 'followable');
    }

    /**
     * Follow the user.
     *
     * @param string $followedType
     * @param        $followedId
     * @throws \Exception
     * @internal param $idolId
     */
    public function follow($followedType = User::class, $followedId = null)
    {
        list($followedType, $followedId) = $this->normalizeFollowParameters($followedType, $followedId);

        if ($this->hadFollowed($followedType, $followedId)) {
            throw new \Exception('You have already followed the ' . $followedType . ' with id: ' . $followedId);
        }

        Followable::query()->create([
            'user_id'         => $this->id,
            'followable_id'   => $followedId,
            'followable_type' => $followedType,
        ]);
    }

    /**
     * Unfollow the user.
     *
     * @param string $followedType
     * @param        $followedId
     * @internal param $idolId
     */
    public function unFollow($followedType = User::class, $followedId = null)
    {
        list($followedType, $followedId) = $this->normalizeFollowParameters($followedType, $followedId);
        Followable::query()->where([
            'user_id'         => $this->id,
            'followable_id'   => $followedId,
            'followable_type' => $followedType,
        ])->delete();
    }

    /**
     * @param string $followedType
     * @param        $followedId
     * @return bool
     * @internal param User|int $user
     */
    public function hadFollowed($followedType = User::class, $followedId = null)
    {
        list($followedType, $followedId) = $this->normalizeFollowParameters($followedType, $followedId);

        return Followable::query()->where([
            'user_id'         => $this->id,
            'followable_id'   => $followedId,
            'followable_type' => $followedType,
        ])->exists();
    }

    public function clearFollowings($className = User::class)
    {
        Followable::query()->where(['user_id' => $this->id, 'favorable_type' => $className])->delete();

        return true;
    }

    /**
     * Normalize the input parameters,for we'll give the user convince to emit the favoriteType to user::class
     *
     * @param string $handledModelType
     * @param null   $handledModelId
     * @return array
     * @throws \Exception
     */
    protected function normalizeFollowParameters($handledModelType = User::class, $handledModelId = null)
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

