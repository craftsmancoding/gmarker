<?php namespace Gmarker;
/**
 * Check license
 */


class License {

    public $store_url = 'https://craftsmancoding.com/products/'; // store_url
    public $product_url = 'https://craftsmancoding.com/products/downloads/gmarker/';
    public $plugin = 'Gmarker'; // item name from store
    public $setting = 'gmarker.license_key';
    public $license_check_frequency_in_days = 7;

    // Move to JSON to avoid:
    // Fatal error: Call to undefined method stdClass::__set_state()
    public $cacheOptions = array(
        //\xPDO::OPT_CACHE_KEY => 'myCache',
        //\xPDO::OPT_CACHE_HANDLER => 'cache.xPDOFileCache',
        \xPDO::OPT_CACHE_FORMAT => \xPDOCacheManager::CACHE_JSON
    );
    public $modx;

    public function __construct($modx)
    {
        $this->modx = $modx;
    }

    /**
     * activate the license, return true on success
     *
     * @param string $license_key
     *
     * @return bool if activation succeeded or false if it failed
     */
    public function activate($license_key) {

        $license_key = trim($license_key);

        if ($response = $this->remote_check_license($license_key,'activate_license'))
        {
            $this->modx->log(\xPDO::LOG_LEVEL_INFO, 'Activated Gmarker License:' .print_r($response,true));
            // We append the license key to the response to store it for comparison later
            $response['key'] = $license_key;
            $this->modx->cacheManager->set($this->setting.$license_key, $response, 60*60*24*$this->license_check_frequency_in_days, $this->cacheOptions);

            if($response['success']) {
                return true; // <-- legit activation here
            }
        }
        // Remote request failed!
        // We will assume it's good and check again tomorrow
        else
        {
            $response = array();
            $response['key'] = $license_key;
            $response['license'] = 'valid';
            $this->modx->cacheManager->set($this->setting.$license_key, $response, 60*60*24*1, $this->cacheOptions);
            return true;
        }

        return false;
    }

    /**
     * Check that the license is valid.
     * cache the result using set_transient
     *
     * @param string $license_key
     * @return string status e.g. 'valid' or 'inactive' or 'expired'
     */
    public function check($license_key) {
        if (!$license_key)
        {
            return 'invalid';
        }

        // Data comes back from cache as an array (not as an object)
        $cached_data = $this->modx->cacheManager->get($this->setting.$license_key, $this->cacheOptions);

        if ($cached_data && isset($cached_data['license'])) {
            $this->modx->log(\xPDO::LOG_LEVEL_INFO, 'Retrieving license info from Cache');
            return $cached_data['license']; // *facepalm* -- status (e.g. "valid") is stored in the 'license' key.
        }
        elseif($response = $this->remote_check_license($license_key,'check_license'))
        {
            $this->modx->log(\xPDO::LOG_LEVEL_INFO, 'MODX Backups -- checking remote server for license status: '. print_r($response,true));
            // We append the license key to the response to store it for comparison later
            $response['key'] = $license_key;
            $this->modx->cacheManager->set($this->setting.$license_key, $response, 60*60*24*$this->license_check_frequency_in_days, $this->cacheOptions);
            $status = $response['license'];
            return $status;
        }
        // Remote request failed!
        // We punt: assume the license is good and check again tomorrow
        else
        {
            $response = array();
            $response['key'] = $license_key;
            $response['license'] = 'valid'; //
            $this->modx->cacheManager->set($this->setting.$license_key, $response, 60*60*24*1, $this->cacheOptions);
            return 'valid';
        }

    }

    /**
     * Call the remote server to validate the given license key.
     * See http://docs.easydigitaldownloads.com/article/384-software-licensing-api
     *
     * We use simple file_get_contents() because curl is not
     * always available...
     * Format of 'activate_license' response is like this:
     *
     * (
     * 	    [success] => 1
     * 	    [license_limit] => 0
     * 	    [site_count] => 1
     * 	    [activations_left] => unlimited
     * 	    [license] => valid
     * 	    [item_name] => ZZZZZZ
     * 	    [expires] => YYYY-MM-DD HH:ii:ss
     * 	    [payment_id] => xxx
     * 	    [customer_name] => XXXX YYYY
     * 	    [customer_email] => xxxx@mail.com
     * )
     *
     * Format of 'check_license' response is like this:
     * (
     *	    [license] => valid
     *	    [item_name] => ZZZZZZZ
     *	    [expires] => 2015-12-09 14:56:54
     *	    [payment_id] => xxx
     *	    [customer_name] => XXXX YYYY
     *	    [customer_email] => xxxx@mail.com
     * )
     *
     * @param string $license_key
     * @param string $action activate_license | check_license
     *
     *@return mixed
     */
    public function remote_check_license($license_key, $action='check_license')
    {
        $api_params = array(
            'edd_action'=> $action,
            'license' 	=> $license_key,
            'item_name' => urlencode( $this->plugin ), // the unique name of our product in EDD,
            'url'       => $this->modx->getOption('site_url'),
            'rand' => uniqid() // cache-busting
        );

        $endpoint = $this->store_url .'?'. http_build_query($api_params);

        $response = @file_get_contents($endpoint);

        if ($response == false)
        {
            $this->modx->log(\xPDO::LOG_LEVEL_ERROR, '['.$this->plugin.'] There was a problem accessing the remote server: '.$this->store_url);
            return false;
        }

        $response = json_decode($response,true); // Decode as array
        if (!is_array($response))
        {
            $this->modx->log(\xPDO::LOG_LEVEL_ERROR,'['.$this->plugin.'] The response from the remote server was not JSON: '.$this->store_url);
            return false;
        }

        return $response;
    }

}