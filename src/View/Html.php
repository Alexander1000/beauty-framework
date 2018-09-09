<?php declare(strict_types = 1);

namespace Beauty\View;

use \Beauty\View;

class Html extends View
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $theme;

    public function __construct(string $theme, string $template)
    {
        $this->theme = $theme;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $loader = new \Twig_Loader_Filesystem([$this->theme], ROOT_PATH . '/templates');
        $twigEnv = new \Twig_Environment($loader);
        return $twigEnv->render($this->template . '.html', $this->context);
    }
}
