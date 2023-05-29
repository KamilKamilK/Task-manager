<?php
declare(strict_types=1);
namespace App\Factory;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Task>
 *
 * @method        Task|Proxy create(array|callable $attributes = [])
 * @method static Task|Proxy createOne(array $attributes = [])
 * @method static Task|Proxy find(object|array|mixed $criteria)
 * @method static Task|Proxy findOrCreate(array $attributes)
 * @method static Task|Proxy first(string $sortedField = 'id')
 * @method static Task|Proxy last(string $sortedField = 'id')
 * @method static Task|Proxy random(array $attributes = [])
 * @method static Task|Proxy randomOrCreate(array $attributes = [])
 * @method static TaskRepository|RepositoryProxy repository()
 * @method static Task[]|Proxy[] all()
 * @method static Task[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Task[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Task[]|Proxy[] findBy(array $attributes)
 * @method static Task[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Task[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TaskFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'completed' => self::faker()->boolean(),
            'deadline' => self::faker()->dateTimeThisMonth('now'),
            'details' => self::faker()->text(255),
            'title' => self::faker()->word(),
            'createdAt'=> self::faker()->dateTimeBetween('-1 month', '-1 day'),
            'updatedAt'=> self::faker()->dateTimeBetween('-1 month', '-1 day')
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Task::class;
    }
}
