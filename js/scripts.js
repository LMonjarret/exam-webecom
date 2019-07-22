// Contient les différents scripts javascript de l'application "Mes fiches jardinages"

$(document).ready(function() {
    
    // Placement des événements sur la page HTML
    $(".liked").on("click", unlike);
    $(".not-liked").on("click", like);
    $(".select_profil").on("change", change);
    
    $(".star").each(function(index) {
        $(this).on("click", index, function() {
            // Rôle : envoyer une requête ajax avec la note utilisateur, puis afficher la note
            // Retour : aucun
            // Paramètre : index, place de l'étoile dans le tableau
            
            // Calcul de la note
            var data = {
                note : index + 1,
                idFiche : $(".stars").attr("data-idFiche")
            };
            
            var url = "index.php?module=note&action=insert";
            
            $.ajax(url, {
                data : data,
                method : "POST",
                success : function(data) {
                    // Rôle : enlever l'affichage des étoiles et afficher la note
                    var moyenne = data.moyenne;
                    var resultat = data.resultat;
                    
                    
                    if (resultat === 1) {
                        $(".stars").replaceWith("<p>Note moyenne de la fiche : " + moyenne + "</p>");
                    }
                }
            });
        });
    });
    
    $(".stars").on("mouseout", star);
    
    $(".star").each(function(index) {
        $(this).on("mouseover", index, function() {
            // Rôle : changer la classe de l'étoile actuelle ainsi que toutes celles la précédent
            // Retour : aucun
            // Paramètre : aucun
            
            var etoiles = $(".star");
            
            for (var i = 0; i <= index; i++) {
                $(etoiles[i]).addClass("yellowStar").removeClass("star");
            }
        });
    });
});

function like() {
    // Rôle : insérer un favori dans la base de données en passant par php et changer la classe de la div
    // Retour : aucun
    // Paramètre : aucun
    
    // Récupération des id
    var fiche = $(".not-liked").attr("data-idFiche");
    
    var data = {
        idFiche: fiche
    };
    
    // Construction de l'url
    var url = "index.php?module=favori&action=insert";
    
    // Ajax
    $.ajax(url, {
        data: data,
        method: "POST",
        success: function(data) {
            
            // On récupère l'id du favori
            var idFavori = data.idFavori;
            
            // On créé le bouton not-liked
            var liked = "<div class='liked' data-id='"+idFavori+"'></div>";
            
            // On remplace le bouton précédent
            $(".not-liked").replaceWith(liked);
            $(".liked").on("click", unlike);
        }
    });
}

function unlike() {
    // Rôle : supprimer un favori dans la base de données en passant par php et changer la classe de la div
    // Retour : aucun
    // Paramètre : aucun
    
    // Récupération des id
    var favori = $(".liked").attr("data-id");
    
    // Construction de l'url
    var url = "index.php?module=favori&action=delete&id="+favori;
    
    // Ajax
    $.ajax(url, {
        success: function(data) {
            
            // On récupère l'id de la fiche
            var idFiche = data.idFiche;
            
            // On créé le bouton not-liked
            var notLiked = "<div class='not-liked' data-idFiche='"+idFiche+"'></div>";
            
            // On remplace le bouton précédent
            $(".liked").replaceWith(notLiked);
            $(".not-liked").on("click", like);
        }
    });
}

function change() {
    // Rôle : envoyer une requ$ete ajax pour modifier le profil de l'utilisateur dans la base de données et changer l'affichage sur la page
    // Retour : aucun
    // Paramètre : aucun
    
    // Construction de l'url
    var id = $("option").attr("data-id");
    var url = "index.php?module=user&action=modifProfil&id="+id;
    
    // Construction des données
    var dataEnvoi = {
        code : $(this).val()
    };
    
    // Requête ajax
    $.ajax(url, {
        data : dataEnvoi,
        method : "POST",
        success : function(profil) {
            $(".statut").html("Statut : "+profil);
        }
    });
}

function star() {
    // Rôle : change la classe de toutes les étoiles en "star"
    // Retour : aucun
    // Paramètre : aucun
    
    $(".yellowStar").each(function() {
        $(this).addClass("star").removeClass("yellowStar");
    });
}

function reCaptchaConnexion(token) {
    // Rôle : envoie du formulaire du connexion
    // Retour : l'envoie du formulaire
    // Paramètre : aucun
    
    return $("#form-connexion").submit();
}

function reCaptchaSouscription(token) {
    // Rôle : envoie du formulaire
    // Retour : l'envoie du formulaire
    // Paramètre : aucun
    
    return $("#form-souscription").submit();
}