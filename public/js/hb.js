
$(document).ready(function(){
    $("ph").click(function(){
        $("img").attr("src", "{{url($employe->photo)}}");
    });
});

function editImageEtudiant() {
    $('#add-modal #form-errors').hide();
    // var element = $(this);
    $("#add-modal .form-loading").show();
    $.ajax({
        type: $('#add-modal form').attr("method"),
        url: $('#add-modal form').attr("action"),
        data: new FormData($('#image-modal form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#add-modal').modal('toggle');
            $('#add-modal .form-loading').hide();
            $('#add-modal .answers-well-saved').show();
            $('#avatar').attr('src', racine + data);
            previewFile();
            //openStagiaireModal(data);
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors.errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#add-modal #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#image-modal .form-loading").hide();
        }
    });
}

function editImageStagiare() {
    //alert(1)
    $('#add-modal #form-errors').hide();
    // var element = $(this);
    $("#add-modal .form-loading").show();

    // var data = $('#image-modal form').serialize();
    $.ajax({
        type: $('#add-modal form').attr("method"),
        url: $('#add-modal form').attr("action"),
        data: new FormData($('#image-modal form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#add-modal').modal('toggle');
            $('#add-modal .form-loading').hide();
            $('#add-modal .answers-well-saved').show();
            $('#avatar').attr('src', racine + data);
            previewFile();
            //openStagiaireModal(data);
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors.errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#add-modal #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#image-modal .form-loading").hide();
        }
    });
}


function getEmployesPDF(){

    document.formst.action = 'employes/exportEmployesPDF';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;

}

function info_card(data)
{
    $("#full_name").html(data.full_name);
    $("#surname").html(data.surname);
    $("#titre_c").html(data.titre);
    $("#profil_info").html(data.info_right);

}

function previewFile() {
   // const preview = document.querySelector('img');
    const preview = document.querySelector("#img_pic");
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        // convert image file to base64 string
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}


function filterEmployes() {

    genre=$("#ref_genre_id").val();
    type_contrat = $("#type_contrat").val();
    refSituationFamilliale = $("#ref_situation_familliale_id").val();


    $('#datatableshow').DataTable().ajax.url(racine + 'employes/getDT/' + genre +'/'+ type_contrat +'/'+refSituationFamilliale).load();
}
function autre_champs(element)
{
    $(element).find('i').toggleClass('fa-plus fa-minus');
    $("#autre_champ").toggle();
}

function addImageModal(id) {


    url = racine + "employes" + '/openModalImage/' + id;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            //modified by Medyahya
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

function get_fiche_pdf()
{
    document.formstpdf.action = 'employes/fiche_pdf';
    document.formstpdf.target = "_blank";    // Open in a new window
    document.formstpdf.submit();             // Submit the page
    return true;
}

/*$('.panel-title > a').click(function() {
    $(this).find('i').toggleClass('fa-plus fa-minus')
        .closest('panel').siblings('panel')
        .find('i')
        .removeClass('fa-minus').addClass('fa-plus');
});*/
