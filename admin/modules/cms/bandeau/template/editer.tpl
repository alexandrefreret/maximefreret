<form action="{path_media}cms/bandeau/save.html" method="POST" id="bandeau_form">
    <input type="hidden" name="bandeau_id_encode" id="bandeau_id_encode" value="{bandeau_id_encode}">

    <div class="col-xs-12">
        <div class="pull-right">
            <a href="{path_media}cms/bandeau/" class="button button-default"><span>Retour</span> <i
                    class="fa fa-reply"></i></a>
            <button class="button button-vert"><span>Enregistrer</span> <i class="fa fa-floppy-o"></i></button>
        </div>
    </div>

    <div class="col-xs-12 col-sm-8">
        <section class="box">
            <header class="panel-header">
                <h2 class="title pull-left">Informations principales</h2>
                <div class="actions panel_actions pull-right">
                    <i class="box_toggle fa fa-chevron-down"></i>
                    <!--<i class="box_close fa fa-times"></i>-->
                </div>
            </header>

            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="bandeau_label">Titre </label>
                            <input type="text" name="bandeau_label" id="bandeau_label" value="{bandeau_label}"
                                   class="form-control">
                        </div>

                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="bandeau_button_text">Texte du bouton</label>
                            <input type="text" name="bandeau_button_text" id="bandeau_button_text"
                                   value="{bandeau_button_text}" class="form-control">
                        </div>
                    </div>


                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="bandeau_button_link">Lien du bouton</label>
                            <input type="text" name="bandeau_button_link" id="bandeau_button_link"
                                   value="{bandeau_button_link}" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="bandeau_position">Position du texte</label>
                            <select name="bandeau_position" id="bandeau_position" class="form-control">
                                <option value="0" {position_0_selected}>Droite</option>
                                <option value="1" {position_1_selected}>Gauche</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="bandeau_contenu">Contenu </label>
                            <textarea name="bandeau_contenu" id="bandeau_contenu" class="trumbowyg">{bandeau_contenu}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xs-12 col-sm-4">
        <section class="box">
            <header class="panel-header">
                <h2 class="title pull-left">Méta</h2>
                <div class="actions panel_actions pull-right">
                    <i class="box_toggle fa fa-chevron-down"></i>
                    <!--<i class="box_close fa fa-times"></i>-->
                </div>
            </header>

            <div class="content-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <h3 class="subtitle">Autre</h3>

                            <div class="form-group">
                                <label for="bandeau_order">Ordre</label>
                                <input type="text" name="bandeau_order" id="bandeau_order" value="{bandeau_order}"
                                       class="form-control">
                            </div>

                            <hr>


                            <h3 class="subtitle">Illustration</h3>

                            <div class="row">
                                <div class="col-xs-12">
                                    <!-- BEGIN no_photo -->
                                    <a href="{path_media}cms/recadrage/?type=bandeau&media_id={bandeau_id}"
                                       onclick="save_bandeau(); return load_modal(this, 'Ajout d\'une image d\'illustration', true);"
                                       class="button button-vert btn-block"><span>Ajouter une image</span> <i
                                            class="fa fa-picture-o"></i></a>
                                    <!-- END no_photo -->


                                    <!-- BEGIN photo -->
                                    <img src="{site_url}assets/img/bandeau/illustration/{bandeau_id_encode}/{photo.media_filename}"
                                         alt="Image d'illustration" class="img-thumbnail img-responsive ">
                                    <br>
                                    <br>
                                    <a href="{path_media}cms/recadrage/?type=bandeau&media_id={bandeau_id}"
                                       onclick="save_bandeau(); return load_modal(this, 'Modifier l\'image d\'illustration', true);"
                                       class="button button-vert btn-block"><span>Modifier l'image</span> <i
                                            class="fa fa-picture-o"></i></a>
                                    <!-- END photo -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</form>