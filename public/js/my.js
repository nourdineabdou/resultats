$(document).ready(function () {
    // racine = '/onispa/public/';

    // Ajout du CSRF Token pour les requettes ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
    //begin referentiesl
    function addnew(){
        // $( '#addNewModal #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        // $("#addNewModal .form-loading").show();
        $('#addNewModal .main-icon').hide();
        $('#addNewModal .spinner-border').show();
        var data = $('#addNewModal form').serialize();
        $.ajax({
            type: $('#addNewModal form').attr("method"),
            url: $('#addNewModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                window.location.href = data;
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';

                    });
                    errorsHtml += '</ul>';
                    $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                // $("#addNewModal .form-loading").hide();
                $('#addNewModal .spinner-border').hide();
                $('#addNewModal .main-icon').show();
                // $(element).removeAttr('disabled');
            }

        });
    }
    function openEditRefModal(array)
    {
        var tableau=array.split(",");
        var ref=tableau[0];
        var id=tableau[1];
        // alert(ref);
        // alert(id);
       $.ajax({
           type: 'get',
           url: racine+'ref/edit/'+ref+'/'+id,
           // alert(url);
           success: function (data) {
            // alert(data);
            $("#main-modal .modal-header-body").html(data);
            var title_modif =$('#title_modif').val();
            $(".title_modif").html(title_modif);
            var libelleref =$('#libelleref').val();
            $(".libelleref").html(libelleref);
            $("#main-modal").modal();
            // initmain();


           },
           error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
       });
    }
    //end referentiel
    //changer le lieu d'employeur
    function changeLieu(){
        if ($('.checkLieu').is(':checked')){
            $( '.divPays' ).hide();
            $( '.divRIM' ).show();
            $("#checkBtn").val(1);

        }
        else {
            $( '.divPays' ).show();
            $( '.divRIM' ).hide();
            $("#checkBtn").val(0);

        }
    }

    function filterFormation()
    {
        type = $("#type").val();
        centre = $("#centre").val();
        domaine = $("#domaine").val();
        langue = $("#langue").val();
        $('#datatableshow').DataTable().ajax.url(racine + "formations/getDT/"+ type + '/'  + centre  + '/'  + domaine  + '/'  + langue  + '/all').load();
    }

    function filterEmployeur()
    {
        secteur = $("#secteur").val();
        $('#datatableshow').DataTable().ajax.url(racine + "employeurs/getDT/"+ secteur +  '/all').load();
    }

    // updating a group des elements
