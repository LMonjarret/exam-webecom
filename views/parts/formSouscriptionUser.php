<?php

// Template de type part, affiche un formulaire de souscription à l'application

$oldMail = isset($_POST["mail"]) ? $_POST["mail"] : "";

$oldSelectedOui = "";
$oldSelectedNon = "";

if (isset($_POST["membre"]) and $_POST["membre"] === "oui") {
    $oldSelectedOui = "checked";
} elseif (isset($_POST["membre"]) and $_POST["membre"] === "non") {
    $oldSelectedNon = "checked";
}

?>
<p>Pour seulement 99€ par mois, profitez des conseils de nos experts et de nos abonnés les plus avisés !</p>
<form class="form-souscription" method="POST" action="index.php?module=user&action=insert" id="form-souscription">
        <fieldset>
            <legend>Etes-vous déjà membre ?</legend>
            <input type="radio" id="oui" name="membre" value="oui" <?= $oldSelectedOui ?>/>
            <label for="oui">Oui</label>
            <input type="radio" id="non" name="membre" value="non" <?= $oldSelectedNon ?>/>
            <label for="non">Non</label>
        </fieldset>
        <label for="mail">Adresse mail : </label>
        <input type="email" name="mail" id="mail" value="<?= htmlentities($oldMail) ?>" />
        <label for="mdp">Votre mot de passe : </label>
        <input type="password" name="mdp" id="mdp" />
        <input type="submit" value="Je souscris !" class="g-recaptcha" data-sitekey="6LcUVq4UAAAAADpwvaaM0df_f7_ONPWu4r3OyK17" data-callback="reCaptchaSouscription" />
</form>