<?php
    require_once("lib/config.php");
    require_once("lib/check-session.php");

    // Se l'utente non è admin non può accedere alla lista utenti
    if($_SESSION['role'] != 1) {
        header("location: issues.php");
    }
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
    <div class="mx-3 my-3">
        <div class="my-4">
            <span class="h2 text-dark font-weight-light">Lista </span><span class="h2 text-black">Utenti</span>
        </div>
        <div class="container-fluid p-0 bg-white rounded">
            <div class="mx-3 py-1">
                <table class="table table-striped table-bordered" id="users-table">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Add-User Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Aggiungi utente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-add-user" action="javascript:;">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nome</label><span class="text-danger font-weight-bold">
                                *</span>
                            <input type="text" id="name" class="form-control" placeholder="Enter name" />
                            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                        </div>
                        <div class="form-group">
                            <label for="surname">Cognome</label>
                            <input type="text" class="form-control" id="surname" placeholder="Enter surname" />
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" class="form-control" id="email" placeholder="Enter email" />
                        </div>
                        <div class="form-group">
                            <label for="mobile">Telefono</label>
                            <input type="text" class="form-control" id="mobile" placeholder="Enter mobile" />
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label><span class="text-danger font-weight-bold"> *</span>
                            <input type="text" id="username" class="form-control" placeholder="Enter username" />
                        </div>
                        <div class="form-group">
                            <label for="role">Ruolo</label><span class="text-danger font-weight-bold">
                                *</span>
                            <select name="role" id="role" class="form-control">
                                <option selected disabled hidden>Scegli Ruolo</option>
                                <option value="1">Admin</option>
                                <option value="2">Editor</option>
                                <option value="3">Viewer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label><span class="text-danger font-weight-bold"> *</span>
                            <input type="password" id="password" class="form-control" placeholder="Enter password" />
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Chiudi
                            </button>
                            <button type="submit" id="add-user-submit" class="btn btn-primary ml-2">
                                Salva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit-User Modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifica utente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-user" action="javascript:;">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nome</label><span class="text-danger font-weight-bold">
                                *</span>
                            <input type="text" name="edit-name" id="edit-name" class="form-control"
                                placeholder="Enter name" />
                            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</!--<small>-->
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Cognome</label>
                            <input type="text" class="form-control" name="edit-surname" id="edit-surname"
                                placeholder="Enter surname" />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Email</label>
                            <input type="text" class="form-control" name="edit-email" id="edit-email"
                                placeholder="Enter email" />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Telefono</label>
                            <input type="text" class="form-control" name="edit-mobile" id="edit-mobile"
                                placeholder="Enter mobile" />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Username</label><span
                                class="text-danger font-weight-bold"> *</span>
                            <input type="text" class="form-control" name="edit-username" id="edit-username"
                                placeholder="Enter username" />
                        </div>
                        <div class="form-group">
                            <label for="role">Ruolo</label><span class="text-danger font-weight-bold"> *</span>
                            <select name="edit-role" id="edit-role" class="form-control">
                                <option selected disabled hidden>Scegli Ruolo</option>
                                <option value="1">Admin</option>
                                <option value="2">Editor</option>
                                <option value="3">Viewer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" name="edit-password" id="edit-password"
                                placeholder="Reset password" />
                        </div>
                        <div class="modal-footer border-0 p-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Chiudi
                            </button>
                            <button type="submit" id="save-edit" class="btn btn-primary" disabled>
                                Salva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete-User Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Elimina utente</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div> -->
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
                        <span class="text-muted text-center ">Vuoi veramente eliminare questo utente? Non potrai tornare
                            indietro.</span>
                    </div>
                    <div class="modal-footer border-top-0 p-0 mt-5 d-flex justify-content-center">
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
    
    <script>
        var role = <?= json_encode($_SESSION["role"]); ?> ;
    </script>
    <script src="js/users/users-list.js"></script>
    <script src="js/users/add-user.js"></script>
</body>

</html>