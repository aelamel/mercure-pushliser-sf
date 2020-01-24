<?php

namespace App\Service;


use App\Entity\Post;
use JMS\Serializer\SerializerInterface;
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

    /** @var SerializerInterface */
    private $serializer;

    /**
     * PostNotificationService constructor.
     * @param MessageBusInterface $bus
     * @param SerializerInterface $serializer
     * @param $mercureConfig
     */
    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer, $mercureConfig)
    {
        $this->messageBus = $bus;
        $this->mercureConfig = $mercureConfig;
        $this->serializer = $serializer;
    }

    /**
     * @param Post $post
     */
    public function sendUpdateNotification(Post $post) {
        $data = [
            'message' => "The post '{$post->getTitle()}' has been updated",
            'target' => 'post',
            'payload' => $post
        ];
        $this->notifyChannel($post, $data);

    }


    /**
     * @param Post $post
     */
    public function sendDeleteNotification(Post $post) {
        $data = [
            'message' => "The post '{$post->getTitle()}' has been deleted",
            'target' => 'post',
            'payload' => $post
        ];
        $this->notifyChannel($post, $data);

    }

    /**
     * @param Post $post
     * @param $data
     */
    protected function notifyChannel(Post $post, $data)
    {
        $jsonContent = $this->serializer->serialize($data, 'json');

        $target = [$this->mercureConfig['target_base_url'] . $this->mercureConfig['posts_target_path']. '/' .$post->getId()];
        $message = new Update($this->mercureConfig['target_base_url']. $this->mercureConfig['notification_channel_path'] , $jsonContent, $target);

        $this->messageBus->dispatch($message);
    }
}
