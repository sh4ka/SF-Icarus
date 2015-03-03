<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="news")
     */
    public function indexAction()
    {
        $newsUrl = $this->container->getParameter("tvrage_news");
        $newsfile = 'news.xml';
        $cacher = $this->get('resource_cacher');
        $newsData = $cacher->cache($newsfile, '720*60', $newsUrl);
        $fileTime = $cacher->getFileTime($newsfile);
        $newsDate = date('M d, Y', $fileTime);
        return $this->render('news/index.html.twig',
            [
                'newsData' => $newsData->channel->item,
                'fileDate' => $newsDate
            ]
        );
    }

    /**
     * @Route("/guide", name="guide")
     */
    public function guideAction()
    {
        $newsUrl = $this->container->getParameter("tvrage_guide");
        $guidefile = 'countdown.xml';
        $cacher = $this->get('resource_cacher');
        $guideData = $cacher->cache($guidefile, '720*60', $newsUrl);
        return $this->render('guide/index.html.twig',
            [
                'guideData' => $guideData->country,
            ]
        );
    }

    /**
     * @Route("/shows", defaults={"letter" = "a"}, name="shows_base")
     * @Route("/shows/{letter}", requirements={"letter" = "^[a-z]{1}$"}, name="shows")
     */
    public function showsAction($letter)
    {
        $bc = $this->get('breadcrumbs_generator');
        $breadcrumbData = $bc->getBreadcrumbsForRequest();

        // get show list
        $cacher = $this->get('resource_cacher');
        $file = $letter."-showlist.xml";
        $showsUrl = $this->container->getParameter("tvrage_shows");
        $xml = $cacher->cache($file, '3600*168', $showsUrl.$letter);
        return $this->render('shows/index.html.twig',
            [
                'breadcrumbs' => $breadcrumbData,
                'showData' => $xml->show
            ]
        );
    }

    /**
     * @Route("/episode/{id}", requirements={"id" = "^\d+$"}, name="episodes")
     */
    public function episodesAction($id)
    {
        $cacher = $this->get('resource_cacher');
        $file = "$id-eplist.xml";
        $episodeUrl = $this->container->getParameter("tvrage_episode");
        $xml = $cacher->cache($file, '3600*24', $episodeUrl.$id);
        $title = $xml->name;
        $letter = strtolower(substr($title[0],0,1));
        return $this->render('episodes/index.html.twig',
            [
                'showLetter' => $letter,
                'episodeData' => $xml
            ]
        );
    }



    /**
     * @param Request $request
     * @Route("/search", name="search")
     * @return View
     */
    public function searchAction(Request $request)
    {
        $xml = false;
        if($request->getMethod() == 'GET' && $request->get('submitted') == '' && $request->get('show') != ''){
            $searchString = $request->get('show');
            $xml = simplexml_load_file($this->container->getParameter("tvrage_search").$searchString);
        }
        return $this->render('search/index.html.twig',
            [
                'result' => $xml
            ]
        );
    }
}
