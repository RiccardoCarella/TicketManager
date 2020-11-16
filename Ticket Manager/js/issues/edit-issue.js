$(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const issueId = urlParams.get("issueId");
    const creatorId = urlParams.get("creatorId");
    const attachmentsToDelete = [];
    $.ajax({
        url: "api/issues/get-issue.php",
        method: "get",
        data: { id: issueId },
        success: function(result) {
            if (result.success) {
                //* CONTROLLO BLOCCO FORM *//
                var lock = false;
                // Se non sono l'admin
                if (role != 1) {
                    // Se non è la mia issue
                    if (result.data.author.id != id) {
                        // Blocco il form
                        lock = true;
                    } else {
                        // Se la issue non è aperta
                        if (result.data.status != "aperto") {
                            // Blocco il form
                            lock = true;
                        }
                    }
                }

                // Get issue data
                var data = {};
                data["title"] = result.data.title;
                data["content"] = result.data.content;
                data["topic"] = result.data.topic;
                data["warner"] = result.data.warner;
                if (result.data.creator)
                    data["creator"] = result.data.creator.id;
                if (result.data.author) data["author"] = result.data.author.id;
                if (result.data.receiver)
                    data["receiver"] = result.data.receiver.id;
                data["kind"] = result.data.kind;
                data["priority"] = result.data.priority;
                data["status"] = result.data.status;
                data["billed"] = result.data.billed;
                data["private"] = result.data.private;
                data["creationDate"] = result.data.creationDate;
                data["lastUpdate"] = result.data.lastUpdate;
                data["attachments[]"] = result.data.attachments;
                var $form = $("#form-edit-issue");

                //* CONTROLLO FORM *//
                CKEDITOR.replace("content", {
                    readOnly: lock
                });

                // Cambiamenti nell'upload
                $("#attachments").change(function() {
                    filesCounter = $("#attachments").prop("files").length;
                    if (filesCounter !== 0) {
                        if (filesCounter == 1) {
                            $("#file-upload-edit").html(
                                "<i class='fa fa-cloud-upload'></i> " +
                                    filesCounter +
                                    " File selezionato"
                            );
                        } else {
                            $("#file-upload-edit").html(
                                "<i class='fa fa-cloud-upload'></i> " +
                                    filesCounter +
                                    " File selezionati"
                            );
                        }
                    } else {
                        $("#file-upload-edit").html(
                            "<i class='fa fa-cloud-upload'></i> " +
                                "Carica file"
                        );
                    }
                });

                // Fill the fields of the form
                $.each(data, (key, value) => {
                    var $input = $('[name="' + key + '"]', $form);
                    switch (key) {
                        case "billed":
                        case "private":
                            if (value == 1) {
                                $input.prop("checked", true);
                            }
                            break;
                        case "creationDate":
                        case "lastUpdate":
                            $input.closest(".input-group").datetimepicker({
                                format: "DD/MM/YYYY HH:mm",
                                date: value
                            });
                            $input.attr("data-value", value);
                            $input
                                .closest(".input-group")
                                .bind("change.datetimepicker", function() {
                                    $input.trigger("form.change");
                                });
                            break;
                        case "warner":
                            if (value) {
                                var array = value.split(",");
                                $input.val(array);
                            }

                            $input.attr("data-value", value);
                            $input.trigger("change");
                            break;
                        case "content":
                            $input.val(value);
                            $input.attr("data-value", value);
                            CKEDITOR.instances.content.on("change", function() {
                                $input.trigger("change");
                            });
                            break;

                        case "attachments[]":
                            if (result.data.attachments.length > 0) {
                                var $ul = $(
                                    "<ul id='ul-attachments' class='m-0'/>"
                                );

                                $.each(value, function(i, attachment) {
                                    $ul.append(
                                        "<li id='li-" +
                                            attachment.id +
                                            "'><a href='" +
                                            attachment.url +
                                            "' target='_blank'>" +
                                            attachment.name +
                                            "</a><i id='" +
                                            attachment.id +
                                            "' class='fa fa-times pl-2 delete-file'></i></li>"
                                    );
                                });
                                $("#numFiles").html($ul);
                                $(".delete-file")
                                    .off()
                                    .on("click", function(e) {
                                        var attachmentId = e.target.id; // Get delete-file-x
                                        $("#li-" + attachmentId).remove();
                                        attachmentsToDelete.push(attachmentId);

                                        if (
                                            $("#ul-attachments li").length === 0
                                        ) {
                                            $("#numFiles").html(
                                                "Nessun file pre-selezionato"
                                            );
                                        }

                                        $(
                                            '[name="attachments[]"]',
                                            $form
                                        ).trigger("change");
                                    });
                            } else {
                                $("#filesCounter").html(
                                    "Nessun file pre-selezionato"
                                );
                            }

                            break;

                        default:
                            $input.val(value);
                            $input.attr("data-value", value);
                            break;
                    }

                    // Control changes to the form
                    $input.bind(
                        "change keyup change.datetimepicker form.change",
                        function() {
                            var isChanged = false;
                            $("[name]", $form).each(function() {
                                var $el = $(this);
                                var name = $el.attr("name");
                                var value = $el.val();
                                var original = $el.attr("data-value");
                                switch (name) {
                                    case "creationDate":
                                    case "lastUpdate":
                                        value = moment(
                                            value,
                                            "DD/MM/YYYY HH:mm"
                                        ).format("YYYY-MM-DD HH:mm");
                                        original = moment(
                                            original,
                                            "YYYY-MM-DD HH:mm:ss"
                                        ).format("YYYY-MM-DD HH:mm");
                                        break;
                                    case "warner":
                                        if (value == "") {
                                            value = undefined;
                                        }
                                        break;
                                    case "content":
                                        value = CKEDITOR.instances.content.getData();
                                        if (value == "") {
                                            original = "";
                                        }
                                        break;
                                    case "attachments[]":
                                        value = $el.prop("files").length;
                                        original = 0;
                                        break;
                                    case "billed":
                                        value = $el.prop("checked");
                                        original =
                                            result.data.billed == 1 ? 1 : 0;
                                        break;
                                    case "private":
                                        value = $el.prop("checked");
                                        original =
                                            result.data.private == 1 ? 1 : 0;
                                        break;
                                }

                                if (
                                    value != original ||
                                    attachmentsToDelete != 0
                                ) {
                                    isChanged = true;
                                }
                            });

                            $('[type="submit"]', $form).attr(
                                "disabled",
                                !isChanged
                            );
                        }
                    );
                });

                //* COMMENT SECTION *\\

                // Load the comments
                comments = result.data.comments;
                $.each(comments, function(i, comment) {
                    var $tr = $("<tr class='border rounded bg-white mb-4'/>");
                    var $spacer = $("<tr class='spacer'/>");

                    $tr.attr("comment-id", comment.id);
                    $tr.attr("comment-author", comment.author.id);

                    $tr.html(
                        "<td class='text-nowrap p-2 text-center border-right' name='comment-name'/>" +
                            "<td class='p-2' name='comment-content'/>" +
                            "<td class='text-nowrap p-2 text-center border-left border-right' name='comment-date'/>" +
                            "<td class='text-nowrap text-center' name='comment-actions'/>"
                    );

                    $("[name='comment-name']", $tr).text(
                        comment.author.fullName
                    );
                    $("[name='comment-content']", $tr).text(comment.content);

                    date = moment(comment.date, "YYYY-MM-DD HH:mm:ss");

                    date = date.format("DD/MM/YYYY HH:mm");
                    $("[name='comment-date']", $tr).text(date);

                    $("[name='comment-actions'", $tr).html(
                        "<i class='fa fa-pencil pl-3 pr-2 action-button edit-button edit-comment-button' style='cursor: pointer'></i>" +
                            "<i class='fa fa-trash pr-3 pl-2 action-button delete-button delete-comment-button' style='cursor: pointer'></i>"
                    );

                    $("#comment-section tbody").append($tr);
                    $("#comment-section tbody").append($spacer);
                });

                // Add a comment
                $("#add-comment-content").bind("change keyup", function() {
                    var content = $("#add-comment-content").val();

                    if (content.length > 0) {
                        $("#add-comment-button").attr("disabled", false);
                    } else {
                        $("#add-comment-button").attr("disabled", true);
                    }

                    $("#add-comment-button")
                        .off()
                        .on("click", function() {
                            var issueId = result.data.id;
                            var date = moment().format("YYYY-MM-DD HH:mm:ss");
                            $.ajax({
                                method: "post",
                                url: "api/comments/add-comment.php",
                                data: {
                                    content: content,
                                    date: date,
                                    issueId: issueId,
                                    fromAjax: 1
                                },
                                success: function(result) {
                                    if (result.success) {
                                        window.location.reload();
                                    } else {
                                        alert(result.msg);
                                    }
                                }
                            });
                        });
                });

                // Edit a comment
                $(".edit-button")
                    .off("click")
                    .on("click", function() {
                        var row = $(this).closest("tr");
                        commentId = row.attr("comment-id");
                        $.ajax({
                            url: "api/comments/get-comment.php",
                            method: "get",
                            data: {
                                id: commentId,
                                fromAjax: 1
                            },
                            success: function(result) {
                                if (result.success) {
                                    var data = {};
                                    if (result.data.author)
                                        data["author"] = result.data.author.id;
                                    data["date"] = result.data.date;
                                    data["content"] = result.data.content;
                                    data["issue"] = result.data.issue;
                                    var $form = $("#form-edit-comment");
                                    $("#edit-comment-modal").modal("show");
                                    // Fill the fields of the form
                                    $.each(data, (key, value) => {
                                        var $input = $(
                                            '[name="edit-comment-' + key + '"]',
                                            $form
                                        );

                                        switch (key) {
                                            case "date":
                                                $input
                                                    .closest(".input-group")
                                                    .datetimepicker({
                                                        format:
                                                            "DD/MM/YYYY HH:mm",
                                                        date: value
                                                    });
                                                $input.attr(
                                                    "data-value",
                                                    value
                                                );
                                                $input
                                                    .closest(".input-group")
                                                    .bind(
                                                        "change.datetimepicker",
                                                        function() {
                                                            $input.trigger(
                                                                "form.change"
                                                            );
                                                        }
                                                    );
                                                break;
                                            default:
                                                $input.val(value);
                                                $input.attr(
                                                    "data-value",
                                                    value
                                                );
                                                break;
                                        }

                                        // Control changes to the form
                                        $input.bind(
                                            "change keyup change.datetimepicker form.change",
                                            function() {
                                                var isChanged = false;

                                                $("[name]", $form).each(
                                                    function() {
                                                        var $el = $(this);
                                                        var name = $el.attr(
                                                            "name"
                                                        );
                                                        var value = $el.val();
                                                        var original = $el.attr(
                                                            "data-value"
                                                        );

                                                        switch (name) {
                                                            case "edit-comment-date":
                                                                value = moment(
                                                                    value,
                                                                    "DD/MM/YYYY HH:mm"
                                                                ).format(
                                                                    "YYYY-MM-DD HH:mm"
                                                                );
                                                                original = moment(
                                                                    original,
                                                                    "YYYY-MM-DD HH:mm:ss"
                                                                ).format(
                                                                    "YYYY-MM-DD HH:mm"
                                                                );
                                                                break;
                                                        }
                                                        if (value != original) {
                                                            isChanged = true;
                                                        }
                                                    }
                                                );

                                                $(
                                                    '[type="submit"]',
                                                    $form
                                                ).attr("disabled", !isChanged);
                                            }
                                        );
                                    });

                                    // Save edit comment
                                    $("#form-edit-comment").on(
                                        "submit",
                                        function() {
                                            // Prendo i nuovi valori
                                            var date = moment(
                                                $("#edit-comment-date").data(
                                                    "date"
                                                ),
                                                "DD/MM/YYYY HH:mm"
                                            );
                                            date = date.format(
                                                "YYYY-MM-DD HH:mm:ss"
                                            );
                                            var issue = $(
                                                "#edit-comment-issue"
                                            ).val();
                                            var author = $(
                                                "#edit-comment-author"
                                            ).val();
                                            var content = $(
                                                "#edit-comment-content"
                                            ).val();

                                            var id = result.data.id;
                                            $.ajax({
                                                method: "post",
                                                url:
                                                    "api/comments/edit-comment.php",
                                                data: {
                                                    id: id,
                                                    date: date,
                                                    issue: issue,
                                                    author: author,
                                                    content: content,
                                                    fromAjax: 1
                                                },
                                                success: function(result) {
                                                    if (result.success) {
                                                        window.location.reload();
                                                    } else {
                                                        alert(result.msg);
                                                    }
                                                }
                                            });
                                        }
                                    );
                                } else {
                                    alert(result.msg);
                                }
                            }
                        });
                    });

                // Delete a comment
                $(".delete-comment-button")
                    .off()
                    .on("click", function() {
                        var row = $(this).closest("tr");
                        commentId = row.attr("comment-id");

                        $("#delete-comment-modal").modal("show");

                        $("#save-delete")
                            .off()
                            .on("click", function() {
                                $.ajax({
                                    url: "api/comments/delete-comment.php",
                                    method: "post",
                                    data: {
                                        id: commentId,
                                        fromAjax: 1
                                    },
                                    success: function(result) {
                                        if (result.success) {
                                            $("#delete-modal").modal("hide");
                                            window.location.reload();
                                        } else {
                                            alert(result.msg);
                                        }
                                    }
                                });
                            });
                    });
                //* CONTROLLO IL RUOLO DELL'UTENTE *//
                // Se non sono admin
                if (role != 1) {
                    if (lock == true) {
                        // Rendo disattivati tutti i campi del form
                        $("#form-edit-issue :input").each(function() {
                            var $input = $(this); // This is the jquery object of the input, do what you will

                            switch ($input.attr("id")) {
                                case "cancel-button":
                                    // Lo lascio attivo
                                    break;
                                case "attachments":
                                    $input.attr("display", "none");
                                    break;
                                default:
                                    $input.attr("readonly", true);
                                    $input.attr("disabled", true);
                                    break;
                            }
                        });

                        // Nascondo i bottoni delle azioni di ogni commento
                        $("[name='comment-actions']").each(function() {
                            $(this).css("display", "none");
                        });
                        // Nascondo il tasto per aggiungere allegati
                        $("#add-file-container").css("display", "none");
                        // Tolgo la possibilità di eliminare i file pre esistenti
                        $(".delete-file").each(function() {
                            var $single = $(this);
                            $single.css("display", "none");
                        });
                        // Trasformo l'"annulla" in "indietro"
                        $("#cancel-button").html("Indietro");
                        // Non mostro il bottone per salvare
                        $("#edit-issue-submit").css("display", "none");
                    }
                }

                //* ISSUE FORM SUBMIT *//
                $("#form-edit-issue").on("submit", function() {
                    // Prendo i nuovi valori
                    var title = $("#title").val();
                    var content = CKEDITOR.instances.content.getData();
                    var topic = $("#topic").val();
                    var warner = $("#warner").val();
                    var creator = creatorId;
                    var author = $("#author").val();
                    var receiver = $("#receiver").val();
                    var kind = $("#kind").val();
                    var priority = $("#priority").val();
                    var status = $("#status").val();
                    var billed = $("#billed").prop("checked");
                    var private = $("#private").prop("checked");
                    var attachments = $("#attachments").prop("files");
                    var creationDate = moment(
                        $("#creationDate").data("date"),
                        "DD/MM/YYYY HH:mm"
                    );
                    var lastUpdate = moment(
                        $("#lastUpdate").data("date"),
                        "DD/MM/YYYY HH:mm"
                    ); // = $("#lastUpdate").data("date");

                    var form_data = new FormData();
                    form_data.append("issueId", issueId);
                    form_data.append("title", title);
                    form_data.append("content", content);
                    form_data.append("topic", topic);
                    form_data.append("warner", warner);
                    form_data.append("creator", creator);
                    form_data.append("author", author);
                    form_data.append("receiver", receiver);
                    form_data.append("kind", kind);
                    form_data.append("priority", priority);
                    form_data.append("status", status);
                    form_data.append("billed", billed ? 1 : 0);
                    form_data.append("private", private ? 1 : 0);
                    form_data.append(
                        "creationDate",
                        creationDate.format("YYYY-MM-DD HH:mm:ss")
                    );
                    form_data.append(
                        "lastUpdate",
                        lastUpdate.format("YYYY-MM-DD HH:mm:ss")
                    );
                    for (
                        i = 0;
                        i < $("#attachments").prop("files").length;
                        i++
                    ) {
                        form_data.append("attachments[]", attachments[i]);
                    }
                    for (i = 0; i < attachmentsToDelete.length; i++) {
                        form_data.append(
                            "attachmentsToDelete[]",
                            attachmentsToDelete[i]
                        );
                    }
                    form_data.append("fromAjax", 1);

                    $.ajax({
                        method: "post",
                        url: "api/issues/edit-issue.php",
                        data: form_data,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            if (result.success) {
                                window.location.href = "./issues.php";
                            } else {
                                alert(result.msg);
                            }
                        }
                    });
                });

                // Issue form cancel
                $("#cancel-button")
                    .off()
                    .on("click", function() {
                        window.location.href = "./issues.php";
                    });
            }
        }
    });
});
