<?php

namespace App\Service;


use App\Entity\Post;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha384;

/**
 * Class JwtGenerator
 * @package App\Service
 */
class JwtGenerator
{
    private $mercureConfig;

    /**
     * JwtGenerator constructor.
     * @param $mercureConfig
     */
    function __construct($mercureConfig)
    {
        $this->mercureConfig = $mercureConfig;
    }

    /**
     * @param Post[] $posts
     * @return string
     */
    public function generateNotificationToken($posts) {
        $prefix = $this->mercureConfig['target_base_url']. $this->mercureConfig['posts_target_path']. '/';
        $subscriptionList = [];

        foreach ($posts as $post) {
            $subscriptionList[] = $prefix.$post->getId();
        }

        return
            (new Builder())
            ->set('mercure', ['subscribe' => $subscriptionList])
            ->sign(new Sha384(), $this->mercureConfig['subscriber_secret'])->getToken();
    }
}