<?php
namespace AppBundle\Twig;

/**
 * Class ShowStatusExtension
 * 
 * @category SymfonyBundle
 * @author   Jesús Flores <jesus.flores@bq.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */
class ShowStatusExtension extends \Twig_Extension
{
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('status', array($this, 'showStatus')),
        );
    }

    public function showStatus($statusId){

    }

    public function getName(){
        return 'show_status';
    }

}