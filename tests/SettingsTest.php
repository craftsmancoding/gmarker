<?php
class SettingsTest extends PHPUnit_Framework_TestCase {

    // Must be static because we set it up inside a static function
    public static $modx;
    public static $Settings;

    /**
     * Load up MODX for our tests.
     *
     */
    public static function setUpBeforeClass()
    {
        $docroot = dirname(dirname(__FILE__));
        while (!file_exists($docroot . '/config.core.php')) {
            if ($docroot == '/') {
                die('Failed to locate config.core.php');
            }
            $docroot = dirname($docroot);
        }
        if (!file_exists($docroot . '/config.core.php')) {
            die('Failed to locate config.core.php');
        }

        include_once $docroot . '/config.core.php';

        if (!defined('MODX_API_MODE')) {
            define('MODX_API_MODE', false);
        }
        require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
        include_once MODX_CORE_PATH . 'model/modx/modx.class.php';

        self::$modx = new modX();
        self::$modx->initialize('mgr');

        self::$Settings = new \Gmarker\Settings(self::$modx);

    }

    public static function tearDownAfterClass()
    {

    }
    //-----------------------------------------------------

    public function testTemplates()
    {
        self::$modx->setOption('gmarker.templates','not a number');
        $results = self::$Settings->testTemplates('gmarker.templates');
        $this->assertEquals('error',$results['status']);

        self::$modx->setOption('gmarker.templates',null);
        $results = self::$Settings->testTemplates('gmarker.templates');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.templates_empty',$results['msg']);

        self::$modx->setOption('gmarker.templates','123123');
        $results = self::$Settings->testTemplates('gmarker.templates');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.templates_do_not_exist',$results['msg']);

    }

}
/*EOF*/