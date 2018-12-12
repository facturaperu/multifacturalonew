<?php

namespace App\Core\Xml\Builder;

class TwigBuilder
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * TwigBuilder constructor.
     * @param array $options [optional] Recommended: 'cache' => '/dir/cache'
     */
    public function __construct($options = [])
    {
        $this->initTwig($options);
    }

    /**
     * Get Content XML from template.
     *
     * @param string $template
     * @param object $company
     * @param object $doc
     * @return string
     */
    public function render($template, $company, $doc)
    {
        return $this->twig->render($template, [
            'company' => $company,
            'doc' => $doc
        ]);
    }

    private function initTwig($options)
    {
        $loader = new \Twig_Loader_Filesystem('../Templates');
        $numFilter = new \Twig_SimpleFilter('n_format', function ($number, $decimals = 2) {
            return number_format($number, $decimals, '.', '');
        });

        $twig = new \Twig_Environment($loader, $options);
        $twig->addFilter($numFilter);
        $twig->addFunction(new \Twig_SimpleFunction('getTributeFunction', ['App\Core\Xml\Filter\TributeFunction', 'getByAffectation']));

        $this->twig = $twig;
    }
}