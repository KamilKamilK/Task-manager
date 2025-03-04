<?php

namespace App\Factory;

use App\Entity\Tool;
use App\Repository\ToolRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Tool>
 *
 * @method        Tool|Proxy create(array|callable $attributes = [])
 * @method static Tool|Proxy createOne(array $attributes = [])
 * @method static Tool|Proxy find(object|array|mixed $criteria)
 * @method static Tool|Proxy findOrCreate(array $attributes)
 * @method static Tool|Proxy first(string $sortedField = 'id')
 * @method static Tool|Proxy last(string $sortedField = 'id')
 * @method static Tool|Proxy random(array $attributes = [])
 * @method static Tool|Proxy randomOrCreate(array $attributes = [])
 * @method static ToolRepository|RepositoryProxy repository()
 * @method static Tool[]|Proxy[] all()
 * @method static Tool[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Tool[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Tool[]|Proxy[] findBy(array $attributes)
 * @method static Tool[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Tool[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ToolsFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $fakeTools = ['hammer', 'computer', 'vacuum cleaner', 'pen', 'pencil', 'foil', 'sponge', 'rubber', 'glue', 'envelope', 'knife', 'fork'];


        return [
            'name' => self::faker()->randomElement($fakeTools),
            'quantity' => self::faker()->randomNumber(1)
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(Tools $tools): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Tool::class;
    }
}
