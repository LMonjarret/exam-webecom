<?php

// Template de type part, affiche un formulaire de connexion à l'application

$oldMail = isset($_POST["mail"]) ? $_POST["mail"] : "";

?>
<p>De nouveaux conseils ont certainement été publiés ! Venez vite les découvrir !</p>
<form class="form-connexion" method="POST" action="index.php?module=user&action=connect" id="form-connexion">
        <label for="mail">Adresse mail : </label>
        <input type="email" name="mail" id="mail" value="<?= htmlentities($oldMail) ?>"/>
        <label for="mdp">Votre mot de passe : </label>
        <input type="password" name="mdp" id="mdp" />
        <input type="submit" value="Je me connecte !" class="g-recaptcha" data-sitekey="6LcUVq4UAAAAADpwvaaM0df_f7_ONPWu4r3OyK17" data-callback="reCaptchaConnexion" />
</form>