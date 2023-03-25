<div class=" container-fluid my-5">
    <form action="" method="post">
        <fieldset>
            <legend>Plus court chemin</legend>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="nomCommuneDepart_id">Nom de la commune de départ</label>
                <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneDepart"
                       id="nomCommuneDepart_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="nomCommuneArrivee_id">Nom de la commune de départ</label>
                <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneArrivee"
                       id="nomCommuneArrivee_id" required>
            </p>
<!--            <input type="hidden" name="XDEBUG_TRIGGER" value="1">-->
            <p>
                <input class="InputAddOn-field" type="submit" value="Calculer"/>
            </p>
        </fieldset>
    </form>


    <script>
        function initMap() {
            // The location of Uluru
            const uluru = {lat: -25.344, lng: 131.031};
// The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4,
                center: uluru,
            });
        }
    </script>
    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiW2J5xEe7wfr9_Q7Odlf-yGEi7S_6qfM&callback=initMap&v=weekly"
            defer
    ></script>

    <div class="container-fluid my-5">


        <div id="map">map
        </div>


    </div>


    <?php if (!empty($_POST)) { ?>
    <p>
        Le plus court chemin entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> mesure <?= $distance ?>km.
    </p>


</div>
<?php } ?>