<?php namespace Gmarker;
/**
 * Used to test/verify Gmarker System Settings
 *
 * @package gmarker
 */


class Settings {

    public $modx;

    public $results = array();

    /**
     *
     */
    public function __construct($modx) {
        $this->modx = $modx;
    }

    /**
     * Goal is to return an array of error/success messages, e.g.
     *
     * Array(
     *      'setting-name' => array(
     *          'key' => 'setting-name',
     *          'status' => 'success|error',
     *          'msg' => 'lexicon-key'
     *      )
     * )
     *
     * @return array
     */
    public function testAll()
    {
        $results = array();

        $results['gmarker.templates'] = $this->testTemplates('gmarker.templates');
        $results['gmarker.lat_tv'] = $this->testTVs('gmarker.lat_tv');

        return $results;
    }

    public function testTemplates($key)
    {
        $templates = trim($this->modx->getOption($key));

        if (empty($templates)) {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => 'gmarker.templates_empty'
            );
        }

        $templates = array_map('trim', explode(',',$templates));

        foreach ($templates as $tid)
        {
            if (!$T = $this->modx->getObject('modTemplate', array('id'=>$tid)))
            {
                return array(
                    'key' => $key,
                    'status' => 'error',
                    'msg' => 'gmarker.templates_do_not_exist'
                );
            }
        }

        return array(
            'key' => $key,
            'status' => 'ok',
            'msg' => 'ok',
        );
    }

    public function testTVs($key)
    {
        $name = trim($this->modx->getOption($key));

        if (empty($name)) {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_empty'
            );
        }




        if (!$TV = $this->modx->getObject('modTemplateVar', array('name'=>$name)))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_does_not_exist'
            );
        }

        return array(
            'key' => $key,
            'status' => 'ok',
            'msg' => 'ok',
        );
    }

}


/*EOF*/
