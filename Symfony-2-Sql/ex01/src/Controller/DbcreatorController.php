<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\User; 

final class DbcreatorController extends AbstractController
{
    #[Route('/', name: 'app_dbcreator')]
    public function index(): Response
    {
         return $this->render('dbcreator/index.html.twig', [
            'controller_name' => 'DbcreatorController',
        ]);
    }
     #[Route('/db-creator',  methods : ['POST'], name: 'app_db'  )]
    public function db(EntityManagerInterface $entityManager ): Response
    {

            $schemaTool = new SchemaTool($entityManager);

            $metadata = [$entityManager->getClassMetadata(User::class)];

            // 3. On génère la table en BDD sans supprimer les tables existantes (paramètre true)
            $schemaTool->updateSchema($metadata, true);
        
        return new Response (" DB créé !") ;
    }
    
}
