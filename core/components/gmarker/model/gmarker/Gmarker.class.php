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
	
	// Google Maps URLs
	public $maps_http = 'http://maps.google.com/maps/api/js?sensor=false';
	public $maps_https = 'https://maps.google.com/maps/api/js?sensor=false';

	// Geocoding URLs
	public $geocoding_http = 'http://maps.googleapis.com/maps/api/geocode/json';
	public $geocoding_https = 'https://maps.googleapis.com/maps/api/geocode/json';

	// Static Maps URLs
	public $static_http = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false';
	public $static_https = 'https://maps.googleapis.com/maps/api/staticmap?sensor=false';



	/**
	 *
	 */
	public function __construct() {
		global $modx;
		$this->modx = $modx;
	}



	/**
	 * Formats a string message for use as a Javascript alert
	 *
	 * @param string  $msg
	 * @return string
	 */
	public function alert($msg) {
		return '<script type="text/javascript"> alert('.json_encode($msg).'); </script>';
	}



	/**
	 * Generate a unique fingerprint of the input parameters that would affect
	 * the results of a lookup.  All properties passed to this should be the props
	 * that would uniquely identify an address, e.g. address, city, state, zip
	 *
	 * @param array
	 * @param unknown $props
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
	 * Read item out of Geocoding JSON
	 *
	 * @param unknown $key
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
	 * Gets one color for each distinct input $val.  Used when &group is set.
	 *
	 * @param string $val
	 * @param string $id (optional)
	 * @return string corresponding to HTML color code
	 */
	public function get_color($val, $id=0) {
		$color_set = array('e3947a','e32d7a','b70004','65c2f5','8e6fec', '79f863','f1ee69','BE9073','FB2FC8','90C57F','0AA87B','38BCB9','ACDA3B','39DBF0','9E6CA3','1042F1','48F6A1','5B49CD','B6DE82','5871A7','B2E5A3','D52213','AD54D7','8E3B32','4E0160','F88439','E7460B','F0EBA1','8962BF','A540D0','72E5C8','405EDB','032543','C329B3','FCF922','0FA4BD','354FA8','1019BC','2E5BE6','438921','197238','6D4134','FAB7AD','921536','1EF0E8','8576E1','5B4F2D','A8EB6E','E57611','2858F9','6E53AA','B5B6B4','9B3EDF','21BFE2','771257','22BECC','ED8099');
		if (!isset($this->colors[$val])) {
			$this->colors[$val] = $color_set[$id]; // <-- new random color here
		}

		return $this->colors[$val];
	}

	/**
	 * Get the URL for the Google Maps API service, optionally includin other keys.
	 * See https://developers.google.com/maps/documentation/javascript/tutorial#api_key
	 *
	 * @param array   (optional) any properties to append to the URL
	 * @param boolean (optional) whether or not to use the secure version of the URL
	 * @param unknown $props  (optional)
	 * @param unknown $secure (optional)
	 * @return string the URL of the service
	 */
	public function get_maps_url($props=array(), $secure=true) {
		$url = '';
		if ($secure) {
			$url = $this->maps_https;
		}
		else {
			$url = $this->maps_http;
		}

		foreach ($props as $k => $v) {
			if ($v) {
				$url .= '&'.$k.'='.trim($v);
			}
		}

		return $url;
	}



	/**
	 * Where the magic happens: JSON is loaded either from cache (when possible) or
	 * from a query to the Google Geocoding API. The JSON is then read to be queried
	 * for data via the $this->get() method.
	 *
	 * @props array $props required for a lookup
	 * @props boolean $secure 1 for https, 0 for http
	 * @props boolen $refresh 1 to ignore cache and force api query
	 * @return string JSON data
	 */
	public function lookup($props, $secure, $refresh=false) {
		// Fingerprint the lookup
		$fingerprint = $this->fingerprint($props);

		$json = $this->modx->cacheManager->get($fingerprint, $this->cache_opts);

		// if $refresh OR if not fingerprint is not cached, then lookup the address
		if ($refresh || empty($json)) {
			// Perform the lookup
			$json = $this->query_api($props, $secure);

			// Cache the lookup
			$this->modx->cacheManager->set($fingerprint, $json, $this->lifetime, $this->cache_opts);
		}

		$this->set_json($json);

		return $json;
	}



	/**
	 * Hit the Google GeoCoding API service: this function builds the URL
	 * See http://stackoverflow.com/questions/6976446/google-maps-geocode-api-inconsistencies
	 *
	 * @param array   $props  defining the search
	 * @param boolean $secure (optional) whether or not the lookup should use HTTPS
	 * @return string JSON result
	 */
	public function query_api($props, $secure=false) {
		$url = $this->geocoding_http;
		if ($secure) {
			$url = $this->geocoding_https;
		}
		$url .= '?sensor=false';

		// Special cleaning of the address: no extra spaces, then all spaces to +
		$props['address'] = preg_replace('/\s+/', ' ', $props['address']);
		$props['address'] = str_replace(' ', '+', $props['address']);
		
		foreach ($props as $k => $v) {
			if (!empty($v)) {
				//$url .= preg_replace('/\s+/', ' ', "&$k=".trim($v));
				$url .= "&$k=".urlencode($v);
			}
		}

		$this->modx->log(xPDO::LOG_LEVEL_DEBUG, "[Gmarker] query URL: $url");

		return file_get_contents($url);
	}

	/**
	 * Get a random HTML color
	 * 
	 * @return string
	 */
	public function rand_color() {
		$chars = "ABCDEF0123456789";
		$size = strlen( $chars );
		$str = array();

		for ( $j = 0; $j < 6; $j++ ) {
			if (isset($str[$j])) {
				$str[$j] .= $chars[ rand( 0, $size - 1 ) ];
			}
			else {
				$str[$j] = $chars[ rand( 0, $size - 1 ) ];
			}
		}

		return implode('', $str);
	}

	/**
	 * This takes a JSON string, converts it to a PHP array
	 *
	 * @param string  JSON array
	 * @param unknown $json
	 */
	public function set_json($json) {
		$this->json = json_decode($json, true);
	}

}


/*EOF*/