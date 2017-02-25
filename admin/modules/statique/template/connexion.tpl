<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Administration {title_site}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

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

            </div>

            <div class="pull-left page-title">
                <h1>
                    <span class="line"></span>
                    CONNEXION
                </h1>
            </div>

            <!--<div class="pull-left">-->
                <!--<ul class="info-menu list-inline list-unstyled">-->
                    <!--<li>-->
                        <!--<a href=""><i class="fa fa-user"></i></a>-->
                    <!--</li>-->
                <!--</ul>-->
            <!--</div>-->
        </div>
    </div>


    <div id="main-content">
        <section class="main-wrapper">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-8">
                        <br/><br/>
                        <!-- BEGIN error -->
                        <div class="alert alert-danger">
                            Erreur de connexion, veuillez réessayer
                        </div>
                        <!-- END error -->
                        <form class="form floating-label" action="" accept-charset="utf-8" method="POST">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <button class="button button-default" type="submit"><span>Connexion</span> <i class="fa fa-user"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



<script src="{path_media}assets/js/bootstrap.min.js"></script>
<!-- BEGIN js -->
<script src="{path_media}assets/js/{js.src}"></script>
<!-- END js -->
<script src="{path_media}assets/js/app.min.js"></script>
</body>
</html>