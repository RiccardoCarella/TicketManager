$(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const issueId = urlParams.get("issueId");
    const creatorId = urlParams.get("creatorId");
    const attachmentsToDelete = [];
    const newAttachments = [];
    $.ajax({
        url: "api/issues/get-issue.php",
        method: "get",
        data: { id: issueId },
        success: function(result) {
            if (result.success) {
                //* CONTROLLO REFRESH PAGE *//
                $(window).bind("beforeunload", function() {
                    // Se non è stato premuto salva
                    if (!window.__closedFromSave) {
                        // Mi salvo gli id degli allegati
                        var attachmentsId = [];
                        $(".attachment-element").each(function() {
                            $this = $(this);
                            if ($this.attr("data-age") != "old") {
                                var id = $this.attr("data-id");
                                attachmentsId.push(id);
                            }
                        });
                        $.ajax({
                            method: "post",
                            url: "api/files/delete-multiple-files.php",
                            data: { ids: attachmentsId, fromAjax: 1 }
                        });
                    }
                });

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
                var $form = $("#form-edit-issue");

                //* DROPZONE *//
                var $myDropzone = $("#form-upload-file").dropzone({
                    dictDefaultMessage:
                        "Clicca o trascina per caricare gli allegati",
                    dictRemoveFile: "Rimuovi",
                    dictCancelUpload: "Cancella",
                    uploadMultiple: true,
                    parallelUploads: 1,
                    parallelChunkUploads: true,
                    retryChunks: true,
                    createImageThumbnails: false,
                    addRemoveLinks: true,
                    timeout: 0,
                    init: function() {
                        // Se tutti i file finiscono di caricare chiudo la modal
                        this.on("complete", function(file) {
                            if (
                                this.getUploadingFiles().length === 0 &&
                                this.getQueuedFiles().length === 0
                            ) {
                                $("#upload-file-modal").modal("hide");
                            }
                        });
                        // Quando il file finisce di caricare
                        this.on("success", function(file, response) {
                            response.data = response.data[0];
                            // Creo il div per i file caricati
                            var $element = $(`
                                <div data-age="new" data-id="${response.data.id}" data-url="${response.data.url}" data-name="${file.name}" class="attachment-element mr-2 mb-2">
                                    <div class="d-flex attachment-items">
                                        <div class="download-attachment-element">
                                            <i class="fa fa-download"></i>
                                        </div>
                                        <div class="delete-attachment-element">
                                            <i class="fa fa-trash"></i>
                                        </div>
                                    </div>
                                    <btn class="btn bg-white border ">${file.name}</btn>
                                </div>`);
                            newAttachments.push(response.data.id);
                            // Lo aggiungo alla lista dei file
                            $("#files-list").append($element);

                            // Triggero il controllo dei cambiamenti
                            $("[name]", $form).trigger("change");

                            // Sul click del download
                            $(".download-attachment-element", $element).on(
                                "click",
                                function() {
                                    var $this = $(this);
                                    var $parent = $this.closest(
                                        ".attachment-element"
                                    );
                                    var url = $parent.attr("data-url");
                                    var name = $parent.attr("data-name");

                                    // Creo un link
                                    var a = document.createElement("a");
                                    document.body.appendChild(a);

                                    // Setto il nome del download
                                    a.download = name;

                                    // Setto l'url
                                    a.href = url;

                                    // Triggero il click
                                    a.click();

                                    // Lo rimuovo dal DOM
                                    a.remove();
                                }
                            );

                            // Sul click del cestino lo rimuovo
                            $(".delete-attachment-element", $element).on(
                                "click",
                                function() {
                                    var $this = $(this);
                                    var $parent = $this.closest(
                                        ".attachment-element"
                                    );
                                    var url = $parent.attr("data-url");
                                    var fileId = $this.parent().attr("data-id");

                                    // Rimuovo il file dall'array dei nuovi allegati
                                    newAttachments.splice(
                                        newAttachments.indexOf(fileId),
                                        1
                                    );

                                    // Triggero il controllo dei cambiamenti
                                    $("[name]", $form).trigger("change");

                                    $.ajax({
                                        method: "post",
                                        url: "api/files/delete-file.php",
                                        data: { id: fileId, fromAjax: 1 },
                                        success: function(result) {
                                            if (result.success) {
                                                $parent.remove();
                                            }
                                        }
                                    });
                                }
                            );
                        });
                    }
                });

                // Aggiungo alla lista degli allegati quelli che già avevo
                if (result.data.attachments.length > 0) {
                    $.each(result.data.attachments, (key, attachment) => {
                        // Creo il div per i file caricati
                        var $element = $(`
                        <div data-age="old" data-id="${attachment.id}" data-url="${attachment.url}" data-name="${attachment.name}" class="attachment-element mr-2 mb-2">
                            <div class="d-flex attachment-items">
                                <div class="download-attachment-element">
                                    <i class="fa fa-download"></i>
                                </div>
                                <div class="delete-attachment-element">
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>  
                            <btn class="btn bg-white border ">${attachment.name}</btn>
                        </div>`);

                        // Lo aggiungo alla lista dei file
                        $("#files-list").append($element);

                        // Sul click del download
                        $(".download-attachment-element", $element).on(
                            "click",
                            function() {
                                var $this = $(this);
                                var $parent = $this.closest(
                                    ".attachment-element"
                                );
                                var url = $parent.attr("data-url");
                                var name = $parent.attr("data-name");

                                // Creo un link
                                var a = document.createElement("a");
                                document.body.appendChild(a);

                                // Setto il nome del download
                                a.download = name;

                                // Setto l'url
                                a.href = url;

                                // Triggero il click
                                a.click();

                                // Lo rimuovo dal DOM
                                a.remove();
                            }
                        );

                        // Sul click del cestino lo salvo nella lista degli allegati da eliminare
                        $(".delete-attachment-element", $element).on(
                            "click",
                            function() {
                                var $this = $(this);
                                var $parent = $this.closest(
                                    ".attachment-element"
                                );
                                var fileId = $this
                                    .closest(".attachment-element")
                                    .attr("data-id");

                                // Nascondo il bottone
                                $parent.remove();
                                attachmentsToDelete.push(fileId);

                                // Triggero il controllo dei cambiamenti
                                $("[name]", $form).trigger("change");
                            }
                        );
                    });
                }
                // Quando premo il bottone per aggiungere un allegato
                $("#file-upload-add").on("click", function() {
                    // Svuoto la dropzone
                    $myDropzone[0].dropzone.removeAllFiles();

                    // Svuoto la lista nella modal dei file caricati
                    $("#preview-list").empty();

                    // Mostro la modal
                    $("#upload-file-modal").modal("show");
                });
                // Quando premo il bottone per chiudere la modal
                $("#close-upload-file").on("click", function() {
                    // Chiudo la modal
                    $("#upload-file-modal").modal("hide");
                });

                //* ISSUE *//
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

                //* CONTROLLO FORM *//
                CKEDITOR.replace("content", {
                    readOnly: lock
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
                                    case "content":
                                        value = CKEDITOR.instances.content.getData();
                                        if (value == "") {
                                            original = "";
                                        }
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
                                    attachmentsToDelete.length != 0 ||
                                    newAttachments.length > 0
                                ) {
                                    isChanged = true;
                                }
                                // console.log(
                                //     `name: ${name}, value: ${value}, original: ${original}, isChanged: ${isChanged}`
                                // );
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

                            // Rimuovo il bottone per aggiungere gli allegati
                            $("#file-upload-add").remove();

                            // Rimuovo gli eventi del cursore sui bottoni dei file pre-esistenti
                            $(".attachment-element").each(function() {
                                $(this).css("pointer-events", "none");
                            });

                            switch ($input.attr("id")) {
                                case "cancel-button":
                                    // Lo lascio attivo
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
                    const submit = function() {
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
                        form_data.append("attachmentsId", newAttachments);
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
                                    // Variabile per controllare se è stato premuto il bottone save
                                    window.__closedFromSave = true;

                                    window.location.href = "./issues.php";
                                } else {
                                    alert(result.msg);
                                }
                            }
                        });
                    };

                    // Se ci sono ancora file in caricamento
                    if (
                        $myDropzone[0].dropzone.getUploadingFiles().length > 0
                    ) {
                        // Mostro la modal
                        $("#alert-modal").modal("show");

                        $("#filesToUpload").html(
                            ` ${
                                $myDropzone[0].dropzone.getUploadingFiles()
                                    .length
                            }`
                        );

                        // Se clicco su continua invio comunque la richiesta ajax
                        $("#save-alert").on("click", function() {
                            submit();
                        });
                    } else {
                        submit();
                    }
                });

                //* ANNULLA *//

                // Sull'annullamento, cancello tutti i file caricati precedentemenete sul database
                $("#cancel-button")
                    .off()
                    .on("click", function() {
                        if (newAttachments.length > 0) {
                            $.ajax({
                                method: "post",
                                url: "api/files/delete-multiple-files.php",
                                data: { ids: newAttachments, fromAjax: 1 },
                                success: function(result) {
                                    if (result.success) {
                                        window.location.href = "./issues.php";
                                    } else {
                                        alert(result.msg);
                                    }
                                }
                            });
                        } else {
                            window.location.href = "./issues.php";
                        }
                    });
            }
        }
    });
});
