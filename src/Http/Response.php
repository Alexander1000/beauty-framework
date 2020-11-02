<?php

declare(strict_types = 1);

namespace Beauty\Http;

class Response implements ResponseInterface
{
    public const HTTP_CODE_OK = 200;
    public const HTTP_CODE_NOT_FOUND = 404;
    public const HTTP_CODE_INTERNAL_ERROR = 500;

    public const HTTP_MESSAGES = [
        self::HTTP_CODE_OK => 'OK',
        self::HTTP_CODE_NOT_FOUND => 'Not found',
        self::HTTP_CODE_INTERNAL_ERROR => 'Internal error',
        301 => 'Moved Permanently',
        302 => 'Found',
    ];

    protected int $httpCode = self::HTTP_CODE_OK;

    protected string $body = '';

    protected \Beauty\Cookie $cookie;

    protected array $headers = [];

    public function __construct(\Beauty\Cookie $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @param string $body
     * @return self
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param int $code
     * @return self
     */
    public function setCode(int $code)
    {
        $this->httpCode = $code;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addHeader(string $name, string $value)
    {
        $this->headers[] = [$name, $value];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reply(): bool
    {
        if (array_key_exists($this->httpCode, self::HTTP_MESSAGES)) {
            header(sprintf('HTTP/1.1 %d %s', $this->httpCode, self::HTTP_MESSAGES[$this->httpCode]));
        }

        foreach ($this->headers as $headerData) {
            header(sprintf('%s: %s', $headerData[0], $headerData[1]));
        }

        foreach ($this->cookie->getCookieList() as $cookie) {
            header(sprintf('Set-Cookie: %s', $cookie), false);
        }

        echo $this->body;
        return true;
    }
}
