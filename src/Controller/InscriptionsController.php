<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InscriptionsController extends AbstractController
{
    #[Route('/inscriptions', name: 'app_inscriptions', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription, [
            'action' => $this->generateUrl('app_inscriptions'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $em->persist($inscription);
                    $em->flush();

                    $this->addFlash('success', "Votre inscription a bien été enregistrée.");
                    return $this->redirectToRoute('app_inscriptions');
                } catch (UniqueConstraintViolationException $e) {
                    $this->addFlash('error', "Cet email est déjà inscrit.");
                } catch (\Throwable $e) {
                    $this->addFlash('error', "Une erreur est survenue. Veuillez réessayer.");
                }
            } else {
                $this->addFlash('error', "Veuillez corriger les erreurs du formulaire.");
            }
        }

        return $this->render('inscriptions/index.html.twig', [
            'form' => $form,
        ]);
    }
}
