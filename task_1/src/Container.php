<?php

namespace Heliostat\Task1;

use Heliostat\Task1\Exceptions\ContainerException;
use Heliostat\Task1\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    /**
     * @var array<string, array{concrete: mixed, singleton: bool, instance?: object}>
     */
    private array $definitions = [];

    /**
     * @var array<string, string>
     */
    private array $aliases = [];


    public function register(string $id, mixed $concrete = null, bool $singleton = false): void
    {
        if ($concrete === null) {
            $concrete = $id;
        }

        $this->definitions[$id] = [
            'concrete' => $concrete,
            'singleton' => $singleton,
        ];
    }

    public function singleton(string $id, mixed $concrete = null): void
    {
        $this->register($id, $concrete, true);
    }

    public function alias(string $alias, string $id): void
    {
        $this->aliases[$alias] = $id;
    }

    /**
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function get($id)
    {
        $serviceId = $this->aliases[$id] ?? $id;

        if (!isset($this->definitions[$serviceId])) {
            throw new NotFoundException("Service '{$id}' not found");
        }

        $def = &$this->definitions[$serviceId];

        if ($def['singleton'] && isset($def['instance'])) {
            return $def['instance'];
        }

        $object = $this->resolve($def['concrete']);

        if ($def['singleton']) {
            $def['instance'] = $object;
        }

        return $object;
    }


    public function has($id): bool
    {
        return isset($this->definitions[$id]) || isset($this->aliases[$id]);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws ContainerException
     */
    private function resolve(mixed $concrete)
    {
        if (is_callable($concrete)) {
            return $concrete($this);
        }

        if (is_string($concrete)) {
            try {
                $ref = new ReflectionClass($concrete);
            } catch (ReflectionException $e) {
                throw new ContainerException($e->getMessage());
            }

            $constructor = $ref->getConstructor();
            if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
                return new $concrete();
            }

            $params = [];
            foreach ($constructor->getParameters() as $param) {
                $type = $param->getType();
                if ($type && !$type->isBuiltin()) {
                    $params[] = $this->get($type->getName());
                } elseif ($param->isDefaultValueAvailable()) {
                    $params[] = $param->getDefaultValue();
                } else {
                    throw new ContainerException("Unable to resolve parameter '".$param->getName()."'");
                }
            }

            return $ref->newInstanceArgs($params);
        }

        throw new ContainerException('Invalid service definition');
    }
}