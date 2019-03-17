<?php

namespace App\Service;


use App\Entity\Post;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

class PostNotificationService
{

    private $messageBus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->messageBus = $bus;
    }

    public function sendUpdateNotification(Post $post) {
        $data = [
            'message' => "The post {$post->getTitle()} has been updated"
        ];
        $this->notifyChannel($post, $data);

    }


    public function sendDeleteNotification(Post $post) {
        $data = [
            'message' => "The post {$post->getTitle()} has been deleted"
        ];
        $this->notifyChannel($post, $data);

    }

    /**
     * @param Post $post
     * @param $data
     */
    protected function notifyChannel(Post $post, $data)
    {
        $target = ["http://local.dev/posts/{$post->getId()}"];
        $message = new Update("http://local.dev/notifications", json_encode($data), $target);

        $this->messageBus->dispatch($message);
    }
}