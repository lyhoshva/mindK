<?php

namespace Framework\Renderer;

use Framework\DI\Service;
use Loader;

/**
 * Class Renderer
 * @package Framework\Renderer
 */
class Renderer
{
    private $title;
    private $description;
    private $keywords;
    private $params = array();
    private $main_layout = '';

    /**
     * Renderer constructor.
     * @param $main_layout
     */
    public function __construct($main_layout)
    {
        $this->main_layout = $main_layout;
    }

    /**
     * Renders layout
     *
     * @param $content
     * @param bool|false $layout
     * @return string
     */
    public function renderLayout($content, $layout = false)
    {
        if ($layout) {
            $template = $layout;
        } else {
            $template = $this->main_layout;
        }

        return $this->render($template, compact('content'), false);
    }

    /**
     * Renders view
     *
     * @param $view
     * @param array $data
     * @param bool|true $wrap_layout
     * @param bool|false $layout
     * @return string
     */
    public function render($view, $data = array(), $wrap_layout = true, $layout = false)
    {
        extract($data);
        $router = Service::get('router');
        $getRoute = function($route) use ($router){
            return $router->generateRoute($route);
        };
        $route = $router->getCurrentRoute();

        ob_start();

        include($view);
        $content = ob_get_clean();

        if ($wrap_layout) {
            $content = $this->renderLayout($content , $layout);
        }

        return $content;
    }

    /**
     * Returns path to view
     *
     * @param $view
     * @param $classname
     * @return string
     */
    public function getViewPath($view, $classname)
    {
        $controller_path = Loader::getInstance()->getPath($classname);
        return str_replace('/Controller/', '/views/', str_replace('Controller.php', '/', $controller_path)) . $view . '.php';
    }

}
