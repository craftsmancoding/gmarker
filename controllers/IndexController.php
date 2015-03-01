<?php namespace Gmarker;
/**
 *
 * @package gmarker
 */


class IndexController extends BaseController {

    /**
     * The page title for this controller
     * @return string The string title of the page
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('settings.pagetitle');
        //return $this->modx->lexicon('index.pagetitle');
    }

    /**
     * $this->scriptProperties will contain $_GET and $_POST stuff
     */
    public function initialize()
    {
        //print '<pre>'.print_r($this->scriptProperties,true).'</pre>'; exit;
    }

    /**
     * Do any page-specific logic and/or processing here
     * @param array $scriptProperties
     * @return void
     */
    public function process(array $scriptProperties = array())
    {
        return $this->fetchTemplate('settings.php');
        //return $this->fetchTemplate('index.php');
    }

}
/*EOF*/