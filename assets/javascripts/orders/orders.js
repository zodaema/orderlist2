    $(document).ready(function(){

        const datatableInit = $('#table-data-all').DataTable({
            "select": true,
            "processing": true,
            "serverSide": true,
            "order": [[ 1, "desc" ]],
            "stateSave": true,
            "ajax": {
                "url": "source/orders.php?action=table_data_all_serverside",
                "type": "GET",
                "data": function(d){
                    d.filter_date = $('select#filter_date').val();
                    d.filter_custom_daterange = $('input#daterange').val();
                    d.filter_status = $('select#filter_status').val();
                    d.filter_source = $('select#filter_source').val();
                    d.filter_craftsman_hire = $('select#filter_craftsman_hire').val();
                    // console.log(d.filter_source);
                },
                "dataSrc": function ( json ) {
                    // console.log(json);
                    return json.data;
                }
            },
            "columns": [
                {
                    data: null, orderable: false, searchable: false, render: DataTable.render.select()
                },
                {
                    "render": function ( data, type, full, meta ) {
                        return '<span class="badge bg-purple-gradient">'+full.orderdate+'</span>';
                    }
                },
                {
                    "render": function ( data, type, full, meta ) {
                        html = " <select class='form-select' name='craftman_status_data' id='craftman_status_data' data-order-id='"+full.id_order+"'>";
                        for(var key in full.craftman_status_data) {
                            html += "<option value=" + key
                            if (key == full.id_cos){
                                html += " selected";
                            }
                            html += ">" +full.craftman_status_data[key]['name'] + "</option>";
                        }
                        html +="</select>";
        
                        return html;
                        return '<a id="data_detail" href="source/data_detail.php?id='+full.id_order+'">'+full.id_order+'</a>';
                    }
                },
                {
                    sortable: false,
                    "render": function ( data, type, full, meta ) {
                        return full.order_detail;
                    }
                },
                {
                    // sortable: false,
                    "render": function ( data, type, full, meta ) {
                        html = " <select class='form-select form-select-sm' name='hire_status' id='hire_status' data-order-id='"+full.id_order+"'>";
                        for(var key in full.hire_data) {
                            html += "<option value=" + key  
                            if (key == full.id_hire){
                                html += " selected";
                            }
                            html +=">" +full.hire_data[key]['name'] + "</option>"
                        }
                        html +="</select>";
        
                        return html;
                    }
                },
                {
                    sortable: false,
                    "render": function ( data, type, full, meta ) {
                        html = '<span class="badge bg-';
                        switch (full.source) {
                            case 'Facebook':
                                html += 'info';
                                break;
                            case 'Instagram':
                                html += 'danger';
                                break;
                            default:
                                html += 'primary'
                        }
                        html += '">'+full.source+'</span>';
                        return html;
                    }
                },
                {
                    sortable: false,
                    "render": function ( data, type, full, meta ) {
                        return full.username;
                    }
                },
                {
                    sortable: false,
                    "render": function ( data, type, full, meta ) {
                        html = `
                            <a id="showEditOrder" class="btn btn-sm btn-warning btn-wave waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editOrder" data-orderId=`+full.id_order+`><i class="ri-edit-line"></i></a>
                            <a id="deleteThisOrder" class="btn btn-sm btn-danger btn-wave waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#deleteOrder" data-orderId=`+full.id_order+`><i class="ri-delete-bin-line"></i></a>
                        `;
                        return html;
                    }
                }
            ]
        });

        $(document).on('click', '#refresh_button', function(){
            datatableInit.ajax.reload(null,false);
        });

        // advanceSearchState save state
        if (localStorage.getItem('advanceSearchState') == 'c' || localStorage.getItem('advanceSearchState') == null) {
            localStorage.setItem('advanceSearchState', 'c');
            $('div.advance-search').addClass('hide');
        }
        else{
            $('div.advance-search').removeClass('hide');
        }

        /* For Date Range Picker */
        flatpickr("#daterange.filter_daterange", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(){
                datatableInit.ajax.reload();
            }
        });

        $('#advance_search_button').on('click', function(e){
            e.preventDefault;
            if($('div.advance-search').hasClass('hide')){
                $('div.advance-search').removeClass('hide');
                $('div.dataTables_filter').addClass('hide');
            }
            else {
                $('div.advance-search').addClass('hide');
                $('div.dataTables_filter').removeClass('hide');
            }

            if(localStorage.getItem('advanceSearchState') == 'c'){
                localStorage.setItem('advanceSearchState', 'o');
            }
            else localStorage.setItem('advanceSearchState', 'c');
        });
        // End Sidebar save state

        // $('#form-advance-search input').on( 'keyup click', function () {
        //     checkSearchInput($(this));
        //     let i =$(this).attr('data-column');
        //     let v =$(this).val();
        //     datatableInit.columns(i).search(v).draw();
        // } );
        $('#form-advance-search select').on( 'change', function () {
            datatableInit.ajax.reload();
        } );

        $('#filter_date').on('change', function(){
            if($(this).val() == '8'){
                $('div.filter_daterange_form').show();
            }
            else{
                $('div.filter_daterange_form').hide();
            }
        });

        $('#reset_advance_search').on('click', function(){
            $("form#form-advance-search").trigger('reset');
            $('#form-advance-search input').removeClass('is-valid');
            $('#form-advance-search select').removeClass('is-valid');
            $('div.filter_daterange_form').hide();
            datatableInit.ajax.reload();
        });

        // $(document).on('click','table tr:not(tr.tb-head)',function (e) {
        //     if (e.target.type !== 'checkbox') {
        //         $(':checkbox', this).trigger('click');
        //     }
        // });

        // $(document).on('change','input#orderSelect:checkbox',function(e){
        //     e.preventDefault();
        //     orderSelectCheck();
        //     orderSelectFocus(this);
        //     checkAllSelectIsChecked();
        // });

        // $(document).on('change','input#orderSelectAll', function(e) {
        //     e.preventDefault();
        //     let selectAll = $(this),
        //         select = $('input#orderSelect');

        //     if(selectAll.prop('checked') === true && selectAll.prop('indeterminate') === false){
        //         select.prop({checked: true});
        //     }
        //     else if(selectAll.prop('indeterminate') === true){
        //         selectAll.prop({checked: true});
        //         select.prop({checked: true});
        //     }
        //     else{
        //         select.prop({checked: false});
        //     }

        //     orderSelectCheck();
        //     orderSelectFocus(select);
        //     checkAllSelectIsChecked();
        // });

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "50",
            "hideDuration": "50",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $(document).on('change' ,'select#craftman_status_data', function(){
            var id_order = $(this).attr('data-order-id');
            var id_cos = $(this).parent().find('option:selected').val();
        
            $.ajax({
                url: "source/orders.php",
                type: "POST",
                data: "&action=change_craftman_status&id_order="+id_order+"&id_cos="+id_cos,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data[0] == 'success') {
                        toastr["success"]("เปลี่ยนสถานะแล้ว.", "Success!")
                    }
                    else if (data[0] == 'no permission') {
                        toastr["error"]("ไม่อนุญาต", "Error!")
                    }
                }
            });
        });

        var number_order_details = 1;
        $(document).on('click', 'button#add_more_order_detail', function(e){
            e.preventDefault();
            number_order_details ++;
            $('input#number_order_details').val(number_order_details);
            html = `
                    <div class="row mb-3">
                            <label for="order_detail_1" class="col-sm-1 col-form-label">#`+number_order_details+`</label>
                            <div class="col-sm-8">
                            <textarea name='order_detail_`+number_order_details+`' rows='1' class='form-control' placeholder='รายละเอียดออเดอร์'></textarea>
                            </div>
                        <div class="col-sm-3">
                        <input name='order_price_`+number_order_details+`' type='number' class='form-control' placeholder='ราคา'>
                        </div>
                    </div>
                    `;
            $("div[name=more_area]").append(html);
            return false;
        });

        $(document).on('submit', 'form#add_new_order_form', function (e) {
            e.preventDefault();
            // $("#submitNewOrder").addSpinnerButton();
            var data = $('form#add_new_order_form').serialize();
            $.ajax({
                url: "source/orders.php",
                type: "POST",
                data: data + "&action=add_new_order",
                dataType: 'json',
                success: function (data) {
                    // $("#submitNewOrder").removeSpinnerButton();
                    console.log(data);
                    if (data[0] == 'success') {
                        // $('#addNewOrder').modal('hide');
                        $('form#add_new_order_form')[0].reset();
                        $('form#add_new_order_form').removeClass('was-validated');
                        datatableInit.ajax.reload();
                        toastr["success"]("เพิ่มออเดอร์แล้ว.", "Success!")
                    }
                    else if (data[0] == 'no permission') {
                        toastr["error"]("ไม่อนุญาต", "Error!")
                    }
                    else if (data[0] == 'duplicate'){
                        toastr["error"]("ข้อมูลซ้ำ", "Error!")
                    }
                    else if (data[0] == 'blank'){
                        toastr["error"]("กรุณาอย่าเว้นช่องว่าง", "Error!")
                    }
                }
            });
        });

        $(document).on('click', 'a#showEditOrder', function (e) {
            e.preventDefault();
            var id_order = $(this).attr('data-orderid');
        
            $.ajax({
                url: "source/orders.php",
                type: "POST",
                data: "&action=edit_order_form&id_order="+id_order,
                dataType: 'html',
                success: function (data) {
                    $('form#edit_order_form div.modal-body').html(data);
                }
            });
        });

        $(document).on('submit', 'form#edit_order_form', function (e) {
            e.preventDefault();
            // $("#submitEditOrder").addSpinnerButton();
            var data = $('form#edit_order_form').serialize();
            var id_order = $(this).parent().find('input[name=orderid]').val();

            $.ajax({
                url: "source/orders.php",
                type: "POST",
                data: data + "&action=edit_order_submit&orderid="+id_order,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    // $("#submitEditOrder").removeSpinnerButton();
                    if (data[0] == 'success') {
                        datatableInit.ajax.reload();
                        toastr["success"]("แก้ไขออเดอร์แล้ว.", "Success!");
                        // $('#editOrder').modal('hide');
                    }
                    else if (data[0] == 'no permission') {
                        toastr["error"]("ไม่อนุญาต", "Error!")
                    }
                    else if (data[0] == 'duplicate'){
                        toastr["error"]("ข้อมูลซ้ำ", "Error!")
                    }
                    else if (data[0] == 'blank'){
                        toastr["error"]("กรุณาอย่าเว้นช่องว่าง", "Error!")
                    }
                }
            });
        });
        
        $(document).on('click', '.add-more-order-detail', function (e) {
            e.preventDefault();
        
            const modal = $(this).closest('.modal');
            const moreArea = modal.find('.more-area');
            const numberInput = modal.find('.number_order_details');
        
            let count = parseInt(numberInput.val()) || 0;
            count++;
            numberInput.val(count);
            console.log(numberInput.val());
        
            const newOrderRow = `
                <div class="row mb-3 order-detail-row" data-index="${count}">
                    <label class="col-sm-1 col-form-label">#${count}</label>
                    <div class="col-sm-8">
                        <textarea 
                            name="order_detail_${count}" 
                            rows="1" 
                            class="form-control" 
                            placeholder="รายละเอียดออเดอร์" 
                            required
                        ></textarea>
                        <input type="hidden" name="idDetail_${count}" value="">
                    </div>
                    <div class="col-sm-2">
                        <input 
                            type="number" 
                            name="order_price_${count}" 
                            class="form-control" 
                            placeholder="ราคา"
                        >
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger btn-sm remove-detail">ลบ</button>
                    </div>
                </div>
            `;
        
            moreArea.append(newOrderRow);
        });

        $(document).on('click', '.remove-detail', function () {
            const modal = $(this).closest('.modal');
            const numberInput = modal.find('.number_order_details');
            $(this).closest('.order-detail-row').remove();

            const rows = modal.find('.order-detail-row');
            
            console.log(numberInput.val());
            numberInput.val(rows.length);
        
        });

        $(document).on('click', 'a#deleteThisOrder', function(e){
            e.preventDefault();
            var id_order = $(this).attr('data-orderid');
        
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "source/orders.php",
                        type: "POST",
                        data: "&action=delete_order&id_order="+id_order,
                        dataType: 'html',
                        success: function (data) {
                            if(data == 'success'){
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                            }
                            datatableInit.ajax.reload();
                        }
                    });
                }
            })
        });

        $(document).on('click', 'a#removeMultiOrder', function(e){

            var listtable = $("#table-data-all").DataTable();
            var selected_rows = listtable.rows({selected: true}).ids();

            var data = $.makeArray(selected_rows);
            var data_count = selected_rows.count();

            // console.log(data);
            
            if(data_count > 0){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you want to delete "+data_count+" row selected",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('delete multi order');
                        $.ajax({
                            url: "source/orders.php",
                            type: "POST",
                            data: "&action=delete_multi_order&id_order="+data,
                            dataType: 'html',
                            success: function (data) {
                                // console.log(data);
                                if(data == 'success'){
                                    Swal.fire(
                                        'Deleted!',
                                        'Your '+data_count+' row has been deleted.',
                                        'success'
                                    )
                                }
                                datatableInit.ajax.reload();
                            }
                        });
                    }
                })
            }
            
        });

    });