<?php
  require_once("lib/config.php");
  require_once("lib/check-session.php");

  $users = User::nameList();
  $warners = Issue::warnersList();
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
    <div class="mx-5 my-3">
        <div class="my-4 d-flex justify-content-between">
            <div>
                <span class="h2 text-dark font-weight-light">Aggiungi </span><span
                    class="h2 text-black">Issue</span>
            </div>
            <div class="form-group">
                <span class="switch">
                    <input type="checkbox" class="switch" id="private" name="private">
                    <label for="private" style="font-size: 1.3rem">Privato</label>
                </span>
            </div>
        </div>
        <div class="container-fluid p-0 rounded">
            <div>
                <form id="form-add-issue" action="javascript:;">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Titolo</label><span class="text-danger font-weight-bold"> *</span>
                            <input type="text" id="title" class="form-control" placeholder="Inserisci titolo" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="topic">Argomento</label>
                            <input type="text" id="topic" class="form-control" placeholder="Inserisci argomento" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content">Contenuto</label>
                        <textarea class="form-control" name="content" id="content" rows="1"
                        placeholder="Inserisci contenuto"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="author" class="input-label">Autore</label><span
                                class="text-danger font-weight-bold"> *</span>
                            <select class="form-control" id="author" name="author" <?= $_SESSION['role'] != 1 ? "disabled" : "" ?>>
                                <option selected disabled hidden>Scegli Autore</option>
                                <option value="<?= $_SESSION['id'] ?>" <?= $_SESSION['role'] != 1 ? "" : "hidden" ?> <?= $_SESSION['role'] != 1 ? "selected" : "" ?>><?= $_SESSION['fullName']?></option>
                                <?php
                                    foreach ($users as $user):
                                ?>
                                <option value="<?= $user->getId() ?>">
                                    <?= $user->getName()." ".$user->getSurname() ?>
                                </option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="warner">Segnalatori</label>
                            <select class="form-control" id="warner" name="warner" multiple="multiple">
                                <?php
                                    foreach ($warners as $warner):
                                ?>
                                <option value="<?= $warner ?>">
                                    <?= $warner ?>
                                </option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="receiver" class="input-label">Destinatario</label><span
                                class="text-danger font-weight-bold">
                                *</span>
                            <select class="form-control" id="receiver" name="receiver">
                                <option selected disabled hidden>Scegli destinatario</option>
                                <?php
                                    foreach ($users as $user):
                                ?>
                                <option value="<?= $user->getId() ?>">
                                    <?= $user->getName()." ".$user->getSurname() ?>
                                </option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="kind" class="input-label">Tipo</label><span
                                class="text-danger font-weight-bold">
                                *</span>
                            <select class="form-control" id="kind" name="kind">
                                <option selected disabled hidden>Scegli tipo</option>
                                <option value="bug">Bug</option>
                                <option value="miglioramento">Miglioramento</option>
                                <option value="proposta">Proposta</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="priority" class="input-label">Priorità</label><span
                                class="text-danger font-weight-bold">
                                *</span>
                            <select class="form-control" id="priority" name="priority">
                                <option selected disabled hidden>Scegli priorità</option>
                                <option value="minore">Minore</option>
                                <option value="importante">Importante</option>
                                <option value="bloccante">Bloccante</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="status" class="input-label">Status</label><span
                                class="text-danger font-weight-bold">
                                *</span>
                            <select class="form-control" id="status" name="status" <?= $_SESSION['role'] != 1 ? "disabled" : "" ?>>
                                <option selected disabled hidden>Scegli status</option>
                                <option value="aperto" <?= $_SESSION['role'] != 1 ? "selected" : "" ?>>Aperto</option>
                                <option value="in carico" <?= $_SESSION['role'] != 1 ? "disabled hidden" : ""?>>In carico</option>
                                <option value="rigettato" <?= $_SESSION['role'] != 1 ? "disabled hidden" : ""?>>Rigettato</option>
                                <option value="risolto" <?= $_SESSION['role'] != 1 ? "disabled hidden" : ""?>>Risolto</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="creationDate" class="input-label">Data creazione</label><span
                                class="text-danger font-weight-bold">
                                *</span>
                            <div class="input-group date" id="creationDate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#creationDate" />
                                <div class="input-group-append" data-target="#creationDate"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="lastUpdate" class="input-label">Data ultima modifica</label><span
                                class="text-danger font-weight-bold">
                                *</span>
                            <div class="input-group date" id="lastUpdate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#lastUpdate" />
                                <div class="input-group-append" data-target="#lastUpdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="attachments" class="input-label">Allegati</label><br>
                            
                            <!-- <input name="attachments[]" id="attachments" class="rounded" type="file"
                                multiple="multiple" /> -->
                            <!-- <span id="numFiles" class="files-counter">Nessun file selezionato</span> -->
                            <div class="d-flex flex-wrap" id="files-list">
                                <label for="attachments" id="file-upload-add" class="custom-file-upload rounded mr-2 mb-2">
                                    <i class="fa fa-cloud-upload"></i> Carica file
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pb-3">
                        <button type="button" id="cancel-button" class="btn btn-secondary" data-dismiss="modal">
                            Annulla
                        </button>
                        <button type="submit" id="add-issue-submit" class="btn btn-primary ml-2">
                            Salva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div class="modal fade" id="upload-file-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog upload-file-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Carica allegati</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form   id="form-upload-file" 
                            class="dropzone bg-light rounded" 
                            style="font-family: 'Roboto', sans-serif;" 
                            action="api/files/upload-file.php">
                    </form>
                    <div id="preview">
                        <ul id="preview-list" class="p-0 my-2" style="list-style-type: none; font-family: 'Roboto', sans-serif">
                        </ul>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="close-upload-file">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Uploading files alert modal -->
    <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="height: 400px;">
                    <div class="container d-flex justify-content-center">
                        <span class="display-1"><i class="fa fa-exclamation" style="color: #dc3545;"></i></span>
                    </div>
                    <div class="container mt-3 d-flex justify-content-center">
                        <span class="h1">Attenzione!</span>
                    </div>
                    <div class="container mt-2 d-flex justify-content-center">
                        <span class="text-muted text-center ">Hai ancora <span id="filesToUpload"></span> file in fase di caricamento.</span>
                    </div>
                    <div class="modal-footer border-top-0 mt-5 d-flex justify-content-center">
                        <button type="button" class="btn btn-lg btn-secondary mx-3" data-dismiss="modal">
                            Indietro
                        </button>
                        <button type="submit" id="save-alert" class="btn btn-lg btn-danger mx-3">
                            Avanti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php
        require_once("templates/scripts.php");
        ?>

    <!-- DROPZONE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    
    <!-- CKEDITOR -->
    <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content')
    </script>

    <!-- SELECT2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.2/dist/select2-bootstrap4.min.css">
    <script>

        $("#warner").select2({
            tags: true,
            theme: "bootstrap4",
            placeholder: "Inserisci segnalatori"
        });
        
    </script>

    <!-- TEMPUS DOMINUS -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
        
    <script>
        Dropzone.autoDiscover = false;
        Dropzone.confirm = function (question, accepted, rejected) { return accepted()};
        var role = <?= json_encode($_SESSION["role"]); ?> ;
    </script>
    <script src="js/issues/add-issue.js"></script>
</body>

</html>