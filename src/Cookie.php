<?php declare(strict_types = 1);

namespace Beauty;

class Cookie
{
    /**
     * @var array
     */
    private $cookie;

    /**
     * @var string[]
     */
    private $cookieList = [];

    public function __construct(array $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @param string $name
     * @return string
     */
    public function get(string $name): string
    {
        return $this->cookie[$name];
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function set(string $name, string $value)
    {
        $this->cookieList[] = sprintf(
            '%s=%s',
            urlencode($name),
            urlencode($value)
        );

        return $this;
    }

    /**
     * @return string[]
     */
    public function getCookieList(): array
    {
        return $this->cookieList;
    }
}
