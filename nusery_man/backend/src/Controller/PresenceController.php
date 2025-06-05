<?php

namespace App\Controller\Api;

use App\Entity\Child;
use App\Entity\Presence;
use App\Repository\ChildRepository;
use App\Repository\PresenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/presences', name: 'api_presences_')]
class PresenceApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PresenceRepository $presenceRepository;
    private ChildRepository $childRepository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        PresenceRepository $presenceRepository,
        ChildRepository $childRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->presenceRepository = $presenceRepository;
        $this->childRepository = $childRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * Récupère les présences d'un enfant pour un mois donné
     */
    #[Route('/{childId}', name: 'get', methods: ['GET'])]
    public function getPresences(int $childId, Request $request): JsonResponse
    {
        $child = $this->childRepository->find($childId);
        if (!$child) {
            return new JsonResponse(['error' => 'Enfant non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $year = $request->query->getInt('year', date('Y'));
        $month = $request->query->getInt('month', date('n'));

        // Validation des paramètres
        if ($month < 1 || $month > 12) {
            return new JsonResponse(['error' => 'Mois invalide'], Response::HTTP_BAD_REQUEST);
        }

        if ($year < 2020 || $year > 2030) {
            return new JsonResponse(['error' => 'Année invalide'], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Récupérer les présences du mois
            $presences = $this->presenceRepository->findByChildAndMonth($child, $year, $month);
            
            // Formater les données pour le frontend
            $selectedDays = [];
            $totalHours = 0;

            foreach ($presences as $presence) {
                $date = $presence->getDate();
                $dayKey = sprintf('%d-%d-%d', $date->format('Y'), $date->format('n'), $date->format('j'));
                $selectedDays[] = $dayKey;
                $totalHours += $presence->getHours();
            }

            return new JsonResponse([
                'selectedDays' => $selectedDays,
                'totalHours' => $totalHours,
                'month' => $month,
                'year' => $year
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la récupération des données'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Sauvegarde les présences d'un enfant pour un mois donné
     */
    #[Route('/{childId}', name: 'save', methods: ['POST'])]
    public function savePresences(int $childId, Request $request): JsonResponse
    {
        $child = $this->childRepository->find($childId);
        if (!$child) {
            return new JsonResponse(['error' => 'Enfant non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        
        if (!$data) {
            return new JsonResponse(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
        }

        $year = $data['year'] ?? date('Y');
        $month = $data['month'] ?? date('n');
        $selectedDays = $data['selectedDays'] ?? [];
        $hoursPerDay = $data['hoursPerDay'] ?? 8; // Heures par défaut par jour

        // Validation
        if ($month < 1 || $month > 12) {
            return new JsonResponse(['error' => 'Mois invalide'], Response::HTTP_BAD_REQUEST);
        }

        if ($year < 2020 || $year > 2030) {
            return new JsonResponse(['error' => 'Année invalide'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->entityManager->beginTransaction();

            // Supprimer les anciennes présences du mois
            $this->presenceRepository->deleteByChildAndMonth($child, $year, $month);

            // Créer les nouvelles présences
            foreach ($selectedDays as $dayKey) {
                $parts = explode('-', $dayKey);
                if (count($parts) !== 3) {
                    continue;
                }

                $dayYear = (int) $parts[0];
                $dayMonth = (int) $parts[1];
                $day = (int) $parts[2];

                // Vérifier que le jour appartient bien au mois demandé
                if ($dayYear !== $year || $dayMonth !== $month) {
                    continue;
                }

                // Créer la date
                $date = \DateTime::createFromFormat('Y-n-j', sprintf('%d-%d-%d', $dayYear, $dayMonth, $day));
                if (!$date) {
                    continue;
                }

                // Créer la présence
                $presence = new Presence();
                $presence->setChild($child);
                $presence->setDate($date);
                $presence->setHours($hoursPerDay);
                $presence->setCreatedAt(new \DateTime());
                $presence->setUpdatedAt(new \DateTime());

                // Valider l'entité
                $errors = $this->validator->validate($presence);
                if (count($errors) > 0) {
                    throw new \Exception('Données de présence invalides');
                }

                $this->entityManager->persist($presence);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return new JsonResponse([
                'success' => true,
                'message' => 'Présences sauvegardées avec succès',
                'count' => count($selectedDays)
            ]);

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return new JsonResponse([
                'error' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Supprime toutes les présences d'un enfant pour un mois donné
     */
    #[Route('/{childId}', name: 'delete', methods: ['DELETE'])]
    public function deletePresences(int $childId, Request $request): JsonResponse
    {
        $child = $this->childRepository->find($childId);
        if (!$child) {
            return new JsonResponse(['error' => 'Enfant non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $year = $request->query->getInt('year', date('Y'));
        $month = $request->query->getInt('month', date('n'));

        try {
            $deleted = $this->presenceRepository->deleteByChildAndMonth($child, $year, $month);
            
            return new JsonResponse([
                'success' => true,
                'message' => sprintf('%d présence(s) supprimée(s)', $deleted)
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de la suppression'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Statistiques des présences pour un enfant
     */
    #[Route('/{childId}/stats', name: 'stats', methods: ['GET'])]
    public function getStats(int $childId, Request $request): JsonResponse
    {
        $child = $this->childRepository->find($childId);
        if (!$child) {
            return new JsonResponse(['error' => 'Enfant non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $year = $request->query->getInt('year', date('Y'));

        try {
            $stats = $this->presenceRepository->getYearlyStats($child, $year);
            
            return new JsonResponse($stats);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de la récupération des statistiques'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}