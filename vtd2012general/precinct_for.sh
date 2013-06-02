#!/usr/bin/env bash
dir=$(dirname $PWD/$0)
latlng=$(geocode "$1" | head -2 | sort -r | cut -d ':' -f2 | tr -d ' ' | paste -sd ',' -)
echo $latlng

echo "
select PCTNAME,PCTCODE from precincts where Contains(precincts.Geometry, MakePoint($latlng));
" | spatialite $dir/db.sqlite | tail -n1


