## 1. Download the AddOn

First, download the Extra from the MODX repository or via the manager in your MODX site.

## 2. Get a Google Maps API Key

An API key is not _strictly_ required, but it is _recommended_, especially on shared hosts: without an API key, Google will use the server IP address to track requests, so on a shared host it's more likely that the request quota is quickly used up.

[Click here to get your Google API key](https://developers.google.com/maps/documentation/javascript/tutorial#api_key).

Enable the following 2 services (as pictured):

* Google Maps API v3
* Static Maps API

![Enable Google Services](https://www.evernote.com/shard/s17/sh/c190c33b-1df7-4a70-86b3-b5b934a7cc1a/c9ce566dd01273df0e4b453518a1c29e/res/3e3f626a-a29e-469d-9d20-f269545c2e4e/skitch.png?resizeSmall&width=832)

After you've enabled these services, you can copy your API key by clicking on the *API Access* link;

![get your API Key](https://www.evernote.com/shard/s17/sh/d0d1828e-aee3-4347-ad61-10c4caca1a0a/c406855d47622960147df71de5e731a5/res/2ca8d829-bda4-4ef5-bef0-3c44c8d31545/skitch.png?resizeSmall&width=832)

## 3. Save the API Key
 
Inside your MODX manager, go to *System -> Settings* and paste your key into the *gmarker.apikey* setting. 

Make sure the value gets saved correctly!

## 4. Create a Dedicated Location Template

If you haven't already, set up your site so it's viably storing location information on your pages.  A typical scenario would be that you have one template for "Locations" or "Stores" or "People", and each using Template Variables (TVs) like "address", "city", "state", "zip".

You must also create 2 additional TVs:  one for latitude and one for longitude.  You can name them whatever you like, but they should be either text fields or hidden fields.  This is where the Geocoding Plugin will save latitude and longitude data.  Make sure these TVs have been added to the template.

INFO: It is possible to use the Gmarker Extra without storing any location information on your site (you can manually provide address information to your maps if desired) but the Gmarker Snippet in particular was built expecting that your local pages contain address data.

When you save this template, take note of its ID -- you'll need it in the next step.  The template ID appears under the Elements tab: when you view all of your templates, the id appears in parentheses, e.g. "MyTemplate (12)" -- in this example "12" is the template ID.

## 5. Update System Settings

Now that you have a template that will store address information, you need to update your System Settings again.  You need to tell Gmarker which template(s) contain address information.  

Set the *gmarker.templates* setting to the template ID of the template(s) you created in the previous step.

Next, set the *gmarker.lat_tv* to the name of the TV that you created in the previous step.  For example, if you named your TV "latitude", then enter "latitude" into the *gmarker.lat_tv* System Setting.

Do the same for the *gmarker.lng_tv*: set it to the name of the TV that you created in the previous step.

Finally, you need to update the value for the *gmarker.formatting_string*.  This is how we tell Google what our address is.  If your pages use TVs for "address", "city", and "state", then your formatting string might look like this:

```
[[+address]], [[+city]], [[+state]]
```

Remember to use the "+" version of any field name or TV.  Do not use the "*" variation of TVs!

## 6. Testing

Now that you have configured the Gmarker Extra, try creating a new location page.  Be sure to choose the template you created previously.  Enter in some address data and save the page.  Then refresh your browser: you should see the latitude and longitude data appear in your dedicated TVs!

If you don't see this, check the MODX error logs.

## See Also

* [Geocoding Plugin](Geocoding-Plugin.md)
* [Gmap Snippet](Gmap-Snippet.md)