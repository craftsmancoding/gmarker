<?php
/**
 * API Docs: https://developers.google.com/maps/documentation/geocoding/
 * Google Docs: https://developers.google.com/maps/documentation/javascript/reference
 * See also: http://gmap3.net/  (features animated pin drops)
 * Static Maps:
 * 		https://developers.google.com/maps/documentation/staticmaps/
 * Info Window:
 * 		https://developers.google.com/maps/documentation/javascript/reference#InfoWindow
 * Animations:
 * 		https://google-developers.appspot.com/maps/documentation/javascript/examples/marker-animations-iteration?hl=sk-SK
 * 		https://google-developers.appspot.com/maps/documentation/javascript/examples/marker-animations?hl=sk-SK
 * 		https://developers.google.com/maps/documentation/javascript/examples/?hl=sk-SK
 * Static Example:
 * 		http://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap
 * &markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
 * &markers=color:red%7Ccolor:red%7Clabel:C%7C40.718217,-73.998284&sensor=false
 * Marker Maps:
 * 		https://developers.google.com/maps/documentation/javascript/controls
 * 		http://gmarker-samples-v3.googlecode.com/svn/trunk/toomanymarkers/toomanymarkers.html
 *
 * Some Icon URLS:
 * 	http://maps.google.com/mapfiles/ms/icons/green-dot.png
 * 	http://maps.google.com/mapfiles/ms/icons/blue-dot.png
 * 	http://maps.google.com/mapfiles/ms/icons/red-dot.png
 *
 * See:
 * 	http://stackoverflow.com/questions/7095574/google-maps-api-3-custom-marker-color-for-default-dot-marker
 * 	http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|FE7569
 *
 * Building a valid URL:
 * 	https://developers.google.com/maps/documentation/webservices/#BuildingURLs
 *
 * Detecting a Mobile Device:
 * 	http://code.google.com/p/php-mobile-detect/
 *
 * @package gmarker
 */


class Gmarker {

	public $cache_opts = array(xPDO::OPT_CACHE_KEY => 'gmarker');
	public $lifetime = 0; // seconds cached lat/lng should live. 0 = forever
	public $json;
	public $modx;
	public $colors = array();

	// Geocoding API URLs
	public $geocoding_http = 'http://maps.googleapis.com/maps/api/geocode/json';
	public $geocoding_https = 'https://maps.googleapis.com/maps/api/geocode/json';

	// Static Maps URLs
	public $static_http = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false';
	public $static_https = 'https://maps.googleapis.com/maps/api/staticmap?sensor=false';



	/**
	 *
	 */
	public function __construct($modx) {
		$this->modx = $modx;
	}

