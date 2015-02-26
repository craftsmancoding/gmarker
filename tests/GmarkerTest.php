<?php
class GmarkerTest extends PHPUnit_Framework_TestCase {

    // Must be static because we set it up inside a static function
    public static $modx;
    public static $Gmarker;

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

        self::$Gmarker = new Gmarker(self::$modx);

    }

    public static function tearDownAfterClass()
    {

    }
    //-----------------------------------------------------

    public function testIsLatLng()
    {
        $this->assertFalse(self::$Gmarker->isLatLng('Gnasdfasdfasdf'));
        $this->assertTrue(self::$Gmarker->isLatLng('21.038364,29.882813'));
        $this->assertFalse(self::$Gmarker->isLatLng('321.038364,29.882813'));
    }

}
/*EOF*/