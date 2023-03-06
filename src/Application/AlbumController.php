<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
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
}