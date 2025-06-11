var datatableInit = $('#datatable-account').DataTable({
    "processing": true,
    "ajax": "source/member.php?action=datatable-account",
    "columns": [
        {
            "render": function ( data, type, full, meta ) {
                return full.id_member;
            }
        },
        {
            "render": function ( data, type, full, meta ) {
                return full.group_name;
            }
        },
        {
            "render": function ( data, type, full, meta ) {
                return full.username;
            }
        },
        {
            "render": function ( data, type, full, meta ) {
                return full.name;
            }
        },
        {
            "className": "actions",
            "render": function ( data, type, full, meta ) {
                return '<a id="edit_account" href="source/member.php?action=edit_account&id='+full.id_member+'"><i class="fa fa-pencil"></i></a>'
                    +'<a id="del_account" href="#" id_member="'+full.id_member+'"><i class="fa fa-trash-o"></i></a>';
            }
        }

    ]
});

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
    $(document).on('submit', 'form#data_detail', function (e) {
        e.preventDefault();
        var data = $('form#data_detail').serialize();
        console.log(data);
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/data.php",
                    type: "POST",
                    data: data + "&action=edit_data",
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        console.log(data);
                        if (data[0] == 'success') {
                            datatableInit.ajax.reload(null,false);
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'แก้ไขข้อมูลแล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no permission') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'คุณไม่มีสิทธิ์แก้ไขข้อมูล.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'empty') {
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

    $(document).on('click', 'a#add_account', function(e){
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

    $(document).on('submit', 'form#add_account', function (e) {
        e.preventDefault();
        var data = $('form#add_account').serialize();
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/data.php",
                    type: "POST",
                    data: data + "&action=add_account_action",
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        console.log(data);
                        if (data[0] == 'success') {
                            datatableInit.ajax.reload(null,false);
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'เพิ่มบัญชีผู้ใช้แล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no permission') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'คุณไม่มีสิทธิเพิ่มบัญชีผู้ใช้.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'empty') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'กรุณาอย่าเว้นช่องว่าง.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'username used') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'Username มีผู้ใช้งานแล้ว.',
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

    $(document).on('submit', 'form#edit_account', function (e) {
        e.preventDefault();
        var data = $('form#edit_account').serialize();
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/data.php",
                    type: "POST",
                    data: data + "&action=edit_account_action",
                    dataType: 'json',
                    success: function (data) {
                        jc.close();
                        console.log(data);
                        if (data[0] == 'success') {
                            datatableInit.ajax.reload(null,false);
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Success!',
                                text: 'แก้ไขบัญชีผู้ใช้แล้ว.',
                                type: 'success'
                            });
                        }
                        else if (data[0] == 'no permission') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'คุณไม่มีสิทธิแก้ไขบัญชีผู้ใช้.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'empty') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'กรุณาอย่าเว้นช่องว่าง.',
                                type: 'error'
                            });
                        }
                        else if (data[0] == 'username used') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'Username มีผู้ใช้งานแล้ว.',
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

    $(document).on('click', 'a#del_account', function (e) {
        e.preventDefault();
        var id_member = $(this).attr('id_member');
        var jc = $.confirm({
            theme: 'hario',
            keyboardEnabled: true,
            title: 'ยืนยันอีกครั้ง!',
            content: 'คุณต้องการทำรายการ จริงๆใช่ไหม?',
            confirm: function () {
                $.ajax({
                    url: "source/member.php",
                    type: "POST",
                    data: "action=del_account&id="+id_member,
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
                        else if (data[0] == 'no account') {
                            $.magnificPopup.close();
                            new PNotify({
                                title: 'Error!',
                                text: 'ไม่พบบัญชี.',
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

}).apply( this, [ jQuery ]);