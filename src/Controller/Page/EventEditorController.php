<?php
namespace App\Controller\Page;

use App\Entity\Event as EventEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EventEditorController extends AbstractController
{
    public function showPage(Request $request): Response
    {
        $eventId = $request->query->get("id");

        $entityManager = $this->getDoctrine()->getManager();

        $event = $entityManager->getRepository(EventEntity::class)->find($eventId);

        $conn = $entityManager->getConnection();
        $query = '
            SELECT evt.start as eventStart, evt.end as eventEnd, reg.id as id, reg.first_name as firstName, reg.last_name as lastName, reg.email as email, reg.phone_number as phoneNumber
            FROM event_registration rgcli
            LEFT JOIN registration reg ON rgcli.id_registration = reg.id
            LEFT JOIN event evt ON rgcli.id_event = evt.id
            WHERE rgcli.id_event = :id_event
            ';
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'id_event' => $event->getId()
        ]);

        return $this->render("page/eventEditor.html.twig",
            [
                "event"             => $event,
                "registrationList"  => $stmt->fetchAllAssociative()
            ]);
    }
}