$(function() {
    // Sul refresh elimino comunque gli allegati
    $(window).bind("beforeunload", function() {
        // Se non è stato premuto salva
        if (!window.__closedFromSave) {
            // Mi salvo gli id degli allegati
            var attachmentsId = [];
            $(".attachment-element").each(function() {
                $this = $(this);
                var id = $this.attr("data-id");
                attachmentsId.push(id);
            });
            $.ajax({
                method: "post",
                url: "api/files/delete-multiple-files.php",
                data: { ids: attachmentsId, fromAjax: 1 }
            });
        }
    });
    //* DROPZONE *//
    var $myDropzone = $("#form-upload-file").dropzone({
        dictDefaultMessage: "Clicca o trascina per caricare gli allegati",
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
                // Creo il div per i file caricati
                response.data = response.data[0];

                var $element = $(`
                <div data-id="${response.data.id}" data-url="${response.data.url}" data-name="${file.name}" class="attachment-element mr-2 mb-2">
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

                // Lo aggiungo alla lista dei file
                $("#files-list").append($element);

                // Sul click del download
                $(".download-attachment-element", $element).on(
                    "click",
                    function() {
                        var $this = $(this);
                        var $parent = $this.closest(".attachment-element");
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
                        var $parent = $this.closest(".attachment-element");
                        var fileId = $parent.attr("data-id");
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

    $("#creationDate").datetimepicker({
        format: "DD/MM/YYYY HH:mm",
        date: moment()
    });

    $("#lastUpdate").datetimepicker({
        format: "DD/MM/YYYY HH:mm",
        date: moment()
    });

    //* ANNULLA *//

    // Sull'annullamento, cancello tutti i file caricati precedentemenete sul database
    $("#cancel-button").on("click", function() {
        // Mi salvo gli id degli allegati
        var attachmentsId = [];
        $(".attachment-element").each(function() {
            $this = $(this);
            var id = $this.attr("data-id");
            attachmentsId.push(id);
        });
        $.ajax({
            method: "post",
            url: "api/files/delete-multiple-files.php",
            data: { ids: attachmentsId, fromAjax: 1 },
            success: function(result) {
                if (result.success) {
                    window.location.href = "./issues.php";
                } else {
                    alert(result.msg);
                }
            }
        });
    });

    //* SUBMIT *//
    $("#form-add-issue").on("submit", function() {
        var $this = $(this);

        const submit = function() {
            // Mi salvo gli id degli allegati
            var attachmentsId = [];
            $(".attachment-element").each(function() {
                $this = $(this);
                var id = $this.attr("data-id");
                attachmentsId.push(id);
            });

            var title = $("#title").val();
            var content = CKEDITOR.instances.content.getData();
            var topic = $("#topic").val();
            var warner = $("#warner").val();
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
            form_data.append("title", title);
            form_data.append("content", content);
            form_data.append("topic", topic);
            form_data.append("warner", warner);
            form_data.append("author", author);
            form_data.append("receiver", receiver);
            form_data.append("kind", kind);
            form_data.append("priority", priority);
            form_data.append("status", status);
            form_data.append("attachmentsId", attachmentsId);
            form_data.append("billed", billed ? 1 : 0);
            form_data.append("private", private ? 1 : 0);
            form_data.append(
                "creationDate",
                creationDate.format("YYYY-MM-DD HH:mm")
            );
            form_data.append(
                "lastUpdate",
                lastUpdate.format("YYYY-MM-DD HH:mm")
            );

            form_data.append("fromAjax", 1);
            $.ajax({
                method: "post",
                url: "api/issues/add-issue.php",
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
        if ($myDropzone[0].dropzone.getUploadingFiles().length > 0) {
            // Mostro la modal
            $("#alert-modal").modal("show");

            $("#filesToUpload").html(
                ` ${$myDropzone[0].dropzone.getUploadingFiles().length}`
            );

            // Se clicco su continua invio comunque la richiesta ajax
            $("#save-alert").on("click", function() {
                submit();
            });
        } else {
            submit();
        }
    });
});
