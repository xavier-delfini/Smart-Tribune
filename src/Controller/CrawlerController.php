<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CrawlerController extends AbstractController
{
    #[Route('/crawler', name: 'app_crawler')]
    public function crawler()
    {

        $command = ['php bin/console app:crawl']; // Replace with your command
        $process = new Process($command);
        
        $result = $process->run();
        
        return new Response($result);
    }
}
