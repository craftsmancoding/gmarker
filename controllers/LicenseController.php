<?php namespace Gmarker;
/**
 *
 * @package gmarker
 */


class LicenseController extends BaseController {

    /**
     * The page title for this controller
     * @return string The string title of the page
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('license.pagetitle');
    }

    /**
     * $this->scriptProperties will contain $_GET and $_POST stuff
     * Remember: the lexicon has not been initialized yet!
     */
    public function initialize()
    {
        if (isset($this->config['error_msg']))
        {
            $this->setPlaceholder('error_msg', $this->config['error_msg']);
        }
    }

    /**
     * Do any page-specific logic and/or processing here
     * @param array $scriptProperties
     * @return void
     */
    public function process(array $scriptProperties = array())
    {

        //return '<pre>'. print_r($this->getPlaceholders(),true).'</pre>';
        return $this->fetchTemplate('license.php');
    }

}
/*EOF*/