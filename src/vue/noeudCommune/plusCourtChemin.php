<div class=" container-fluid my-5">


    <div class="container-fluid d-flex justify-content-center">
        <form action="" method="post">
            <fieldset>
                <div class="d-flex align-content-center justify-content-center">
                    <h1> Calculer un plus court chemin</h1>
                </div>

                <div class="my-5">


                    <div class="form-group row">
                        <label for="nomCommuneDepart_id" class="col-sm-2 col-form-label">Départ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nomCommuneDepart_id" placeholder="Ex : Menton">
                            <div id="autocompletionD"></div>
                        </div>
                    </div>

                    <div class="form-group row my-4">
                        <label for="nomCommuneArrivee_id" class="col-sm-2 col-form-label">Arrivée</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nomCommuneArrivee_id" placeholder="Ex : Menton">
                            <div id="autocompletionA"></div>
                        </div>
                    </div>



                    <!--            <input type="hidden" name="XDEBUG_TRIGGER" value="1">-->
                    <div class="d-flex justify-content-center my-4">
                        <button type="submit" class="btn btn-light">Calculer</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="container-fluid my-5 col-7">


        <div id="map">
        </div>


    </div>


    <?php if (!empty($_POST)) { ?>
    <p>
        Le plus court chemin entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> mesure <?= $distance ?>km.
    </p>


</div>
<?php } ?>