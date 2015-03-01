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
        $this->setPlaceholder('gmarker.templates_msg', 'ddd');

        return $this->fetchTemplate('settings.php');
    }

}
/*EOF*/