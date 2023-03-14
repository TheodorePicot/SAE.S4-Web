-- =====================================================================================================================
-- Voici la requête la plus optimisée
-- =====================================================================================================================
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_troncon tr
         join lateral (select nr.gid, nr.geom
                        from view_gid_geom_routier nr
                        where nr.gid = :gidTag) as nr
              on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                  or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
         join lateral (select nr2.gid, nr2.geom
                       from view_gid_geom_routier nr2
                       where nr2.gid != :gidTag) as nr2
              on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                  or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001);

-- =====================================================================================================================
-- Ajout de clé primaire sur gid pour
-- =====================================================================================================================

alter table troncon_route
    add constraint troncon_route_pk
        primary key (gid);

alter table noeud_routier
    add constraint noeud_routier_pk
        primary key (gid);

alter table noeud_commune
    add constraint noeud_commune_pk
        primary key (gid);

-- Index sur l'attribut geom de noeud Routier

CREATE INDEX idx_gid_geom_noeud_routier
    ON noeud_routier
        USING gist (geom);

-- Index sur l'attribut geom de troncon route

CREATE INDEX idx_gid_geom_troncon_route
    ON troncon_route
        USING gist (geom);


-- Vue noeudRoutier GID GEOM

CREATE MATERIALIZED VIEW view_gid_geom_routier AS
SELECT gid, geom
FROM noeud_routier;

-- Vue troncon Route GID GEOM

CREATE MATERIALIZED VIEW view_gid_geom_troncon AS
SELECT gid, geom, longueur
FROM troncon_route;


CREATE TABLE voisins AS
select nr.gid as noeud_routier_base, nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_troncon tr
         join lateral ( select nr.gid, nr.geom
                        from view_gid_geom_routier nr) as nr
              on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                  or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
         join lateral (select nr2.gid, nr2.geom
                       from view_gid_geom_routier nr2
                       where nr2.gid != nr.gid) as nr2
              on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                  or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001);

CREATE INDEX idx_gid_voisins
    ON voisins
        USING hash (noeud_routier_base);

DROP INDEX idx_gid_voisins;