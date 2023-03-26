<?php

namespace TheFeed\Application\API;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TheFeed\Business\Exception\ServiceException;

class AlbumControllerAPI extends Controller
{
    public function removeAlbum($idAlbum) {
        $userService = $this->container->get('utilisateur_service');
        $userId = $userService->getUserId();
        $service = $this->container->get('album_service');
        try {
            $service->removeAlbum($userId, $idAlbum);
            return new JsonResponse(["result" => true]);
        } catch (ServiceException $exception) {
            return new JsonResponse(["result" => false, "error" => $exception->getMessage()], 400);
        }
    }
}