<?php declare(strict_types = 1);

namespace Beauty;

abstract class Controller implements ControllerInterface
{
    /**
     * Http код ответа
     * @var int
     */
    protected $httpCode = Http\Response::HTTP_CODE_OK;

    /**
     * Http загловоки
     * @var array
     */
    protected $headers = [];

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $action
     * @param RequestInterface $request
     * @param array $params
     * @return Http\ResponseInterface
     */
    final public function run(string $action, RequestInterface $request, array $params): Http\ResponseInterface
    {
        $controllerMethod = sprintf('%sAction', $action);
        return $this->{$controllerMethod}($params);
    }

    /**
     * @return Request
     */
    final protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Http\ResponseInterface
     */
    protected function getResponse(): Http\ResponseInterface
    {
        return new Http\Response($this->request->getCookie());
    }

    /**
     * @param ViewInterface $view
     * @return Http\ResponseInterface
     */
    protected function renderView(ViewInterface $view): Http\ResponseInterface
    {
        return $this->getResponse()
            ->setCode($this->httpCode)
            ->setBody($view->render());
    }
}
