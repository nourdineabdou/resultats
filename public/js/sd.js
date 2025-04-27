$(document).ready(function(){
    // Add minus icon for collapse element which is open by default
    /*$(".collapse.show").each(function(){
        $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
    });*/

    // Toggle plus minus icon on show hide of collapse element
   /* $(".collapse").on('show.bs.collapse', function(){
        $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
    }).on('hide.bs.collapse', function(){
        $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
    });
    */

});


function get_etabs() {
    id_pays = $("#pays_etude").val();
    if (id_pays != 'all') {
        $.ajax({
            type: 'get',
            url: racine + 'DE/etudes/pays_etabs/' + id_pays,
            cache: false,
            success: function (data) {
                $("#etabs_pays").empty();
                $('#etabs_pays').html(data);
                resetInit();

            },
            error: function () {

                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                //$.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });

    } else {
        $("#etabs_pays").html('');
    }
}

function addObject_sd(element, lemodule, datatable = "#datatableshow", modal = "add-modal", tab = 1, largeModal = false) {
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        //link=$(datatable).attr('link');
        //alert(link);
        //$(datatable).DataTable().ajax.url(link + "/" + id).load();
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            $('#add-modal').modal('toggle');
            openObjectModal_sd(id, lemodule, datatable, modal, tab, largeModal);
        }, 1500);
    });
}

function afterAddExp(data)
{
     datatableshow = data.datatable;
     id = data.id;

    $('#de_tab-modal').modal('toggle');
    link = $(datatableshow).attr('link');
    //alert(link);
    $(datatableshow).DataTable().ajax.url(link + "/" + id).load();


}
function addCompetence(element, lemodule, datatable = "#datatableshow", id_de) {
    /* saveform(element, function (id) {
         //$(datatable).DataTable().ajax.reload();

         //$(datatable).DataTable().ajax.url(link + "/" + id).load();
        // $(element).attr('disabled', 'disabled');

     });*/

    link = $(datatableshow).attr('link');
    //alert(link);

    var container = $(element).attr('container');

    $('#' + container + ' #form-errors').hide();
    $(element).attr('disabled', 'disabled');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
    var data = $('#' + container + ' form').serialize();

    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: data,
        dataType: 'json',
        success: function (data) {
            console.log(data);
            //$('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
            var cat = $('#' + container + ' form #cat select').serialize()

            update_competence(id_de, data.cat);
            $(datatableshow).DataTable().ajax.url(link + "/" + data.id).load();
            //
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .main-icon').show();
            $(element).removeAttr('disabled');
        }
    });
}

function update_competence(id_de, cat) {
    $.ajax({
        type: 'get',
        url: racine + 'DE/competences/auther_competences/' + id_de + '/' + cat,
        cache: false,
        success: function (data) {
            $("#competences").empty();
            $('#competences').html(data);
            resetInit();

        },
        error: function () {

            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            //$.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });

}

//openObjectModal_sd(2,'DE','#datatableshow','de-modam',1,'xl')
function openObjectModal_sd(id, lemodule, datatableshow = "#datatableshow", modal = "main-modal", tab = 1, largeModal = false) {


    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {
            if (largeModal) $("#" + modal + "-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#" + modal + "-modal .modal-header-body").html(data);
            $("#" + modal + '-modal').modal();

            if (tab != false)
                setMainTabs(tab, true);
            link = $(datatableshow).attr('link');
            //alert(link);
            $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    return false;
}

function encours() {
    if ($('#en_cours').is(':checked')) {
        $('.fin').hide();
    } else {
        $('.fin').show();
    }
}

function filterDE() {
    niveau = $("#niveau").val();
    sit_prof = $("#sit_prof").val();
    etat = $("#etat").val();
    genre = $("#genre").val();

    $('#datatableshow_ind').DataTable().ajax.url(racine + 'DE/getDT/' + niveau + '/' + sit_prof + '/' + genre + '/' + etat).load();
}

function filterCat_comp(id) {
    cat = $("#cat_comp").val();
    $('.datatableshow_ind3').DataTable().ajax.url(racine + 'DE/competences/getDT/' + cat + '/' + id).load();
}

function get_competences_cat(id_de) {
    cat = $("#cat_competence").val();
    update_competence(id_de, cat);
}

function valider_de(id,name,msg)
{
    var datatableshow='#datatableshow_ind';
    var conf= confirm(msg +': '+name);
    if(conf){

        //loading_show();
        url = 'DE/valider_de/'+id;
        type='get';
        res ='resultat_msg';
        $.ajax({
            type: type,
            url: racine+url,
            success: function(data){
                $("#"+res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();      // lead('listeServices1');
            }
        });
        return false;

    }
}

function devalider_de(id,name,msg)
{
    var datatableshow='#datatableshow_ind';
    var conf= confirm(msg +': '+name);
    if(conf){

        //loading_show();
        url = 'DE/devalider_de/'+id;
        type='get';
        res ='resultat_msg';
        $.ajax({
            type: type,
            url: racine+url,
            success: function(data){
                $("#"+res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();      // lead('listeServices1');
            }
        });
        return false;

    }
}

function valider_profil(id,msg)
{
    var conf= confirm(msg);
    if(conf){

        //loading_show();
        url = 'DE/valider_profile/'+id;
        type='get';
        res ='resultat_msg';
        $.ajax({
            type: type,
            url: racine+url,
            success: function(data){
                $("#"+res).html(data).show("slow").delay(4000).hide("slow");
                $("#btn_valide").hide();
            }
        });
        return false;

    }
}
function valider_modif_profil(id,msg)
{
    var conf= confirm(msg);
    if(conf){

        //loading_show();
        url = 'DE/valider_modif_profile/'+id;
        type='get';
        res ='resultat_msg';
        $.ajax({
            type: type,
            url: racine+url,
            success: function(data){
                $("#"+res).html(data).show("slow").delay(4000).hide("slow");
                $("#btn_valide").hide();
            }
        });
        return false;

    }
}
function disabled_form()
{
    //$('#myfieldset').prop('disabled', true);
    isDisabled = $('#myfieldset').is('[disabled=""]');
    if (isDisabled)
     $('#myfieldset').prop('disabled',false)
    else
        $('#myfieldset').prop('disabled',true)
}
function lieu_naissance()
{
    id_pays = $("#pays_nais").val();
    if(id_pays==1)
    {
        $("#div_commune").show();
        $("#div_lieu_nais").hide();
    }
    else {
        $("#div_commune").hide();
        $("#div_lieu_nais").show();
    }
}

