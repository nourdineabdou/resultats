$(document).ready(function () {
    // Code js ici !
});
function matieres_profil() {
    var profil = $('#profil').val();
    if (profil != '') {
        $('select[name="matiere"]').empty();
        $.ajax({
            type: 'get',
            url: racine + 'matieresPR/getmatieres/' + profil,
            success: function (data) {
                if (data != '') {
                    $('select[name="matiere"]').append('<option  value=""></option>');
                    $.each(data, function (key, value) {
                        $('select[name="matiere"]').append('<option value="' + value.id + '"> ' + value.libelle + '</option>');
                    });
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function imprimerListeEmergemet1() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='col'; }
    if ($('#ind').is(':checked')== true){ choix='ind'; }

    document.formst.action = 'examens/imprimerListeEmergemet1/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerListeEmergemet() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='col'; }
    if ($('#ind').is(':checked')== true){ choix='ind'; }

    document.formst.action = 'examens/imprimerListeEmergemet/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}
function imprimerCollectNotes() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='col'; }
    if ($('#ind').is(':checked')== true){ choix='ind'; }

    document.formst.action = 'examens/imprimerCollectNote/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerCorrespontNotes() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='col'; }
    if ($('#ind').is(':checked')== true){ choix='ind'; }

    document.formst.action = 'examens/imprimerCorrespontNote/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerPVNotes() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#matiere1').is(':checked')== true){ choix='1'; }
    if ($('#module').is(':checked')== true){ choix='2'; }
    if ($('#semestre1').is(':checked')== true){ choix='3'; }

    document.formst.action = 'examens/imprimerPVNotes/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function getBultinImpressionCollect() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    document.formst1.action = 'examens/getBultinImpressionCollect/'+profil+'/'+groupe+'/'+semestre+'/'+etape;
    document.formst1.target = "_blank";    // Open in a new window
    document.formst1.submit();             // Submit the page
    return true;
}

function getbultin(id,semestre) {

    document.formst1.action = 'examens/getbultin/'+id+'/'+semestre;
    document.formst1.target = "_blank";    // Open in a new window
    document.formst1.submit();             // Submit the page
    return true;
}

function getBultinImpressionIndiRel() {
    var nodos = $('#nodos').val();
    var semestre = $('#semestrein').val();
    var annee_id = $('#annee_id').val();
    if (nodos !='' && semestre!='' && annee_id!='')
    document.formst1.action = 'examens/getBultinImpressionIndiRel/'+nodos+'/'+semestre+'/'+annee_id+'';
    document.formst1.target = "_blank";    // Open in a new window
    document.formst1.submit();             // Submit the page
    return true;
}
function getMajSemestre() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();

    document.formst1M.action = 'examens/getMajSemestre/'+profil+'/'+groupe+'/'+semestre+'/'+etape;
    document.formst1M.target = "_blank";    // Open in a new window
    document.formst1M.submit();             // Submit the page
    return true;
}

function getBultinImpressionCollect11() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();

    document.formst11.action = 'examens/getBultinImpressionCollect11/'+profil+'/'+groupe+'/'+semestre+'/'+etape;
    document.formst11.target = "_blank";    // Open in a new window
    document.formst11.submit();             // Submit the page
    return true;
}
function getBultinImpressionCollect11AN() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();

    document.formst11.action = 'examens/getBultinImpressionCollect11AN/'+profil+'/'+groupe+'/'+semestre+'/'+etape;
    document.formst11.target = "_blank";    // Open in a new window
    document.formst11.submit();             // Submit the page
    return true;
}

function getBultinImpressionCollect11AN2() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();

    document.formst11.action = 'examens/getBultinImpressionCollect11AN2/'+profil+'/'+groupe+'/'+semestre+'/'+etape;
    document.formst11.target = "_blank";    // Open in a new window
    document.formst11.submit();             // Submit the page
    return true;
}