	/**
	 * A function devoted to determining whether the given $str represents lat,lng coordinates.
	 * @param $str
	 * @return boolean
	 */
	public function isLatLng($str)
	{
		$commas = substr_count($str,',');
		$dots = substr_count($str,'.');
		if ($commas == 1 && $dots == 2)
		{
			list($lat,$lng) = explode(',', $str);
			if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Generate a unique fingerprint of the input parameters that would affect
	 * the results of a lookup.  All properties passed to this should be the props
	 * that would uniquely identify an address, e.g. address, city, state, zip
	 *
	 * @param array $props
	 * @return string
	 */
	public function fingerprint($props) {

		foreach ($props as $k => $v) {
			$v = trim($v);
			$v = preg_replace('/\./', ' ', $v); // periods are not significant
			$v = preg_replace('/\s+,/', ',', $v);
			$v = preg_replace('/,\s+/', ',', $v);
			$v = preg_replace('/\s+/', ' ', $v);
			$props[$k] = $v;
		}

		return md5(print_r($props, true));
	}



	/**
	 * Read item out of Geocoding JSON response
	 *
	 * @param string $key
	 * @return string
	 */
	public function get($key) {

		switch ($key) {
		case 'formatted_address':
			if (isset($this->json['results'][0]['formatted_address'])) {
				return $this->json['results'][0]['formatted_address'];
			}
			break;
		case 'location.lat':
			if (isset($this->json['results'][0]['geometry']['location']['lat'])) {
				return $this->json['results'][0]['geometry']['location']['lat'];
			}
			else {
				return 0; // include a valid default.
			}
			break;
		case 'location.lng':
			if (isset($this->json['results'][0]['geometry']['location']['lng'])) {
				return $this->json['results'][0]['geometry']['location']['lng'];
			}
			else {
				return 0; // include a valid default.
			}
			break;
		case 'northeast.lat':
			if (isset($this->json['results'][0]['geometry']['viewport']['northeast']['lat'])) {
				return $this->json['results'][0]['geometry']['viewport']['northeast']['lat'];
			}
			break;
		case 'northeast.lng':
			if (isset($this->json['results'][0]['geometry']['viewport']['northeast']['lng'])) {
				return $this->json['results'][0]['geometry']['viewport']['northeast']['lng'];
			}
			break;
		case 'southwest.lat':
			if (isset($this->json['results'][0]['geometry']['viewport']['southwest']['lat'])) {
				return $this->json['results'][0]['geometry']['viewport']['southwest']['lat'];
			}
			break;
		case 'southwest.lng':
			if (isset($this->json['results'][0]['geometry']['viewport']['southwest']['lng'])) {
				return $this->json['results'][0]['geometry']['viewport']['southwest']['lng'];
			}
			break;
		case 'location_type':
			if (isset($this->json['results'][0]['geometry']['location_type'])) {
				return $this->json['results'][0]['geometry']['location_type'];
			}
			break;
		case 'status':
			if (isset($this->json['status'])) {
				return $this->json['status'];
			}
			break;
		}

		return $this->modx->lexicon('node_not_found', array('node'=>$key));
	}


	/**
	 * This function acts as a gateway protector to the API.  Results are loaded either from cache (when possible) or
	 * from a query to the Google Geocoding API. Access the data using $this->get()
	 *
	 * @props array $props required for a lookup (&address, etc)
	 * @props boolean $secure 1 for https, 0 for http
	 * @props boolean $refresh 1 to ignore cache and force api query
	 * @return string JSON data
	 */
	public function lookup($props, $secure=1, $refresh=0) {
		// Fingerprint the lookup
		$fingerprint = $this->fingerprint($props);

		$json = $this->modx->cacheManager->get($fingerprint, $this->cache_opts);

		// if $refresh OR if not fingerprint is not cached, then lookup the address
		if ($refresh || empty($json)) {
			// Perform the lookup
			$json = $this->queryApi($props, $secure);

			// Cache the lookup
			$this->modx->cacheManager->set($fingerprint, $json, $this->lifetime, $this->cache_opts);
		}

		$this->set_json($json);

		return $json;
	}



	/**
	 * Hit the Google GeoCoding API service: this function builds the URL. Remember to URL-encode your values:
	 * See http://stackoverflow.com/questions/6976446/google-maps-geocode-api-inconsistencies
	 *
	 * @param array   $props  defining the search
	 * @param boolean $secure (optional) whether or not the lookup should use HTTPS
	 * @return string JSON result
	 */
	public function queryApi($props, $secure=false) {
		$url = $this->geocoding_http;
		if ($secure) {
			$url = $this->geocoding_https;
		}
		$url .= '?sensor=false'; // TODO: no longer required

		// Special cleaning of the address: no extra spaces, then all spaces to +
		$props['address'] = preg_replace('/\s+/', ' ', $props['address']);
		$props['address'] = str_replace(' ', '+', $props['address']);
		
		foreach ($props as $k => $v) {
			if (!empty($v)) {
				$url .= "&$k=".urlencode($v);
			}
		}

		$this->modx->log(xPDO::LOG_LEVEL_DEBUG, "[Gmarker] query URL: $url");
		
		$ch = curl_init();
	 
	    	curl_setopt($ch, CURLOPT_URL, $url); 
	    	curl_setopt($ch, CURLOPT_HEADER, 0);
	    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    	curl_setopt($ch, CURLOPT_TIMEOUT, 10);

	    	$curlOutput = curl_exec($ch);
	 
	    	curl_close($ch);

		return $curlOutput;
	}


	/**
	 * This takes a JSON string, converts it to a PHP array
	 *
	 * @param string $json JSON array
	 */
	public function set_json($json) {
		$this->json = json_decode($json, true);
	}

}


/*EOF*/
