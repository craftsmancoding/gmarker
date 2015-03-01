<?php namespace Gmarker;
/**
 * The "almost abstract" Manager Controller.
 * As expected, in this class, we define behaviors we want on all of our controllers.
 *
 * See the IndexManagerController class (index.class.php) for routing info.
 *
 * @package gmarker
 */

require_once MODX_CORE_PATH.'model/modx/modmanagercontroller.class.php';
class BaseController extends \modExtraManagerController {
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = true;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = true;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = true;
    /** @var array An array of possible paths to this controller's templates directory. */
    public $templatesPaths = array();
    /** @var array An array of possible paths to this controller's directory. */
    //public $controllersPaths;
    /** @var modContext The current working context. */
    //public $workingContext;
    /** @var modMediaSource The default media source for the user */
    //public $defaultSource;
    /** @var string The current output content */
    //public $content = '';
    /** @var array An array of request parameters sent to the controller */
    // public $scriptProperties = array();
    /** @var array An array of css/js/html to load into the HEAD of the page */
    //public $head = array('css' => array(),'js' => array(),'html' => array(),'lastjs' => array());
    /** @var array An array of placeholders that are being set to the page */
    //public $placeholders = array();


    public $modx; // for static refs
    public $config;

    private $core_path;
    private $assets_url;
    private $jquery_url;



    /**
     * Map a function name to a MODX permission, e.g.
     * 'edit_product' => 'edit_document'
     */
    private $perms = array(
        'edit_product' => 'edit_document',
    );

    /**
     * This is the permission tested against if nothing is explicitly defined
     * in the $perms array.
     */
    private $default_perm = 'view_document';

    function __construct(\modX &$modx,$config = array()) {
        parent::__construct($modx,$config);
        $this->modx =& $modx;
        $this->config = $config;

        $this->config['core_path'] = $this->modx->getOption('gmarker.core_path', null, MODX_CORE_PATH.'components/gmarker/');
        $this->config['assets_url'] = $this->modx->getOption('gmarker.assets_url', null, MODX_ASSETS_URL.'components/gmarker/');

        $this->modx->regClientCSS($this->config['assets_url'] . 'css/mgr.css');
    }

    /**
     * Catch all for bad function requests -- our 404
     */
    public function __call($name,$args) {
        $this->modx->log(\modX::LOG_LEVEL_ERROR,'[mod] Invalid function name '.$name);
        $this->addStandardLayout($args); // For some reason we have to do this here (?)
        $class = '\\Gmarker\\ErrorController'; // string prep
        $Error = new $class($this->modx,$config);
        $args['msg'] = 'Invalid routing function name: '. $name;
        // We need to send headers like this, otherwise Ajax requests etc. get confused.
        header('HTTP/1.0 404 Not Found');
        return $Error->get404($args);
    }


    /**
     * We can use this to check if the user has permission to see this controller
     * @return bool
     */
    public function checkPermissions() {
        return true; // TODO
    }

    /**
     * Defines the lexicon topics to load in our controller.
     * @return array
     */
    public function getLanguageTopics() {
        return array('gmarker:default');
    }


    /**
     * Override parent function.
     * Override Smarty. I don't wants it. But BEWARE: the loadHeader and loadFooter bits require
     * the functionality of the original fetchTemplate function.  ARRRGH.  You try to escape but you can't.
     *
     * @param string $file (relative to the views directory)
     * @return rendered string (e.g. HTML)
     */
    public function fetchTemplate($file) {
        // Conditional override! Gross! 
        // If we don't give Smarty a free pass, we end up with "View file does not exist" errors because
        // MODX relies on the parent fetchTemplate function to load up its header.tpl and footer.tpl files. Ick.
        if (substr($file,-4) == '.tpl') {
            return parent::fetchTemplate($file);
        }
        $this->modx->log(\modX::LOG_LEVEL_DEBUG, 'File: '.$file,'','BaseController::'.__FUNCTION__);

        $path = $this->modx->getOption('gmarker.core_path','', MODX_CORE_PATH.'components/gmarker/').'views/';

        $data = $this->getPlaceholders();

        $this->modx->log(\modX::LOG_LEVEL_DEBUG, 'View: ' .$file.' data: '.print_r($data,true),'','BaseController::'.__FUNCTION__,'Line:'.__LINE__);
        if (!is_file($path.$file)) {
            $this->modx->log(\modX::LOG_LEVEL_ERROR, 'View file does not exist: '. $file, '','BaseController::'.__FUNCTION__,'Line:'.__LINE__);
            return $this->modx->lexicon('view_not_found', array('file'=> 'views/'.$file));
        }

        // Load up our page [header] + content + [footer]
        ob_start();

        include $path.$file;

        $content = ob_get_clean();

        return $content;
    }

    use ControllerHelpers;
//    /**
//     * Gotta look up the URL of our CMP and its actions
//     * TODO: don't hardcode this
//     * @param string $page default: index
//     * @param array any optional arguments, e.g. array('action'=>'children','parent'=>123)
//     * @return string
//     */
//    public static function page($page='index',$args=array()) {
//        $url = MODX_MANAGER_URL;
//        $url .= '?a=index&namespace=gmarker&page='.$page;
//        if ($args) {
//            foreach ($args as $k=>$v) {
//                $url.='&'.$k.'='.$v;
//            }
//        }
//
//        return $url;
//    }

}