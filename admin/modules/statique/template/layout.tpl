<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{TITLE} - Administration {title_site}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400" rel="stylesheet">

    <link rel="stylesheet" href="{path_media}assets/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="{path_media}assets/css/bootstrap-theme.min.css">-->
    <link rel="stylesheet" href="{path_media}assets/css/font-awesome.min.css">
    <!-- BEGIN css -->
    <link rel="stylesheet" href="{path_media}assets/css/{css.src}">
    <!-- END css -->

    <link rel="stylesheet" href="{path_media}assets/css/styles.css">

    <script src="{path_media}assets/js/jquery.min.js"></script>



</head>
<body>

    <div id="content">
        <div class="topbar">
            <div class="logo-area"></div>

            <div class="quick-area">
                <div class="pull-left ">
                    <ul class="info-menu toggle-menu list-inline list-unstyled">
                        <li>
                            <a href="" onclick="toggle_menu(); return false;" class="toggle-link"><i class="fa fa-bars"></i></a>
                        </li>
                    </ul>
                </div>

                <div class="pull-left page-title">
                    <h1>
                        <span class="line"></span>
                        {TITLE}
                    </h1>
                </div>

                <div class="pull-left">
                    <ul class="info-menu list-inline list-unstyled">
                        <li>
                            <a href="{path_media}cms/compte/profil"><i class="fa fa-user"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="sidebar">
            <div class="sidebar-wrapper">
                <div class="profile-info">
                    <div class="profile-image col-md-12 col-sm-12 col-xs-12 text-center">
                        <a href="{path_media}cms/compte/profil">
                            <!-- BEGIN no_contact_photo -->
                            <img src="{path_media}assets/img/placeholder/users.png" alt="Image utilisateur" class="img-responsive">
                            <!-- END no_contact_photo -->

                            <!-- BEGIN contact_photo -->
                            <img src="{path_media}assets/img/users/{contact_id}/{contact_img}" alt="Image utilisateur" class="img-responsive">
                            <!-- END contact_photo -->
                        </a>
                    </div>

                    <div class="profile-details col-md-12 col-sm-12 col-xs-12 text-center">
                        <h3><a href="{path_media}cms/compte/profil">{contact_prenom} {contact_nom}</a></h3>
                    </div>
                </div>


                <nav>
                    <ul>
                        <li>
                            <a href="{path_media}cms/">
                                <i class="fa fa-th-large"></i>
                                <span class="title">Accueil</span>
                            </a>
                        </li>

                        <li>
                            <a href="{path_media}cms/page.html">
                                <i class="fa fa-file-o"></i>
                                <span class="title">Page</span>
                            </a>
                        </li>

                        <li class="submenu">
                            <a href="{path_media}cms/actualites/">
                                <i class="fa fa-newspaper-o"></i>
                                <span class="title">Actualites</span>
                                <span class="arrow fa fa-chevron-down"></span>
                            </a>
                            <ul class="submenu-list">
                                <li>
                                    <a href="{path_media}cms/actualites/">Toutes</a>
                                </li>
                                <li>
                                    <a href="{path_media}cms/actualites/ajouter/">Ajouter une actus</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="{path_media}cms/evenement/">
                                <i class="fa fa-calendar"></i>
                                <span class="title">Evènement</span>
                                <span class="arrow fa fa-chevron-down"></span>
                            </a>
                            <ul class="submenu-list">
                                <li>
                                    <a href="{path_media}cms/evenement/">Tous</a>
                                </li>
                                <li>
                                    <a href="{path_media}cms/evenement/ajouter/">Ajouter</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="{path_media}cms/Bandeau/">
                                <i class="fa fa-picture-o"></i>
                                <span class="title">Bandeau</span>
                                <span class="arrow fa fa-chevron-down"></span>
                            </a>
                            <ul class="submenu-list">
                                <li>
                                    <a href="{path_media}cms/bandeau/">Tous</a>
                                </li>
                                <li>
                                    <a href="{path_media}cms/bandeau/ajouter/">Ajouter</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>

        <div id="main-content">
            <section class="main-wrapper">
                <div class="content-wrapper">
                    {CONTENT}
                </div>
            </section>
        </div>
    </div>


    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="button button-default" id="cancel_modal" data-dismiss="modal"><span>Fermer</span> <i class="fa fa-times"></i></button>
                    <button type="button" class="button button-vert" id="submit_modal" onclick="$('#modal').find('form').submit();"><span>Enregistrer</span> <i class="fa fa-floppy-o"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script src="{path_media}assets/js/bootstrap.min.js"></script>
    <!-- BEGIN js -->
    <script src="{path_media}assets/js/{js.src}"></script>
    <!-- END js -->
    <script src="{path_media}assets/js/app.min.js"></script>
</body>
</html>