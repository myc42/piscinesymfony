<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Persons ;
use App\Entity\Address ;
use App\Entity\BankAccount ;
use App\Repository\PersonsRepository;
use App\Repository\BankAccountRepository;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;











final class Ex09Controller extends AbstractController
{
    #[Route('/', name: 'app_ex09')]
    public function index(): Response
    {
        return $this->render('ex09/index.html.twig', [
            'controller_name' => 'Ex09Controller',
        ]);
    }


    #[Route('/ex09/person/new', name: 'person_new')]
    public function createPerson(EntityManagerInterface $em): Response
    {
        $person = new Persons();
        $person->setUsername('ard');
        $person->setName('aews ver');
        $person->setEmail('john@example.com');
        $person->setEnable(true);
        $person->setBirthdate(new \DateTime('1990-01-01'));
        $person->setMaritalStatus('single');

        $em->persist($person);
        $em->flush();

        return new Response('Personne créée avec succès ! ID: ' . $person->getId());
    }

    #[Route('/ex09/person/{id}/add-relations', name: 'app_relations')]
    public function addRelations(int $id, EntityManagerInterface $em): Response
    {
        $person = $em->getRepository(Persons::class)->find($id);

        if (!$person) {
            return new Response('Personne non trouvée', 404);
        }

        $account = new BankAccount();
        $account->setIban('FR761234567890');
        $account->setPersonId($person);
        $em->persist($account);

        // --- Relation One-to-Many : Address ---
        $address1 = new Address();
        $address1->setStreet('10 Rue de Paris');
        $address1->setPerson($person); // On lie l'adresse à la personne

        $address2 = new Address();
        $address2->setStreet('5 Avenue des Champs');
        $address2->setPerson($person);

        $em->persist($address1);
        $em->persist($address2);

        // On enregistre tout en BDD
        $em->flush();

        return new Response('Compte bancaire et adresses associés à ' . $person->getName());
    }

  #[Route('/ex09/list', name: 'app_list')]
    public function list(
        Request $request,
        EntityManagerInterface $em,
        BankAccountRepository $bankAccountRepo,
        AddressRepository $addressRepo
    ): Response {
        // 1. Récupération des paramètres de filtrage et de tri (Formulaire)
        $search = $request->query->get('search', '');
        $sort = $request->query->get('sort', 'name');
        $direction = $request->query->get('direction', 'ASC') === 'DESC' ? 'DESC' : 'ASC';

        // 2. Requête SQL brute exigée par l'exercice 11 (Jointure, Condition, Tri)
        $connection = $em->getConnection();

        // On fait une jointure (même si on ne retourne que les champs de persons, 
       // Requête SQL brute avec une jointure (address), une condition (WHERE) et un tri (ORDER BY)
        $sql = "SELECT DISTINCT p.* 
                FROM persons p 
                LEFT JOIN address a ON p.id = a.person_id 
                WHERE 1=1";

        $params = [];

        // Condition (WHERE)
        if (!empty($search)) {
            $sql .= " AND p.name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        // Tri (ORDER BY sécurisé)
        $allowedSorts = [
            'name' => 'p.name',
            'email' => 'p.email'
        ];
        $orderBy = $allowedSorts[$sort] ?? 'p.name';

        $sql .= " ORDER BY {$orderBy} {$direction}";

       

        // Exécution de la requête SQL
        $stmt = $connection->prepare($sql);
        $resultSet = $stmt->executeQuery($params);
        $personsArray = $resultSet->fetchAllAssociative();

        // Pour que ton template Twig qui utilise des objets Entity fonctionne, 
        // on récupère les vrais objets Person correspondants aux IDs retournés par le SQL :
        $personRepo = $em->getRepository(Persons::class);
        $persons = [];
        foreach ($personsArray as $row) {
            $persons[] = $personRepo->find($row['id']);
        }

        // On garde tes listes de comptes et adresses pour ton Twig d'origine
        $accounts = $bankAccountRepo->findAll();
        $addresses = $addressRepo->findAll();

        return $this->render('ex09/list.html.twig', [
            'persons' => $persons,
            'accounts' => $accounts,
            'addresses' => $addresses,
            'currentSearch' => $search,
            'currentSort' => $sort,
            'currentDirection' => $direction,
        ]);
    }}