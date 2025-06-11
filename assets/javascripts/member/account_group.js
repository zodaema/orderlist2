var datatableInit = $('#datatable-account-group').DataTable({
    "processing": true,
    "ajax": "source/member.php?action=datatable-account-group",
    "columns": [
        {
            "render": function ( data, type, full, meta ) {
                return full.id_group;
            }
        },
        {
            "render": function ( data, type, full, meta ) {
                return full.group_name;
            }
        },
        {
            "render": function ( data, type, full, meta ) {
                return full.detail;
            }
        },
        {
            "render": function ( data, type, full, meta ) {
                return full.amount;
            }
        },
        {
            "className": "actions",
            "render": function ( data, type, full, meta ) {
                return '<a id="edit_account_group" href="source/member.php?action=edit_account_group&id='+full.id_group+'"><i class="fa fa-pencil"></i></a>'
                    +'<a id="del_account_group" href="#" id_group="'+full.id_group+'"><i class="fa fa-trash-o"></i></a>';
            }
        }

    ]
});

(function( $ ) {

    'use strict';

    $(document).on('click', '.modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });

    $(document).on('click', 'a#edit_account', function(e){
        e.preventDefault();
        $.magnificPopup.open({
            items: {
                src: this
            },
            type: 'ajax',
            mainClass: 'my-mfp-zoom-in'
        });
        return false;
    });

    $(document).on('click', 'a#del_account_group', function (e) {
        e.preventDefault();
        var id_group = $(this).attr('id_group');
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/member.php",
                    type: "POST",
                    data: "action=del_account_group&id="+id_group,
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        console.log(data);
                        if (data[0] == 'success') {
                            datatableInit.ajax.reload(null,false);
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'ลบข้อมูลแล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no permission') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'คุณไม่มีสิทธิ์ลบข้อมูล.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'no account group') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'ไม่พบกลุ่มผู้ใช้นี้.',
                                type: 'error'
                            });
                        }
                    }
                });
            },
            confirmButton: 'ใช่',
            confirmButtonClass: 'btn-primary',
            cancelButton: 'ไม่ใช่',
            cancelButtonClass: 'btn-danger'
        });
    });

    $(document).on('click', 'a#edit_account_group', function(e){
        e.preventDefault();
        $.magnificPopup.open({
            items: {
                src: this
            },
            type: 'ajax',
            mainClass: 'my-mfp-zoom-in'
        });
        return false;
    });

    $(document).on('submit', 'form#edit_account_group', function (e) {
        e.preventDefault();
        var data = $('form#edit_account_group').serialize();
        console.log(data);
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/member.php",
                    type: "POST",
                    data: data + "&action=edit_account_group_action",
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        console.log(data);
                        if (data[0] == 'success') {
                            datatableInit.ajax.reload();
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'แก้ไขกลุ่มผู้ใช้งานแล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no permission') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'คุณไม่มีสิทธิ์แก้ไขกลุ่มผู้ใช้งาน.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'empty'){
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'กรุณาอย่าเว้นช่องว่าง.',
                                type: 'error'
                            });
                        }
                    }
                });
            },
            confirmButton: 'ใช่',
            confirmButtonClass: 'btn-primary',
            cancelButton: 'ไม่ใช่',
            cancelButtonClass: 'btn-danger'
        });
    });

    $(document).on('submit', 'form#add_account_group', function (e) {
        e.preventDefault();
        var data = $('form#add_account_group').serialize();
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/member.php",
                    type: "POST",
                    data: data + "&action=add_account_group_action",
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        console.log(data);
                        if (data[0] == 'success') {
                            datatableInit.ajax.reload();
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'เพิ่มกลุ่มผู้ใช้งานแล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no permission') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'คุณไม่มีสิทธิ์เพิ่มกลุ่มผู้ใช้งาน.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'empty'){
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'กรุณาอย่าเว้นช่องว่าง.',
                                type: 'error'
                            });
                        }
                    }
                });
            },
            confirmButton: 'ใช่',
            confirmButtonClass: 'btn-primary',
            cancelButton: 'ไม่ใช่',
            cancelButtonClass: 'btn-danger'
        });
    });

    $(document).on('click', 'a#add_account_group', function(e){
        e.preventDefault();
        $.magnificPopup.open({
            items: {
                src: this
            },
            type: 'ajax',
            mainClass: 'my-mfp-zoom-in'
        });
        return false;
    });

}).apply( this, [ jQuery ]);