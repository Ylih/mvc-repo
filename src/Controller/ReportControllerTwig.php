<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/credit", name: "credit")]
    public function credit(): Response
    {
        return $this->render('credit.html.twig');
    }

    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 4);
        $image = ["img/me.png", "img/glider.svg", "img/scenery.jpg", "img/shamrock.svg", "img/crown-coin.svg"];
        $path = $image[$number];

        $data = [
            'number' => $number + 1,
            'image' => $path
        ];

        return $this->render('lucky.html.twig', $data);
    }
}
