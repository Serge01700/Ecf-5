<?php
namespace App\Controller;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/meals')]
class MealController extends AbstractController
{
    public function __construct(
        private MealRepository $mealRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('', name: 'meal_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $date = $request->query->get('date', date('Y-m-d'));
        $dateObj = new \DateTime($date);
        
        $meals = $this->mealRepository->findByDate($dateObj);
        
        // Grouper les repas par type
        $groupedMeals = [
            'matinee' => [],
            'dejeuner' => [],
            'gouter' => []
        ];
        
        foreach ($meals as $meal) {
            $groupedMeals[$meal->getType()][] = $meal;
        }
        
        return $this->json($groupedMeals, Response::HTTP_OK, [], ['groups' => 'meal:read']);
    }

    #[Route('', name: 'meal_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $meal = new Meal();
        $meal->setType($data['type'])
             ->setName($data['name'])
             ->setDescription($data['description'] ?? null)
             ->setAgeGroup($data['ageGroup'] ?? null)
             ->setDate(new \DateTime($data['date'] ?? 'today'))
             ->setSortOrder($data['sortOrder'] ?? 0);

        $errors = $this->validator->validate($meal);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($meal);
        $this->entityManager->flush();

        return $this->json($meal, Response::HTTP_CREATED, [], ['groups' => 'meal:read']);
    }

    #[Route('/{id}', name: 'meal_update', methods: ['PUT'])]
    public function update(Request $request, Meal $meal): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $meal->setType($data['type'] ?? $meal->getType())
             ->setName($data['name'] ?? $meal->getName())
             ->setDescription($data['description'] ?? $meal->getDescription())
             ->setAgeGroup($data['ageGroup'] ?? $meal->getAgeGroup())
             ->setSortOrder($data['sortOrder'] ?? $meal->getSortOrder());

        if (isset($data['date'])) {
            $meal->setDate(new \DateTime($data['date']));
        }

        $errors = $this->validator->validate($meal);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return $this->json($meal, Response::HTTP_OK, [], ['groups' => 'meal:read']);
    }

    #[Route('/{id}', name: 'meal_delete', methods: ['DELETE'])]
    public function delete(Meal $meal): JsonResponse
    {
        $this->entityManager->remove($meal);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}


