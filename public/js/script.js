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
});