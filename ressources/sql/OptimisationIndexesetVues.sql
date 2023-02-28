-- Création des indexes sur l'attribut geom de noeud_routier et troncon_route

-- Requete getVoisins de base

select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from noeud_routier nr, troncon_route tr, noeud_routier nr2
            where (st_distancesphere(nr.geom, st_startpoint(tr.geom)) < 1
                and st_distancesphere(nr2.geom, st_endpoint(tr.geom)) < 1
                and  nr.gid = :gidTag)
            union
select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from noeud_routier nr, troncon_route tr, noeud_routier nr2
            where (st_distancesphere(nr2.geom, st_startpoint(tr.geom)) < 1
                and st_distancesphere(nr.geom, st_endpoint(tr.geom)) < 1
                and  nr.gid = :gidTag);

-- Requete getVoisins de base avec st_dwithin

select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from noeud_routier nr, troncon_route tr, noeud_routier nr2
            where (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                and st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                and  nr.gid = :gidTag)
            union
select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from noeud_routier nr, troncon_route tr, noeud_routier nr2
            where (st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
                and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
                and  nr.gid = :gidTag);

-- Requete getVoisins avec st_dwithin et vue

select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr, view_gid_geom_routier nr2
            where (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                and st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                and  nr.gid = :gidTag)
            union
select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr, view_gid_geom_routier nr2
            where (st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
                and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
                and  nr.gid = :gidTag);

select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr, view_gid_geom_routier nr2
            where (nr.geom <-> tr.geom < 1
                and nr2.geom <-> tr.geom < 1
                and nr.gid = :gidTag)
            union
select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr, view_gid_geom_routier nr2
            where (nr2.geom <-> tr.geom < 1
                and nr.geom <-> tr.geom < 1
                and  nr.gid = :gidTag);

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

-- Vue voisins

CREATE MATERIALIZED VIEW view_voisins AS
select nr.gid as noeud_routier_gid, nr2.gid as noeud_routier_gid_voisin, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr, view_gid_geom_routier nr2
            where (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                and st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001))
            union
select nr.gid as noeud_routier_gid, nr2.gid as noeud_routier_gid_voisin, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr, view_gid_geom_routier nr2
            where (st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
                and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001));

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

