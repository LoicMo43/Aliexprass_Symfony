<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property EntityManagerInterface $manager
 */
class ApiController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    #[Route('/api', name: 'api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @param Calendar|null $calendar
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     * @throws JsonException
     * @throws Exception
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request, ManagerRegistry $doctrine): Response
    {
        // On récupère les données
        $donnees = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

        if(
            isset($donnees->title, $donnees->start, $donnees->description, $donnees->backgroundColor, $donnees->borderColor, $donnees->textColor)
            && !empty($donnees->title)
            && !empty($donnees->start)
            && !empty($donnees->description)
            && !empty($donnees->backgroundColor)
            && !empty($donnees->borderColor)
            && !empty($donnees->textColor)
        )
        {
            // Les données sont complètes
            // On initialise un code
            $code = 200;
            //On vérifie si l'id existe
            if (!$calendar) {
                // On instancie
                $calendar = new Calendar;

                // On change le code
                $code = 201;
            }
            // On hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStart(new DateTime($donnees->start));
            if($donnees->allDay) {
                $calendar->setEnd(new DateTime($donnees->start));
            }
            else {
                $calendar->setEnd(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);

            $em = $doctrine->getManager();
            $em->persist($calendar);
            $em->flush();

            return new Response('Ok', $code);
        }
        return new Response('Données incomplètes', 404);
    }
}
