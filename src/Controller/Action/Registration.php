<?php
namespace App\Controller\Action;

use App\Entity\Event as EventEntity;
use App\Entity\Registration as RegistrationEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Registration extends AbstractController
{
    public function create(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $eventId = $request->query->get("eventId");
        $requestedRegistration = $request->request->get("registration");

        $entityManager = $this->getDoctrine()->getManager();

        $event = NULL;
        if($eventId)
        {
            $event = $entityManager->getRepository(EventEntity::class)->find($eventId);
        }

        if(isset($event))
        {
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

            if($stmt->rowCount()<5)
            {
                $registration = new RegistrationEntity();
                $registration->setFirstName($requestedRegistration["firstName"]);
                $registration->setLastName($requestedRegistration["lastName"]);
                $registration->setEmail($requestedRegistration["email"]);
                $registration->setPhoneNumber($requestedRegistration["phoneNumber"]);
                $entityManager->persist($registration);
                $entityManager->flush();

                $conn = $entityManager->getConnection();
                $query = 'INSERT INTO event_registration(id_event, id_registration) VALUES(:id_event, :id_registration)';
                $stmt = $conn->prepare($query);
                $stmt->execute([
                    'id_registration'   => $registration->getId(),
                    'id_event'          => $eventId
                ]);
            }

            $action = $this->redirectToRoute("indexPage");
        }
        else $action = $this->redirectToRoute("indexPage");

        return $action;
    }
}