<?php
namespace App\Controller;

use App\Form\UserType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class DbcreatorController extends AbstractController
{
    public function __construct(
        private Connection $connection
    ) {}

    #[Route('/', name: 'app_dbcreator')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // 1. Création et sélection de la BDD
                $this->connection->executeStatement('CREATE DATABASE IF NOT EXISTS ex02');
                $this->connection->executeStatement('USE ex02');

                // 2. Création de la table
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

                $data = $form->getData();

                // 3. Vérification de l'existence de l'utilisateur
                $userExists = $this->connection->fetchOne(
                    'SELECT id FROM users WHERE username = :username OR email = :email',
                    [
                        'username' => $data['username'],
                        'email'    => $data['email'],
                    ]
                );

                if ($userExists !== false) {
                    return new Response('Le username ou l\'email existe déjà.');
                }

                // 4. Insertion en base de données
                $this->connection->insert('users', [
                    'username'  => $data['username'],
                    'name'      => $data['name'],
                    'email'     => $data['email'],
                    'enable'    => $data['enable'] ? 1 : 0,
                    'birthdate' => $data['birthdate'] ? $data['birthdate']->format('Y-m-d H:i:s') : null,
                    'adresse'   => $data['adresse'],
                ]);

            } catch (DBALException $e) {
                return new Response('Erreur DBAL : ' . $e->getMessage());
            }
        }

        return $this->render('dbcreator/index.html.twig', [
            'controller_name' => 'DbcreatorController',
            'form'            => $form->createView(),
        ]);
    }

    #[Route('/allusers', name: 'app_db')]
    public function db(): Response
    {
        try {
            $this->connection->executeStatement('USE ex02');

            // Récupération de tous les utilisateurs (équivalent à fetchAll(PDO::FETCH_ASSOC))
            $data = $this->connection->fetchAllAssociative('SELECT * FROM users');

        } catch (DBALException $e) {
            return new Response('Erreur lors de la récupération des données : ' . $e->getMessage());
        }

        return $this->render('dbcreator/users.html.twig', [
            'controller_name' => 'DbcreatorController',
            'data'            => $data,
        ]);
    }
}