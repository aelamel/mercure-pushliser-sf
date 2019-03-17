<?php

namespace App\Service;


use App\Entity\Post;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha384;

class JwtGenerator
{

    /**
     * @param Post[] $posts
     * @return string
     */
    public function generateNotificationToken($posts) {
        $prefix = "http://local.dev/posts/";
        $subscriptionList = [];

        foreach ($posts as $post) {
            $subscriptionList[] = $prefix.$post->getId();
        }

        return (new Builder())->set('mercure', ['subscribe' => $subscriptionList])->sign(new Sha384(), 'adzadza')->getToken();
    }
}