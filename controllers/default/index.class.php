<?php
/**
 * MODX 2.3.x
 *
 * There's not much flexibility possible given the class & function structure of the MODX manager controllers,
 * so this one file with a wonky name must exist.  We use it to "catch" requests and route them to
 * other manager controller classes.
 *
 * ROUTING:
 *
 * I'm overriding a fair number of the modExtraManagerController functions there to support
 * custom routing.  By overriding the getInstance() method I am abandoning
 * the somewhat limited MODX convention of mapping the &action URL parameter to a controller class
 * and instead I'm organizing requests as follows:
 *
 *  &page = classname of the controller class.
 *
 * This ultimately calls the process() method, so your URLs look something like
 *
 * http://yoursite.com/manager/?a=index&namespace=gmarker&page=tests
 *
 * @package gmarker
 */

// Gotta do this here because we don't have a reliable event for this. 
require_once dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';

class GmarkerIndexManagerController extends \modExtraManagerController {

    public static $routing_key = 'page';

    /**
     * So close, but this function is ultimately only useful as a jumping off point for our custom routing because
     * the name of this class MUST follow a *convention* that is not customizable.  The name of *this* class is
     * ultimately defined in a non-overridable class (modManagerResponse).
     * The classname must be {Namespace}{Action}ManagerController where the namespace and action come from the action.
     * So overriding this function will not spare you from having a whacko classname for THIS class.
     *
     *
     * @static
     *
     * @param modX $modx A reference to the modX object.
     * @param string $className The name of the class that is being requested.
     * @param array $config A configuration array of options related to this controller's action object.
     *
     * @return The class specified by $className
     */
    public static function getInstance(\modX &$modx, $className, array $config = array()) {


        $modx->lexicon->load('gmarker:default');
        if (!$modx->getOption('gmarker.license_key') )
        {
            $config['error_msg'] = $modx->lexicon('reqs_license_msg');
            return new \Gmarker\LicenseController($modx,$config);
        }
        else
        {
            $License = new \Gmarker\License($modx);
            $status = $License->check($modx->getOption('gmarker.license_key'));
            // Activate License if needed
            if ($status == 'inactive')
            {
                if (!$License->activate($modx->getOption('gmarker.license_key')))
                {
                    $config['error_msg'] = $modx->lexicon('activation_problem_msg');
                    return new \Gmarker\LicenseController($modx,$config);
                }
            }
            // Check it again, in case it just got activated
            $status = $License->check($modx->getOption('gmarker.license_key'));
            if ($status != 'valid')
            {
                $config['error_msg'] = $modx->lexicon('invalid_expired_msg');
                return new \Gmarker\LicenseController($modx,$config);

            }
            else
            {
                $modx->log(\xPDO::LOG_LEVEL_INFO, 'MODX Backup License status: '. $status);
            }
        }


        // Manual routing
        $className = (isset($_GET[self::$routing_key])) ? '\\Gmarker\\'.ucfirst($_GET[self::$routing_key]).'Controller': '\\Gmarker\\IndexController';

        unset($_GET[self::$routing_key]);
        if (!class_exists($className))
        {
            return new \Gmarker\ErrorController($modx,$config);
        }
        try {
            return new $className($modx, $config);
        }
        catch (\Exception $e)
        {
            $config['Exception'] = $e;
            return new \Gmarker\ErrorController($modx,$config);
        }

    }


}
/*EOF*/
