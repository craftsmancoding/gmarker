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

    public function testLatTv()
    {
        self::$modx->setOption('gmarker.lat_tv','');
        $results = self::$Settings->testTVs('gmarker.lat_tv');
        $this->assertEquals('error',$results['status']);

        self::$modx->setOption('gmarker.lng_tv','Does not exist');
        $results = self::$Settings->testTVs('gmarker.lng_tv');
        $this->assertEquals('error',$results['status']);
    }

    public function testLngTv()
    {
        self::$modx->setOption('gmarker.lng_tv','');
        $results = self::$Settings->testTVs('gmarker.lng_tv');
        $this->assertEquals('error',$results['status']);

        self::$modx->setOption('gmarker.lng_tv','Does not exist');
        $results = self::$Settings->testTVs('gmarker.lng_tv');
        $this->assertEquals('error',$results['status']);
    }

    public function testHeight()
    {
        self::$modx->setOption('gmarker.default_height','');
        $results = self::$Settings->testDimensions('gmarker.default_height');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.default_height_empty',$results['msg']);

        self::$modx->setOption('gmarker.default_height','50');
        $results = self::$Settings->testDimensions('gmarker.default_height');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.default_height_missing_units',$results['msg']);

        self::$modx->setOption('gmarker.default_height','16.5px');
        $results = self::$Settings->testDimensions('gmarker.default_height');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.default_height_invalid',$results['msg']);

        self::$modx->setOption('gmarker.default_height','-50%');
        $results = self::$Settings->testDimensions('gmarker.default_height');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.default_height_invalid',$results['msg']);

        self::$modx->setOption('gmarker.default_height','500px');
        $results = self::$Settings->testDimensions('gmarker.default_height');
        $this->assertEquals('ok',$results['status']);

    }

    public function testApiKey()
    {
        self::$modx->setOption('gmarker.apikey','');
        $results = self::$Settings->testApi('gmarker.apikey');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.apikey_empty',$results['msg']);

    }

    public function testFormattingString()
    {
        self::$modx->setOption('gmarker.formatting_string','');
        $results = self::$Settings->testFormattingString('gmarker.formatting_string');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.formatting_string_empty',$results['msg']);

        self::$modx->setOption('gmarker.formatting_string','Nada');
        $results = self::$Settings->testFormattingString('gmarker.formatting_string');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.formatting_string_invalid',$results['msg']);

        self::$modx->setOption('gmarker.formatting_string','[[++does_not_exist]]');
        $results = self::$Settings->testFormattingString('gmarker.formatting_string');
        $this->assertEquals('error',$results['status']);
        $this->assertEquals('gmarker.formatting_string_unknown_fields',$results['msg']);

    }

}
/*EOF*/