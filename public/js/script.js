// pour calculer automatiquement le poids total à charge
// en cas de changement du poids à vide
$( ".vhc_pav" ).on( "change", function() {
    PoidsTotalACharge();
} );
// en cas de changement de la charge utile
$( ".vhc_cu" ).on( "change", function() {
    PoidsTotalACharge();
} );

function PoidsTotalACharge(){
    if( $('.vhc_cu').val() != "" && $('.vhc_pav').val() != "" ){
        $('.vhc_ptac').val(parseInt($('.vhc_cu').val()) + parseInt($('.vhc_pav').val()));
    } else {
        $('.vhc_ptac').val("");
    }
}

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
    } else if( $('.reception_type option:selected').text() == "ISOLE" ){
        $(".isole").attr("style","display: block;");
        $(".par_type").attr("style","display: none;");
    } else if( $('.reception_type option:selected').text() == "" ){
        $(".isole").attr("style","display: none;");
        $(".par_type").attr("style","display: none;");
    } else {
        $(".isole").attr("style","display: none;");
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

    // $('.reception_type option:selected').val(2);
    //$('.reception_type option:selected').text() = 2;
    if( $('.reception_type option:selected').text() == "PAR TYPE" ){
        $(".par_type").attr("style","display: block;");
        $(".isole").attr("style","display: none;");
    } else if( $('.reception_type option:selected').text() == "ISOLE" ){
        $(".isole").attr("style","display: block;");
        $(".par_type").attr("style","display: none;");
    } else if( $('.reception_type option:selected').text() == "" ){
        $(".isole").attr("style","display: none;");
        $(".par_type").attr("style","display: none;");
    } else {
        $(".isole").attr("style","display: none;");
        $(".par_type").attr("style","display: none;");
    }

    $(".multi").select2();
    $(".select").select2();
    $("#select").select2();
    $("select").select2();
    //$(".form-control").select2();
    //$("#form_ct_centre_id").select2();
    //$(".form_ct_centre_id").select2();
    //$("option").select2();
});

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_premiere_circule" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_premiere_circule').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_premiere_circule').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_premiere_circule').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_premiere_circule').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_type_car" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_type_car').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_type_car').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_type_car').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_type_car').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_num_serie_type" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_num_serie_type').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_num_serie_type').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_num_serie_type').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_num_serie_type').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_num_moteur" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_num_moteur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_num_moteur').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_num_moteur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_num_moteur').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_cylindre" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_cylindre').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_cylindre').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_cylindre').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_cylindre').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_puissance" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_puissance').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_puissance').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_puissance').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_puissance').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_nbr_assis" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_nbr_assis').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_nbr_assis').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_nbr_assis').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_nbr_assis').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_largeur" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_largeur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_largeur').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_largeur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_largeur').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_hauteur" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_hauteur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_hauteur').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_hauteur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_hauteur').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_longueur" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_longueur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_longueur').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_longueur').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_longueur').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_charge_utile" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_charge_utile').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_charge_utile').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_charge_utile').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_charge_utile').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_poids_vide" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_cad_poids_vide').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_poids_vide').val());
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_cad_poids_vide').val($('#ct_constatation_ct_const_av_ded_carac_note_descriptive_cad_poids_vide').val());
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_ct_genre_id" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_ct_genre_id').val($(this).val()).change();
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_ct_genre_id').val($(this).val()).change();
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_ct_marque_id" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_ct_marque_id').val($(this).val()).change();
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_ct_marque_id').val($(this).val()).change();
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_ct_source_energie_id" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_ct_source_energie_id').val($(this).val()).change();
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_ct_source_energie_id').val($(this).val()).change();
} );

$( "#ct_constatation_ct_const_av_ded_carac_note_descriptive_ct_carrosserie_id" ).on( "change", function() {
    $('#ct_constatation_ct_const_av_ded_carac_carte_grise_ct_carrosserie_id').val($(this).val()).change();
    $('#ct_constatation_ct_const_av_ded_carac_corps_vehicule_ct_carrosserie_id').val($(this).val()).change();
} );