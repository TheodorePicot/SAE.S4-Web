--
--
--                     Création des indexes sur l'attribut geom de noeud_routier et troncon_route
--
-- =====================================================================================================================
--                                                     Winner !
-- =====================================================================================================================
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_troncon tr
         join lateral ( select nr.gid, nr.geom
                        from view_gid_geom_routier nr
                        where nr.gid = :gidTag) as nr
              on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                  or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
         join lateral (select nr2.gid, nr2.geom
                       from view_gid_geom_routier nr2
                       where nr2.gid != :gidTag) as nr2
              on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                  or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001);

SELECT *
from noeud_commune
where nom_comm = 'Dieppe';
SELECT *
from noeud_commune
where nom_comm = 'Varengeville-sur-Mer';


-- =====================================================================================================================
--                                          Requete getVoisins de base
-- =====================================================================================================================

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from noeud_routier nr,
     troncon_route tr,
     noeud_routier nr2
where (st_distancesphere(nr.geom, st_startpoint(tr.geom)) < 1
    and st_distancesphere(nr2.geom, st_endpoint(tr.geom)) < 1
    and nr.gid = :gidTag)
union
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from noeud_routier nr,
     troncon_route tr,
     noeud_routier nr2
where (st_distancesphere(nr2.geom, st_startpoint(tr.geom)) < 1
    and st_distancesphere(nr.geom, st_endpoint(tr.geom)) < 1
    and nr.gid = :gidTag);

-- =====================================================================================================================
--                                          Requete getVoisins de base avec st_dwithin
-- =====================================================================================================================

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from noeud_routier nr,
     troncon_route tr,
     noeud_routier nr2
where (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
    and st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
    and nr.gid = :gidTag)
union
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from noeud_routier nr,
     troncon_route tr,
     noeud_routier nr2
where (st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
    and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
    and nr.gid = :gidTag);

-- =====================================================================================================================
--                                          Requete getVoisins avec st_dwithin et vue
-- =====================================================================================================================

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_routier nr,
     view_gid_geom_troncon tr,
     view_gid_geom_routier nr2
where (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
    and st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
    and nr.gid = :gidTag)
union
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_routier nr,
     view_gid_geom_troncon tr,
     view_gid_geom_routier nr2
where (st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
    and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
    and nr.gid = :gidTag);

-- =====================================================================================================================
--                                                       Cross Join
-- =====================================================================================================================

select noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_routier nr,
     view_gid_geom_troncon tr
         CROSS JOIN LATERAL (
         SELECT nr2.gid
         FROM view_gid_geom_routier nr2
         where (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
             and st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
             and nr.gid = :gidTag)
         ) as noeud_routier_gid
union
select noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from view_gid_geom_routier nr,
     view_gid_geom_troncon tr
         CROSS JOIN LATERAL (
         SELECT nr2.gid
         FROM view_gid_geom_routier nr2
         where (st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
             and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
             and nr.gid = :gidTag)
         ) as noeud_routier_gid;

-- =====================================================================================================================
-- Avec join et union --
-- =====================================================================================================================

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from troncon_route tr
         join noeud_routier nr
              on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
         join noeud_routier nr2
              on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
where nr.gid = :gidTag
union
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from troncon_route tr
         join noeud_routier nr2
              on st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
         join noeud_routier nr
              on st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
where nr.gid = :gidTag;

-- =====================================================================================================================
-- Avec uniquement join --
-- =====================================================================================================================

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
from troncon_route tr
         join noeud_routier nr
              on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001) or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
         join noeud_routier nr2
              on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001) or
                 st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
where nr.gid = :gidTag;


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

-- Index sur les vues

CREATE INDEX idx_geom_view_noeud_routier
    ON view_gid_geom_routier
        USING gist (geom);

CREATE INDEX idx_gid_view_noeud_routier
    ON view_gid_geom_routier
        USING btree (gid);

CREATE INDEX idx_geom_view_troncon_route
    ON view_gid_geom_troncon
        USING gist (geom);

CREATE INDEX idx_gid_view_troncon_route
    ON view_gid_geom_troncon
        USING btree (gid);

-- Vue voisins

CREATE MATERIALIZED VIEW view_voisins AS
select nr.gid as noeud_routier_gid, nr2.gid as noeud_routier_gid_voisin, tr.gid as troncon_gid, tr.longueur
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

select noeud_routier_gid, troncon_gid, longueur
from voisins
where noeud_routier_gid = 1;

CREATE INDEX idx_gid_voisins
    ON view_voisins
        USING btree (noeud_routier_gid);

-- Clé primaire des tables

alter table troncon_route
    add constraint troncon_route_pk
        primary key (gid);

alter table noeud_routier
    add constraint noeud_routier_pk
        primary key (gid);

alter table noeud_commune
    add constraint noeud_commune_pk
        primary key (gid);


-- =====================================================================================================================
-- IUT --
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

select noeud_routier_gid, troncon_gid, longueur
from voisins
where noeud_routier_base = 1;

CREATE INDEX idx_gid_voisins
    ON voisins
        USING hash (noeud_routier_base);

DROP INDEX idx_gid_voisins;

