<?php
namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DbcreatorController extends AbstractController
{
    public function __construct(
        private Connection $connection
    ) {}

    #[Route('/', name: 'app_dbcreator')]
    public function index(): Response
    {
        return $this->render('dbcreator/index.html.twig', [
            'controller_name' => 'DbcreatorController',
        ]);
    }

    #[Route('/db-creator', name: 'app_db')]
    public function db(): Response
    {
        try {
            // Création de la base de données
            $this->connection->executeStatement('CREATE DATABASE IF NOT EXISTS ex00');

            // Sélection de la base de données
            $this->connection->executeStatement('USE ex00');

            // Création de la table
            $this->connection->executeStatement('
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) UNIQUE,
                    name VARCHAR(255),
                    email VARCHAR(255) UNIQUE,
                    enable TINYINT(1) DEFAULT 1,
                    birthdate DATETIME,
                    adresse LONGTEXT
                )
            ');
        } catch (DBALException $e) {
            return new Response('Erreur DBAL : ' . $e->getMessage());
        }

        return new Response('DB créée !');
    }
}