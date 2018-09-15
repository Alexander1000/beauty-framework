<?php declare(strict_types = 1);

namespace Beauty;

use Psr\Container\ContainerInterface;

final class App
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param RouterInterface $router
     * @param Request $request
     * @return Http\ResponseInterface
     */
    public function process(RouterInterface $router, Request $request): Http\ResponseInterface
    {
        return $router->dispatch($request, $request->getUri());
    }
}
