<?php
namespace App\Controller\Page;

use App\Entity\Event as EventEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegistrationEditorController extends AbstractController
{
    public function showPage(Request $request): Response
    {
        $eventId = $request->query->get("eventId");

        $entityManager = $this->getDoctrine()->getManager();

        $event = $entityManager->getRepository(EventEntity::class)->find($eventId);

        $conn = $entityManager->getConnection();
        $query = '
            SELECT reg.first_name as firstName, reg.last_name as lastName, reg.email as email, reg.phone_number as phoneNumber
            FROM event_registration rgcli
            LEFT JOIN registration reg on rgcli.id_registration = reg.id
            WHERE rgcli.id_event = :id_event
            ';
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'id_event' => $event->getId()
        ]);

        return $this->render("page/registrationEditor.html.twig",
            [
                "event"             => $event,
                "registrationList"  => $stmt->fetchAllAssociative(),
                "isEventFull"       => $stmt->rowCount()>=5
            ]);
    }
}