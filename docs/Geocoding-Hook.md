The Gmarker Snippet can be used as a regular Snippet or as a hook (e.g. for the FormIt Snippet). See https://github.com/craftsmancoding/gmarker/wiki/Glocation-Snippet[Glocation Snippet] for how to use this as a regular stand-alone Snippet.

When used as a hook (e.g. for Formit or for the Login's Register Snippet), the Snippet parameters are irrelevant and you must instead rely on the system settings or on data passed in the form. Any fields referenced in your *gmarker.formatting_string* must be available as form fields and accessible via the $hook->getValue() method.  After executing, the Glocation hook will add 2 fields named after the *gmarker.lat_tv* and *gmarker.lng_tv*.  This is useful if another hook writes this data to the database.

## Example

Make sure your form passes all the information required to construct a valid address using your formatting string. E.g. if your gmarker.formatting_string is "[[+address]],[[+city]],[[+state]]", then the following FormIt call could be used to perform Geocoding:

 [[!FormIt?
   &hooks=`Glocation,write2database`
   &emailTpl=`MyEmailChunk`
   &emailTo=`me@mail.com`
   &redirectTo=`12`
   &validate=`name:required,
      email:email:required,
      city:required,
      state:required:stripTags,
      zip:required,
      address:required`
 ]]
 <form action="[[~[[*id]]]]" method="post" class="form">
    <!-- other form fields -->
    <input type="text" name="address" id="address" value="[[!+fi.address]]" />
    <input type="text" name="city" id="city" value="[[!+fi.city]]" />
    <input type="text" name="state" id="state" value="[[!+fi.state]]" />
    <input type="text" name="zip" id="zip" value="[[!+fi.zip]]" />
    <input type="submit" value="Submit" />
 </form>

Assuming that *gmarker.lat_tv* were set to "latitude" and the *gmarker.lng_tv* were set to "longitude" in this example, then the location values would be appended to the other form data:

### Before:

 Array
 (
    [address] => 515 S Flower St
    [city] => Los Angeles
    [state] => CA
    [zip] => 90071
 )

### After:

 Array
 (
    [address] => 515 S Flower St
    [city] => Los Angeles
    [state] => CA
    [zip] => 90071
    [latitude] => 34.0512905
    [longitude] => -118.2567286
 )

The additional values would be available via the $hook object in your subsequent hook-snippets:

 $hook->getValue('latitude');
 $hook->getValue('longitude');

## Parameters

You can also pass hidden form fields to specify the values for the following options:

* *gmarker.lat_tv*  _(defaults to System Setting)_
* *gmarker.lng_tv*  _(defaults to System Setting)_
* *gmarker.bounds*  _(defaults to System Setting)_
* *gmarker.components*  _(defaults to System Setting)_
* *gmarker.region*  _(defaults to System Setting)_
* *gmarker.language*  _(defaults to System Setting)_

WARNING: The *gmarker.formatting_string* _cannot_ be set via a hidden field: the System Setting _must_ be set.  This is because placeholder tags are parsed if they are placed on the page.

### Overriding System Settings

```
 <input type="hidden" name="gmarker.lat_tv" value="lat" />
```

This would cause the latitude information to be written to a new field named "lat", regardless of the *gmarker.lat_tv* System Setting.  Subsequent hooks could access the data by referencing that name:

```
 $hook->getValue('lat');
```