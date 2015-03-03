<?php namespace Gmarker;
/**
 *
 * @package gmarker
 */


class ApitestController extends BaseController {

    public $Settings;

    /**
     * The page title for this controller
     * @return string The string title of the page
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('apitest.pagetitle');
    }

    /**
     * $this->scriptProperties will contain $_GET and $_POST stuff
     */
    public function initialize()
    {
//        $this->Settings = new Settings($this->modx);
//        $results = $this->Settings->testAll();
//        $this->setPlaceholders(array('settings'=>$results));
        //print '<pre>'.print_r($results,true).'</pre>'; exit;
    }

    /**
     * Do any page-specific logic and/or processing here
     * @param array $scriptProperties
     * @return void
     */
    public function process(array $scriptProperties = array())
    {
        return 'Map goes here';
        //$this->setPlaceholder('gmarker.templates_msg', 'ddd');

        //return $this->fetchTemplate('settings.php');
    }

}
/*EOF*/