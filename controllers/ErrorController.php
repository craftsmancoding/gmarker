<?php namespace Gmarker;
/**
 * See the IndexManagerController class (index.class.php) for routing info.
 *
 * @package moxycart
 */

class ErrorController extends BaseController {
    //public $loadHeader = false;
    //public $loadFooter = false;
    //public $loadBaseJavascript = false; // GFD... this can't be set at runtime.



    /**
     * Do any page-specific logic and/or processing here
     * @param array $scriptProperties
     * @return void
     */
    public function process(array $scriptProperties = array())
    {

        return '404 error...';
    }

}
/*EOF*/