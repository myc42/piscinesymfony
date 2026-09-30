<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use PDO;



final class DbcreatorController extends AbstractController
{
    #[Route('/', name: 'app_dbcreator')]
    public function index(Request $request): Response
    {

            $form = $this->createForm(UserType::class);
            $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) 
             {
                        try {
                                $pdo = new PDO('mysql:host=127.0.0.1;port=8889','root', 'root' );
                        }
                        catch (PDOException $e) 
                            {
                                    return new Response('Erreur : ' . $e->getMessage());
                            }

                        $createdb = $pdo->exec('CREATE DATABASE IF NOT EXISTS ex06') ;
                        if ($createdb === false) 
                        {
                                echo "Erreur : la base de données n'a pas pu être créée.";           // problème

                        }
                        try {
                                $pdo->exec('USE ex06');

                                $createtable = $pdo->exec('CREATE TABLE IF NOT EXISTS users (
                                    id INT AUTO_INCREMENT PRIMARY KEY,
                                    username VARCHAR(255) UNIQUE,
                                    name VARCHAR(255),
                                    email VARCHAR(255) UNIQUE,
                                    enable TINYINT(1) DEFAULT 1,
                                    birthdate DATETIME,
                                    adresse LONGTEXT
                                ) ') ;
                    } catch (PDOException $e) 
                    {
                                echo "Erreur lors de la création de la table : " . $e->getMessage();
                    }

                    if ($createtable === false) 
                    {
                                            // problème

                    }
                    
                     $data = $form->getData();
                     $username = $data['username'];
                        $name = $data['name'];
                        $email = $data['email'];
                        $enable = $data['enable'];
                        $birthdate = $data['birthdate'];
                        $adresse = $data['adresse'];
                            // Requête SQL


                            $sql = "SELECT id FROM users
        WHERE username = :username
        OR email = :email";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'username' => $username,
    'email' => $email,
]);

$userExists = $stmt->fetch();

if ($userExists) {
    return new Response('Le username ou l\'email existe déjà.');
}

                    $sql = "INSERT INTO users
                    (username, name, email, enable, birthdate, adresse)
                    VALUES
                    (:username, :name, :email, :enable, :birthdate, :adresse)";

                    $stmt = $pdo->prepare($sql);

                    

            // Exécution
            $stmt->execute([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'enable' => $data['enable'] ? 1 : 0,
                'birthdate' => $data['birthdate']->format('Y-m-d H:i:s'),
                'adresse' => $data['adresse'],
            ]);

             }
        
        return $this->render('dbcreator/index.html.twig', [
            'controller_name' => 'DbcreatorController',
               'form' => $form,
        ]);
    }

    #[Route('/allusers',  methods : ['POST'], name: 'app_db'  )]
    public function db(): Response
    {
                     try {
                            $pdo = new PDO('mysql:host=127.0.0.1;port=8889','root', 'root' );
                      }
                        catch (PDOException $e) 
                            {
                                    return new Response('Erreur : ' . $e->getMessage());
                            }
                    $pdo->exec('USE ex06');

                        
            $request = $pdo->query('SELECT * FROM ex06.users');

$data = $request->fetchAll(PDO::FETCH_ASSOC);

        
            return $this->render('dbcreator/users.html.twig', [
                                'controller_name' => 'DbcreatorController',
                                    'data' => $data

                            ]);

        
            }

#[Route('/delete/{id}', methods: ['POST'], name: 'app_delete')]
    public function delete($id): Response
    {
        try {
                            $pdo = new PDO('mysql:host=127.0.0.1;port=8889','root', 'root' );
                      }
                        catch (PDOException $e) 
                            {
                                    return new Response('Erreur : ' . $e->getMessage());
                            }
                    $pdo->exec('USE ex06');
           
$request = $pdo->query("SELECT id FROM users WHERE id = $id");
$user = $request->fetch();
if (!$user) {
    return new Response("Cet utilisateur n'existe pas.");
}
$pdo->query("DELETE FROM users WHERE id = $id");
return new Response("Utilisateur supprimé.");



    }

    #[Route('/update/{id}', methods: ['POST'], name: 'app_update')]
    public function update($id,Request $request): Response
    {

                try {
                                        $pdo = new PDO('mysql:host=127.0.0.1;port=8889','root', 'root' );
                                }
                                    catch (PDOException $e) 
                                        {
                                                return new Response('Erreur : ' . $e->getMessage());
                                        }
                                $pdo->exec('USE ex06');
                    
         
                                /// obliger de le faire en trois etape prepare , execute la requete et recuepre le resultat  
                             $rqt  = $pdo->prepare('SELECT * FROM users WHERE id = :id');
                             $rqt ->execute(['id' => $id]);
                             // Récupérer le résultat si cetait par exemple 7 cest comme si kje faisais SELECT * FROM users WHERE id = 7, Et fetch() transforme cette ligne en tableau PHP :
                             $user = $rqt->fetch(PDO::FETCH_ASSOC);

                             if (!$user) {
        return new Response("Cet utilisateur n'existe pas.");
    }
   







            if (!$user) {
                return new Response("Cet utilisateur n'existe pas.");
            }
$user['enable'] = (bool) $user['enable'];
$user['birthdate'] = new \DateTime($user['birthdate']);

 $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);


return $this->render('dbcreator/update.html.twig', [
            'controller_name' => 'DbcreatorController',
               'form' => $form,
        ]);
            
}  }