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
     * @param Request $request
     * @return Http\ResponseInterface
     */
    public function process(Request $request): Http\ResponseInterface
    {
        /** @var Router $router */
        $router = $this->container->get(Router::class);
        $router->setContainer($this->container);
        return $router->dispatch($request, $request->getUri());
    }
}
