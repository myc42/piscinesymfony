<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeController extends AbstractController
{
    // READ : liste
    #[Route('/', name: 'app_employee')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    // CREATE
    #[Route('/new', name: 'app_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();

            $this->addFlash('success', 'Employé créé avec succès.');
            return $this->redirectToRoute('app_employee');
        }

        if ($form->isSubmitted()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs.');
        }

        return $this->render('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    // UPDATE
    #[Route('/{id}/edit', name: 'app_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if (!$employee) {
            $this->addFlash('error', 'Employé introuvable.');
            return $this->redirectToRoute('app_employee');
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Employé modifié avec succès.');
            return $this->redirectToRoute('app_employee');
        }

        if ($form->isSubmitted()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs.');
        }

        return $this->render('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    // DELETE
    #[Route('/{id}/delete', name: 'app_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, ?Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if (!$employee) {
            $this->addFlash('error', 'Employé introuvable, impossible de le supprimer.');
            return $this->redirectToRoute('app_employee');
        }

        if (!$this->isCsrfTokenValid('delete' . $employee->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Token de sécurité invalide.');
            return $this->redirectToRoute('app_employee');
        }

        if (!$employee->getSubordinates()->isEmpty()) {
            $this->addFlash('error', 'Impossible : cet employé est le manager d\'autres employés.');
            return $this->redirectToRoute('app_employee');
        }

        $entityManager->remove($employee);
        $entityManager->flush();

        $this->addFlash('success', 'Employé supprimé avec succès.');
        return $this->redirectToRoute('app_employee');
    }
}