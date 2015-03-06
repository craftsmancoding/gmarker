The Geocoding Plugin takes address information added to a MODX page and uses it to set dedicated latitude and longitude Template Variables.  This functionality will only work if the page being saved is listed in the *gmarker.templates* System Setting and if valid TVs have been defined in the *gmarker.lat_tv* and *gmarker.lng_tv* System Settings.

If no templates are defined in the *gmarker.templates* Setting, then the plugin will not take any action.

## Events

The Geocoding Plugin fires on the following events:

 * OnDocFormSave

## Configuration

The following [System Settings](System-Settings.md) _must_ be defined in order for this to work:

* *gmarker.formatting_string*
* *gmarker.templates*
* *gmarker.lat_tv*
* *gmarker.lng_tv*

The other System Settings may also affect behavior. See the [System Settings](System-Settings.md) page for details about each setting.