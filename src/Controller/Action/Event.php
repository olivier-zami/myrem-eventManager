<?php
namespace App\Controller\Action;

use App\Entity\Event as EventEntity;
use App\Entity\Registration as RegistrationEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Event extends AbstractController
{
    public function addRegistration(Request $request)
    {
        $requestedEventId = $request->query->get("id");
        $requestedEvent = $request->request->get("event");
        $requestedRegistration = $request->request->get("registration");

        $entityManager = $this->getDoctrine()->getManager();

        if(!isset($eventId) || !$eventId)
        {
            $dateStart = new \DateTime($requestedEvent["start"]);
            $dateEnd =  new \DateTime($requestedEvent["end"]);
            $event = new EventEntity();
            $event->setStart($dateStart);
            $event->setEnd($dateEnd);
            $entityManager->persist($event);
            $entityManager->flush();
        }
        return $this->redirectToRoute("indexPage");
    }

    public function update(Request $request): ?\Symfony\Component\HttpFoundation\RedirectResponse
    {
        $eventId = $request->query->get("id");
        $subject = $request->query->get("subject");
        $dataEvent = $request->request->get("event");
        $dataRegistration = $request->request->get("registration");

        $entityManager = $this->getDoctrine()->getManager();

        $action = NULL;
        $event = NULL;
        $event = $entityManager->find(EventEntity::class, $eventId);

        switch($subject)
        {
            case "event":
                if($event)
                {
                    if(isset($dataEvent["update"]["start"]))
                    {
                        $dateStart = new \DateTime($dataEvent["start"]);
                        $event->setStart($dateStart);
                        $entityManager->persist($event);
                        $entityManager->flush();
                    }
                    elseif(isset($dataEvent["update"]["end"]))
                    {
                        $dateEnd = new \DateTime($dataEvent["end"]);
                        $event->setEnd($dateEnd);
                        $entityManager->persist($event);
                        $entityManager->flush();
                    }
                    else{}
                }
                $action = $this->redirect("/eventEditor?id=".$event->getId());
                break;
            case "registration":
                reset($dataRegistration["id"]);
                $registrationId = key($dataRegistration["id"]);
                $registration = $entityManager->find(RegistrationEntity::class, $registrationId);
                $registration->setFirstName($dataRegistration[$registrationId]["firstName"]);
                $registration->setLastName($dataRegistration[$registrationId]["lastName"]);
                $registration->setEmail($dataRegistration[$registrationId]["email"]);
                $registration->setPhoneNumber($dataRegistration[$registrationId]["phoneNumber"]);
                $entityManager->persist($registration);
                $entityManager->flush();
                $action = $this->redirect("/eventEditor?id=".$event->getId());
                break;
        }
        return $action;
    }

    public function delete(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $eventId = $request->query->get("id");

        $entityManager = $this->getDoctrine()->getManager();

        $event = NULL;
        if($eventId)
        {
            $event = $entityManager->find(EventEntity::class, $eventId);

            if($event)
            {
                $entityManager->remove($event);
                $entityManager->flush();
                $action = $this->redirectToRoute("indexPage");
            }
            else
            {
                $action = $this->redirectToRoute("indexPage");
            }
        }
        else
        {
            $action = $this->redirectToRoute("indexPage");
        }

        return $action;
    }
}