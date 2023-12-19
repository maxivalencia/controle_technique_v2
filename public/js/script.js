// pour cacher et afficher les options après sélection de option transport
$( ".istransport" ).on( "change", function() {
    if( $('.istransport option:selected').text() == "Oui" ){
        $(".sitransport").attr("style","display: block;");
    }
    else{
        $(".sitransport").attr("style","display: none;");
    }
} );

// pour cacher et afficher les options après sélection anomalie
$( ".is_anomalie" ).on( "change", function() {
    if( $('.is_anomalie option:selected').val() ){
        $(".duree").attr("style","display: block;");
    }
    else{
        $(".duree").attr("style","display: none;");
    }
} );

// pour modifier l'option de recéption après sélection du type de réception
$( ".reception_type" ).on( "change", function() {
    if( $('.reception_type option:selected').text() == "PAR TYPE" ){
        $(".par_type").attr("style","display: block;");
        $(".isole").attr("style","display: none;");
    }
    if( $('.reception_type option:selected').text() == "ISOLE" ){
        $(".isole").attr("style","display: block;");
        $(".par_type").attr("style","display: none;");
    }
} );

// pour cacher et afficher les options après chargement de la page
$( document ).ready(function() {
    if( $('.istransport option:selected').text() == "Oui" ){
        $(".sitransport").attr("style","display: block;");
    }
    else{
        $(".sitransport").attr("style","display: none;");
    }
    
    if( $('.is_anomalie option:selected').val() ){
        $(".duree").attr("style","display: block;");
    }
    else{
        $(".duree").attr("style","display: none;");
    }
    
    if( $('.reception_type option:selected').text() == "PAR TYPE" ){
        $(".par_type").attr("style","display: block;");
        $(".isole").attr("style","display: none;");
    }
    if( $('.reception_type option:selected').text() == "ISOLE" ){
        $(".isole").attr("style","display: block;");
        $(".par_type").attr("style","display: none;");
    }
});