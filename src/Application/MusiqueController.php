<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TheFeed\Business\Exception\ServiceException;

class MusiqueController extends Controller
{
    public function getNewMusique(){
        $userService = $this->container->get('utilisateur_service');
        $albumService = $this->container->get('album_service');
        $userId = $userService->getUserId();
        $user = $userService->getUtilisateur($userId);
        $albums = $albumService->getAlbumsFrom($userId);
        return $this->render("Musique/new.html.twig", ["albums" => $albums]);
    }

    public function submitMusique(Request $request){
        $userService = $this->container->get('utilisateur_service');
        $userId = $userService->getUserId();
        $titre = $request->get("titre");
        $idAlbum = $request->get("album");
        $musique = $request->files->get("musique");
        $musiqueService = $this->container->get('musique_service');
        try {
            $createdMusique = $musiqueService->createMusique($titre, $musique, $userId);
            if($idAlbum != "default"){
                $musiqueService->setAlbum($idAlbum,$createdMusique->getIdMusique());
            }
            $this->addFlash("success","Nouvelle musique postée!");
        }
        catch(ServiceException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('feed');
    }

    public function pageMusique($idMusique){
        $albumService = $this->container->get('album_service');
        $musiquesService = $this->container->get('musique_service');
        try {
            $musique = $musiquesService->getMusique($idMusique);
            $album = $albumService->getAlbumFromMusique($idMusique);
            return $this->render("Musique/show.html.twig", [
                "musique" => $musique,
                "album" => $album]);
        }
        catch (ServiceException $exception) {
            throw new ResourceNotFoundException();
        }
    }
}