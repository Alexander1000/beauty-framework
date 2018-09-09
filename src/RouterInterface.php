<?php declare(strict_types = 1);

namespace Beauty;

use Psr\Container\ContainerInterface;

interface RouterInterface
{
    /**
     * Возвращает подходящий сценарий в виде объекта RequestInterface
     * @param Request $request
     * @param string|null $uri
     * @return Http\ResponseInterface
     * @throws Exception\NotFound
     */
    public function dispatch(Request $request, ?string $uri): Http\ResponseInterface;

    /**
     * Список маршрутов
     * @return array
     */
    public function getRoutes(): array;

    /**
     * Переменные
     * @param array $vars
     * @return self
     */
    public function setVars(array $vars);

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container);
}
