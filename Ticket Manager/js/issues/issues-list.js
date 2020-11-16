$(function() {
    $("#issues-table").DataTable({
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
                    className: "btn "
                }
            },

            buttons: [
                {
                    text: "Aggiungi",
                    className: "btn-sm btn-primary rounded",
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
        iDisplayLength: 10,
        order: [[8, "asc"]],

        ajax: {
            url: "api/issues/issues-list.php",
            method: "GET",
            data: function() {
                var start = moment(
                    $("#start").data("date"),
                    "DD/MM/YYYY"
                ).startOf("day");
                var end = moment($("#end").data("date"), "DD/MM/YYYY").endOf(
                    "day"
                );
                var obj = {};
                obj.start = start.format("YYYY-MM-DD HH:mm:ss");
                obj.end = end.format("YYYY-MM-DD HH:mm:ss");

                // var myJSON = JSON.stringify(obj);
                // return myJSON;
                return obj;
            }
        },
        columns: [
            // {
            //     data: "id",
            //     title: "Id",
            //     className: "text-center align-middle",
            //     width: "1%"
            // },
            {
                data: "creationDate",
                title: "Creazione",
                className: "text-nowrap align-middle",
                width: "1%",
                render: function(data, type, row) {
                    if (data) {
                        data = moment(data, "YYYY-MM-DD HH:mm:ss");
                        data = data.format("DD/MM/YY HH:mm");
                    }
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "lastUpdate",
                title: "Modifica",
                className: "text-nowrap align-middle",
                width: "1%",
                render: function(data, type, row) {
                    if (data) {
                        data = moment(data, "YYYY-MM-DD HH:mm:ss");
                        data = data.format("DD/MM/YY HH:mm");
                    }
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "title",
                title: "Titolo",
                className: "align-middle text-nowrap issue-list-title",
                width: "1%",
                render: function(data, type, row) {
                    var content = $("<div/>")
                        .html(data)
                        .text();
                    var previousContent = content;

                    if (content.length >= 30) {
                        content = content.substr(0, 30);

                        return (
                            "<span title='" +
                            previousContent +
                            "'>" +
                            content +
                            "</span>..."
                        );
                    } else if (content.length > 0 && content.length < 30) {
                        return (
                            "<span title='" +
                            previousContent +
                            "'>" +
                            content +
                            "</span>"
                        );
                    } else {
                        return "<div class='text-center align-middle'>-</div>"; // Se data è {null, '', '0', 0, false, undefined}
                    }
                }
            },
            {
                data: "content",
                title: "Contenuto",
                className: "align-middle issue-list-content",
                width: "100%",
                render: function(data, type, row) {
                    var content = $("<div/>")
                        .html(data)
                        .text();
                    var previousContent = content;
                    if (content.length >= 50) {
                        content = content.substr(0, 50);

                        return (
                            "<span title='" +
                            previousContent +
                            "'>" +
                            content +
                            "</span>..."
                        );
                    } else if (content.length > 0 && content.length < 50) {
                        return (
                            "<span title='" +
                            previousContent +
                            "'>" +
                            content +
                            "</span>"
                        );
                    } else {
                        return "<div class='text-center align-middle'>-</div>"; // Se data è {null, '', '0', 0, false, undefined}
                    }
                }
            },
            {
                data: "topic",
                title: "Argomento",
                className: "align-middle issue-list-topic",
                width: "100%",
                render: function(data, type, row) {
                    var topic = $("<div/>")
                        .html(data)
                        .text();
                    var previousTopic = topic;
                    if (topic.length >= 50) {
                        topic = topic.substr(0, 50);

                        return (
                            "<span title='" +
                            previousTopic +
                            "'>" +
                            topic +
                            "</span>..."
                        );
                    } else if (topic.length > 0 && topic.length < 50) {
                        return (
                            "<span title='" +
                            previousTopic +
                            "'>" +
                            topic +
                            "</span>"
                        );
                    } else {
                        return "<div class='text-center align-middle'>-</div>"; // Se data è {null, '', '0', 0, false, undefined}
                    }
                }
            },
            // {
            //     data: "creator.fullName",
            //     title: "Creatore",
            //     className: "text-nowrap align-middle",
            //     width: "1%",
            //     render: function(data, type, row) {
            //         return (
            //             data || "<div class='text-center align-middle'>-</div>"
            //         ); // Se data è {null, '', '0', 0, false, undefined}
            //     }
            // },
            {
                data: "author.fullName",
                title: "Autore",
                className: "text-nowrap align-middle",
                width: "1%",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "receiver.fullName",
                title: "Destinatario",
                className: "text-nowrap align-middle",
                width: "1%",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "kind",
                title: "Tipo",
                className: "align-middle",
                width: "1%",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "priority",
                title: "Priorità",
                className: "align-middle text-center",
                width: "1%",
                render: function(data, type, row) {
                    if (type == "display") {
                        if (data) {
                            if (data == "minore") {
                                return "<img src='img/minor.svg' class='minor-priority'/>";
                            } else if (data == "importante") {
                                return "<img src='img/major.svg' class='major-priority'/>";
                            } else if (data == "bloccante") {
                                return "<img src='img/blocker.svg' class='blocker-priority'/>";
                            } else {
                                return data;
                            }
                        } else {
                            return "<div class='text-center align-middle'>-</div>";
                        }
                    }

                    // Data è in italiano
                    // Ritorna il tipo se viene cercato dal javascript
                    switch (data) {
                        case "minore":
                            return "minor";
                        case "importante":
                            return "major";
                        case "bloccante":
                            return "blocker";
                        default:
                            return data;
                    }
                }
            },
            {
                data: "attachments",
                title: "<i class='fa fa-paperclip'></i>",
                className: "align-middle text-center",
                width: "1%",
                render: function(data, type, row) {
                    return (
                        data.length ||
                        "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "comments",
                title: "<i class='fa fa-comments'></i>",
                className: "align-middle text-center",
                width: "1%",
                render: function(data, type, row) {
                    return (
                        data.length ||
                        "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                data: "billed",
                title: "<i class='fa fa-usd'></i>",
                className: "align-middle text-center",
                width: "1%",
                orderable: false,
                render: function(data, type, row) {
                    if (data == 1) {
                        return "<i class='fa fa-check'></i>";
                    } else {
                        return "<div class='text-center align-middle'>-</div>"; // Se data è {null, '', '0', 0, false, undefined}
                    }
                }
            },
            {
                data: "private",
                title: "<i class='fa fa-lock'></i>",
                className: "align-middle text-center privateClass",
                width: "1%",
                orderable: false,
                visible: role != 1 ? false : true,
                render: function(data, type, row) {
                    if (data == 1) {
                        return "<i class='fa fa-check'></i>";
                    } else {
                        return "<div class='text-center align-middle'>-</div>"; // Se data è {null, '', '0', 0, false, undefined}
                    }
                }
            },
            {
                data: "status",
                title: "Stato",
                className: "align-middle text-center",
                width: "1%",
                render: function(data, type, row) {
                    return (
                        data || "<div class='text-center align-middle'>-</div>"
                    ); // Se data è {null, '', '0', 0, false, undefined}
                }
            },
            {
                title: "Azioni",
                width: "1%",
                render: function(data, type, row) {
                    return `<i class='fa fa-pencil mr-2 action-button edit-button' style='cursor: pointer'></i>
                    <i class='fa fa-trash action-button delete-button' style='cursor: pointer; ${
                        role == 1 ||
                        (row.author.id == id && row.status == "aperto")
                            ? ""
                            : "color: grey; pointer-events: none"
                    }'></i>`;
                },
                className: "text-center align-middle",
                orderable: false
            }
        ],

        dom:
            "<'row mt-3'<'col-sm-12 col-md-6 mb-2'l><'col-sm-12 col-md-6'<'d-flex align-items-center justify-content-center justify-content-md-end'f<'ml-2'B>>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5 my-3'<'d-flex align-items-center justify-content-center justify-content-md-start h-100'i>><'col-sm-12 col-md-7 my-3'<'justify-content-center justify-content-md-end d-flex' p>>>",

        initComplete: function() {
            $filter = $(
                "<label><i class='fa fa-filter filter-button mr-3'></i></label>"
            );

            $("#issues-table_filter").prepend($filter);

            //* DATE INTERVAL *//

            $("#start").bind("change.datetimepicker", function(e) {
                $("#end").datetimepicker(
                    "minDate",
                    moment($(this).data("date"), "DD/MM/YYYY")
                );

                $("#issues-table")
                    .DataTable()
                    .ajax.reload();
            });
            $("#end").bind("change.datetimepicker", function() {
                $("#start").datetimepicker(
                    "maxDate",
                    moment($(this).data("date"), "DD/MM/YYYY")
                );

                $("#issues-table")
                    .DataTable()
                    .ajax.reload();
            });
            //* FILTERS *//

            var filterTypeOptions = [];
            var filterPriorityOptions = [];
            var filterTopicOptions = [];
            var filterStatusOptions = [];

            if (
                filterPriorityOptions.length > 0 ||
                filterTypeOptions.length > 0 ||
                filterTopicOptions.length > 0 ||
                filterStatusOptions.length > 0
            ) {
                $filter.addClass("filter-applied");
            }

            //* FILTER CLICK*//
            $filter.off("click").on("click", function() {
                $("#add-filter-modal").modal("show");

                //* TYPE *//
                // Disabilito tutte le checkbox type
                ["bug", "improve", "proposal"].forEach(function(option) {
                    $element = $("[name='filter-type-" + option + "']");
                    $element.prop("checked", false);
                });
                // Abilito solo quelle già inserite type
                filterTypeOptions.forEach(option => {
                    $element = $("[name='filter-type-" + option + "']");
                    $element.prop("checked", true);
                });

                //* PRIORITY *//
                // Disabilito tutte le checkbox priority
                ["minor", "major", "blocker"].forEach(function(option) {
                    $element = $("[name='filter-priority-" + option + "']");
                    $element.prop("checked", false);
                });
                // Abilito solo quelle già inserite priority
                filterPriorityOptions.forEach(option => {
                    $element = $("[name='filter-priority-" + option + "']");
                    $element.prop("checked", true);
                });

                //* STATUS *//
                // Disabilito tutte le checkbox status
                ["opened", "incharge", "rejected", "solved"].forEach(function(
                    option
                ) {
                    $element = $("[name='filter-status-" + option + "']");
                    $element.prop("checked", false);
                });
                // Abilito solo quelle già inserite status
                filterStatusOptions.forEach(option => {
                    $element = $("[name='filter-status-" + option + "']");
                    $element.prop("checked", true);
                });

                //* TOPIC *//
                if (filterTopicOptions.length === 0) {
                    filterTopicOptions = [""];
                }
                // Add row function
                var addRow = function(el) {
                    $single = $(
                        `<div class="d-flex mt-3">
                            <div class=" w-100">
                                <input type="text" class="form-control" data-target="topic"
                                    placeholder="Inserisci argomento" />
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary ml-2" data-target="topic-button">
                                    <i class="fa fa-plus" style="font-size: 0.8em"></i>
                                </button>
                            </div>
                        </div>
                    `
                    );

                    if (el) {
                        $("input", $single).val(el);
                    }

                    $("[data-target='topic-button']", $single)
                        .off("click")
                        .on("click", function() {
                            addRow();
                        });

                    $("#filter-topic-all").append($single);
                };

                // Svuoto le row topic
                $("#filter-topic-all").empty();

                // Riempo il topic con il valore precedente
                filterTopicOptions.forEach(el => {
                    addRow(el);
                });

                //* SUBMIT *//
                $("#form-add-filter").on("submit", function() {
                    var table = $("#issues-table").DataTable();

                    //* TYPE *//
                    var searchType = [];
                    ["bug", "improve", "proposal"].forEach(function(type) {
                        var $checkbox = $("#filter-type-" + type);
                        var checked = $checkbox.prop("checked");
                        var value = type;

                        switch (type) {
                            case "bug":
                                value = "bug";
                                break;
                            case "improve":
                                value = "miglioramento";
                                break;
                            case "proposal":
                                value = "proposta";
                                break;
                        }
                        if (checked) {
                            searchType.push(value);
                            if (filterTypeOptions.indexOf(type) == -1)
                                filterTypeOptions.push(type);
                        } else {
                            if (filterTypeOptions.indexOf(type) != -1)
                                filterTypeOptions.splice(
                                    filterTypeOptions.indexOf(type),
                                    1
                                );
                        }
                    });
                    table.column(7).search(searchType.join("|"), true);

                    //* PRIORITY *//
                    var searchPriority = [];
                    ["minor", "major", "blocker"].forEach(function(priority) {
                        var $checkbox = $("#filter-priority-" + priority);
                        var checked = $checkbox.prop("checked");

                        if (checked) {
                            searchPriority.push(priority);
                            if (filterPriorityOptions.indexOf(priority) < 0)
                                filterPriorityOptions.push(priority);
                        } else {
                            if (filterPriorityOptions.indexOf(priority) >= 0)
                                filterPriorityOptions.splice(
                                    filterPriorityOptions.indexOf(priority),
                                    1
                                );
                        }
                    });
                    table.column(8).search(searchPriority.join("|"), true);

                    //* STATUS *//
                    var searchStatus = [];
                    ["opened", "incharge", "rejected", "solved"].forEach(
                        function(status) {
                            var $checkbox = $("#filter-status-" + status);
                            var checked = $checkbox.prop("checked");

                            if (checked) {
                                switch (status) {
                                    case "opened":
                                        searchStatus.push("Aperto");
                                        break;
                                    case "incharge":
                                        searchStatus.push("In carico");
                                        break;
                                    case "rejected":
                                        searchStatus.push("Rigettato");
                                        break;
                                    case "solved":
                                        searchStatus.push("Risolto");
                                        break;
                                    default:
                                        searchStatus.push(status);
                                        break;
                                }
                                if (filterStatusOptions.indexOf(status) < 0)
                                    filterStatusOptions.push(status);
                            } else {
                                if (filterStatusOptions.indexOf(status) >= 0)
                                    filterStatusOptions.splice(
                                        filterStatusOptions.indexOf(status),
                                        1
                                    );
                            }
                        }
                    );
                    table
                        .column(13)
                        .search(searchStatus.join("|"), true, false);

                    //* TOPIC *//
                    var searchTopic = [];
                    filterTopicOptions = [];
                    $("[data-target='topic']").each(function() {
                        topic = $(this).val();

                        // Se è stato inserito qualcosa lo aggiungo all'array
                        if (topic) {
                            searchTopic.push(topic);
                            if (filterTopicOptions.indexOf(topic) < 0)
                                filterTopicOptions.push(topic);
                        }

                        // Rimuovo tutti i campi vuoti nell'array
                        filterTopicOptions.forEach(element => {
                            if (element == "") {
                                filterTopicOptions.splice(
                                    filterTopicOptions.indexOf(element),
                                    1
                                );
                            }
                        });
                    });

                    table.column(4).search(searchTopic.join("|"), true);

                    //* FILTER COLOR CHANGE *//
                    if (
                        filterPriorityOptions.length > 0 ||
                        filterTypeOptions.length > 0 ||
                        filterTopicOptions.length > 0 ||
                        filterStatusOptions.length > 0
                    ) {
                        $filter.addClass("filter-applied");
                    } else {
                        $filter.removeClass("filter-applied");
                    }

                    //* DRAW FILTER *//
                    table.draw();
                    $("#add-filter-modal").modal("hide");
                });
            });

            $("#add-button").on("click", function() {
                window.location.href = "./add-issue-view.php";
            });
        },

        rowCallback: function(row, data) {
            // console.log(this.find(".privateClass").find("i"));
            // if (data.private == 1 && role != 1 && data.author.id != id) {
            //     console.log(row);
            //     row.remove();
            // }
            $(".delete-button", row)
                .off("click")
                .on("click", function() {
                    $("#delete-modal").modal("show");

                    $("#save-delete")
                        .off("click")
                        .on("click", function() {
                            $.ajax({
                                url: "api/issues/delete-issue.php",
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
                    window.location.href =
                        "./edit-issue-view.php?issueId=" +
                        data.id +
                        "&creatorId=" +
                        data.creator.id;
                });
        }
    });

    return false;
});
