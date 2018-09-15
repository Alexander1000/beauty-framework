<?php declare(strict_types = 1);

namespace Beauty;

use Psr\Container\ContainerInterface;

abstract class Router implements RouterInterface
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $schema;

    /**
     * Переменные из placeholder'ов
     * @var array
     */
    protected $vars = [];

    /**
     * Список placeholder'ов
     * @var string[]
     */
    protected $placeholders;

    protected $replacement = [
        'integer' => '\d+',
        'int' => '\d+',
        'string' => '[\w\d_-]+'
    ];

    /**
     * @var string
     */
    protected $schemaPath;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function filterPlaceholders(string $uri)
    {
        $this->placeholders = [];

        return preg_replace_callback(
            '#\{([\w\d]+):([\w\d]+)\}#si',
            function ($matches) {
                if (isset($this->replacement[$matches[2]])) {
                    $this->placeholders[] = $matches[1];
                    return sprintf('(%s)', $this->replacement[$matches[2]]);
                }

                return $matches[0];
            },
            $uri
        );
    }

    /**
     * Дополнительные проверки маршрута
     * @param Request $request
     * @param array $routeInfo
     * @return bool
     */
    protected function isValid(Request $request, array $routeInfo): bool
    {
        if (!empty($routeInfo['method'])) {
            return in_array(
                strtoupper($request->getMethod()),
                explode(' ', strtoupper($routeInfo['method']))
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function dispatch(Request $request, ?string $uri): Http\ResponseInterface
    {
        $uri = preg_replace('/(.*?)\?.*/si', '$1', $uri ?: '/');

        foreach ($this->getRoutes() as $routeInfo) {
            $pattern = $this->filterPlaceholders($routeInfo['uri']);
            $regexp = sprintf('#%s#si', $pattern);

            if (preg_match($regexp, $uri, $matches) && $this->isValid($request, $routeInfo)) {
                if (isset($routeInfo['route'])) {
                    array_shift($matches);

                    return $this->getRouterFromInfo($routeInfo)
                        ->setVars(
                            array_merge(
                                $this->vars,
                                array_combine($this->placeholders, $matches)
                            )
                        )
                        ->dispatch($request, preg_replace($regexp, '', $uri));
                } elseif (strlen($uri) == strlen($matches[0])) {
                    array_shift($matches);
                    $this->setVars(array_merge($this->vars, array_combine($this->placeholders, $matches)));
                    return $this->callController($request, $routeInfo + ['params' => $this->vars]);
                }
            }
        }

        throw new Exception\NotFound();
    }

    /**
     * @param Request $request
     * @param array $params
     * @return Http\ResponseInterface
     */
    protected function callController(Request $request, array $params): Http\ResponseInterface
    {
        /** @var ControllerInterface $controller */
        $controller = $this->container->get($params['controller']);
        return $controller->run($params['action'], $request, $params);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getRoutes(): array;

    /**
     * Получаем вложенный RouterInterface
     * @param array $routeInfo
     * @return RouterInterface
     */
    protected function getRouterFromInfo(array $routeInfo): RouterInterface
    {
        return $this->container
            ->get($routeInfo['class'] ?? static::class)
            ->setContainer($this->container);
    }
}
