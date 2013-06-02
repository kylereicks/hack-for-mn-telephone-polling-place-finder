#!/usr/bin/env bash
dir=$(dirname $PWD/$0)
rm $dir/db.sqlite

# load the SHP with the geometry in the Geometry26915 column
# Then transform the projection from 26915 to 4326
echo "
.loadshp vtd2012general/vtd2012general precincts CP1252 26915 Geometry26915
select AddGeometryColumn('precincts', 'Geometry', 4326, 'MULTIPOLYGON', 'XY');
update precincts set Geometry = TRANSFORM(Geometry26915, 4326);
.dumpgeojson precincts Geometry precincts.geojson
" | spatialite $dir/db.sqlite
