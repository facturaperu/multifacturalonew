<?php

namespace App\CoreBuilder\Xml\Builder;

//use App\Core\Xml\Filter\TributeFunction;

class BladeBuilder
{
//    /**
//     * @var \Twig_Environment
//     */
//    protected $twig;

//    /**
//     * TwigBuilder constructor.
//     * @param array $options [optional] Recommended: 'cache' => '/dir/cache'
//     */
    public function __construct($options = [])
    {
        $this->initTwig($options);
    }

    public function render($template, $doc)
    {
        view()->addLocation(__DIR__.'/../Templates');
//        dd(view('invoice')->render());
        $view = view($template, [
            'company' => $doc->getCompany(),
            'document' => $doc->getDocument()
        ])->render();

        dd($view);
        file_put_contents(public_path('prueba.xml'), $view);

        return $view;
//        return $this->twig->render($template, [
//            'company' => $doc->getCompany(),
//            'doc' => $doc->getDocument()
//        ]);
    }

    private function initTwig($options)
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/../Templates');
        $numFilter = new \Twig_SimpleFilter('n_format', function ($number, $decimals = 2) {
            return number_format($number, $decimals, '.', '');
        });

        $twig = new \Twig_Environment($loader, $options);
        $twig->addFilter($numFilter);
        $twig->addFunction(new \Twig_SimpleFunction('getTributeFunction', [TributeFunction::class, 'getByAffectation']));

        $this->twig = $twig;
    }
}