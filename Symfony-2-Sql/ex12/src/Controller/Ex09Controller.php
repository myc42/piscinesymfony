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
        $person->setUsername('re');
        $person->setName('xx ver');
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
        PersonsRepository $personsRepository,
        BankAccountRepository $bankAccountRepo,
        AddressRepository $addressRepo
    ): Response {
        // 1. Récupération et validation basique des paramètres
        $search = trim($request->query->get('search', ''));
        $sort = $request->query->get('sort', 'name');
        
        // Validation stricte de la direction (ASC ou DESC uniquement)
        $direction = strtoupper($request->query->get('direction', 'ASC'));
        if (!in_array($direction, ['ASC', 'DESC'])) {
            $direction = 'ASC';
        }

        // Validation stricte des colonnes de tri autorisées pour éviter les injections
        $allowedSorts = ['name', 'email'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }

        // 2. Utilisation exclusive de l'ORM via le Repository (avec Jointure, Condition et Tri)
        $persons = $personsRepository->findWithSearchAndJoin($search, $sort, $direction);

        // Récupération des comptes et adresses pour le template
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