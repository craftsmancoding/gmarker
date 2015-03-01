<?php namespace Gmarker;

trait ControllerHelpers {
    /**
     * Gotta look up the URL of our CMP and its actions
     * TODO: don't hardcode this
     * @param string $page default: index
     * @param array any optional arguments, e.g. array('action'=>'children','parent'=>123)
     * @return string
     */
    public static function page($page='index',$args=array()) {
        $url = MODX_MANAGER_URL;
        $url .= '?a=index&namespace=gmarker&page='.$page;
        if ($args) {
            foreach ($args as $k=>$v) {
                $url.='&'.$k.'='.$v;
            }
        }

        return $url;
    }
}