function updateGroupeElements(element = null) {
    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    var idgroup = $(".group-elements").attr('idgroup');
    var lien = $(".group-elements").attr('lien');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + qsts + '/' + idgroup;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            $('.datatableshow1').DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

//ouvrir le model de modification des donnees d'authentification des employeurs
function openFormAuthInModal(employeur) {
    $.ajax({
        type: 'get',
        url: racine +  'employeurs/getAuthEmp/' +  employeur,
        success: function (data) {
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function addAuthEmployeur(element){
        // $( '#addNewModal #form-errors' ).hide();
        // var element = $(this);
    var container = $(element).attr('container');
       $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
    var data = $('#' + container + ' form').serialize();
    // alert($('#' + container + ' form').attr("action"));
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('#second-modal').modal('toggle');
                // getTheContent('employeurs/getTab/' + data, '/1');
                $('#main-modal').modal('toggle');

                openObjectModal(data,"employeurs");

                // getTheContent('employeurs/getTab/' + data + '/1/', '#tab1');


            },
            error: function(data){
                 if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
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


    function retirerDE (element) {
        // alert(racine+'formations/retirerDE/'+element);
        var confirme = confirm("Êtes-vous sûr de vouloir retire ce demadeur ? ");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+'formations/retirerDE/'+element,
                success: function (data)
                {
                    $('.datatableshow2').DataTable().ajax.reload();
                },
                error: function(data){
                    if( data.status === 422 ) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each( errors, function( key, value ) {
                          errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $( '#fichlaboModal #form-errors' ).show().html( errorsHtml );
                    }
                    else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                }
            });
        };
    }

    // Ouvrire le modal ajout d'un demandeur d'emploi a une formation
    function openAddDEModal(id_form) {

        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'formations/addDEForm/'+id_form,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-xl");

                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //recuperer les listes des DEs
    function changeTypeDE() {

        form_id = $("#form_id").val();
        type = $("#type_de").val();

        // alert(type);
        $.ajax({
            type: 'get',
            url: racine + 'formations/getDEs/'+ type + '/' + form_id,
            success: function (data) {
                // alert(data);
                $("#demandeurs_form").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function addDE_affectes(element){
        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
            url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                //$('.datatableshow').DataTable().ajax.reload();
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('.datatableshow2').DataTable().ajax.reload();
                $('#second-modal').modal('toggle');
                getTheContent('formations/getTab/' + data + '/2/', '#tab2');


            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
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

    function deleteCompte (id_user) {

        var confirme = confirm("Êtes-vous sûr de vouloir supprimer ce compte");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+'employeurs/deleteUser/'+id_user,
                success: function (data)
                {
                    $('#main-modal').modal('toggle');
                    openObjectModal(data,"employeurs");
                    // getTheContent('employeurs/getTab/' + id_user + '/1');

                },
                error: function(data){
                    if( data.status === 422 ) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each( errors, function( key, value ) {
                          errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $( '#fichlaboModal #form-errors' ).show().html( errorsHtml );
                    }
                    else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                }
            });
        };
    }


    // updating a group des elements
function updateElementsDEform(element = null) {
    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    // var idgroup = $(".addDE").attr('idgroup');
    // var lien = $(".addDE").attr('lien');
    var idgroup = $(element).attr('idgroup');
    var lien = $(element).attr('lien');
    var elem = $(element).attr('idelt');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + elem + '/' + idgroup;
    // alert(link);
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close addDE" lien="formations/retirerDE"  idgroup="' + idgroup + '" aria-hidden="true" onclick="updateElementsDEform(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            $('.datatableshow2').DataTable().ajax.reload();
            $('.datatableshow5').DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


    function filterDEsLibres()
    {
        niveau = $("#niveau").val();
        domaine = $("#domaine").val();
        genre = $("#genre").val();
        form_id = $("#form_id").val();
        $('.datatableshow3').DataTable().ajax.url(racine + "formations/getDEsLibres/"+ niveau + '/'  + domaine   +'/'  + genre   + '/' + form_id).load();
    }

    function filterOffres(element)
    {
        etat = $("#etat").val();
        lieu = $("#lieu").val();
        // form_id = $("#form_id").val();
        // alert(racine + "employeurs/getDTOffres/"+ etat + '/'  + lieu   + '/' + element);
        $('.datatableshow3').DataTable().ajax.url(racine + "employeurs/getDTOffres/"+ etat + '/'  + lieu   + '/' + element).load();
    }

    //check all input in DEs libres
    function checkAllDEs(element){
        if ($(element).is(':checked')) {
            $('.btnAddDEslibres').show();
        }
        else
        {
            var str = 0;

            $(':checkbox').each(function() {
                str += this.checked ? 1 : 0;
            });
            if (str == 0) {
                $('.btnAddDEslibres').hide();
            }
            else
                $('.btnAddDEslibres').show();

        }
    }

    function filterOffre()
    {




        employeur = $("#employeur").val();
        agence = $("#agence").val();
        // commune = $("#commune").val();
        secteur = $("#secteur").val();
        $('#datatableshow').DataTable().ajax.url(racine + "offres/getDT/"+ employeur + '/'  + agence  + '/'  + secteur  + '/all').load();
    }


//recuperer les listes des agents par agence
    function changeAgence() {

        agence = $("#agence_id").val();

        // alert(type);
        $.ajax({
            type: 'get',
            url: racine + 'offres/getAgents/'+ agence,
            success: function (data) {
                // alert(data);
                $("#agent_id").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function changeGrCompetance() {

        gr_competance = $("#gr_competance").val();

        // alert(type);
        $.ajax({
            type: 'get',
            url: racine + 'offres/getCompetance/'+ gr_competance,
            success: function (data) {
                // alert(data);
                $("#competence_id").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }




    function openDiv(element,id) {


        switch(element) {
            case 1: //competance
                var lien =  'offres/competances/get_form_compe/'+id;
                var nameDiv = "competance_div";
            break;
            case 2: //formation offre
                var lien =  'offres/formation/get_compeOffre/'+id;
                var nameDiv = "form_div";
            break;
            break;
              default:
                // code block
        }
        // alert(lien+"///"+nameDiv);

        $.ajax({
            type: 'get',
            url: racine + lien,
            success: function (data) {
                $("#"+nameDiv).html(data);
                $("#"+nameDiv).show();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function deleteCompetance (element) {
        // alert(element);
        var confirme = confirm("Êtes-vous sûr de vouloir supprimer cette competance ? ");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+'offres/competances/deleteCompetance/'+element,
                success: function (data)
                {
                    $('.datatableshow4').DataTable().ajax.reload();
                },
                error: function(data){
                    if( data.status === 422 ) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each( errors, function( key, value ) {
                          errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $( '#fichlaboModal #form-errors' ).show().html( errorsHtml );
                    }
                    else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                }
            });
        };
    }

    function deleteObject (array) {
        // alert(array);
        var tableau=array.split(",");
        var id=tableau[0];
        var type=tableau[1];
        // alert(id+"///"+type);
        // var type =  2;
        switch(type) {
            case '1': //competance
                var lien =  'offres/competances/deleteCompetance/'+id;
                var datatable = "datatableshow2";
                var msg = "Êtes-vous sûr de vouloir supprimer cette competance ? ";
            break;
            case '2': //formation offre
                var lien =  'offres/formation/deleteOffre/'+id;
                var datatable = "datatableshow3";
                var msg = "Êtes-vous sûr de vouloir supprimer cette formation ? ";
            break;
            default:
                // code block
        }

        // alert(lien + "//"+ nameDiv);
        // alert(element);
        var confirme = confirm(msg);
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+lien,
                success: function (data)
                {
                    $('.'+datatable).DataTable().ajax.reload();
                },
                error: function(data){
                    if( data.status === 422 ) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each( errors, function( key, value ) {
                          errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $( '#fichlaboModal #form-errors' ).show().html( errorsHtml );
                    }
                    else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                }
            });
        };
    }
    function addNewObject(element){
        // alert(element);
        switch(element) {
            case 1: //competance
                var lien =  "offres/competances/getCompetancesDT/";
                var nameDiv = "competance_div";
                var datatable = "datatableshow2";
            break;
            case 2: //formation offre
                var lien =  "offres/formation/getFormDT/";
                var nameDiv = "form_div";
                var datatable = "datatableshow3";
            break;
            default:
            // code block
        }
        // alert('#' + nameDiv + ' #form-errors');

        $( '#' + nameDiv + ' #form-errors' ).hide();
        var element = $(this);
        $('#' + nameDiv + ' .main-icon').hide();
        $('#' + nameDiv + ' .spinner-border').show();
        // $('#' + nameDiv + ' .spinner-border').show();
        var data = $('#' + nameDiv + ' form').serialize();
        $.ajax({
            type: $('#' + nameDiv + ' form').attr("method"),
            url: $('#' + nameDiv + ' form').attr("action"),
            data: data,
            success: function(data){
                // $('#' + nameDiv'+ ').modal('toggle');
                // openStagiaireModal(data);
                $('#' + nameDiv + ' .spinner-border').hide();
                $('#' + nameDiv + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + nameDiv + ' .answers-well-saved').hide();
                    $('#' + nameDiv + ' .main-icon').show();

                }, 3500);

                // $('.'+datatable).DataTable().ajax.url(racine + lien + data).load();
                    $('.'+datatable).DataTable().ajax.reload();

                /*var form = document.getElementById("formSTAG");
                form.reset();*/
                $('#' + nameDiv).hide();
            },
            error: function(data){
               if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors.errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                   $( '#' + nameDiv + '  #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + nameDiv + '  .spinner-border').hide();
                $('#' + nameDiv + ' .main-icon').show();
            }
        });
    }



    // updating a group des elements
function updateElementsDEoffre(element = null) {
    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    // var idgroup = $(".addDE").attr('idgroup');
    // var lien = $(".addDE").attr('lien');
    var idgroup = $(element).attr('idgroup');
    var lien = $(element).attr('lien');
    var elem = $(element).attr('idelt');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + elem + '/' + idgroup;
    // alert(link);
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close addDE" lien="offres/retirerDESelectionner"  idgroup="' + idgroup + '" aria-hidden="true" onclick="updateElementsDEoffre(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            // $('.datatableshow2').DataTable().ajax.reload();
            $('.datatableshow5').DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


//changer le lieu d'employeur
    function changeBtnCandt(element){
        if (element == 1){
            $( '.divSel' ).show();
            $( '.divPre' ).hide();
        }
        else {
            $( '.divSel' ).hide();
            $( '.divPre' ).show();
        }
        resetInit();

    }

    //filter des listes des candidatures
    function filterCandidatures(element)
    {
        etat = $("#etat").val();
        $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselections/"+ etat + '/'  + element).load();
    }


    //ajouter des DEs pour l'offresfunction addDE_affectes(element){
     function addDE_offre(element){
        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
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
                // $('.datatableshow4').DataTable().ajax.reload();
                $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselections/"+ data.etat + '/'  + data.offre_id).load();

                // $('#second-modal').modal('toggle');
                // getTheContent('offres/getTab/' + data + '/4/', '#tab4');


            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
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


    //retirer un DE offre
    function retirerDE(id, offre, etat) {
        // alert(id+"//"+offre+"//"+etat);
        var confirme = confirm("Êtes-vous sûr de vouloir retirer ce demandeur d'emploi ?");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine + 'offres/retirerDE/'+id +'/'+offre+'/'+etat,
                success: function (data)
                {
                    $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselections/"+ etat + '/'  + offre).load();

                },
                error: function(data){
                    if( data.status === 422 ) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        $.each( errors, function( key, value ) {
                          errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul></div>';
                        $( '#fichlaboModal #form-errors' ).show().html( errorsHtml );
                    }
                    else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                }
            });
        };
    }
