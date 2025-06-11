(function( $ ) {

    'use strict';

    /*
     Modal Dismiss
     */
    $(document).on('click', '.modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });

    /*
     Modal Confirm
     */
    $(document).on('click', 'a#import', function (e) {
        e.preventDefault();
        var data = $('form#select-csv-col').serialize();
        var filename = $('a#import').attr('data-file');
        console.log(data);
        var l = Ladda.create(document.querySelector('a#import.ladda-button'));
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/import_csv.php",
                    type: "POST",
                    data: data + "&action=import_csv_data&filename="+ filename,
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        l.stop();

                        console.log(data);
                        if (data[0] == 'success') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'อิมพอร์ตข้อมูลแล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no id user') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'กรุณาเลือกช่องรหัสนักศึกษา.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'blank'){
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'กรุณาอย่าเว้นช่องว่าง.',
                                type: 'error'
                            });
                        }
                    },
                    beforeSend:function(){
                        l.start();
                        l.isLoading();
                    },
                    xhr: function(){
                        var xhr = new window.XMLHttpRequest();
                        if ('withCredentials' in new XMLHttpRequest()) {
                            xhr.upload.addEventListener("progress", function(evt){
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    l.setProgress( percentComplete );
                                }
                            }, false);
                        }
                        return xhr;
                    }
                });
            },
            confirmButton: 'ใช่',
            confirmButtonClass: 'btn-primary',
            cancelButton: 'ไม่ใช่',
            cancelButtonClass: 'btn-danger'
        });
    });

    $(document).on('change', 'select#export', function (e) {
        e.preventDefault();
        var data = $('form#export-file').serialize();
        console.log(data);
        $.ajax({
            url: "source/export_csv.php",
            type: "POST",
            data: data + "&action=example",
            success: function (data) {
                console.log(data);
                $("tbody#example").html(data);
            }
        });
    });

    $(document).on('click', 'a#export-button', function (e) {
        e.preventDefault();
        var data = $('form#export-file').serialize();
        console.log(data);
        var l = Ladda.create(document.querySelector('a#export-button.ladda-button'));
        $.ajax({
            url: "source/export_csv.php",
            type: "POST",
            data: data + "&action=export_csv_data",
            dataType: 'json',
            success: function (data) {
                l.stop();
                console.log(data);
                if(data[0] == 'success'){
                    window.location.href = data[1];
                }
                else if(data[0] =='no select'){
                    new PNotify({
                        title: 'Error!',
                        text: 'กรุณาเลือกสิ่งที่ต้องการส่งออก.',
                        type: 'error'
                    });
                }
            },
            beforeSend:function(){
                l.start();
                l.isLoading();
            },
            xhr: function(){
                var xhr = new window.XMLHttpRequest();
                if ('withCredentials' in new XMLHttpRequest()) {
                    xhr.upload.addEventListener("progress", function(evt){
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            l.setProgress( percentComplete );
                        }
                    }, false);
                }
                return xhr;
            }
        });
    });

}).apply( this, [ jQuery ]);

function checkTheDropdownsImport(){
    var arr  = $('select#import').find(':selected');
    $('select#import').find('option').show();
    $.each($('select#import'), function(){
        var self = this;
        var selectVal = $(this).val();
        $.each(arr, function(){
            if (selectVal !== $(this).val()){
                $(self).find('option[value="'+$(this).val()+'"]').hide();
            } else {
                $(self).find('option[value="'+$(this).val()+'"]').show()
            }
        });
    });

    $('select#import option[value=""]').show();
};

checkTheDropdownsImport();
$(document).change('select#import', checkTheDropdownsImport);

$('select#export').on('change', checkTheDropdownsExport);
function checkTheDropdownsExport(){
    var arr  = $('select#export').find(':selected');
    $('select#export').find('option').show();
    $.each($('select#export'), function(){
        var self = this;
        var selectVal = $(this).val();
        $.each(arr, function(){
            if (selectVal !== $(this).val()){
                $(self).find('option[value="'+$(this).val()+'"]').hide();
            } else {
                $(self).find('option[value="'+$(this).val()+'"]').show()
            }
        });
    });

    $('select#export option[value=""]').show();
};

checkTheDropdownsExport();
$('select#export').on('change', checkTheDropdownsExport);