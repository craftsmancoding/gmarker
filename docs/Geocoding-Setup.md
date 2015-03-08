If you are not storing location information locally on your site (or you otherwise do not need to operate on collections of latitude/longitude coordinates), then you can skip this section on configuring the Geocoding plugin.

Geocoding address information and storing the latitude/longitude coordinates is useful if your site is storing location information, e.g. store addresses, and you want to display locations on a map.  Once configured, the Geocoding plugin will cause your pages to store latitude and longitude coordinates as Template Variables in addition to the standard address location.

## 1. Create a Dedicated Location Template

In order to geocode addresses, you first need to define one or more page templates that will store/display location information.  A typical scenario would be that you have one template for "Locations" or "Stores" or "People".  It is assumed that you already have a template in use for storing location/address information, so Gmarker does not create one for you.

In your MODX System Settings, list the template ids in the `gmarker.templates` Setting, e.g. `5,8`.  Use commas to separate multiple ids.

## 2. Configure Template Variables 

Your site will need to rely on two TVs to store latitude and longitude information.  Gmarker ships with two template variables that you may use for this purpose: `lat` and `lng`.

If you already have TVs you are using to store latitude and longitude information, then update the `gmarker.lat_tv` and `gmarker.lng_tv` System Settings to refer to the names of your existing TVs.

Finally, you must ensure that these TVs are associated with your location templates.  For each of your location templates, add both the latitude and longitude TVs.

WARNING: In order for the geocoding process to work, the `lat` nad `lng` TVs (or your custom alternatives) _must_ be assigned to each of your location templates (as defined in the `gmarker.templates` System Settings).

It is common for your location templates to include TVs such as "city" and "state", but they are not strictly required.  



## 3. Set Formatting String

The final configuration that is required for geocoding is to define a formatting string in the `gmarker.formatting_string` System Setting.  This behaves like a MODX chunk which formats data from your location pages into a useable address string, i.e. into what you might type into a search field on Google Maps.  

The syntax for the for formatting string should use standard MODX placeholders with a "+" symbol.  The placeholders are named after the available TVs, e.g.:

```
[[+address]], [[+city]], [[+state]]
```

You can also use any fields from your MODX pages, e.g. if you store a full address inside the pagetitle field, your formatting string might look like:

```
[[+placeholder]]
```

This is an appropriate time to review some of the other [Gmarker System Settings](System-Settings.md) which can help autocomplete incomplete addresses.  For example, if all of your locations are in New Zealand, you can set the `gmarker.components` value to `country:NZL`.  Likewise, if you want to restrict results to a certain region by latitude and longitude, you can set a region for the `gmarker.bounds` setting.  Careful configuration of these advanced settings can help you avoid adding fields to your locations.


