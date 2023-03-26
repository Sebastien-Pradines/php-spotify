<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TheFeed\Business\Exception\ServiceException;

class AlbumController extends Controller
{
    public function getNewAlbum(){
        return $this->render("Album/new.html.twig");
    }

    public function submitAlbum(Request $request){
        $userService = $this->container->get('utilisateur_service');
        $userId = $userService->getUserId();
        $nom = $request->get("nom");
        $albumImg = $request->files->get("albumImg");
        $albumService = $this->container->get('album_service');
        try {
            $albumService->createAlbum($nom, $albumImg, $userId);
            $this->addFlash("success","Nouvel album posté!");
        }
        catch(ServiceException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('feed');
    }

    public function pageAlbum($idAlbum){
        $albumService = $this->container->get('album_service');
        $musiquesService = $this->container->get('musique_service');
        try {
            $musiques = $musiquesService->getMusiquesFromAlbum($idAlbum);
            $album = $albumService->getAlbum($idAlbum);
            return $this->render("Album/show.html.twig", [
                "musiques" => $musiques,
                "album" => $album]);
        }
        catch (ServiceException $exception) {
            throw new ResourceNotFoundException();
        }
    }
}