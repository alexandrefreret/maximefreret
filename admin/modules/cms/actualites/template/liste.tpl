<div class="col-xs-12">
    <section class="box">
        <header class="panel-header">
            <h2 class="title pull-left">Toutes les actualités</h2>
            <div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_close fa fa-times"></i>
            </div>
        </header>

        <div class="content-body">
            <div class="row">
                <div class="col-md-7 col-sm-6 col-xs-12">
                    <form class="navbar-search" role="search">
                        <div class="form-group">
                            <!--<div class="input-group">-->
                                <!--<div class="input-group-addon"><i class="fa fa-search"></i></div>-->
                                <!--<input type="text" class="form-control" id="actualitesSearch" name="actualitesSearch" placeholder="Rechercher une actualités">-->
                            <!--</div>-->
                        </div>
                    </form>
                </div>

                <div class="col-md-5 col-sm-6 col-xs-12">
                    <div class="pull-right">
                        <!-- BEGIN pagination -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <!-- BEGIN page -->
                                <li class="{pagination.page.class}"><a href="{path_media}cms/actualites/?page={pagination.page.i}" >{pagination.page.i}</a></li>
                                <!-- END page -->
                            </ul>
                        </nav>
                        <!-- END pagination -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <!-- BEGIN actualites -->
                    <div class="post">
                        <h3><a href="{path_media}cms/actualites/editer/{actualites.news_label_url},{actualites.news_id_encode}.html">{actualites.news_label}</a></h3>
                        <h5>Ecrit par <b>{actualites.contact_prenom}</b> le <b>{actualites.news_datedebut_fr}</b></h5>
                        <p class="post-infos">
                            <!--<i class="fa fa-pencil"></i> <a href="{path_media}cms/actualites/editer/{actualites.news_label_url},{actualites.news_id_encode}.html">Editer</a>-->
                        </p>

                        <div class="row blog-content">
                            <div class="col-xs-12 col-sm-5">
                                <!-- BEGIN media -->
                                <img src="{site_url}assets/img/news/illustration/{actualites.news_id_encode}/{actualites.media.media_filename}" class="img-responsive img-thumbnail" alt="Image d'illustration">
                                <!-- END media -->

                                <!-- BEGIN no_media -->
                                <img src="{path_media}assets/img/placeholder/news.png" class="img-responsive img-thumbnail" alt="Image d'illustration">
                                <!-- END no_media -->
                            </div>

                            <div class="col-xs-12 col-sm-7">
                                <div class="blog-resume">
                                    {actualites.news_resume}
                                </div>
                            </div>
                        </div>

                        <a href="{path_media}cms/actualites/editer/{actualites.news_label_url},{actualites.news_id_encode}.html" class="button button-bleu">
                            <span>Editer</span> <i class="fa fa-pencil"></i>
                        </a>

                        <a href="{path_media}cms/actualites/supprimer/{actualites.news_id_encode}.html" class="button button-rouge">
                            <span>Supprimer</span> <i class="fa fa-trash-o"></i>
                        </a>
                    </div>
                    <hr>
                    <!-- END actualites -->
                </div>
            </div>
        </div>
    </section>
</div>