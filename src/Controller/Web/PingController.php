<?php

namespace App\Controller\Web;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PingController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     */
    public function index() {

        $response = new Response();
        $response->setContent('Hello World !');
        return $response;

    }

    /**
     * @Route("/ping", methods={"POST"})
     *
     */
    public function ping(MessageBusInterface $bus) {

        $update = new Update("http://local.dev/ping", "[]");

        $bus->dispatch($update);

        return $this->redirectToRoute('home');
    }

}