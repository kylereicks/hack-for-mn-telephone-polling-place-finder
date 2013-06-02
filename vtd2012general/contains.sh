#!/usr/bin/env bash
dir=$(dirname $PWD/$0)
echo "
.headers on
.explain on
.nullvalue null

select * from precincts where Contains(precincts.Geometry, MakePoint(-93.261478,44.970697));
" | spatialite $dir/db.sqlite
