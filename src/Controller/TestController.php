<?php

declare(strict_types=1);

namespace App\Controller;

use Redis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{

    public function __construct(private readonly Redis $redisClient)
    {
    }

    #[Route('/test', name: 'test')]
    public function new(Request $request): Response
    {
//        $this->redisClient->get();

        return new Response();
    }
}
