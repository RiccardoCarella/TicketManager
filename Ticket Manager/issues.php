<?php
  require_once("lib/config.php");
  require_once("lib/check-session.php");

  $users = User::nameList();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
       require_once("templates/header.php");
    ?>
</head>

<body>
    <?php
        require_once("templates/navbar.php")
    ?>
    <div class="mx-2 my-3">
        <div class="my-4 d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <div>
                    <span class="h2 text-dark font-weight-light">Lista </span>
                    <span class="h2 text-black">Issues</span>
                </div>
            </div>
            <div class="d-flex mx-3 align-items-center">
                <div class="form-group mx-3 mb-0">
                    <div class="d-flex align-items-center">
                        <label for="start" class="input-label m-0 mr-1">dal</label>
                        <div class="input-group date" id="start" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input"
                                data-target="#start" />
                            <div class="input-group-append" data-target="#start"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="d-flex align-items-center">
                        <label for="end" class="input-label m-0 mr-1">al</label>
                        <div class="input-group date" id="end" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input"
                                data-target="#end" />
                            <div class="input-group-append" data-target="#end"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid p-0 bg-white rounded">
            <div class="mx-3 py-1">
                <table class="table table-striped table-bordered" id="issues-table">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add-Filter Modal -->
    <div class="modal fade" id="add-filter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="">
                    <h5 class="modal-title" id="exampleModalLabel">Aggiungi filtri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-add-filter" action="javascript:;">
                        <div class="form-group">
                            <div class="d-flex justify-content-start">
                                <div class="d-flex align-items-center">
                                    <h4 class="font-weight option-title">Tipo</h4>
                                </div>
                                <div class="d-flex justify-content-end w-100">
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-type-bug" class="mr-1 mb-0 option-label">Bug</label>
                                        <input type="checkbox" class="switch" id="filter-type-bug"
                                            name="filter-type-bug">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start  ml-4">
                                        <label for="filter-type-improve"
                                            class="mr-1 mb-0 option-label">Miglioramento</label>
                                        <input type="checkbox" class="switch" id="filter-type-improve"
                                            name="filter-type-improve">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start  ml-4">
                                        <label for="filter-type-proposal"
                                            class="mr-1 mb-0 option-label">Proposta</label>
                                        <input type="checkbox" class="switch" id="filter-type-proposal"
                                            name="filter-type-proposal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h4 class="font-weight option-title">Priorità</h4>
                                </div>
                                <div class="d-flex justify-content-end w-100">
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-priority-minor" class="mr-1 mb-0 option-label">Minore</label>
                                        <input type="checkbox" class="switch" id="filter-priority-minor"
                                            name="filter-priority-minor">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-priority-major"
                                            class="mr-1 mb-0 option-label">Importante</label>
                                        <input type="checkbox" class="switch" id="filter-priority-major"
                                            name="filter-priority-major">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-priority-blocker"
                                            class="mr-1 mb-0 option-label">Bloccante</label>
                                        <input type="checkbox" class="switch" id="filter-priority-blocker"
                                            name="filter-priority-blocker">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h4 class="font-weight option-title">Status</h4>
                                </div>
                                <div class="d-flex justify-content-end w-100">
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-status-opened" class="mr-1 mb-0 option-label">Aperto</label>
                                        <input type="checkbox" class="switch" id="filter-status-opened"
                                            name="filter-status-opened">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-status-incharge"
                                            class="mr-1 mb-0 option-label">In carico</label>
                                        <input type="checkbox" class="switch" id="filter-status-incharge"
                                            name="filter-status-incharge">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-status-rejected"
                                            class="mr-1 mb-0 option-label">Rigettato</label>
                                        <input type="checkbox" class="switch" id="filter-status-rejected"
                                            name="filter-status-rejected">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start ml-4">
                                        <label for="filter-status-solved"
                                            class="mr-1 mb-0 option-label">Risolto</label>
                                        <input type="checkbox" class="switch" id="filter-status-solved"
                                            name="filter-status-solved">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="d-flex">
                                    <h4 class="font-weight option-title">Argomento</h4>
                                </div>
                                <div class="w-100 ml-5" id="filter-topic-all">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" id="add-filter-close" class="btn btn-secondary" data-dismiss="modal">
                                Chiudi
                            </button>
                            <button type="submit" id="add-filter-submit" class="btn btn-primary ml-2">
                                Salva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete-Issue Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="height: 400px;">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="delete-id" />
                    </div>
                    <div class="container d-flex justify-content-center">
                        <span class="display-1"><i class="fa fa-times" style="color: #dc3545;"></i></span>
                    </div>
                    <div class="container mt-3 d-flex justify-content-center">
                        <span class="h1">Sicuro?</span>
                    </div>
                    <div class="container mt-2 d-flex justify-content-center">
                        <span class="text-muted text-center ">Vuoi veramente eliminare questa issue? Non potrai tornare
                            indietro.</span>
                    </div>
                    <div class="modal-footer border-top-0 mt-5 d-flex justify-content-center">
                        <button type="button" class="btn btn-lg btn-secondary mx-3" data-dismiss="modal">
                            Annulla
                        </button>
                        <button type="submit" id="save-delete" class="btn btn-lg btn-danger mx-3">
                            Elimina
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once("templates/scripts.php");
    ?>
    <!-- TEMPUS DOMINUS -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

    <!-- My Scripts -->
    <script>
        // Inizializzo il datetimepicker
        $("#start").datetimepicker({
            format: "DD/MM/YYYY",
        });

        // Bindo il click dell'input per aprire il widget picker
        $("#start input").on("click", function() {
            $("#start").datetimepicker("toggle");
        })

        // Inizializzo il datetimepicker
        $("#end").datetimepicker({
            format: "DD/MM/YYYY",
        });

        // Bindo il click dell'input per aprire il widget picker 
        $("#end input").on("click", function() {
            $("#end").datetimepicker("toggle");
        })

        var role = <?= json_encode($_SESSION["role"]); ?> ;
        var id = <?= json_encode($_SESSION["id"]); ?> ;
    </script>
    <script src="js/issues/issues-list.js"></script>

</body>

</html>