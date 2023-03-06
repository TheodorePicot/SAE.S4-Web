-- Link to the tutorial
-- https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/Appendix.PostgreSQL.CommonDBATasks.PostGIS.html

CREATE ROLE gis_admin LOGIN PASSWORD 'postgis';
GRANT rds_superuser TO gis_admin;
CREATE DATABASE lab_gis;
GRANT ALL PRIVILEGES ON DATABASE lab_gis TO gis_admin;

CREATE EXTENSION postgis;

CREATE EXTENSION postgis_raster;

CREATE EXTENSION fuzzystrmatch;

CREATE EXTENSION postgis_tiger_geocoder;

CREATE EXTENSION postgis_topology;

CREATE EXTENSION address_standardizer_data_us;

SET search_path TO public, "sae-s4", tiger, tiger_data, topology;
