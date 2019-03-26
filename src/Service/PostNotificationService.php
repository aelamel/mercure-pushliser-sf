<?php

namespace App\Service;


use App\Entity\Post;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class PostNotificationService
 * @package App\Service
 */
class PostNotificationService
{

    private $messageBus;

    private $mercureConfig;

    /**
     * PostNotificationService constructor.
     * @param MessageBusInterface $bus
     * @param $mercureConfig
     */
    public function __construct(MessageBusInterface $bus, $mercureConfig)
    {
        $this->messageBus = $bus;
        $this->mercureConfig = $mercureConfig;
    }

    /**
     * @param Post $post
     */
    public function sendUpdateNotification(Post $post) {
        $data = [
            'message' => "The post '{$post->getTitle()}' has been updated"
        ];
        $this->notifyChannel($post, $data);

    }


    /**
     * @param Post $post
     */
    public function sendDeleteNotification(Post $post) {
        $data = [
            'message' => "The post '{$post->getTitle()}' has been deleted"
        ];
        $this->notifyChannel($post, $data);

    }

    /**
     * @param Post $post
     * @param $data
     */
    protected function notifyChannel(Post $post, $data)
    {
        $target = [$this->mercureConfig['target_base_url'] . $this->mercureConfig['posts_target_path']. '/' .$post->getId()];
        $message = new Update($this->mercureConfig['target_base_url']. $this->mercureConfig['notification_channel_path'] , json_encode($data), $target);

        $this->messageBus->dispatch($message);
    }
}