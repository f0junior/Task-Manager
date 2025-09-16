<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Repositories\InMemory\InMemoryUserRepository;
use App\Repositories\InMemory\InMemoryTaskRepository;
use App\Services\UserService;
use App\Services\TaskService;

// 1. Criar instâncias dos repositórios (InMemory por enquanto)
$userRepo = new InMemoryUserRepository();
$taskRepo = new InMemoryTaskRepository();

// 2. Criar instâncias dos services
$userService = new UserService($userRepo);
$taskService = new TaskService($taskRepo, $userRepo);

// 3. Testar criação de usuário
$user = $userService->register("João", "joao@email.com", "senha123");

echo "Usuário criado: " . $user->name . " (" . $user->email . ")" . PHP_EOL;

// 4. Testar criação de tarefa para esse usuário
$task = $taskService->create(
    $user->id,
    "Estudar SOLID",
    "Revisar princípios SOLID aplicados no projeto"
);

echo "Tarefa criada: " . $task->title . " para o usuário " . $task->userId . PHP_EOL;

// 5. Testar listar usuários e tarefas
var_dump($userRepo->findById($user->id));
var_dump($taskRepo->findByUserId($user->id));
