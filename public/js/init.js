$(document).ready(function () {
    // racine = '/onispa/public/';

    // Ajout du CSRF Token pour les requettes ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Customzing dataTable ajax errors
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        console.log(message);
        $.alert("Une erreur est survenue lors du chargement du contenu veuillez réessayer ou actualiser la page!");
    };

    loading_content = '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

    resetInit();
});

// Ouvrir dans le Main Modal
function openInModal(link, aftersave = null) {
    $.ajax({
        type: 'get',
        url: link,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            resetInit();
            if (aftersave)
                aftersave();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

// Get the content from Ajax and show it in a div
function getTheContent(link, container, element = null) {
    if (element) {
        $('.tr-list').css('background-color', '#fff');
        $(element).css('background-color', '#eee');
    }
    $(container).html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + link,
        success: function (data) {
            $(container).html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

// Init of DataTables
function setDataTable(element) {
    // Data tables to load
    if (!$.fn.dataTable.isDataTable(element) && $(element).length) {
        var colonnes = [];
        var index = [];
        var target;
        var ordre;
        var search;
        if (typeof $(element).attr("index") !== 'undefined') {
            var lists = $(element).attr("index").split(',');
            for (var i = 0; i < lists.length; i++) {
                index.push(parseInt(lists[i]));
            }
        } else {
            index.push(-1);
        }
        var nbr = $(element).attr("nbr");
        if (typeof $(element).attr("nbr") !== 'undefined') {
            nbr = $(element).attr("nbr");
        } else {
            nbr = 10;
        }
        if (typeof $(element).attr("ordre") !== 'undefined') {
            ordre = $(element).attr("ordre");
        }
        else
            ordre='asc';
        if (typeof $(element).attr("search") !== 'undefined') {
            search = false;
        } else {
            search = true;
        }
        var lists = $(element).attr("colonnes").split(',');
        for (var i = 0; i < lists.length; i++) {
            colonnes.push({
                'data': lists[i],
                'name': lists[i]
            });
        }
        target = 'targets:' + index;
        oTable = $(element).DataTable({
            oLanguage: {
                sUrl: racine + "vendor/datatables/datatable-fr.json",
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "bDestroy": true,
            "cache": false,
            "searching": search,
            "pageLength": nbr,
            "iDisplayLength": nbr,
            //"ordering": false,
            "order": [[ 0, ordre ]],
            "columnDefs": [{
                orderable: false,
                targets: index
            },
                {
                    searchable: false,
                    targets: index
                }
            ],
            "ajax": $(element).attr("link"),
            "columns": colonnes,
            "drawCallback": function () {
                // init tooltips
                if ($(".status-check").length) {
                    $('.status-check').bootstrapToggle({
                        on: 'Présent',
                        off: 'Absent'
                    });
                }
                ;
                // init tooltips
                $('[data-toggle="tooltip"]').tooltip();
                $('.delete').confirm({
                    title: 'Confirmation',
                    content: 'Êtes-vous sûr de vouloir supprimer cet élément',
                    buttons: {
                        ok: {
                            text: 'Oui',
                            btnClass: 'btn-default',
                            action: function () {
                                $.ajax({
                                    type: 'GET',
                                    url: this.$target.attr('href'),
                                    success: function (data) {
                                        if (data.success == "true") {
                                            //alert($(element).attr("datatable_name"));
                                            $('#datatableshow').DataTable().ajax.reload()
                                            $.alert(data.msg, 'Elément supprimé');
                                        } else $.alert(data.msg, 'Erreur');
                                    },
                                    error: function () {
                                        $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Non',
                            btnClass: 'btn-default'
                        }
                    }
                });
                resetInit();
            }
        })
    }
}

function openObjectModal(id, lemodule, tab = 1, largeModal = 'lg') {
    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {

            $("#main-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            setMainTabs(tab);
            $("#datatableshow").attr('link');
            $('#datatableshow').DataTable().ajax.url($("#datatableshow").attr('link') + "/" + id).load();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function openFormAddInModal(lemodule, id = false) {
    if (id != false)
        url = racine + lemodule + '/add/' + id;
    else
        url = racine + lemodule + '/add/';

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

function setMainTabs(tab = 1,first=false) {
    $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $($(e.target).attr("href")).empty();
        getTheContent($(e.target).attr("link"), $(e.target).attr("href"));

    });

    if (tab == 1 || first==true) {
        let link = $('#link'+tab).attr("link");
        getTheContent(link, '#tab'+tab);
    } else
        $('#link' + tab).trigger('click');

}

function saveform_all(element, aftersave = null) {
    var containers = $(element).attr('container');
    var froms = containers.split(',');
    $.each( froms, function( index, value ) {
        saveform(element,null,value)
    });
}

function addObject(element, lemodule,datatable="#datatableshow") {
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            $('#add-modal').modal('toggle');
            openObjectModal(id, lemodule);
        }, 1500);
    });
}

function saveform(element, aftersave = null) {
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
            if (aftersave) {
                aftersave(data);
            }
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

function confirmAction(link, text, aftersave = null) {
    $.confirm({
        title: 'Confirmation',
        content: text,
        buttons: {
            confirm: function () {
                $.ajax({
                    type: 'GET',
                    url: link,
                    success: function (data) {
                        if (data.success == "true") {
                            if (typeof data.datatableshow !== 'undefined') {
                                datatableshow = data.datatableshow;
                            } else {
                                datatableshow="#datatableshow";
                            }
                            $(datatableshow).DataTable().ajax.reload()
                            $.dialog(data.msg, 'Confirmation');
                            if (aftersave) {
                                aftersave(data);
                            }
                        } else $.dialog(data.msg, 'Erreur');
                    },
                    error: function () {
                        $.dialog("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                });
            },
            close: function () {
            }
        }
    });
}

// updating a group des elements
function updateGroupeElements(element = null) {
    $('[data-toggle="tooltip"]').tooltip('dispose');
    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    var idgroup = $(".group-elements").attr('idgroup');
    var datatble = $(".group-elements").attr('datatable-source');
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
            $(datatble).DataTable().ajax.reload();
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

function resetInit() {
    //$(":input").inputmask();
    $("#img_profile").fileinput({
        language: "fr",
        'showUpload': false,
        // uploadUrl: "/site/image-upload",
        uploadUrl: "/file-upload-batch/2",
        allowedFileExtensions: ["jpg", "png", "gif"],
        maxImageWidth: 20,
        maxImageHeight: 10,
        resizePreference: 'height',
        maxFileCount: 1,
        resizeImage: true
    });

    $(".collapse").on('show.bs.collapse', function(){
        $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
    }).on('hide.bs.collapse', function(){
        $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
    });
    // init du select picker
    $('.selectpicker').selectpicker({
        size: 10
    });
    //tagsinput
    $('#choix').tagsInput({
        'height':'80px',
        'width':'100%',
        'interactive':true,
        'defaultText':'Nouvelle valeur',
        'placeholderColor' : '#666666'
    });
    /*$(".sortable").sortable({
        axis: 'y',
        update: function (event, ui) {}
    });*/

    //Grouping
    $(".group-elements").sortable({
        axis: 'y',
        update: function (event, ui) {
            updateGroupeElements();
        }
    });

    // Datatables to load General
    if ($('#datatableshow').length) setDataTable('#datatableshow');
    if ($('#datatableshow_ind').length) setDataTable('#datatableshow_ind');
    if ($('#datatableshow_ged').length) setDataTable('#datatableshow_ged');

    if ($('.datatableshow').length) setDataTable('.datatableshow');
    // Datatables des onglets
    for (let i = 1; i < 6; i++) {
        if ($('.datatableshow' + i).length) setDataTable('.datatableshow' + i);
        if ($('.datatableshow_ind' + i).length) setDataTable('.datatableshow_ind' + i);
    }

    // init tooltips
    $('[data-toggle="tooltip"]').tooltip();


    // MAKE SAVE BTN LIKE BTN SAVE LOADING AND STUFF


    //bouton enregister ajax
    $(".btn-save").on('click', function () {
        var container = $(this).attr('container');
        $('#' + container + ' #form-errors').hide();
        var element = $(this);
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
                $('.datatableshow').DataTable().ajax.reload();
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    // console.log(errors);
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
    });

}

$('#main-modal').on('hidden.bs.modal', function () {
    if ($('.datatableshow').length) {
        $('.datatableshow').DataTable().ajax.reload();
    } else if ($('#datatableshow').length) {
        $('#datatableshow').DataTable().ajax.reload();
    }
});

(function($, window) {
    'use strict';

    var MultiModal = function(element) {
        this.$element = $(element);
        this.modalCount = 0;
    };
    MultiModal.BASE_ZINDEX = 1040;
    MultiModal.prototype.show = function(target) {
        var that = this;
        var $target = $(target);
        var modalIndex = that.modalCount++;
        $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);
        window.setTimeout(function() {
            if(modalIndex > 0)
                $('.modal-backdrop').not(':first').addClass('hidden');

            that.adjustBackdrop();
        });
    };
    MultiModal.prototype.hidden = function(target) {
        this.modalCount--;

        if(this.modalCount) {
            this.adjustBackdrop();

            // bootstrap removes the modal-open class when a modal is closed; add it back
            $('body').addClass('modal-open');
        }
    };

    MultiModal.prototype.adjustBackdrop = function() {
        var modalIndex = this.modalCount - 1;
        $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
    };

    function Plugin(method, target) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('multi-modal-plugin');

            if(!data)
                $this.data('multi-modal-plugin', (data = new MultiModal(this)));

            if(method)
                data[method](target);
        });
    }

    $.fn.multiModal = Plugin;
    $.fn.multiModal.Constructor = MultiModal;

    $(document).on('show.bs.modal', function(e) {
        $(document).multiModal('show', e.target);
    });

    $(document).on('hidden.bs.modal', function(e) {
        $(document).multiModal('hidden', e.target);
    });
}(jQuery, window));
