<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{title_site}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{path_media}assets/css/styles.min.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <h1 class="hide">Maxime Freret Ostéopathe</h1>
                        <a href="{WEBROOT}" class="header-logo">
                            <img src="{path_media}assets/img/logo-banniere.png" alt="Logo de Maxime Freret osteopathe">
                        </a>
                    </div>

                    <div class="col-sm-8">
                        <div class="collapse navbar-collapse" id="">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="{WEBROOT}">Accueil</a></li>
                                <li><a href="{WEBROOT}contact/">Contact</a></li>
                                <li><a href="tel:+33666666666"><i class="fa fa-phone"></i> +33 6 66 66 66 66</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="owl-carousel owl-theme">
        <!-- BEGIN bandeau -->
        <div class="item {bandeau.bandeau_class}">

            <!-- BEGIN bandeau_label -->
            <div class="bandeau_label">{bandeau.bandeau_label}</div>
            <!-- END bandeau_label -->

            <!-- BEGIN bandeau_contenu -->
            <div class="bandeau_contenu">{bandeau.bandeau_contenu}</div>
            <!-- END bandeau_contenu -->

            <!-- BEGIN bandeau_buttonlink -->
            <a href="{bandeau.bandeau_button_link}" class="bandeau_btn">{bandeau.bandeau_button_text}</a>
            <!-- END bandeau_buttonlink -->

            <img src="{path_media}assets/img/bandeau/illustration/{bandeau.bandeau_id_encode}/{bandeau.media_filename}" alt="Bandeau">

        </div>
        <!-- END bandeau -->

    </div>

    {CONTENT}

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <script src="{path_media}assets/js/jquery.min.js"></script>
    <script src="{path_media}assets/js/owl.carousel.min.js"></script>
    <script src="{path_media}assets/js/app.min.js"></script>
</body>
</html>