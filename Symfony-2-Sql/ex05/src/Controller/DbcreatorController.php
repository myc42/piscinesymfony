<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;



final class DbcreatorController extends AbstractController
{
    #[Route('/',  name: 'app_dbcreator')]
    public function index(EntityManagerInterface $EntityManagerInterface,  Request $request, UserRepository $UserRepository, ): Response
    {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $email = $user->getEmail();
                $username = $user->getUsername();
            
                if ($UserRepository->existsByEmailOrUsername($email, $username)) {
                     return $this->redirectToRoute('app_dbcreator');

                }
                $EntityManagerInterface->persist($user);
                $EntityManagerInterface->flush();
                return $this->redirectToRoute('app_dbcreator');
            }



        return $this->render('dbcreator/index.html.twig', [
            'controller_name' => 'DbcreatorController',
             'form' => $form,
        ]);
    }

    #[Route('/show',  name: 'app_show')]
    public function show(UserRepository $UserRepository): Response
    {
        $users = $UserRepository->findAll();
        return $this->render('dbcreator/show.html.twig', [
            'controller_name' => 'DbcreatorController',
            'users' => $users,
        ]);
    }

     #[Route('/delete/{id}', methods: ['POST'],  name: 'app_delete')]
    public function delete(UserRepository $UserRepository , $id, EntityManagerInterface  $EntityManagerInterface): Response
    {
        $user = $UserRepository->findOneBy(['id' => $id]);
        if ($user === null)
            {
                return new Response("Utilisateur inexistant.");
            }
        $EntityManagerInterface->remove($user);
        $EntityManagerInterface->flush();
        return new Response("Utilisateur supprimé.");
    }

   
     
}

