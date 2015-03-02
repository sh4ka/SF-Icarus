<?php

namespace AppBundle\Cacher;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Cacher
 * 
 * @category SymfonyBundle
 * @package  AppBundle
 * @author   Jesús Flores <jesus.flores@bq.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */
class Cacher 
{
    var $cacheDir = '';

    public function __construct($cacheDir){
        $fs = new Filesystem();
        if(!$fs->exists($cacheDir . '/tvrage/')){
            try {
                $fs->mkdir($cacheDir . '/tvrage/');
            } catch (IOException $e) {
                echo "An error occured while creating cache directory";
            }
        }
        $this->cacheDir = $cacheDir. '/tvrage/';
    }

    public function cache($file, $time, $url) {
        $fullPathFile = $this->cacheDir.$file;
        if (file_exists($fullPathFile) && (time() -
                $time < filemtime($fullPathFile))) {
            if (file_exists($fullPathFile) &&
                filesize($fullPathFile) == 0) {
                $content = file_get_contents($url);
                $xml = fopen($fullPathFile, 'w');
                fwrite($xml, $content);
                fclose($xml);
                $xml = simplexml_load_file($fullPathFile);
            } else {
                //where done, just grab xml from cache =)
                $xml = simplexml_load_file($fullPathFile);
            } #file
        } else { // grab fresh xml
            $content = file_get_contents($url);
            $xml = fopen($fullPathFile, 'w');
            fwrite($xml, $content);
            fclose($xml);
            $xml = simplexml_load_file($fullPathFile);
        } #xml
        return $xml;
    }

    public function getFileTime($file){
        return filemtime($this->cacheDir.$file);
    }
}