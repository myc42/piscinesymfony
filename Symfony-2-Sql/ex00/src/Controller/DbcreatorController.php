<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PDO;
use PDOException;

final class DbcreatorController extends AbstractController
{
    #[Route('/', name: 'app_dbcreator')]
    public function index(): Response
    {
        
        return $this->render('dbcreator/index.html.twig', [
            'controller_name' => 'DbcreatorController',
        ]);
    }

    #[Route('/db-creator', methods: ['POST'], name: 'app_db')]
    public function db(): Response
    {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;port=8889', 'root', 'root');
        } catch (PDOException $e) {
            return new Response('Erreur : ' . $e->getMessage());
        }

        $createdb = $pdo->exec('CREATE DATABASE IF NOT EXISTS ma_base');

        if ($createdb === false) {
            return new Response('Erreur : la base de données n\'a pas pu être créée.');
        }

        $pdo->exec('USE ma_base');

        try {
            $createtable = $pdo->exec('CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) UNIQUE,
                name VARCHAR(255),
                email VARCHAR(255) UNIQUE,
                enable TINYINT(1) DEFAULT 1,
                birthdate DATETIME,
                adresse LONGTEXT
            )');
        } catch (PDOException $e) {
            return new Response('Erreur lors de la création de la table : ' . $e->getMessage());
        }

        if ($createtable === false) {
            return new Response('Erreur : la table n\'a pas pu être créée.');
        }

        return new Response('DB créée !');
    }
}
