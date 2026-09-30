<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;

final class Exercise14Controller extends AbstractController
{
    #[Route('/', name: 'app_exercise14')]
    public function index(Request $request, Connection $connection): Response
    {
        $tableName = 'evvvvvv';
        $schemaManager = $connection->createSchemaManager();
        $tables = $schemaManager->listTableNames();

        $tableExists = in_array($tableName, $tables);

        if (!$tableExists) {
            $sql = "CREATE TABLE $tableName (
                id INT AUTO_INCREMENT PRIMARY KEY,
                content VARCHAR(255) NOT NULL
            )";
            $connection->executeStatement($sql);
            $tableExists = true; 
            $message = "Table does not exist";
        } else {
            $message = "Table exists";
        }

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');
            
            $sqlInsert = "INSERT INTO $tableName (content) VALUES ('" . $content . "')";
            $connection->executeStatement($sqlInsert);
        }

        // Récupérer les données pour les afficher (preuve de l'injection)
        $rows = $connection->fetchAllAssociative("SELECT * FROM $tableName");

        return $this->render('exercise14/index.html.twig', [
            'table_message' => $tableExists ? "Table exists" : "Table does not exist",
            'rows' => $rows,
        ]);
    }
}