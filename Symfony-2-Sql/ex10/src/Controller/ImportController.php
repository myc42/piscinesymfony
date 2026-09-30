<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserOrm;
use App\Repository\UserOrmRepository ;


final class ImportController extends AbstractController
{

public function __construct(
        private Connection $connection
    ) {}

   #[Route('/import', name: 'app_import')]
public function index(
    EntityManagerInterface $entityManager, 
    UserOrmRepository $userOrmRepository
): Response {
    $sql = '
        CREATE TABLE IF NOT EXISTS users_sql (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            age INT NOT NULL,
            city VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )
    ';
       
    try {
        // Utilisation de $this->connection
        $this->connection->executeStatement($sql);
    } catch (\Exception $e) {
        // Affiche l'erreur si la création échoue
        dd($e->getMessage()); 
    }

    $file = $this->getParameter('users_file');
    if (!file_exists($file)) {
        throw $this->createNotFoundException("Le fichier spécifié n'existe pas.");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    $data = [];
    foreach ($lines as $line) {
        $data[] = explode(',', $line);
    }

    foreach ($data as $row) {
        $name = $row[0];
        $age = (int) $row[1];
        $city = $row[2];

        try {
            $insertSql = '
                INSERT INTO users_sql (name, age, city)
                VALUES (:name, :age, :city)
            ';

            $this->connection->executeStatement($insertSql, [
                'name' => $name,
                'age' => $age,
                'city' => $city,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        try {
            $user = new UserOrm();
            $user->setName($name);
            $user->setAge($age);
            $user->setCity($city);

            $entityManager->persist($user);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    // Un seul flush à la fin de la boucle est plus performant
    $entityManager->flush();

    $users = $userOrmRepository->findAll();

    return $this->render('import/index.html.twig', [
        'controller_name' => 'ImportController',
        'users' => $users,
    ]);
}
}
