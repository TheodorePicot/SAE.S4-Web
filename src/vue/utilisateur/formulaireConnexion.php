<?php
use App\PlusCourtChemin\Lib\Conteneur;
$generateurUrl = Conteneur::recupererService("generateurUrl");
$assistantUrl = Conteneur::recupererService("assistantUrl");?>
<div class="container-fluid d-flex justify-content-center my-5">
    <form method="<?= $method ?>" action="<?=$generateurUrl->generate("connecter");?>">
        <fieldset>

            <div class="d-flex align-content-center justify-content-center">
                <h1>Connexion</h1>
            </div>

            <div class="my-4">
                <div class="form-group row">
                    <div class="my-2">
                        <label for="login_id"></label><input type="text" class="form-control" id="login_id" placeholder="Login" name="login">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="my-2">
                        <label for="mdp_id"></label><input type="password" class="form-control" value="" placeholder="Mot de passe" name="mdp"
                                                           id="mdp_id" required>
                    </div>
                </div>


<!--                <input type='hidden' name='action' value='connecter'>-->
<!--                <input type='hidden' name='controleur' value='utilisateur'>-->
                <div class="d-flex align-content-center justify-content-center">

                    <button type="submit" class="btn btn-light my-4">Se connecter</button>
                </div>
            </div>

        </fieldset>
    </form>
</div>


