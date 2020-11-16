$(function() {
    $("#users-table").DataTable({
        language: {
            sEmptyTable: "Nessun dato presente nella tabella",
            sInfo: "Vista da _START_ a _END_ di _TOTAL_ elementi",
            sInfoEmpty: "Vista da 0 a 0 di 0 elementi",
            sInfoFiltered: "(filtrati da _MAX_ elementi totali)",
            sInfoPostFix: "",
            sInfoThousands: ",",
            sLengthMenu: "Visualizza _MENU_ elementi",
            sLoadingRecords: "Caricamento...",
            sProcessing: "Elaborazione...",
            sSearch: "Cerca:",
            sZeroRecords: "La ricerca non ha portato alcun risultato.",
            oPaginate: {
                sFirst: "Inizio",
                sPrevious: "Precedente",
                sNext: "Successivo",
                sLast: "Fine"
            },
            oAria: {
                sSortAscending:
                    ": attiva per ordinare la colonna in ordine crescente",
                sSortDescending:
                    ": attiva per ordinare la colonna in ordine decrescente"
            }
        },
        buttons: {
            dom: {
                button: {
                    className: "btn btn-sm btn-primary"
                }
            },

            buttons: [
                {
                    text: "Aggiungi",
                    className: "",
                    attr: {
                        id: "add-button"
                    },
                    action: function(e, dt, node, config) {
                        //
                    }
                }
            ]
        },

        scrollX: "100%",

        autoWidth: false,

        lengthMenu: [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Tutti"]
        ],

        order: [[0, "asc"]],

        ajax: {
            url: "api/users/users-list.php",
            method: "GET"
        },
        columns: [
            { data: "id", title: "Id", className: "text-center align-middle" },
            {
                data: "name",
                title: "Nome",
                className: "text-nowrap align-middle"
            },
            {
                data: "surname",
                title: "Cognome",
                className: "text-nowrap align-middle",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "username",
                title: "Username",
                className: "text-nowrap align-middle"
            },
            {
                data: "role",
                title: "Ruolo",
                className: "align-middle",
                render: function(data, type, row) {
                    if (type == "display") {
                        if (data) {
                            if (data == 1) {
                                return "<div class='align-middle'>Admin</div>";
                            } else if (data == 2) {
                                return "<div class='align-middle'>Editor</div>";
                            } else if (data == 3) {
                                return "<div class='align-middle'>Viewer</div>";
                            } else {
                                return data;
                            }
                        } else {
                            return "<div class='text-center align-middle'>-</div>";
                        }
                    }
                    return data;
                }
            },
            {
                data: "email",
                title: "E-mail",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "mobile",
                title: "Telefono",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                title: "Azioni",
                render: function(data, type, row) {
                    return "<i class='fa fa-pencil mr-3 action-button edit-button' style='cursor: pointer'></i><i class='fa fa-trash action-button delete-button' style='cursor: pointer'></i>";
                },
                className: "text-center align-middle"
            }
        ],
        columnDefs: [
            {
                targets: 6,
                orderable: false
            },
            {
                targets: [0, 6],
                width: "1%"
            }
        ],

        dom:
            "<'row mt-3'<'col-sm-12 col-md-6 mb-2'l><'col-sm-12 col-md-6'<'d-flex align-items-center justify-content-center justify-content-md-end'f<'ml-2'B>>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5 my-3'<'d-flex align-items-center justify-content-center justify-content-md-start h-100'i>><'col-sm-12 col-md-7 my-3'<'justify-content-center justify-content-md-end d-flex' p>>>",

        initComplete: function() {
            $("#add-button").on("click", function() {
                $("#add-modal").modal("show");
            });
        },

        rowCallback: function(row, data) {
            $(".delete-button", row)
                .off("click")
                .on("click", function() {
                    $("#delete-modal").modal("show");

                    $("#save-delete")
                        .off("click")
                        .on("click", function() {
                            $.ajax({
                                url: "api/users/delete-user.php",
                                method: "post",
                                data: {
                                    id: data.id,
                                    fromAjax: 1
                                },
                                success: function(result) {
                                    if (result.success) {
                                        if (result.msg) {
                                            alert(result.msg);
                                        }
                                        $("#delete-modal").modal("hide");
                                        window.location.reload();
                                    } else {
                                        alert(result.msg);
                                    }
                                }
                            });
                        });
                });

            $(".edit-button", row)
                .off("click")
                .on("click", function() {
                    var previousData = {};
                    previousData["name"] = data.name;
                    previousData["surname"] = data.surname;
                    previousData["email"] = data.email;
                    previousData["mobile"] = data.mobile;
                    previousData["username"] = data.username;
                    previousData["password"] = "";
                    previousData["role"] = data.role;

                    var $form = $("#form-edit-user");
                    $("#edit-modal").modal("show");

                    $.each(previousData, (key, value) => {
                        var $input = $('[name="edit-' + key + '"]', $form);
                        $input.val(value);
                        $input.attr("data-value", value);

                        $input.bind("change keyup", function() {
                            var isChanged = false;

                            $("[name]", $form).each(function() {
                                var value = $(this).val();
                                var original = $(this).attr("data-value");

                                if (value != original) {
                                    isChanged = true;
                                }
                            });

                            $('[type="submit"]', $form).attr(
                                "disabled",
                                !isChanged
                            );
                        });
                    });

                    $("#save-edit")
                        .off("click")
                        .on("click", function() {
                            $.ajax({
                                url: "api/users/edit-user.php",
                                method: "post",
                                data: {
                                    id: data.id,
                                    name: $("#edit-name").val(),
                                    surname: $("#edit-surname").val(),
                                    email: $("#edit-email").val(),
                                    username: $("#edit-username").val(),
                                    mobile: $("#edit-mobile").val(),
                                    password: $("#edit-password").val(),
                                    role: $("#edit-role").val(),
                                    fromAjax: 1
                                },
                                success: function(result) {
                                    if (result.success) {
                                        $("#edit-modal").modal("hide");
                                        window.location.reload();
                                    } else {
                                        alert(result.msg);
                                    }
                                }
                            });
                        });
                });
        }
    });

    return false;
});
