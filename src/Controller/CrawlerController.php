<?php

namespace App\Controller;

use PharIo\Manifest\Application;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CrawlerController extends AbstractController
{
    #[Route('/crawler', name: 'app_crawler')]
    public function crawler()
    {

        $command = ['php bin/console app:crawl']; // Replace with your command
        $process = new Process($command);
        
        $test = $process->run();
        
        return new Response($test);

        // return $this->render('crawler/index.html.twig', [
        //     'controller_name' => 'CrawlerController',
        // ]);
    }
}
