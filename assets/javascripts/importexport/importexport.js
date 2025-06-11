Dropzone.options.dropzoneImportCsv = {
    acceptedFiles: '.csv',
    success: function(data , callback) {
        $("div#csv-showup").load("source/import_csv.php?action=" + callback);
    },
    dictDefaultMessage: "Drop files here to import .csv"
};