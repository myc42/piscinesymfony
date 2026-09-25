<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ColorController extends AbstractController
{
    #[Route('/', name: 'app_color')]
    public function index(): Response
    {

        $nbLignes = $this->getParameter('e03.number_of_colors');
        $lignes = [];
        for ($i = 0; $i < $nbLignes; $i++) {
            $intensite = $i * 20;
            $lignes[] = [
                'noir'  => "rgb($intensite, $intensite, $intensite)",
                'rouge' => "rgb($intensite, 0, 0)",
                'bleu'  => "rgb(0, 0, $intensite)",
                'vert'  => "rgb(0, $intensite, 0)",
            ];
        }
        return $this->render('color/index.html.twig', [
            'controller_name' => 'ColorController',
            'lignes' => $lignes
        ]);
    }
}
