<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\NoteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

final class NotesController extends AbstractController
{
    #[Route('/e02', name: 'app_notes')]
    public function index(Request $request): Response
    {


    //$file = $this->getParameter('app.notes_filename');
  $filename = $this->getParameter('app.notes_filename');
    $file = $this->getParameter('kernel.project_dir') . '/' . $filename;
    //Crée-moi un formulaire basé sur mon NoteType.
    $form = $this->createForm(NoteType::class);
    //vérifies à chaque appel du contrôleur si cette requête correspond à une soumission.
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) 
    {
        if (!file_exists($file)) {
            touch($file);
        }

        $data = $form->getData();

        $message = $data['message'];

        if ($data['includeTimestamp'] === 'Yes') {
            $line = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
        } else {
            $line = $message . PHP_EOL;
        }

        file_put_contents(
            $file,
            $line,
            FILE_APPEND
        );
    }
$lastLine = null;
if (file_exists($file) && filesize($file) > 0) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!empty($lines)) {
        $lastLine = end($lines);
    }
}

    return $this->render('notes/index.html.twig', [
            'controller_name' => 'NotesController',
            'form' => $form,
            'last_line' => $lastLine,
        ]);
    }
}
