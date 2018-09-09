<?php declare(strict_types = 1);

namespace Beauty\Http;

class Response implements ResponseInterface
{
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_INTERNAL_ERROR = 500;

    const HTTP_MESSAGES = [
        self::HTTP_CODE_OK => 'OK',
        self::HTTP_CODE_NOT_FOUND => 'Not found',
        self::HTTP_CODE_INTERNAL_ERROR => 'Internal error',
    ];

    /**
     * @var int
     */
    protected $httpCode;

    /**
     * @var string
     */
    protected $body;

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
     * {@inheritdoc}
     */
    public function reply(): bool
    {
        if (array_key_exists($this->httpCode, self::HTTP_MESSAGES)) {
            header(sprintf('HTTP/1.1 %d %s', $this->httpCode, self::HTTP_MESSAGES[$this->httpCode]));
        }

        echo $this->body;
        return true;
    }
}
