<?php declare(strict_types = 1);

namespace Beauty;

class Request implements RequestInterface
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PATCH = 'PATCH';

    /**
     * @var array
     */
    private $get;

    /**
     * @var array
     */
    private $post;

    /**
     * @var Cookie
     */
    private $cookie;

    /**
     * @var array
     */
    private $server;

    /**
     * @var array
     */
    private $files;

    public function __construct(array $get, array $post, array $cookie, array $server, array $files)
    {
        $this->get = $get;
        $this->post = $post;
        $this->cookie = new Cookie($cookie);
        $this->server = $server;
        $this->files = $files;
    }

    /**
     * @return Request
     */
    public static function instance(): self
    {
        return new static(
            $_GET,
            $_POST,
            $_COOKIE,
            $_SERVER,
            $_FILES
        );
    }

    /**
     * @return null|string
     */
    public function getUri(): ?string
    {
        return $this->server['REQUEST_URI'] ?? null;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->server['REQUEST_METHOD'] == self::METHOD_POST;
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->server['REQUEST_METHOD'] == self::METHOD_GET;
    }

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getGet(string $paramName, $defaultValue = null)
    {
        return $this->get[$paramName] ?? $defaultValue;
    }

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getPost(string $paramName, $defaultValue = null)
    {
        return $this->post[$paramName] ?? $defaultValue;
    }

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     * @throws \InvalidArgumentException
     */
    public function getParam(string $paramName, $defaultValue = null)
    {
        switch ($this->getMethod()) {
            case self::METHOD_GET:
                return $this->getGet($paramName, $defaultValue);
            case self::METHOD_POST:
                return $this->getPost($paramName, $defaultValue);
        }

        throw new \InvalidArgumentException('Method not allowed');
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getParams(): array
    {
        switch ($this->getMethod()) {
            case self::METHOD_GET:
                return $this->get;
            case self::METHOD_POST:
                return $this->post;
        }

        throw new \InvalidArgumentException('Method not allowed');
    }

    /**
     * @return Cookie
     */
    public function getCookie(): Cookie
    {
        return $this->cookie;
    }
}
