<?php

namespace App\Controller;

use App\Form\EditPhotoType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     *  Contrôleur de la page d'accueil
     */
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
    return $this->render('main/home.html.twig',);
    }

    /**
     *  Contrôleur de la page "mon profil"
     *
     * Accès réservé aux connectés (ROLE_USER)
     */
    #[Route('/mon-profil/', name: 'main_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {

        return $this->render('main/profile.html.twig',);
    }

    /**
     *  Contrôleur de la page de modification de la photo de profil
     */
    #[Route('/changer-photo-de-profil/', name: 'main_edit_photo')]
    #[IsGranted('ROLE_USER')]
    public function editPhoto(Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(EditPhotoType::class);

        $form->handleRequest($request);

        // Si le form a été envoyé et sans erreur
        if($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();

            $newFileName = 'user' . $this->getUser()->getId() . '.' . $photo->guessExtension();

            // Mise à jour du nom de la photo du compte connecté
            $this->getUser()->setPhoto($newFileName);
            $em = $doctrine->getManager();
            $em->flush();

            // Sauvegarde physique de la photo côté serveur
            $photo->move(
                $this->getParameter('app.user.photo.directory'),
                $newFileName
            );

            // Message de succès
            $this->addFlash('success', ' Photo de profil modifiée avec succès !');

            // Redirection vers la page profil
            return $this->redirectToRoute('main_profile');

        }

        return $this->render('main/edit_photo.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
