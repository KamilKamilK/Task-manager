<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Task;
use App\Repository\TaskRepository;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;


class TaskService
{
    private TaskRepository $taskRepository;


    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getUserTasks($user): array
    {
        $userId = $user->getId();
        return $this->taskRepository->findBy(['user' => $userId], ['createdAt' => 'DESC']);
    }

    public function getFilteredTasks(UserInterface $user, $params): array
    {
        $title = $params['title'] ?? null;
        $details = $params['details'] ?? null;
        $deadline = $params['deadline'] ?? null;
        $completed = $params['completed'] ? $params['completed'] === 'true' : null;

        $user_id = $user->getId();

        $tasks = $this->taskRepository->findAllByFilterField($user_id, $completed, $title, $details, $deadline);

        if (empty($tasks)) {
            throw new \Exception();
        }
        return $tasks;
    }

    public function serializer($tasks): array
    {
        $serializedArr = [];

        foreach ($tasks as $task) {
            $taskData = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'details' => $task->getDetails(),
                'deadline' => $task->getDeadline()->format('d-m-Y'),
                'completed' => $task->isCompleted(),
            ];

            $serializedArr[] = $taskData;
        }
        return $serializedArr;
    }

    #[NoReturn] public function updateTask(string $taskId, array $params): Task
    {
        $details = $params['details'] ?? null;
        $title = $params['title'] ?? null;
        $detail = $params['detail'] ?? null;
        $deadline = $params['deadline'] ?? null;
        $isCompleted = isset($params['completed']) ? $params['completed'] === true : null;

        $task = $this->taskRepository->find($taskId);
        if ($isCompleted !== null) {
            $task->setCompleted($isCompleted === false ? false : true);
        }
        if ($details !== null) {
            $task->setDetails($details);
        }
        if ($title !== null) {
            $task->setTitle($title);
        }
        if ($detail !== null) {
            $task->setDetails($detail);
        }
        if ($deadline !== null) {
            $format = "d.m.Y";
            $deadline = \DateTime::createFromFormat($format, $deadline);
            $task->setDeadline($deadline);
        }

        $this->taskRepository->save($task, true);
        return $task;
    }

    public function createNewTask($user, array $params): void
    {
        $task = (new Task())
            ->setUser($user)
            ->setTitle($params['title'])
            ->setDetails($params['details'])
            ->setDeadline($params['deadline'])
            ->setCompleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $this->taskRepository->save($task, true);
    }

    #[NoReturn] public function deleteTask($taskId): void
    {
        $task = $this->taskRepository->find($taskId);
        $this->taskRepository->remove($task, true);
    }

    public function validateRequest($params): bool
    {
        return !(
            isset($params['title']) && empty($params['title']) ||
            isset($params['details']) && empty($params['details']) ||
            isset($params['deadline']) && empty($params['deadline'])
        );
    }
}