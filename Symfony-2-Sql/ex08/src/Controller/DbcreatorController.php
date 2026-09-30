<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PDO;
use PDOException;
use Doctrine\DBAL\Connection;

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

    #[Route('/db-creator', methods: ['GET'], name: 'app_db')]
    public function db(): Response
    {
        $sql = "CREATE TABLE IF NOT EXISTS persons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE,
            name VARCHAR(255),
            email VARCHAR(255) UNIQUE,
            enable TINYINT(1) DEFAULT 1,
            birthdate DATETIME
        )";

        try {
            $this->connection->executeStatement($sql);
            return new Response('Succès : Table persons créée avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de la création de persons : ' . $e->getMessage());
        }
    }

     #[Route('/alter-marital', name: 'app_alter_marital')]
    public function alterMarital(): Response
    {
        $sql = "ALTER TABLE persons ADD COLUMN marital_status ENUM('single', 'married', 'widower')";

        try {
            $this->connection->executeStatement($sql);
            return new Response('Succès : Colonne marital_status ajoutée !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'ajout de la colonne : ' . $e->getMessage());
        }
    }

    #[Route('/create-tables', name: 'app_create_tables')]
    public function createOtherTables(): Response
    {
        $sqlAddresses = "CREATE TABLE IF NOT EXISTS addresses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            street VARCHAR(255),
            city VARCHAR(255),
            person_id INT
        )";

        $sqlBank = "CREATE TABLE IF NOT EXISTS bank_accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            account_number VARCHAR(255) UNIQUE,
            person_id INT UNIQUE
        )";

        try {
            $this->connection->executeStatement($sqlAddresses);
            $this->connection->executeStatement($sqlBank);
            return new Response('Succès : Tables addresses et bank_accounts créées !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de la création des tables : ' . $e->getMessage());
        }
    }

    #[Route('/create-relations', name: 'app_create_relations')]
    public function createRelations(): Response
    {
       try {
            // Vérifier si la contrainte de clé étrangère existe déjà
            $checkFk = "SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = DATABASE() 
                        AND CONSTRAINT_NAME = 'fk_address_person'";

            $exists = $this->connection->fetchOne($checkFk);

            if ($exists > 0) {
                $message = "Info : Les relations (Foreign Keys) ont déjà été créées.";
            } else {
                // Clé étrangère 1:N (addresses -> persons)
                $sqlFk1 = "ALTER TABLE addresses 
                           ADD CONSTRAINT fk_address_person 
                           FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE";

                // Clé étrangère 1:1 (bank_accounts -> persons)
                $sqlFk2 = "ALTER TABLE bank_accounts 
                           ADD CONSTRAINT fk_bank_person 
                           FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE";

                $this->connection->executeStatement($sqlFk1);
                $this->connection->executeStatement($sqlFk2);

                $message = "Succès : Relations 1:1 et 1:N créées avec succès !";
            }
        } catch (\Exception $e) {
            $message = "Erreur lors de la création des relations : " . $e->getMessage();
        }

        return $this->render('dbcreator/index.html.twig', ['message' => $message]);
    }
}
