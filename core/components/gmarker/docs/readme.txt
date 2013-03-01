--------------------
Extra: Gmarker
--------------------
Version: 0.1
First Released: October 2012
Author: Everett Griffiths <everett@craftsmancoding.com>
License: GNU GPLv2 (or later at your option)

Implements version 3.9 of the Google Maps API. 

See this video for a brief demo: https://www.youtube.com/watch?v=dayu6RXZld4

---------
Features: 
---------
- Geocoding of pages that contain address information (i.e.
you can automatically determine latitude and longitude) via 
the Geocoding plugin.

- Quickly draw maps via the Gmap snippet -- just specify an &address!

- Easily draw markers on maps with the Gmarker snippet.

- MODX caching is leveraged so you do not hit the Google API 
more than is strictly necessary.

-------
Setup
-------
1. After installing this AddOn, head to your MODX System Settings (System -> System Settings).
View all "gmarker" settings, then set the gmarker.apikey to your Google API key -- be sure 
you enable the Google Maps and Google Static Maps in your Google control panel.

2. If you haven't already, create a template dedicated to storing address information.  It 
could use TVs for "address", "city", "state" and so on.

3. Be sure to create dedicated template variables for storing latitude and longitude information.
These can be text fields or hidden fields: you should let the Gmarker AddOn write to them automatically.
Add these TVs to your location template(s) (see previous step).

4. Back in the System Settings, be sure you specify the names of the TVs you created in the previous
step.  You do this to tell the plugin where it should store latitude and longitude information.

5. In System Settings, set the gmarker.templates setting to include a list of any templates (from 
step 2) that contain address information and for which you want to lookup latitude and longitude coordinates.

6. Ensure that the plugin is tied to the "OnDocFormSave" event.

---------
Examples:
---------

[[Gmap? &address=`Times Square, New York, NY` &height=`300` &width=`800` &zoom=`5`]]

[[Gmarker? &address=`Times Square, New York, NY` &height=`300` &width=`800` &zoom=`5` &parents=`4,5`]]

[[Glocation? address=`Times Square, New York, NY`]]


See https://github.com/craftsmancoding/gmarker for more info and examples.

Thanks for using Gmarker!

Everett Griffiths
everett@carftsmancoding.com