<?php namespace Gmarker;
/**
 * Used to test/verify Gmarker System Settings on the front-end.
 *
 * @package gmarker
 */


class Settings {

    public $modx;
    public $templates;
    public $doc_cols = array();
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
        $results['gmarker.lng_tv'] = $this->testTVs('gmarker.lng_tv');
        $results['gmarker.default_height'] = $this->testDimensions('gmarker.default_height');
        $results['gmarker.default_width'] = $this->testDimensions('gmarker.default_width');
        $results['gmarker.apikey'] = $this->testApi('gmarker.apikey');
        $results['gmarker.style'] = $this->testApi('gmarker.style');
        $results['gmarker.formatting_string'] = $this->testFormattingString('gmarker.formatting_string');

        return $results;
    }

    public function testTemplates($key)
    {
        $this->templates = trim($this->modx->getOption($key));

        if (empty($this->templates)) {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => 'gmarker.templates_empty'
            );
        }

        $this->templates = array_map('trim', explode(',',$this->templates));

        foreach ($this->templates as $tid)
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

    public function testDimensions($key)
    {
        $name = trim($this->modx->getOption($key));
        if (empty($name))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_empty',
            );
        }
        if (is_numeric($name))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_missing_units',
            );
        }
        if (!preg_match('/^\d+(px|%)$/',$name))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_invalid',
            );
        }
        return array(
            'key' => $key,
            'status' => 'ok',
            'msg' => 'ok',
        );
    }

    public function testApi($key)
    {
        $name = trim($this->modx->getOption($key));
        if (empty($name))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_empty',
            );
        }
        // I don't want to go down the rabbit hole of verifying whether some text is a valid key
        return array(
            'key' => $key,
            'status' => 'ok',
            'msg' => 'ok',
        );
    }

    public function testFormattingString($key)
    {
        $name = trim($this->modx->getOption($key));
        if (empty($name))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_empty',
            );
        }

        preg_match_all('/\[\[\+\+(\w+)\]\]/', $name, $matches);

        if (empty($matches[1]))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_invalid',
            );
        }

        $page_cols = array_keys($this->modx->getFields('modResource'));


        foreach ($this->templates as $tid)
        {
            $cols = $this->getAllFieldsForTemplate($tid);
            $cols = array_merge($page_cols, $cols);

            foreach ($matches[1] as $field)
            {
                if (!in_array($field, $cols))
                {
                    return array(
                        'key' => $key,
                        'status' => 'error',
                        'msg' => $key.'_unknown_fields',
                    );
                }
            }
        }


        return array(
            'key' => $key,
            'status' => 'ok',
            'msg' => 'ok',
        );
    }

    public function getAllFieldsForTemplate($templateid)
    {
        if (!$TVs = $this->modx->getCollectionGraph('modTemplateVarTemplate', '{"TemplateVar":{}}',array('templateid' => $templateid)))
        {
            return array();
        }
        $out = array();
        foreach ($TVs as $t)
        {
            $out[] = $t->TemplateVar->get('name');
        }

        return $out;
    }

    public function testStyle($key)
    {
        $name = trim($this->modx->getOption($key));

        // Empty is ok
        if (empty($name)) {
            return array(
                'key' => $key,
                'status' => 'ok',
                'msg' => 'ok'
            );
        }
        $json = json_decode($name,true);

        if (!is_array($json))
        {
            return array(
                'key' => $key,
                'status' => 'error',
                'msg' => $key.'_invalid'
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
