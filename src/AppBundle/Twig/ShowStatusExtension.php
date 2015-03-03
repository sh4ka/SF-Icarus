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
        switch($statusId){
            case 1: $status = 'Returning Series'; break;
            case 2: $status = 'Canceled/Ended'; break;
            case 3: $status = 'TBD/On The Bubble'; break;
            case 4: $status = 'In Development'; break;
            case 7: $status = 'New Series'; break;
            case 8: $status = 'Never Aired'; break;
            case 9: $status = 'Final Season'; break;
            case 10: $status = 'On Hiatus'; break;
            case 11: $status = 'Pilot Ordered'; break;
            case 12: $status = 'Pilot Rejected'; break;
            default: $status = 'Unknown'; break;
        }
        return $status;
    }

    public function getName(){
        return 'show_status';
    }

}