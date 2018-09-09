<?php declare(strict_types = 1);

namespace Beauty;

interface ControllerInterface
{
    /**
     * @param string $action
     * @param RequestInterface $request
     * @param array $params
     * @return Http\ResponseInterface
     */
    public function run(string $action, RequestInterface $request, array $params): Http\ResponseInterface;
}
