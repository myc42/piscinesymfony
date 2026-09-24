<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticlesController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->redirectToRoute('articles');
    }
    
    #[Route('/e01', name: 'articles')]
    public function articles(): Response
    {
        $articles = $this->getParameter('app.articles');

        return $this->render('articles/index.html.twig', [
            'articles' => $articles 
        ]);
    }

    #[Route('/e01/{article}', name: 'aoc')]
    public function aoc(string $article): Response
    {
        $articles = $this->getParameter('app.articles');

        foreach ($articles as $item) {
            if ($item['article'] === $article) {
                return $this->render('articles/articlesauchoix.html.twig', [
                    'article' => $item
                ]);
            }
        }
        return $this->redirectToRoute('articles');
    }

   
}