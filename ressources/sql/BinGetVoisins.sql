-- =====================================================================================================================
                                                    -- Autres --
-- =====================================================================================================================
show search_path;

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from troncon_route tr
            cross join lateral (
                select nr.gid, nr.geom
                from noeud_routier nr
                where nr.gid = :gidTag
                order by nr.geom <-> st_startpoint(tr.geom)
                limit 1) as nr
            cross join lateral (
                select nr2.gid, nr2.geom
                from noeud_routier nr2
                where nr2.gid != :gidTag
                order by nr2.geom <-> st_endpoint(tr.geom)
                limit 1) as nr2
union
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from troncon_route tr
            cross join lateral (
                select nr.gid, nr.geom
                from noeud_routier nr
                where nr.gid = :gidTag
                order by nr.geom <-> st_endpoint(tr.geom)
                limit 1) as nr
            cross join lateral (
                select nr2.gid, nr2.geom
                from noeud_routier nr2
                where nr2.gid != :gidTag
                order by nr2.geom <-> st_startpoint(tr.geom)
                limit 1) as nr2;


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

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr
            join lateral ( select tr.gid, tr.geom, tr.longueur
                           from view_gid_geom_troncon tr) as tr
            on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                   or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
            join lateral (select nr2.gid, nr2.geom
                from view_gid_geom_routier nr2
                where nr2.gid != :gidTag) as nr2
            on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                   or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
where nr.gid = :gidTag;


select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_troncon tr
            join view_gid_geom_routier nr
            on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                   or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
            join view_gid_geom_routier nr2
            on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
            and st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
            or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
            and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
where nr.gid = :gidTag;

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from troncon_route tr
            join noeud_routier nr
            on st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                   or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
            join noeud_routier nr2
            on st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
            or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001)
where nr.gid = :gidTag;

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_troncon tr
            join view_gid_geom_routier nr
            on nr.gid = :gidTag
                  and (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                   or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001))
            join view_gid_geom_routier nr2
            on nr2.gid != :gidTag and
                    (st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                    or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001))
where nr.gid = :gidTag;

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_troncon tr
            cross join lateral ( select nr.gid, nr.geom
                           from view_gid_geom_routier nr
                where nr.gid = :gidTag
                  and (st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001)
                   or st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001))
                 ) as nr
            cross join lateral ( select nr2.gid from view_gid_geom_routier nr2
                where nr2.gid != :gidTag and
                    (st_dwithin(nr2.geom, st_endpoint(tr.geom), 0.001)
                    or st_dwithin(nr2.geom, st_startpoint(tr.geom), 0.001))) as nr2;

select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr,
            lateral ( select nr2.gid
                from view_gid_geom_routier nr2
                where nr2.gid != :gidTag
                order by nr2.geom <-> st_startpoint(tr.geom)
                limit 1 ) as nr2
            where nr.gid = :gidTag
            and st_dwithin(nr.geom, st_endpoint(tr.geom), 0.001)
union
select nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from view_gid_geom_routier nr, view_gid_geom_troncon tr,
            lateral ( select nr2.gid
                from view_gid_geom_routier nr2
                where nr2.gid != :gidTag
                order by nr2.geom <-> st_endpoint(tr.geom)
                limit 1 ) as nr2
            where nr.gid = :gidTag
            and st_dwithin(nr.geom, st_startpoint(tr.geom), 0.001);