<?php
    require_once 'source/model/table_class.php';
    $objTable = new queryTable;

?>

<!-- Main Zone -->
        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                  <h1 class="page-title my-auto"><?php echo $page_detail['name']; ?></h1>
                  <div>
                    <ol class="breadcrumb mb-0">
                      <li class="breadcrumb-item">
                        <a href="javascript:void(0)"><?php echo $page_detail['subname']; ?></a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page"><?php echo $page_detail['name']; ?></li>
                    </ol>
                  </div>
                </div>
                <!-- PAGE-HEADER END -->

                <!-- Start::row-1 -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">

                            <div class="card-header">
                                <div class="card-title">
                                    rebello Database
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="container">
                                    <div class="d-flex mb-3 control-panel">
                                        <div class="me-auto left-control-button">
                                            <a class="btn btn-success shadow-success btn-wave waves-effect waves-light label-btn" data-bs-toggle="modal" data-bs-target="#addNewOrder"><i class="fe fe-plus label-btn-icon me-2"></i> เพิ่ม</a>
                                            <a class="btn btn-danger shadow-danger btn-wave waves-effect waves-light label-btn" id="removeMultiOrder"><i class="fe fe-minus label-btn-icon me-2"></i> ลบ</a>
                                        </div>
                                        <div class="text-end right-control-button">
                                            <a href="#" id="advance_search_button" class="btn btn-primary shadow-primary btn-wave waves-effect waves-light label-btn"><i class="fe fe-search label-btn-icon me-2"></i> ค้นหาแบบละเอียด</a>
                                            <a href="#" id="refresh_button" class="btn btn-success shadow-success btn-wave waves-effect waves-light label-btn"><i class="fe fe-refresh-cw label-btn-icon me-2"></i> Refresh</a>
                                        </div>
                                    </div>

                                    <div class="advance-search hide mt-3">
                                        <form id="form-advance-search">
                                            <div class="row mb-2 col-sm-12">
                                                <div class="col-md-3">
                                                    <label for="filter_date" class="col-form-label">วัน</label>
                                                    <select name="filter_date" class="form-select form-select-sm" id="filter_date">
                                                        <option value="1">ทั้งหมด</option>
                                                        <option value="2">วันนี้</option>
                                                        <option value="3">เมื่อวาน</option>
                                                        <option value="4">7 วันที่ผ่านมา</option>
                                                        <option value="5">30 วันที่ผ่านมา</option>
                                                        <option value="6">เดือนนี้</option>
                                                        <option value="7">ปีนี้</option>
                                                        <option value="8">กำหนดเอง</option>
                                                    </select>

                                                    <div class="form-group filter_daterange_form mt-1" style="display:none">
                                                        <div class="input-group">
                                                            <div class="input-group-text text-muted">
                                                                <i class="ri-calendar-line"></i>
                                                            </div>
                                                            <input type="text" class="form-control flatpickr-input active filter_daterange" id="daterange" placeholder="Date range picker" readonly="readonly">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3"><label for="filter_status" class="col-form-label">สถานะ</label>
                                                    <select name="filter_status" class="form-select form-select-sm" id="filter_status">
                                                        <option value="">แสดงทุกค่า</option>
                                                        <?php
                                                            $status_data = $objTable->queryByName('craftsman_order_status');
                                                            while($status_row = $status_data->fetch_assoc()){
                                                                if($status_row['id_cos'] != '5'){
                                                                    echo '<option value="'.$status_row['id_cos'].'" >'.$status_row['name'].'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-3"><label for="filter_source" class="col-form-label">ช่องทาง</label>
                                                    <select name="filter_source" class="form-select form-select-sm" id="filter_source">
                                                        <option value="">แสดงทุกค่า</option>
                                                        <?php
                                                            $source_data = $objTable->queryByName('customer_source');
                                                            while($source_row = $source_data->fetch_assoc()){
                                                                    echo '<option value="'.$source_row['id_source'].'" >'.$source_row['name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-3"><label for="filter_craftsman_hire" class="col-form-label">วิธีการทำ</label>
                                                    <select name="filter_craftsman_hire" class="form-select form-select-sm" id="filter_craftsman_hire">
                                                        <option value="">แสดงทุกค่า</option>
                                                        <?php
                                                            $craftsman_hire_data = $objTable->queryByName('craftsman_order_hire');
                                                            while($craftsman_hire_row = $craftsman_hire_data->fetch_assoc()){
                                                                    echo '<option value="'.$craftsman_hire_row['id_hire'].'" >'.$craftsman_hire_row['name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row col-sm-12">
                                                <div class="col-sm-12 text-end"><a href="#" id="reset_advance_search" class="btn btn-primary btn-block">ล้างข้อมูล</a></div>
                                            </div>
                                        </form>
                                    </div>
                                
                                    <div class="row mb-3">
                                        <div class="table-responsive">
                                            <table id="table-data-all" class="table table-bordered text-nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>วันสั่งซื้อ</th>
                                                        <th style="min-width:140px;">สถานะ</th>
                                                        <th>รายละเอียด</th>
                                                        <th style="min-width:120px;">วิธีการทำ</th>
                                                        <th>ช่องทาง</th>
                                                        <th>ลูกค้า</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- Modal Block Zone -->
        <!-- Add New Order Modal -->
        <div class="modal fade" id="addNewOrder" tabindex="-1" aria-labelledby="addNewOrder" data-bs-keyboard="false" aria-hidden="true"> 
            <!-- Scrollable modal --> 
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <h6 class="modal-title" id="staticBackdropLabel2">เพิ่มข้อมูลออเดอร์ใหม่ </h6> 
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                    </div> 

                    <form id="add_new_order_form" class="needs-validation" novalidate>

                        <div class="modal-body"> 
                            <div class="container mb-3">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">ช่องทาง</label>
                                            <select name="source" id="id_source" class="form-select" required>
                                            
                                            <?php
                                                $data = $objTable->queryByName('customer_source');
                                                while($data_row = $data->fetch_assoc()){
                                                    echo '<option value="'.$data_row['id_source'].'" >'.$data_row['name'].'</option>';
                                                }
                                            ?>
                                            </select>
                                            <input name="username" class="form-control mt-1" placeholder="ชื่อจากที่มา" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">วันที่</label>
                                            <input name="date" type="date" class="form-control" required>
                                            <input name="time" type="time" class="form-control mt-1">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">ข้อมูลลูกค้า</label>
                                            <div class="row mb-3">
                                                <textarea id="temporary_address" name="temporary_address" rows="3" class="form-control" placeholder="กล่องพักที่อยู่ลูกค้า"></textarea>
                                            </div>
                                            <div class="row">
                                                <input name="name" class="form-control" placeholder="ชื่อผู้รับ" required>
                                                <input name="tel" class="form-control mt-1" type="tel" placeholder="เบอร์โทร" required>
                                                <textarea name="address" rows="3" class="form-control mt-1" placeholder="ที่อยู่" required></textarea>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-12 mb-3">
                                        <label class="form-label">ออเดอร์</label>
                                        <div class="row mb-3">
                                            <textarea id="temporary_detail" name="temporary_detail" rows="3" class="form-control" placeholder="กล่องพักรายละเอียดออเดอร์"></textarea>
                                        </div>
                                        <div name="more_area">
                                            <div class="row mb-3">
                                                <label for="order_detail_1" class="col-sm-1 col-form-label">#1</label>
                                                <div class="col-sm-8">
                                                    <textarea id="order_detail_1" name="order_detail_1" rows="1" class="form-control" placeholder="รายละเอียดออเดอร์" required></textarea>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="number" name="order_price_1" class="form-control" placeholder="ราคา" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 text-center">
                                            <button type="button" id="add_more_order_detail" class="btn btn-success">+ เพิ่ม</button>
                                            <input name="number_order_details" id="number_order_details" value="1" style="display:none;" />
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-5 text-center">
                                            <label class="form-label">ค่าจัดส่ง</label>
                                            <input type="number" name="shipping_price" class="form-control text-center" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer"> 
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
                                <button id="ClearFormModal" type="button" class="btn btn-warning">Clear</button> 
                                <button id="submitNewOrder" type="submit" class="btn btn-primary">Add Order</button> 
                        </div>

                    </form>

                </div> 
            </div> 
        </div>

        <!-- Edit Order Modal -->
        <div class="modal fade" id="editOrder" tabindex="-1" aria-labelledby="editOrder" data-bs-keyboard="false" aria-hidden="true"> 
            <!-- Scrollable modal --> 
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <h6 class="modal-title" id="staticBackdropLabel2">แก้ไขข้อมูล </h6> 
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                    </div> 

                    <form id="edit_order_form" class="needs-validation" novalidate>

                        <div class="modal-body"> 
                        </div>

                        <div class="modal-footer"> 
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button id="submitEditOrder" type="submit" class="btn btn-success">Save</button> 
                        </div>

                    </form>

                </div> 
            </div> 
        </div>