$(document).ready(function(){
    webroot = $('meta[name="webroot"]').attr("content");
    

    if($('.typeahead').length > 0)
    {
        var options = {

            // url: webroot+"assets/json/villes.json",
            url: function(phrase) {
                return webroot+'utilisateurs/search_ville.html?query=' + phrase;
            },

            getValue: function(element) {
                return element.ville_code_postal + " " + element.ville_nom_simple;
            },
            requestDelay: 200,
            list: {
                maxNumberOfElements: 20,
                match: {
                    enabled: true
                },
                onSelectItemEvent: function() {
                    var ville_code_postal = $(".typeahead").getSelectedItemData().ville_code_postal;
                    var ville_nom_simple = $(".typeahead").getSelectedItemData().ville_nom_simple;

                    $("#contact_cp").val(ville_code_postal).trigger("change");
                    $("#contact_ville").val(ville_nom_simple).trigger("change");
                }
            }
        };

        $(".typeahead").easyAutocomplete(options);
    }



    if($('.summernote').length > 0)
    {        // Full toolbar
        $('.summernote').summernote({
            height: $('.summernote').height()
        });
    }


    $('.submenu > a').on("click",function(e){
        e.preventDefault();
        if($(this).parent().hasClass("active"))
        {
            $(".submenu").removeClass("active");
        }
        else
        {
            $(".submenu").removeClass("active");
            $(this).parent().addClass("active");
        }
    });


    $('.box_toggle').on("click",function(){
        $(this).closest(".box").toggleClass("min");
    });


    if($('.trumbowyg').length > 0)
    {
        var configurations = {
            plugins: {
                btnsDef: {
                    // Customizables dropdowns
                    image: {
                        dropdown: ['insertImage', 'upload', 'base64', 'noEmbed'],
                        ico: 'insertImage'
                    }
                },
                btns: [
                    ['viewHTML'],
                    ['undo', 'redo'],
                    ['formatting'],
                    'btnGrp-design',
                    ['link'],
                    ['image'],
                    'btnGrp-justify',
                    'btnGrp-lists',
                    ['foreColor', 'backColor'],
                    'preformatted','horizontalRule',
                    ['fullscreen'],
                    ['soPlaceholder']
                ],
                plugins:
                {
                    // Add imagur parameters to upload plugin
                    upload: {
                        serverPath: '/mancheagriconseil/admin/upload_image',
                        fileFieldName: 'image',
                        urlPropertyName: 'data.link'
                    }
                },
                lang: 'fr',
                semantic: false
            }

        };
        $('.trumbowyg').trumbowyg(configurations["plugins"]);
    }


    if($('.airdatepicker').length > 0)
    {
        $('.airdatepicker').datepicker({
            language: 'fr',
            dateFormat : "dd/mm/yyyy",
            position : "bottom right",
            minDate: new Date(),
            onSelect : function(fd, date, inst){
                if($(inst.el).hasClass("airdatepicker_debut"))
                {
                    mindate_air_datepicker('.airdatepicker_fin',fd);
                }
            }
        });
    }






});



function redirect(url)
{
    document.location.href = url;
}


function date2En(dateFr)
{
    var dateEn = dateFr.split('/');
    return dateEn[2] + '-' + dateEn[1] + '-' + dateEn[0];
}

function update_air_datepicker(element,date_fr)
{
    var date_en = date2En(date_fr);
    var date = new Date(date_en);

    $(element).val(date_fr);
    $(element).datepicker().data('datepicker').selectDate(date);
}


function mindate_air_datepicker(element,date_fr)
{
    var date_en = date2En(date_fr);
    var date = new Date(date_en);

    var current_date = $('.airdatepicker_fin').val();
    var date_en_end = date2En(current_date);
    var date_end = new Date(date_en_end);

    var begin_date = $('.airdatepicker_debut').val();
    var date_en_begin = date2En(begin_date);
    var date_begin = new Date(date_en_begin);

    $(element).datepicker().data('datepicker').update('minDate', date);

    if(date_begin > date_end)
    {
        $(element).val(date_fr);
        $(element).datepicker().data('datepicker').selectDate(date);
    }

}


function load_modal(self, title, large)
{
    var $modal = $('#modal');
    var link = $(self).attr("href");

    if(title != undefined)
    {
        $modal.find(".modal-title").html(title);
    }
    else
    {
        $modal.find(".modal-title").html("");
    }


    if(large != undefined)
    {
        $modal.find(".modal-dialog").addClass("modal-lg");
    }
    else
    {
        $modal.find(".modal-dialog").removeClass("modal-lg");
    }


    $.ajax({
        url : link,
        method : "GET",
        success : function(data)
        {
            $modal.find('.modal-body').html(data);
            $modal.modal("show");
        }
    });

    return false;
}


function save_news()
{
    var $form = $('#news_form');
    var data = $form.serialize();
    var url = $form.attr("action");
    $.post(url, data);
}


function save_bandeau()
{
    var $form = $('#bandeau_form');
    var data = $form.serialize();
    var url = $form.attr("action");
    $.post(url, data);
}

function toggle_menu()
{
    
}