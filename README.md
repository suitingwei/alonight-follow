# alonight-follow
---

> The follow module designed for laravel,which includes follow, subscribe,like,favorite
---

## How to Use it?

1. Install via composer.

   `composer require alonight/follow` 
2. First add service providers into the `config/app.php`.

    `\Alonight\Follow\AlonightFollowServiceProvider::class,`
3. Publish the models, and migrations

   `php artisan vendor:publish --provider='\Alonight\Follow\AlonightFollowServiceProvider'`
4. Now we have 4 types of trait to use.

   | CanxxxTraits| Note|
   | :--- | :--- |
   | CanFollow | A user can follow somthing|
   | CanSubscribe| A user can subscribe somthing|
   | CanLike| A user can like somthing|
   | CanFavorite| A user can favorite somthing|
   
   > All these traits should be used with namespace the `Alonight\Follow\Traits\Can` 
   
   ### Example
   ```php
   
    //This will follow the user with id of 1.
    $user->follow(1); 
 
    //This will follow the channel with id of 2.
    $user->follow(App\Models\Channel::class,2); 
 
    //This will unfollow the channl with id of 2.
    $user->unFollow(App\Models\Channel::class,2);
 
    //This will get the user's followings users.
    $user->followings()->get();
 
    //This will get the user's followings channels.
    $user->followings(App\Models\Channel::class)->get();
 
    //This will clear all followings.
    $user->clearFollowings();

   ```
   
   

