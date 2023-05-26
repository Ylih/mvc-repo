<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectControllerTwig extends AbstractController
{
    #[Route("/proj", name: "project")]
    public function project(): Response
    {
        return $this->render('proj/project.html.twig');
    }

    #[Route("/proj/about", name: "project_about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }
}
