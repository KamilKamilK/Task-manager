<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\AddTaskFormType;
use App\Form\SearchFormType;
use App\Services\TaskService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private TaskService $service;

    public function __construct(TaskService $service)
    {

        $this->service = $service;
    }

    #[Route('/', name: 'app_homepage')]
    #[IsGranted("ROLE_USER")]
    public function index(): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $user = $this->getUser();

        try {
            $userTasks = $this->service->getUserTasks($user);
        } catch (\Exception) {
            throw $this->createNotFoundException();
        }



        return $this->render('tasks/searchList/index.html.twig', [
            'tasks' => $userTasks,
            'form' => $form->createView()
        ]);
    }

    #[Route('/tasks', name: 'app_tasks')]
    #[IsGranted("ROLE_USER")]
    public function getTasks(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!empty($request->request->all()['search_form'])) {
            $params = $request->request->all()['search_form'];
            try {
                $tasks = $this->service->getFilteredTasks($user, $params);
            } catch (\Exception $exception) {
                $error = new NotFoundHttpException($exception->getMessage());
                $this->addFlash('error', $exception->getMessage());

                return new JsonResponse($error);
            }
        }

        $tasks = $this->service->serializer($tasks);

        return new JsonResponse($tasks);
    }

    #[Route('/tasks/{taskId}', name: 'app_update_task', requirements: ['taskId' => '\d+'])]
    #[IsGranted("ROLE_USER")]
    public function toggleTaskComplete($taskId, Request $request): JsonResponse|RedirectResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \RuntimeException('Invalid request', Response::HTTP_BAD_REQUEST);
        }

        if (!empty($request->getContent())) {
            $params = json_decode($request->getContent(), true);
            $validation = $this->service->validateRequest($params);

            if ($validation) {
                try {
                    $task = $this->service->updateTask($taskId, $params);
                    return new JsonResponse([
                        'success' => 'Task updated successfully.',
                        'email' => $task->getUser()->getEmail(),
                        'title' => $task->getTitle()
                    ]);
                } catch (\Exception $e) {
                    return new JsonResponse(['error' => 'Something went wrong with updating the task.']);
                }
            }
        }

        return new JsonResponse(['error' => 'Invalid data provided']);
    }

    #[Route('/tasks/add', name: 'app_add_task')]
    #[IsGranted("ROLE_USER")]
    public function addTask(Request $request): Response|RedirectResponse
    {
        $user = $this->getUser();
        $userTasks = $this->service->getUserTasks($user);

        $form = $this->createForm(AddTaskFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $form->getData();

            try {
                $this->service->createNewTask($user, $params);
                $this->addFlash('success', 'Task added successfully.');
                $userTasks = $this->service->getUserTasks($user);

                return $this->redirectToRoute('app_add_task');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while creating the task.');
            }
        }

        $response = $this->render('tasks/addEditTask/addEditTask.html.twig', [
            'form' => $form->createView(),
            'tasks' => $userTasks
        ]);

        if ($form->isSubmitted() && !$form->isValid()) {
            $response->setStatusCode(422);
        }

        return $response;
    }

    #[Route('/tasks/{taskId}/delete', name: 'app_delete_task', requirements: ['taskId' => '\d+'])]
    #[IsGranted("ROLE_USER")]
    public function deleteTask($taskId): Response
    {
        $this->service->deleteTask($taskId);

        try {
            $userTasks = $this->service->getUserTasks($this->getUser());
        } catch (\Exception) {
            throw $this->createNotFoundException();
        }

        $tasks = $this->service->serializer($userTasks);

        return new JsonResponse([
            'success' => true,
            'tasks' => $tasks
        ]);
    }
}
