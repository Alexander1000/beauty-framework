<?php declare(strict_types = 1);

namespace Beauty\Controller;

use Beauty;

abstract class Web extends Beauty\Controller
{
    /**
     * @return string
     */
    abstract protected function getTheme(): string;

    /**
     * @param string $template
     * @return Beauty\ViewInterface
     */
    protected function getView(string $template): Beauty\ViewInterface
    {
        return new Beauty\View\Html($this->getTheme(), $template);
    }

    /**
     * @param string $template
     * @param array $context
     * @return Beauty\Http\ResponseInterface
     */
    protected function render(string $template, array $context = []): Beauty\Http\ResponseInterface
    {
        return $this->renderView(
            $this
                ->getView($template)
                ->setVars($context)
        );
    }
}
