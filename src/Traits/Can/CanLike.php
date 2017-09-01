<?php
namespace Alonight\Follow\Traits;

use App\Models\Likeable;
use App\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanLike
{
    /**
     * A user may likeite many resources.
     *
     * @param string $className
     * @return MorphMany
     */
    public function likings($className = User::class)
    {
        return $this->morphedByMany($className, 'likeable');
    }

    /**
     * Clear all likeiting things.
     *
     * @param string $className
     * @return bool
     */
    public function clearLikings($className = User::class)
    {
        Likeable::query()->where(['user_id' => $this->id, 'likeable_type' => $className])->delete();

        return true;
    }

    /**
     * likeite the user.
     *
     * @param string $likeableType
     * @param        $likeableId
     * @throws \Exception
     * @internal param $idolId
     */
    public function like($likeableType = User::class, $likeableId = null)
    {
        list($likeableType, $likeableId) = $this->normalizeLikeParameters($likeableType, $likeableId);

        if ($this->hadLiked($likeableType, $likeableId)) {
            throw new \Exception('You have already likeited the ' . $likeableType . ' with id: ' . $likeableId);
        }

        Likeable::query()->create([
            'user_id'       => $this->id,
            'likeable_id'   => $likeableId,
            'likeable_type' => $likeableType,
        ]);
    }

    /**
     * Unlikeite the user.
     *
     * @param string $likeableType
     * @param        $likeableId
     * @internal param $idolId
     */
    public function unLike($likeableType = User::class, $likeableId = null)
    {
        list($likeableType, $likeableId) = $this->normalizeLikeParameters($likeableType, $likeableId);
        Likeable::query()->where([
            'user_id'       => $this->id,
            'likeable_id'   => $likeableId,
            'likeable_type' => $likeableType,
        ])->delete();
    }

    /**
     * @param string $likeableType
     * @param        $likeableId
     * @return bool
     * @internal param User|int $user
     */
    public function hadLiked($likeableType = User::class, $likeableId = null)
    {
        list($likeableType, $likeableId) = $this->normalizeLikeParameters($likeableType, $likeableId);

        return Likeable::query()->where([
            'user_id'       => $this->id,
            'likeable_id'   => $likeableId,
            'likeable_type' => $likeableType,
        ])->exists();
    }

    /**
     * Normalize the input parameters,for we'll give the user convince to emit the likeiteType to user::class
     *
     * @param string $handledModelType
     * @param null   $handledModelId
     * @return array
     * @throws \Exception
     */
    protected function normalizeLikeParameters($handledModelType = User::class, $handledModelId = null)
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
