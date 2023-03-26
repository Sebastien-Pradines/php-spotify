<?php

namespace TheFeed\Application\API;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TheFeed\Business\Exception\ServiceException;

class MusiqueControllerAPI extends Controller
{
    public function removeMusique($idMusique) {
        $userService = $this->container->get('utilisateur_service');
        $userId = $userService->getUserId();
        $service = $this->container->get('musique_service');
        try {
            $service->removeMusique($userId, $idMusique);
            return new JsonResponse(["result" => true]);
        } catch (ServiceException $exception) {
            return new JsonResponse(["result" => false, "error" => $exception->getMessage()], 400);
        }
    }
}