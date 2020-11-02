<?php declare(strict_types = 1);

namespace Beauty\Http;

interface ResponseInterface
{
    /**
     * Ответ
     * @return bool
     */
    public function reply(): bool;

    /**
     * Тело ответа
     * @param string $body
     * @return static
     */
    public function setBody(string $body);

    /**
     * Код ответа
     * @param int $code
     * @return static
     */
    public function setCode(int $code);

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addHeader(string $name, string $value);
}
