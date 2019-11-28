<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Service\SwapiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SwaPeopleController extends AbstractController
{
    function list(Request $request, SwapiService $swapiService) {
        $page = \json_decode($request->getContent(), true)['page'] ?? $request->get('page');

        return new JsonResponse(
            $swapiService->searchPeople($page)
        );
    }
}
