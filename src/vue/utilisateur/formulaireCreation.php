<?php

use App\PlusCourtChemin\Lib\Conteneur;

$generateurUrl = Conteneur::recupererService("generateurUrl");
$assistantUrl = Conteneur::recupererService("assistantUrl");
?>


<div class="container-fluid col-3 my-5">
    <div class="d-flex align-content-center justify-content-center">
        <a href="<?= $generateurUrl->generate("plusCourtChemin"); ?>">
            <svg width="300px" height="100px" xmlns="http://www.w3.org/2000/svg"
                 viewBox="99.16875000000002 14 301.66249999999997 122" style="background: rgba(253, 253, 253, 0);"
                 preserveAspectRatio="xMidYMid">
                <defs>
                    <filter id="editing-extrude-glow">
                        <feFlood flood-color="#bbcedd" result="flood-1"></feFlood>
                        <feMorphology operator="erode" radius="1" in="SourceAlpha" result="erode"></feMorphology>
                        <feComposite operator="in" in="flood-1" in2="erode" result="comp1"></feComposite>
                        <feConvolveMatrix order="3,3" divisor="1" in="comp1" result="convolve"
                                          kernelMatrix="0 0 0 1 1 1 0 0 0"></feConvolveMatrix>
                        <feOffset dx="-3" dy="0" in="convolve" result="extrude"></feOffset>
                        <feComposite operator="in" in="flood-1" in2="extrude" result="comp-extrude"></feComposite>
                        <feFlood flood-color="#dce4eb" result="flood-2"></feFlood>
                        <feComposite operator="in" in="flood-2" in2="SourceAlpha" result="comp2"></feComposite>
                        <feMorphology operator="dilate" radius="1" in="comp2" result="dilate"></feMorphology>
                        <feOffset dx="-4.5" dy="0" in="dilate" result="offset"></feOffset>
                        <feGaussianBlur in="offset" stdDeviation="5.8" result="blur"></feGaussianBlur>
                        <feComponentTransfer in="blur" result="shadow">
                            <feFuncA type="linear" slope="0.8" intercept="-0.2"></feFuncA>
                        </feComponentTransfer>
                        <feMerge>
                            <feMergeNode in="shadow"></feMergeNode>
                            <feMergeNode in="comp-extrude"></feMergeNode>
                            <feMergeNode in="SourceGraphic"></feMergeNode>
                        </feMerge>
                    </filter>
                </defs>
                <g filter="url(#editing-extrude-glow)">
                    <g transform="translate(146.76997709274292, 97.39999961853027)">
                        <path d="M27.01-20.61L39.94-21.76L39.94-21.76Q35.33-11.65 35.33 0.06L35.33 0.06L35.33 0.06Q33.60 1.28 31.07 1.28L31.07 1.28L31.07 1.28Q28.54 1.28 26.94 0.10L26.94 0.10L26.94 0.10Q25.34-1.09 25.02-2.30L25.02-2.30L25.02-2.30Q23.42-0.64 20.83 0.32L20.83 0.32L20.83 0.32Q18.24 1.28 15.49 1.28L15.49 1.28L15.49 1.28Q12.74 1.28 10.34 0.38L10.34 0.38L10.34 0.38Q7.94-0.51 6.08-2.50L6.08-2.50L6.08-2.50Q1.98-6.85 1.98-15.10L1.98-15.10L1.98-15.10Q1.98-27.90 8.83-35.58L8.83-35.58L8.83-35.58Q15.87-43.52 28.35-43.52L28.35-43.52L28.35-43.52Q36.99-43.52 40.06-39.04L40.06-39.04L40.06-39.04Q41.02-37.63 41.02-35.94L41.02-35.94L41.02-35.94Q41.02-34.24 40.29-32.80L40.29-32.80L40.29-32.80Q39.55-31.36 38.40-30.21L38.40-30.21L38.40-30.21Q35.65-27.65 32.26-27.65L32.26-27.65L32.26-27.65Q30.91-27.65 29.63-28.10L29.63-28.10L29.63-28.10Q29.89-30.02 29.89-32.26L29.89-32.26L29.89-32.26Q29.89-34.50 29.73-35.52L29.73-35.52L29.73-35.52Q29.57-36.54 29.18-37.38L29.18-37.38L29.18-37.38Q28.35-39.10 26.53-39.10L26.53-39.10L26.53-39.10Q24.70-39.10 22.69-37.15L22.69-37.15L22.69-37.15Q20.67-35.20 19.07-32L19.07-32L19.07-32Q15.55-24.83 15.55-16.19L15.55-16.19L15.55-16.19Q15.55-12.22 17.09-9.41L17.09-9.41L17.09-9.41Q18.75-6.34 21.70-6.34L21.70-6.34L21.70-6.34Q22.72-6.34 23.65-6.85L23.65-6.85L23.65-6.85Q24.58-7.36 24.96-7.74L24.96-7.74L27.01-20.61ZM71.17 1.28L71.17 1.28L71.17 1.28Q62.98 1.28 62.21-10.37L62.21-10.37L51.20-10.37L51.20-10.37Q49.92-7.42 49.02-4.86L49.02-4.86L47.36 0L38.40 0L58.56-42.24L72.32-42.24L75.01-11.14L75.01-11.14Q75.65-4.42 78.21-2.43L78.21-2.43L78.21-2.43Q76.35 1.28 71.17 1.28ZM56.70-23.17L53.18-15.04L62.02-15.04L61.25-31.49L61.25-33.22L56.70-23.17ZM108.67-9.86L108.67-9.86L108.67-9.86Q109.89-8.32 109.89-5.34L109.89-5.34L109.89-5.34Q109.89-2.37 107.62-0.54L107.62-0.54L107.62-0.54Q105.34 1.28 101.38 1.28L101.38 1.28L101.38 1.28Q99.07 1.28 95.49 0.77L95.49 0.77L95.49 0.77Q88.45-0.32 86.43-0.32L86.43-0.32L86.43-0.32Q84.42-0.32 83.58-0.22L83.58-0.22L83.58-0.22Q82.75-0.13 81.41 0L81.41 0L89.15-42.24L103.17-42.24L96.77-7.04L96.77-7.04Q97.60-6.91 98.37-6.91L98.37-6.91L99.97-6.91L99.97-6.91Q105.34-6.91 108.67-9.86ZM140.93-9.86L140.93-9.86L140.93-9.86Q142.14-8.32 142.14-5.34L142.14-5.34L142.14-5.34Q142.14-2.37 139.87-0.54L139.87-0.54L139.87-0.54Q137.60 1.28 133.63 1.28L133.63 1.28L133.63 1.28Q131.33 1.28 127.74 0.77L127.74 0.77L127.74 0.77Q120.70-0.32 118.69-0.32L118.69-0.32L118.69-0.32Q116.67-0.32 115.84-0.22L115.84-0.22L115.84-0.22Q115.01-0.13 113.66 0L113.66 0L121.41-42.24L135.42-42.24L129.02-7.04L129.02-7.04Q129.86-6.91 130.62-6.91L130.62-6.91L132.22-6.91L132.22-6.91Q137.60-6.91 140.93-9.86ZM159.68 0L145.73 0L153.86-42.24L167.94-42.24L159.68 0ZM197.44 1.28L197.44 1.28L197.44 1.28Q189.25 1.28 188.48-10.37L188.48-10.37L177.47-10.37L177.47-10.37Q176.19-7.42 175.30-4.86L175.30-4.86L173.63 0L164.67 0L184.83-42.24L198.59-42.24L201.28-11.14L201.28-11.14Q201.92-4.42 204.48-2.43L204.48-2.43L204.48-2.43Q202.62 1.28 197.44 1.28ZM182.98-23.17L179.46-15.04L188.29-15.04L187.52-31.49L187.52-33.22L182.98-23.17Z"
                              fill="#e9ebed"></path>
                    </g>
                </g>
                <style>text {
                        font-size: 64px;
                        font-family: Arial Black;
                        dominant-baseline: central;
                        text-anchor: middle;
                    }</style>
            </svg>
        </a>
    </div>

    <form method="<?= $method ?>" action="<?= $generateurUrl->generate("creerDepuisFormulaire"); ?>">

        <fieldset>
            <div class="my-4">

                <div class="my-4">

                    <div class="form-group row">
                        <div class="my-2">
                            <input class="form-control" type="text" value="" placeholder="Login" name="login"
                                   id="login_id" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="my-2">
                            <input class="form-control" type="text" value="" placeholder="Prenom" name="prenom"
                                   id="prenom_id" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="my-2">
                            <input class="form-control" type="text" value="" placeholder="Nom" name="nom"
                                   id="nom_id" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="my-2">
                            <input class="form-control" type="text" value="" placeholder="Email" name="email"
                                   id="email_id" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="my-2">
                            <input class="form-control" type="password" value="" placeholder="Mot de passe" name="mdp"
                                   id="mdp_id" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="my-2">
                            <input class="form-control" type="password" value="" placeholder="Vérification du mot de passe" name="mdp2"
                                   id="mdp2_id" required>
                        </div>
                    </div>


                <?php

                use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

                if (ConnexionUtilisateur::estAdministrateur()) {
                    ?>
                    <p class="InputAddOn">
                        <label class="InputAddOn-item" for="estAdmin_id">Administrateur</label>
                        <input class="InputAddOn-field" type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">
                    </p>
                <?php } ?>
                <input type='hidden' name='action' value='creerDepuisFormulaire'>
                <input type='hidden' name='controleur' value='utilisateur'>

                    <div class="d-flex align-content-center justify-content-center">

                        <button type="submit" class="btn btn-light my-4">S'incrire</button>
                    </div>


            </div>
        </fieldset>
    </form>
</div>