function genererSalles() {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/genererSalles/',
            success: function (data) {
                if (data==1){
                    alert('bien generer')
                }
                if (data==2){
                    alert('Deja attecher les candidats au salles')
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
}

function repartisserLesSalles() {
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    choix='all';
    msg='هل تريد تةزيع القاعات';
    var confirme = confirm(msg);
    if (confirme){
    $.ajax({
        type: 'get',
        url: racine + 'examens/genererSalles/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix,
        success: function (data) {
            if (data==1){
                alert('bien generer')
            }
            if (data==2){
                alert('Deja attecher les candidats au salles')
            }
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    }
}
function genererAnonymats() {

    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    if(etape)
    {
        $.ajax({
            type: 'get',
            url: racine + 'examens/genererAnonymats/' + profil+'/'+etape,
            success: function (data) {
                if (data==1){
                    alert('bien generer')
                }
                if (data==0){
                    alert('verifier la plage de cet profil <br> voir paramettrage')
                }
                if (data==2){
                    alert('Deja generer l anonymat')
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function genererAnonymatsConcours() {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/genererAnonymats/',
            success: function (data) {
                if (data==1){
                    alert('bien generer')
                }

                if (data==2){
                    alert('Deja generer l anonymat')
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
}

function calculerNoteFinalEtud(msg) {
    var confirme = confirm(msg);
    if (confirme)
    {
        //$("#divContenueplaysup").html(loading_content);
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/calculerNoteEtud/',
            success: function (data) {
                if (data==1){
                    alert('bien generer')
                }

                if (data==2){
                    alert('Deja generer l anonymat')
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}
function calculer3Correction(msg) {
    var confirme = confirm(msg);
    if (confirme)
    {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/calculer3correction/',
            success: function (data) {
                if (data==1){
                    alert('bien generer')
                }

                if (data==2){
                    alert('Deja generer l anonymat')
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
       /* */
}

function generer3Correction(msg) {
    var matiere = $('#matiere').val();
    var confirme = confirm(msg);
    if (confirme)
    {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/generer3correction/'+matiere,
            success: function (data) {
                if (data==1){
                    alert('bien generer')
                }

                if (data==2){
                    alert('Deja generer l anonymat')
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
       /* */
}

function imprimerListeEmergemetInd() {
    var matiere = $('#matiers_profil').val();
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='all'; }
    if ($('#ind').is(':checked')== true){ choix=matiere; }

    document.formst.action = 'examens/imprimerListeEmergemet/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerListeEmargementParSalle(){
    var matiere = $('#matiere').val();

    document.formst.action = 'examensCON/imprimerListeEmargementParSalle/'+matiere;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerListeEmargementParSalle1(){
    var matiere = $('#matiere').val();

    document.formst.action = 'examensCON/imprimerListeEmargementParSalle1/'+matiere;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerListeEmargementTroisiemeCorrection(){
    var matiere = $('#mat').val();

    document.formstmat.action = 'examensCON/imprimerListeEmargementTroisiemeCorrection/'+matiere;
    document.formstmat.target = "_blank";    // Open in a new window
    document.formstmat.submit();             // Submit the page
    return true;
}

function getImprimerAnnymatParDosier(){
    var matiere = $('#matiere').val();
    document.formstan.action = 'examensCON/getImprimerAnnymatParDosier/'+matiere;
    document.formstan.target = "_blank";    // Open in a new window
    document.formstan.submit();             // Submit the page
    return true;
}

function imprimerCollectNoteParSalle(){
    //sera changer au lieu de salle doit etre par pacquet
    var matiere = $('#matiere').val();
    document.formst.action = 'examensCON/imprimerCollectNoteParSalle/'+matiere;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function getImpressionCandidat(){
    //sera changer au lieu de salle doit etre par pacquet
   document.formst4.action = 'examensCON/imprimerCollectNoteCand/';
    document.formst4.target = "_blank";    // Open in a new window
    document.formst4.submit();             // Submit the page
    return true;
}

function getImpressionCandidatDefinitve(){
    //sera changer au lieu de salle doit etre par pacquet
    document.formst5.action = 'examensCON/imprimerCollectNoteCandFinal/';
    document.formst5.target = "_blank";    // Open in a new window
    document.formst5.submit();             // Submit the page
    return true;
}
function getImpressionCandidatDefinitveAdmis(){
    //sera changer au lieu de salle doit etre par pacquet
    document.formst6.action = 'examensCON/imprimerCollectNoteCandFinalAdmis/';
    document.formst6.target = "_blank";    // Open in a new window
    document.formst6.submit();             // Submit the page
    return true;
}
function imprimerListeCollectInd() {
    var matiere = $('#matiers_profil').val();
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='all'; }
    if ($('#ind').is(':checked')== true){ choix=matiere; }

    document.formst.action = 'examens/imprimerCollectNote/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}
function imprimerListeCorrespondInd() {
    var matiere = $('#matiers_profil').val();
    var groupe = $('#groupe').val();
    var profil = $('#profil').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var choix='';
    if ($('#col').is(':checked')== true){ choix='all'; }
    if ($('#ind').is(':checked')== true){ choix=matiere; }

    document.formst.action = 'examens/imprimerCorrespontNote/'+profil+'/'+groupe+'/'+semestre+'/'+etape+'/'+choix;
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function calculerNotes() {
    msg='هل تريد  حساب النقاط';
    var confirme = confirm(msg);
    if (confirme)
    {
        var groupe = $('#groupe').val();
        var profil = $('#profil').val();
        var semestre = $('#semestre').val();
        var etape = $('#etape').val();
        $.ajax({
            type: 'get',
            url: racine + 'examens/calculerNotes/' + profil+'/'+semestre+'/'+groupe+'/'+etape,
            success: function (data) {
                if (data ==1) {
                    alert('ok')
                }
                if (data ==2) {
                    alert('not ok')
                }
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}

function calculerNote() {
    msg='هل تريد  حساب النقاط';
    var confirme = confirm(msg);
    if (confirme)
    {
        var groupe = $('#groupe').val();
        var id = $('#idsEt').val();
        var profil = $('#profil').val();
        var semestre = $('#semestre').val();
        var etape = $('#etape').val();

        $.ajax({
            type: 'get',
            url: racine + 'examens/calculerNote/' + profil+'/'+semestre+'/'+groupe+'/'+etape+'/'+id+'',
            success: function (data) {
                if (data ==1) {
                    alert('ok')
                }
                if (data ==2) {
                    alert('not ok')
                }
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}

function annulercalculerNoteAn() {
    msg='هل تريد  حساب النقاط';
    var confirme = confirm(msg);
    if (confirme)
    {
        var groupe = $('#groupe').val();
        var id = $('#idsEt').val();
        var profil = $('#profil').val();
        var semestre = $('#semestre').val();
        var etape = $('#etape').val();

        $.ajax({
            type: 'get',
            url: racine + 'examens/annulercalculerNoteAn/' + profil+'/'+semestre+'/'+groupe+'/'+etape+'/'+id+'',
            success: function (data) {
                if (data ==1) {
                    alert('ok')
                }
                if (data ==2) {
                    alert('not ok')
                }
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}


function calculerNoteNow() {
    msg='هل تريد  حساب النقاط';
    var confirme = confirm(msg);
    if (confirme)
    {
        var groupe = $('#groupe').val();
        var id = $('#idsEt').val();
        var profil = $('#profil').val();
        var semestre = $('#semestre').val();
        var etape = $('#etape').val();

        $.ajax({
            type: 'get',
            url: racine + 'examens/calculerNoteNow/' + profil+'/'+semestre+'/'+groupe+'/'+etape+'/'+id+'',
            success: function (data) {
                if (data ==1) {
                    alert('ok')
                }
                if (data ==2) {
                    alert('not ok')
                }
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}

function annulercalculerNotes() {
    msg='هل تريد القاء حساب النقاط';
    var confirme = confirm(msg);
    if (confirme)
    {
        var groupe = $('#groupe').val();
        var profil = $('#profil').val();
        var semestre = $('#semestre').val();
        var etape = $('#etape').val();
        $.ajax({
            type: 'get',
            url: racine + 'examens/annulercalculerNotes/' + profil+'/'+semestre+'/'+groupe+'/'+etape,
            success: function (data) {
                alert('bien supprime')
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}

function getMatieresParProfil(id) {
    var etape=$("#etape").val()
    if (id != '') {
        $('select[name="matiers_profil"]').empty();
        $.ajax({
            type: 'get',
            url: racine + 'examens/getmatiers_profil/' + id+'/'+etape,
            success: function (data) {
                if (data != '') {
                    $('select[name="matiers_profil"]').append('<option  value=""></option>');
                    $.each(data, function (key, value) {
                        $('select[name="matiers_profil"]').append('<option value="' + value.matiere_id + '"> ' + value.matiere.libelle + '</option>');
                    });
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function activerDivInd() {
    if ($("#matiers_profil").val()!=''){
        $("#divind").show();
        $("#divcol").hide();
    }
    else{
        $("#divind").hide();
        $("#divcol").hide();
    }

}
function getform(form) {
    if (form== 'ind' && $('#ind').is(':checked')== true){
        $("#col").prop("checked", false);
        getMatieresParProfil($("#profil").val());
        $("#divonmatier").show();
        $("#divcol").hide();
    }
    if (form== 'col' && $('#col').is(':checked')== true){
        $("#ind").prop("checked", false);
        $("#divonmatier").hide();
        $("#divcol").show();
        $("#divind").hide();
    }
}

function getParemetreImpression(){
    url = racine + 'examens/getImpression';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function getParemetreImpression1(){
    url = racine + 'examens/getImpression1';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function changeClick()
{

    if ($('#matiere1').is(':checked')== true) {
        $("#module").prop("checked", false);
        $("#semestre1").prop("checked", false);
    }
    else{
        $("#matiere1").prop("checked", false);
    }
}

function changeClick1()
{

    if ($('#module').is(':checked')== true) {
        $("#matiere1").prop("checked", false);
        $("#semestre1").prop("checked", false);
    }
    else{
        $("#module").prop("checked", false);
    }
}

function changeClick2()
{
    if ($('#semestre1').is(':checked')== true) {
        $("#matiere1").prop("checked", false);
        $("#module").prop("checked", false);
    }
    else{
        $("#semestre1").prop("checked", false);
    }
}
function getNoteIndiv(){
    var idsEt = $('#idsEt').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    url = racine + 'examens/getNoteIndiv/'+idsEt+'/'+semestre+'/'+etape;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function getNoteIndivModifier(){
    var idsEt = $('#idsEt').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    document.formst1MM.action = 'examens/getNoteIndivModifier/'+idsEt+'/'+semestre+'/'+etape;
    document.formst1MM.target = "_blank";    // Open in a new window
    document.formst1MM.submit();
}

function getNoteIndivan(){
    var idsEt = $('#idsEt').val();
    var semestre = $('#semestre').val();
    var etape = $('#etape').val();
    var profil = $('#profil').val();
    url = racine + 'examens/getNoteIndivan/'+idsEt+'/'+semestre+'/'+etape+'/'+profil;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function getParemetreImpressionCollect(){
    url = racine + 'examens/getImpressionCollect';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function getPVImpressionCollect(){
    url = racine + 'examens/getPVImpressionCollect';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function getParemetreImpressionCorrespond(){
    url = racine + 'examens/getImpressionCorrespond';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function groupes_profil() {
    var profil = $('#profil').val();
    if (profil != '') {
        $('#datatableshow').DataTable().ajax.url(racine + 'examens/getDT/' + profil).load();
        /*$('select[name="groupe"]').empty();
        $.ajax({
            type: 'get',
            url: racine + 'profilGroupes/getgroupeprofil/' + profil,
            success: function (data) {
                if (data != '') {
                    $('select[name="groupe"]').append('<option  value=""></option>');
                    $.each(data, function (key, value) {
                        $('select[name="groupe"]').append('<option value="' + value.groupe_id + '"> ' + value.ref_groupe.libelle + '</option>');
                    });
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });*/
    }
}
function modulles_profil() {
    var profil = $('#profil').val();
    if (profil != '') {
        $('select[name="modulle"]').empty();
        $.ajax({
            type: 'get',
            url: racine + 'matieres/getmodulle/' + profil,
            success: function (data) {
                if (data != '') {
                    $('select[name="modulle"]').append('<option  value=""></option>');
                    $.each(data, function (key, value) {
                        $('select[name="modulle"]').append('<option value="' + value.id + '"> ' + value.libelle + '</option>');
                    });
                }
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}
    function modulles_profil1() {
        var profil = $('#profil1').val();
        if (profil != '') {
            $('select[name="modulle"]').empty();
            $.ajax({
                type: 'get',
                url: racine + 'matieres/getmodulle/' + profil,
                success: function (data) {
                    if (data != '') {
                        $('select[name="modulle"]').append('<option  value=""></option>');
                        $.each(data, function (key, value) {
                            $('select[name="modulle"]').append('<option value="' + value.id + '"> ' + value.libelle + '</option>');
                        });
                    }
                    resetInit();
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
    }
function getBachellier(id){
        $.ajax({
            type: 'get',
            url: racine + 'inscriptions/getBachellier/'+id,
            success: function (data) {
                $("#addbachelier").html(data);
                $("#addbachelier").show();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
}

function getEtudiant(id){
    $.ajax({
        type: 'get',
        url: racine + 'reinscriptions/getEtudiant/'+id,
        success: function (data) {
            $("#addbachelier").html(data);
            $("#addbachelier").show();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function changeProfil(id){
        $.ajax({
            type: 'get',
            url: racine + 'inscriptions/changeProfil/'+id,
            success: function (data) {
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
}

function annulerAttribution(id) {
    $("#divContenueplaysup").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'inscriptions/annulerAttribution/'+id,
        success: function (data) {
            $("#divContenueplaysup").html('');
            $("#divContenueplaysup").html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function annulerAttributionReinscription(id) {
    $("#divContenueplaysup").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'reinscriptions/annulerAttribution/'+id,
        success: function (data) {
            $("#divContenueplaysup").html('');
            $("#divContenueplaysup").html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function attribuerAttribution(id) {
    $("#divContenueplaysup").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'inscriptions/attribuerAttribution/'+id,
        success: function (data) {
            $("#divContenueplaysup").html('');
            $("#divContenueplaysup").html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function bloquer(id,msg='هل تريد اقاف تسجيل  الطالب')
{
    var confirme = confirm(msg);
    if (confirme) {
        $.ajax({
            type: 'get',
            url: racine + 'editions/bloqueEtudiant/' + id,
            success: function (data) {
                location.reload();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function bloquer1(id,msg='حظر الطالب')
{
    $.ajax({
        type: 'get',
        url: racine + 'editions/bloqueEtudiant1/'+id,
        success: function (data) {
           location.reload();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function vesualisezSalle(id,msg=' هل تريد حجز القاعة') {
    var confirme = confirm(msg);
    if (confirme) {
        $.ajax({
            type: 'get',
            url: racine + 'salles/vesualisezSalle/' + id,
            success: function (data) {
                $('#datatableshow').DataTable().ajax.url(racine + 'salles/getDT/all').load();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function devesualisezSalle(id,msg=' هل تريد القاء حجز القاعة') {
    var confirme = confirm(msg);
    if (confirme) {
        $.ajax({
            type: 'get',
            url: racine + 'salles/devesualisezSalle/' + id,
            success: function (data) {
                $('#datatableshow').DataTable().ajax.url(racine + 'salles/getDT/all').load();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}
function supprimerReinscription(id,msg='القاء تسجيل  الطالب')
{
    var confirme = confirm(msg);
    if (confirme) {
        $.ajax({
            type: 'get',
            url: racine + 'editions/supprimerReinscription/' + id,
            success: function (data) {
                location.reload();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function corrigerAttestation(id)
{
    $.ajax({
        type: 'get',
        url: racine + 'editions/corrigerAttestation/'+id,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function chagerProfil(id)
{
    $.ajax({
        type: 'get',
        url: racine + 'editions/chagerProfil/'+id,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function chagerProfil1(id)
{
    $.ajax({
        type: 'get',
        url: racine + 'editions/chagerProfil1/'+id,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function chanerNumero(id)
{
    $.ajax({
        type: 'get',
        url: racine + 'editions/chanerNumero/'+id,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function attribuerAttributionReinscription(id) {
    $("#divContenueplaysup").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'reinscriptions/attribuerAttribution/'+id,
        success: function (data) {
            $("#divContenueplaysup").html('');
            $("#divContenueplaysup").html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });

}

function SupprimeerMatierDejaProgrammee(id,idEtd) {
    var confirme = confirm('تاكيد حذف هذا العنصر');
    if (confirme)
    {
        $("#divContenueplaysup").html(loading_content);
        $.ajax({
            type: 'get',
            url: racine + 'editions/SupprimeerMatierDejaProgrammee/'+id,
            success: function (data) {
                $("#second-modal").closest();
                corrigerAttestation(idEtd);
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}



function addImageModalEtudiant(id) {
    url = racine +  'inscriptions/openModalImage/' + id;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
             $("#add-modal .modal-dialog").addClass("modal-lg");
            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function showmatieres()
{
    var profil = $("#profil").val();
    if (profil!=''){
        $("#divmatieres").show();
        changeProfil(profil);
    }
    else
    {
        $("#divmatieres").hide();
    }
}
function getMatieresPr(){
    var profil = $("#profil").val();
    url = racine + 'inscriptions/inserteTemp/'+profil;
    $.ajax({
        type: 'get',
        url: url,
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
//49741123
function getMatieresPrRein(){
    var profil = $("#profil").val();
    var profil1 = $("#profil1").val();
    var id_b= $("#id_b").val();
    if (profil1 == '')
    {
        profil1=0;
    }
    url = racine + 'reinscriptions/inserteTemp/'+profil+'/'+profil1+'/'+id_b;
    $.ajax({
        type: 'get',
        url: url,
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
function changeAnnees(){
    annee = $("#annee").val();
    if (annee != '')
    {
        $('#datatableshow').DataTable().ajax.url(racine + 'inscriptions/getDT/'+ annee).load();
    }
}

function changeProfilEdition(){
    profil = $("#profil").val();
    groupe = 'all';
    groupe = $("#groupe").val();
    if (profil != '' && profil != 'all')
    {
        $("#getGroupe").show();
        deleteAllAtt();
        $('#datatableshow').DataTable().ajax.url(racine + 'editions/getDT/'+ profil+'/'+ groupe+'/all').load();
    }
    else
    {
        $("#getGroupe").hide();
    }
}

function changeGroupeEdition(){
    profil = 'all';
    profil = $("#profil").val();
    groupe = $("#groupe").val();
    if (groupe != '' && groupe != 'all' )
    {
        $('#datatableshow').DataTable().ajax.url(racine + 'editions/getDT/'+ profil+'/'+ groupe+'/all').load();
    }
    else
    {
        $("#getGroupe").hide();
    }
}

function saisieNotes()
{
    var profil = $("#profil").val();
    var semestre = $("#semestre").val();
    var etape = $("#etape").val();
     $.ajax({
        type:'get',
        url: racine + 'examens/getNotes/'+profil+'/'+semestre+'/'+etape,
        success:function(data){
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            $('.btnAffecter').hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    return false;
}
function releveNoteIndiv()
{
    $.ajax({
        type:'get',
        url: racine + 'examens/releveNoteIndiv/',
        success:function(data){
            $("#second-modal .modal-dialog").addClass("modal-lg");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            $('.btnAffecter').hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    return false;
}

function saisieNotesSemestreAN()
{
    var profil = $("#profil").val();
    var groupe = $("#groupe").val();
    var semestre = $("#semestre").val();

     $.ajax({
        type:'get',
        url: racine + 'examens/saisieNotesSemestreAN/'+profil+'/'+groupe+'/'+semestre,
        success:function(data){
            $("#second-modal .modal-dialog").addClass("modal-xl");
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            $('.btnAffecter').hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    return false;
}

function imprimerSemestreAN()
{
    var profil = $("#profil").val();
    var groupe = $("#groupe").val();
    var semestre = $("#semestre").val();


    document.formst12.action = 'examens/imprimerSemestreAN/'+profil+'/'+groupe+'/'+semestre;
    document.formst12.target = "_blank";    // Open in a new window
    document.formst12.submit();             // Submit the page
    return true;
}
function getAllInscritsL3AUT()
{


    document.formst.action = 'reinscriptions/getAllInscritsL3AUT';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function Listel2avecmoyenne()
{


    document.formst.action = 'reinscriptions/Listel2avecmoyenne';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function imprimerAtteAN()
{
    var profil = $("#profil").val();
    var groupe = $("#groupe").val();
    var semestre = $("#semestre").val();


    document.formst13.action = 'examens/imprimerAtteAN/'+profil+'/'+groupe+'/'+semestre;
    document.formst13.target = "_blank";    // Open in a new window
    document.formst13.submit();             // Submit the page
    return true;
}

function optionTroisiemeConcours()
{
    var matiere = $("#matiere").val();
    if (matiere) {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/optionTroisiemeConcours/' + matiere,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-lg");
                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                $('.btnAffecter').hide();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    return false;
}

function saisieNoteConcoursCorr3()
{

    var matiere = $("#matiere").val();
    if (matiere) {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/getNotesCorr3/' + matiere,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-lg");
                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                $('.btnAffecter').hide();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    return false;
}
function saisieNoteConcours()
{

    var matiere = $("#matiere").val();
    if (matiere) {
        $.ajax({
            type: 'get',
            url: racine + 'examensCON/getNotes/' + matiere,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-lg");
                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                $('.btnAffecter').hide();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    return false;
}

function get_NoteImp() {
    var id_matiere = $("#matiere_id").val();
    var etatpe_id = $("#etape").val();
    var groupe = $("#groupe").val();
    var profil = $("#profil").val();
    var semestre = $("#semestre").val();
    //document.addForm.action = "etudiants/Devis"
    window.open( 'examens/get_NoteImp/'+id_matiere+'/'+etatpe_id+'/'+profil+'/'+semestre+'/'+groupe+'', '_blank');
   /* document.formstpdf.location = 'examens/get_NoteImp/'+id_matiere+'/'+etatpe_id+'/'+profil+'/'+semestre+'/'+groupe;
    document.formstpdf.target = "_blank";    // Open in a new window
    document.formstpdf.submit();             // Submit the page
    */
   return true;
}

function Delete_Note(msg='تريد حذف نتايج هذا العنصر') {

    var confirme = confirm(msg);
    if (confirme) {
        var id_matiere = $("#matiere_id").val();
        var etatpe_id = $("#etape").val();
        var groupe = $("#groupe").val();
        var profil = $("#profil").val();
        var semestre = $("#semestre").val();
       // $("#addForm").html(loading_content);
        url = racine + 'examens/Delete_Note/' + id_matiere + '/' + etatpe_id + '/' + profil + '/' + semestre + '/' + groupe;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                alert('تم الحذف')
                $("#addForm").html('');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}
function Delete_NoteDoublon(msg='تريد حذف نتايج هذا العنصرالمتكررة') {

    var confirme = confirm(msg);
    if (confirme) {
        var id_matiere = $("#matiere_id").val();
        var etatpe_id = $("#etape").val();
        var groupe = $("#groupe").val();
        var profil = $("#profil").val();
        var semestre = $("#semestre").val();
       // $("#addForm").html(loading_content);
        url = racine + 'examens/Delete_NoteDoublon/' + id_matiere + '/' + etatpe_id + '/' + profil + '/' + semestre + '/' + groupe;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                alert('تم الحذف')
                $("#addForm").html('');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}
function getNoEtudiants(){
    var id_matiere = $("#matiere_id").val();
    var etatpe_id = $("#etape").val();
    var groupe = $("#groupe").val();
    var profil = $("#profil").val();
    var semestre = $("#semestre").val();
    if(id_matiere != ''){
        $("#getEtudiants").html(loading_content);
        $.ajax({
            type:'get',
            url: racine + 'examens/getNoEtudiants/'+id_matiere+'/'+etatpe_id+'/'+profil+'/'+semestre+'/'+groupe,
            success:function(data){
                    $("#getEtudiants").html(data);
                    $("#getEtudiants").show();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else
    {
        $("#getEtudiants").html('');
        $("#getEtudiants").hide();
    }
}

function getCandidatSalle(){
    var matiere = $("#matiere").val();
    var pacquet = $("#pacquet").val();
    var correction = $("#correction").val();

    if(matiere != '' &&  pacquet != '' &&  correction != ''){
        $("#getEtudiants").html(loading_content);
        $.ajax({
            type:'get',
            url: racine + 'examensCON/getCandidatsSalle/'+matiere+'/'+pacquet+'/'+correction,
            success:function(data){
                $("#getEtudiants").html(data);
                $("#getEtudiants").show();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else
    {
        $("#getEtudiants").html('');
        $("#getEtudiants").hide();
    }
}

function getCandidatCorr3(){
    var matiere = $("#matiere").val();
    var pacquet = $("#pacquet").val();
    var correction = $("#correction").val();

    if(matiere != '' &&  pacquet != '' &&  correction != ''){
        $("#getEtudiants").html(loading_content);
        $.ajax({
            type:'get',
            url: racine + 'examensCON/getCandidatCorr3/'+matiere+'/'+pacquet+'/'+correction,
            success:function(data){
                $("#getEtudiants").html(data);
                $("#getEtudiants").show();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else
    {
        $("#getEtudiants").html('');
        $("#getEtudiants").hide();
    }
}

function optionsBouuttons() {
    var matiere = $("#matiere").val();
    if (matiere !='')
    {
        $("#divlefdesabled").show();
        $("#divrigtdesabled").show();
    }
    else{
        $("#divlefdesabled").hide();
        $("#divrigtdesabled").hide();
    }
}
function activedivs(){
    var etatpe_id = $("#etape").val();
    var groupe = $("#groupe").val();
    var profil = $("#profil").val();
    var semestre = $("#semestre").val();
    if (etatpe_id !='' && groupe !='' && profil!='' && semestre !='' )
    {
        $("#divlefdesabled").show();
        $("#divrigtdesabled").show();
    }
    else{
        $("#divlefdesabled").hide();
        $("#divrigtdesabled").hide();
    }
}


function saveBachalier(element) {
    saveform(element, function (id) {
        alert('Bien Inserer')
        location.reload();
    });
}
function saveeTUDIANTrEIN(element) {
    saveform(element, function (id) {
        alert('Bien Inserer')
        location.reload();
    });
}
function saveEtudiant(element) {
    saveform(element, function (id) {
            alert('Bien Inserer')
       location.reload();

    });
}
function affecterNotesEtudiansdev(element) {
    var confirme = confirm('تريد ارداع الامضاء');
    if (confirme){
        document.addForm.action = "examens/devalidernote"    // First target
    //document.addForm.ta();    // Open in a iframe
    // document.addForm.submit();        // Submit the page
    saveform(element, function (id) {
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            //second-modal
            $("#btn2").hide();
            $("#btn1").hide();
            alert('devalider')

        }, 1500);
    });
}
}
function affecterNotesEtudians2(element)
{
    //var confirme = confirm(msg);
    document.addForm.action = "examens/validernote"    // First target
    //document.addForm.ta();    // Open in a iframe
    // document.addForm.submit();        // Submit the page
    saveform(element, function (id) {
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            //second-modal
             $("#btn2").hide();
            $("#btn1").hide();
            alert('Bien Valider')

        }, 1500);
    });
}

function affecterNotesEtudians(element) {
    saveform(element, function (id) {
        $("#btn1").hide();
        if (id == 2)
        {
            $("#sit").val('save');
            alert('Bien Inserer')
        }
        if (id == 1)
        {
            $("#sit").val('edit');
            alert('Bien Inserer')
        }
        if (id == 0)
        {
            alert('Existe un erreur')
        }

    });
}

function affecterNotesEtudiansNote(element) {
    saveform(element, function (id) {
        $("#btn1").hide();
        if (id == 1)
        {

            alert('Bien Inserer')
        }
        if (id == 0)
        {
            alert('Existe un erreur')
        }

    });
}

function test(value,noteEssay) {

    if (parseFloat(value)==value && value <=noteEssay && value >=0) {} else alert('La note doit étre entre 0 et '+noteEssay+ '');
}

function exporteattestationPDF(id){
    document.formst.action = 'editions/exporteattestationPDF/'+id+'';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function pdfListeEtudiant(){
    profil = $("#profil").val();
    document.formst1.action = 'editions/pdfListeEtudiant/'+profil;
    document.formst1.target = "_blank";    // Open in a new window
    document.formst1.submit();             // Submit the page
    return true;
}
function pdfstatiNSEtudiant(){
    profil = $("#profil").val();
    document.formst1.action = 'editions/pdfstatiNSEtudiant/'+profil;
    document.formst1.target = "_blank";    // Open in a new window
    document.formst1.submit();             // Submit the page
    return true;
}
function pdfListeRenvoyer(){

    document.formst1.action = 'editions/pdfListeRenvoyer';
    document.formst1.target = "_blank";    // Open in a new window
    document.formst1.submit();             // Submit the page
    return true;
}

function pdfattestationColl(){
    profil = $("#profil").val();
     //groupe = $("#groupe").val();
    groupe='ا';
    document.formst3.action = 'editions/pdfattestationColl/'+profil+'/'+groupe;
    document.formst3.target = "_blank";    // Open in a new window
    document.formst3.submit();             // Submit the page
    return true;
}
function selectEtudiantsEdit(id) {
    if ($('#'+id).is(':checked')== true){
        url = racine + 'editions/inserteTemp/'+id;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else{
        url = racine + 'editions/DeleteTemp/'+id;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function selectEtudiantsExamen(etudiant)
{
    // alert(etudiant)
    $("#idsEt").val('');
    $("#idsEt").val(etudiant);

}
function deleteAllAtt() {
    url = racine + 'editions/DeleteAll';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
