<?php
namespace App\Controller\Page;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function showPage(): Response
    {
        $eventRepository = $this->getDoctrine()
            ->getRepository(Event::class);

        return $this->render("page/index.html.twig",
            [
                "selectedEvent" => $eventRepository->findAll(),
            ]);
    }
}