<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepositoryInterface;
use App\PlusCourtChemin\Service\NoeudRoutierServiceInterface;
use App\PlusCourtChemin\Service\UtilisateurService;
use App\PlusCourtChemin\Service\UtilisateurServiceInterface;

class VerificationEmail
{

    public function __construct(
        private readonly UtilisateurRepositoryInterface $utilisateurRepository
    )
    {
    }

    public function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Configuration::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controleur=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

        // Temporairement avant d'envoyer un vrai mail
//        MessageFlash::ajouter("success", $corpsEmail);

         mail(
             $utilisateur->getEmailAValider(),
             "Validation de votre adresse mail",
             "<a href=\"$lienValidationEmail\">Validation</a>"
         );
    }

    public function traiterEmailValidation($login, $nonce): bool
    {
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur === null)
            return false;

        if ($utilisateur->getNonce() !== $nonce) {
            return false;
        }

        $utilisateur->setEmail($utilisateur->getEmailAValider());
        $utilisateur->setEmailAValider("");
        $utilisateur->setNonce("");

        $this->utilisateurRepository->mettreAJour($utilisateur);
        return true;
    }

    public static function aValideEmail(Utilisateur $utilisateur): bool
    {
        return $utilisateur->getEmail() !== "";
    }
}
