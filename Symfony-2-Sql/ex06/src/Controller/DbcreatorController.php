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
                // Initialisation BDD et Table
                $this->connection->executeStatement('CREATE DATABASE IF NOT EXISTS ex06');
                $this->connection->executeStatement('USE ex06');

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

                // Vérification unicité
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

                // Insertion
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

    #[Route('/allusers', methods: ['GET', 'POST'], name: 'app_db')]
    public function db(): Response
    {
        try {
            $this->connection->executeStatement('USE ex06');
            $data = $this->connection->fetchAllAssociative('SELECT * FROM users');
        } catch (DBALException $e) {
            return new Response('Erreur : ' . $e->getMessage());
        }

        return $this->render('dbcreator/users.html.twig', [
            'controller_name' => 'DbcreatorController',
            'data'            => $data,
        ]);
    }

    #[Route('/delete/{id}', methods: ['POST'], name: 'app_delete')]
    public function delete(int $id): Response
    {
        try {
            $this->connection->executeStatement('USE ex06');

            $user = $this->connection->fetchOne('SELECT id FROM users WHERE id = :id', ['id' => $id]);

            if ($user === false) {
                return new Response("Cet utilisateur n'existe pas.");
            }

            $this->connection->delete('users', ['id' => $id]);

            return new Response("Utilisateur supprimé.");

        } catch (DBALException $e) {
            return new Response('Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    #[Route('/update/{id}', methods: ['GET', 'POST'], name: 'app_update')]
    public function update(int $id, Request $request): Response
    {
        try {
            $this->connection->executeStatement('USE ex06');

            // Récupération de l'utilisateur sous forme de tableau associatif
            $user = $this->connection->fetchAssociative('SELECT * FROM users WHERE id = :id', ['id' => $id]);

            if (!$user) {
                return new Response("Cet utilisateur n'existe pas.");
            }

            // Normalisation des types pour le formulaire Symfony
            $user['enable'] = (bool) $user['enable'];
            if (!empty($user['birthdate'])) {
                $user['birthdate'] = new \DateTime($user['birthdate']);
            }

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            // Traitement de la soumission de la mise à jour
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $this->connection->update(
                    'users',
                    [
                        'username'  => $data['username'],
                        'name'      => $data['name'],
                        'email'     => $data['email'],
                        'enable'    => $data['enable'] ? 1 : 0,
                        'birthdate' => $data['birthdate'] ? $data['birthdate']->format('Y-m-d H:i:s') : null,
                        'adresse'   => $data['adresse'],
                    ],
                    ['id' => $id] // Clause WHERE id = $id
                );

                return new Response("Utilisateur mis à jour avec succès !");
            }

        } catch (DBALException $e) {
            return new Response('Erreur : ' . $e->getMessage());
        }

        return $this->render('dbcreator/update.html.twig', [
            'controller_name' => 'DbcreatorController',
            'form'            => $form->createView(),
        ]);
    }
}