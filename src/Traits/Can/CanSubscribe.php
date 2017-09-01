<?php
namespace Alonight\Follow\Traits;

use App\Models\Subscribable;
use App\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanSubscribe
{
    /**
     * A user may subscribeite many resources.
     *
     * @param string $className
     * @return MorphMany
     */
    public function subscribings($className = User::class)
    {
        return $this->morphedByMany($className, 'subscribable');
    }

    /**
     * Clear all subscribeiting things.
     *
     * @param string $className
     * @return bool
     */
    public function clearsubscribings($className = User::class)
    {
        Subscribable::query()->where(['user_id' => $this->id, 'subscribeable_type' => $className])->delete();

        return true;
    }

    /**
     * subscribeite the user.
     *
     * @param string $subscribeableType
     * @param        $subscribeableId
     * @throws \Exception
     * @internal param $idolId
     */
    public function subscribe($subscribeableType = User::class, $subscribeableId = null)
    {
        list($subscribeableType, $subscribeableId) = $this->normalizesubscribeParameters($subscribeableType, $subscribeableId);

        if ($this->hadsubscribed($subscribeableType, $subscribeableId)) {
            throw new \Exception('You have already subscribeited the ' . $subscribeableType . ' with id: ' . $subscribeableId);
        }

        Subscribable::query()->create([
            'user_id'           => $this->id,
            'subscribable_id'   => $subscribeableId,
            'subscribable_type' => $subscribeableType,
        ]);
    }

    /**
     * Unsubscribeite the user.
     *
     * @param string $subscribeableType
     * @param        $subscribeableId
     * @internal param $idolId
     */
    public function unsubscribe($subscribeableType = User::class, $subscribeableId = null)
    {
        list($subscribeableType, $subscribeableId) = $this->normalizesubscribeParameters($subscribeableType, $subscribeableId);
        Subscribable::query()->where([
            'user_id'           => $this->id,
            'subscribable_id'   => $subscribeableId,
            'subscribable_type' => $subscribeableType,
        ])->delete();
    }

    /**
     * @param string $subscribeableType
     * @param        $subscribeableId
     * @return bool
     * @internal param User|int $user
     */
    public function hadsubscribed($subscribeableType = User::class, $subscribeableId = null)
    {
        list($subscribeableType, $subscribeableId) = $this->normalizesubscribeParameters($subscribeableType, $subscribeableId);

        return Subscribable::query()->where([
            'user_id'           => $this->id,
            'subscribable_id'   => $subscribeableId,
            'subscribable_type' => $subscribeableType,
        ])->exists();
    }

    /**
     * Normalize the input parameters,for we'll give the user convince to emit the subscribeiteType to user::class
     *
     * @param string $handledModelType
     * @param null   $handledModelId
     * @return array
     * @throws \Exception
     */
    protected function normalizesubscribeParameters($handledModelType = User::class, $handledModelId = null)
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
