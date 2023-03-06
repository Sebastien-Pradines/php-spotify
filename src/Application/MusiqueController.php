<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class MusiqueController extends Controller
{
    public function getNewMusique(){
        return $this->render("Musique/new.html.twig");
    }

    public function submitMusique(Request $request){
        $userService = $this->container->get('utilisateur_service');
        $userId = $userService->getUserId();
        $titre = $request->get("titre");
        $musique = $request->files->get("musique");
        $musiqueService = $this->container->get('musique_service');
        try {
            $musiqueService->createMusique($titre, $musique, $userId);
            $this->addFlash("success","Nouvelle musique postée!");
        }
        catch(ServiceException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('feed');
    }
}