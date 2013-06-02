The state gis agency has a [much nicer polling place finder](http://www.gis.leg.mn/OpenLayers/precincts/) than the one on the SOS website[1].

This takes the shapefile they published of the 2012 precinct boundaries
and loads it into a lightweight GIS database. Then it can ask what
precinct contains a given lat/lng.

[1] Although

> PLEASE NOTE: This Web application is intended to provide general
information only.  Due to limitations in the data, the District Finder
may incorrectly identify which district you live in, especially if the
address is near the boundary of the district (where errors are more
likely to occur).  Please examine the map to determine whether your
address is near the boundary.  If it is, we recommend that you contact
your county elections office.

Install
===

* Install [spatialite](http://www.gaia-gis.it/gaia-sins/) (`brew install
spatialite`) and [geocoder](http://www.rubygeocoder.com) (`gem install geocoder`)
* `./vtd2012general/create_db.sh`
* `./vtd2012general/precinct_for 'south minneapolis, mn'`

[2012 Minnesota Precinct data](http://www.gis.leg.mn/metadata/vtd2012.htm#ordering)
