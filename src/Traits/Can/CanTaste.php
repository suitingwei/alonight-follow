<?php

namespace Alonight\Follow\Traits;

use App\Models\Taste;
use App\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait CanTaste
 * Used for the model which can initiatively taste other things, showing the "taste" of the main subject.
 * This is frequently used in the user model, case the user can like or dislike something. Notice this time
 * we have two types of the taste : like , dislike.
 * If the user don't want to like the subject, he should cancelLike(something) rather dislike(something)
 * @package Alonight\Follow\Traits
 */
trait CanTaste
{
    /**
     * Like the subject.
     *
     * @param string $subjectType
     * @param        $subjectId
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function like($subjectType = User::class, $subjectId = null)
    {
        return $this->taste(Taste::ACTION_TYPE_LIKE, $subjectType, $subjectId);
    }

    /**
     * Dislike the subject.
     * @param string $subjectType
     * @param null $subjectId
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function dislike($subjectType = User::class, $subjectId = null)
    {
        return $this->taste(Taste::ACTION_TYPE_DISLIKE, $subjectType, $subjectId);
    }

    /**
     * Determine whether the user had liked the subject.
     * @param string $subjectType
     * @param null $subjectId
     * @return bool
     */
    public function hadLiked($subjectType = User::class, $subjectId = null)
    {
        return $this->hadTasted(Taste::ACTION_TYPE_LIKE, $subjectType, $subjectId);
    }

    /**
     * Determine whether the user had disliked the subject.
     * @param string $subjectType
     * @param null $subjectId
     * @return bool
     */
    public function hadDisliked($subjectType = User::class, $subjectId = null)
    {
        return $this->hadTasted(Taste::ACTION_TYPE_DISLIKE, $subjectType, $subjectId);
    }

    /**
     * @param string $actionType
     * @param string $subjectType
     * @param null $subjectId
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function taste($actionType = Taste::ACTION_TYPE_LIKE, $subjectType = User::class, $subjectId = null)
    {
        list($subjectType, $subjectId) = $this->normalizeParameters($subjectType, $subjectId);

        if ($this->hadTasted($actionType, $subjectType, $subjectId)) {
            throw new \Exception('You have already liked the ' . $subjectType . ' with id: ' . $subjectId);
        }

        return Taste::query()->create($this->buildModelData($actionType, $subjectType, $subjectId));
    }

    /**
     * Determine whether the user had tasted the subject with certain type.
     * @param string $actionType
     * @param string $subjectType
     * @param null $subjectId
     * @return bool
     */
    protected function hadTasted($actionType = Taste::ACTION_TYPE_LIKE, $subjectType = User::class, $subjectId = null)
    {
        list($subjectType, $subjectId) = $this->normalizeParameters($subjectType, $subjectId);

        return Taste::query()->where($this->buildModelData($actionType, $subjectType, $subjectId))->exists();
    }

    /**
     * Build the model data for create or search.
     * @param $actionType
     * @param $subjectType
     * @param $subjectId
     * @return array
     */
    protected function buildModelData($actionType, $subjectType, $subjectId)
    {
        return [
            'action_type' => $actionType,
            'user_id'     => $this->id,
            'tasted_id'   => $subjectId,
            'tasted_type' => $subjectType,
        ];
    }

    /**
     * @param $actionType
     * @return HasMany
     */
    public function tastes($actionType)
    {
        return $this->hasMany(Taste::class)->where('action_type', $actionType);
    }

    /**
     * A user may liked many resources.
     */
    public function likings()
    {
        return $this->tastes(Taste::ACTION_TYPE_LIKE);
    }

    /**
     * Get the user's disliked things.
     * @return HasMany
     */
    public function disLikings()
    {
        return $this->tastes(Taste::ACTION_TYPE_DISLIKE);
    }

    /**
     * Clear all likeiting things.
     *
     * @param string $className
     * @return bool
     */
    public function clearLikings($className = User::class)
    {
        Taste::query()->where(['user_id' => $this->id, 'likeable_type' => $className])->delete();

        return true;
    }

    /**
     * Unlikeite the user.
     *
     * @param string $subjectType
     * @param        $subjectId
     * @return mixed
     */
    public function cancelLike($subjectType = User::class, $subjectId = null)
    {
        return $this->cancelTaste(Taste::ACTION_TYPE_LIKE,$subjectType,$subjectId);
    }

    /**
     * @param string $subjectType
     * @param null $subjectId
     * @return mixed
     */
    public function cancelDislike($subjectType= User::class, $subjectId=null)
    {
        return $this->cancelTaste(Taste::ACTION_TYPE_DISLIKE,$subjectType,$subjectId);
    }

    /**
     * @param $actionType
     * @param $subjectType
     * @param $subjectId
     * @return mixed
     */
    protected function cancelTaste($actionType,$subjectType,$subjectId)
    {
        list($subjectType, $subjectId) = $this->normalizeParameters($subjectType, $subjectId);

        return Taste::query()->where($this->buildModelData($actionType,$subjectType,$subjectId))->delete();
    }

    /**
     * Normalize the input parameters,for we'll give the user convince to emit the likeiteType to user::class
     *
     * @param string $handledModelType
     * @param null $handledModelId
     * @return array
     * @throws \Exception
     */
    protected function normalizeParameters($handledModelType = User::class, $handledModelId = null)
    {
        if (!is_null($handledModelId)) {
            return [$handledModelType, $handledModelId];
        }

        if (is_int($handledModelType)) {
            $handledModelId = $handledModelType;
            $handledModelType = User::class;
            return [$handledModelType, $handledModelId];
        }

        throw new \Exception('Input parameters incorccet.');
    }

}
