<?php

namespace AppBundle\Breadcrumbs;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Breadcrumbs
 * 
 * @category SymfonyBundle
 * @author   Jesús Flores <jesus.flores@bq.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */
class Breadcrumbs 
{

    public function __construct(RequestStack $requestStack, Router $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    function getBreadcrumbsForRequest() {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $path = array_filter(explode('/', $currentRequest->getRequestUri()));
        $bc = array("<a href=".$this->router->generate('news').">Home</a>");
        $last = array_keys($path);
        $last = end($last);
        foreach ($path as $x => $crumb) {
            $title = ucwords($crumb);
            ($x != $last) ? $bc[] = "<a href=".$this->router->generate(strtolower($title)).">$title</a>" : $bc[] = $title;
        }
        return implode(' &raquo; ', $bc);
    }
}