<div class="col-xs-12">
    <section class="box">
        <header class="panel-header">
            <h2 class="title pull-left">Tous les bandeaux</h2>
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
                                <!--<input type="text" class="form-control" id="bandeauSearch" name="bandeauSearch" placeholder="Rechercher une actualités">-->
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
                                <li class="{pagination.page.class}"><a href="{path_media}cms/bandeau/?page={pagination.page.i}" >{pagination.page.i}</a></li>
                                <!-- END page -->
                            </ul>
                        </nav>
                        <!-- END pagination -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th class="col-xs-6">Titre</th>
                                    <th class="col-xs-6">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                            <!-- BEGIN bandeau -->
                                <tr>
                                    <td>{bandeau.bandeau_label}</td>
                                    <td >
                                        <a href="{path_media}cms/bandeau/editer/{bandeau.bandeau_id_encode}.html" class="button button-bleu">
                                            <span>Editer</span> <i class="fa fa-pencil"></i>
                                        </a>

                                        <a href="{path_media}cms/bandeau/supprimer/{bandeau.bandeau_id_encode}.html" class="button button-rouge">
                                            <span>Supprimer</span> <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            <!-- END bandeau -->

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>