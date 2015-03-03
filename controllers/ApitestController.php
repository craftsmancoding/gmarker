<?php namespace Gmarker;
/**
 *
 * @package gmarker
 */


class ApitestController extends BaseController {

    public $Gmarker;

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
        $this->Gmarker = new \Gmarker($this->modx);

    }

    /**
     * Do any page-specific logic and/or processing here
     * @param array $scriptProperties
     * @return void
     */
    public function process(array $scriptProperties = array())
    {
        //return 'Map goes here<pre>'.print_r($scriptProperties,true).'</pre>';
        //$this->setPlaceholder('gmarker.templates_msg', 'ddd');

        // Google props: what we will send to the API
        $goog = array();
        $goog['address'] = $this->modx->getOption('address', $scriptProperties);
        $goog['bounds'] = $this->modx->getOption('gmarker.bounds');
        $goog['components'] = $this->modx->getOption('gmarker.components');
        $goog['region'] = $this->modx->getOption('gmarker.region');
        $goog['language'] = $this->modx->getOption('gmarker.language');


        $secure = (int) $this->modx->getOption('gmarker.secure');
        $json = $this->Gmarker->lookup($goog,$secure,true); // force an API lookup

        $this->setPlaceholder('status', $this->Gmarker->get('status'));
        $this->setPlaceholder('formatted_address', $this->Gmarker->get('formatted_address'));
        $this->setPlaceholder('lat', $this->Gmarker->get('location.lat'));
        $this->setPlaceholder('lng', $this->Gmarker->get('location.lng'));
        $this->setPlaceholder('zoom', $this->modx->getOption('gmarker.zoom',null, 8));
        $this->setPlaceholder('apikey', $this->modx->getOption('gmarker.apikey'));

        $this->setPlaceholder('raw', $json);
        //return '<pre>'. print_r($json, true).'</pre>';
        return $this->fetchTemplate('map.php');
    }

}
/*EOF*/