<?php
    require_once 'source/model/table_class.php';
    $objTable = new queryTable;

    require_once  __DIR__.'/../source/model/member_class.php';
    $domain = domain::getDomain();

?>

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title"><?php echo $page_detail['name']; ?></h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $page_detail['subname']; ?></a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $page_detail['name']; ?></li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- ROW-1 OPEN -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                                <div class="card cart">
                                    <div class="card-header">
                                        <h3 class="card-title">Shopping Cart</h3>
                                    </div>
                                    <div class="card-body cart">
                                        <div class="table-responsive">
                                            <?php

                                            $objMemberDetail = new memberDetail();
                                            $member_detail = $objMemberDetail->thisLogin();

                                            $cart_result = $objTable->search('cart','id_member',$member_detail['id_member']);
                                            
                                            if(mysqli_num_rows($cart_result) != 0){

                                            echo '
                                            <table class="table table-bordered table-vcenter">
                                                <thead>
                                                    <tr class="border-top">
                                                        <th>Product</th>
                                                        <th>Title</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Subtotal</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';

                                                while($cart_row = $cart_result->fetch_assoc()){

                                                    $search_product_detail = $objTable->search('product','id_product',$cart_row['id_product']);
                                                    $product_detail_row = $search_product_detail->fetch_assoc();
                                                echo '
                                                    <tr>
                                                        <td>
                                                            <div class="text-center">
                                                                <img src="../img/product/'.$product_detail_row['img'].'" alt="" class="cart-img text-center">
                                                            </div>
                                                        </td>
                                                        <td>'.$product_detail_row['product'].'</td>
                                                        <td class="fw-bold">'.$product_detail_row['price'].' ฿</td>
                                                        <td><a id="cart2_amount_edit" href="#" data-pk="'.$cart_row['id_cart'].'">'.$cart_row['amount'].'</a>';

                                                            // echo'<div class="handle-counter" id="handleCounter4">
                                                            //     <button type="button" class="counter-minus btn btn-white lh-2 shadow-none">
                                                            //         <i class="fa fa-minus text-muted"></i>
                                                            //     </button>
                                                            //     <input name="amount_spin" type="text" value="'.$cart_row['amount'].'" class="qty" id-cart="'.$cart_row['id_cart'].'">
                                                            //     <button type="button" class="counter-plus btn btn-white lh-2 shadow-none">
                                                            //         <i class="fa fa-plus text-muted"></i>
                                                            //     </button>
                                                            // </div>';

                                                        echo'
                                                        </td>
                                                        <td><span name="subtotal" id-cart="'.$cart_row['id_cart'].'">'.$product_detail_row['price']*$cart_row['amount'].'</span> ฿</td>
                                                        <td>
                                                            <div class=" d-flex g-2">
                                                                <a class="btn text-secondary bg-secondary-transparent btn-icon py-1 me-2" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="bi bi-heart fs-16"></span></a>
                                                                <a class="btn text-danger bg-danger-transparent btn-icon py-1" data-bs-toggle="tooltip" data-bs-original-title="Delete"><span class="bi bi-trash fs-16"></span></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    ';
                                                }
                                                    echo'
                                                </tbody>
                                            </table>
                                            ';
                                            }

                                            else{
                                                echo'<center>Empty Cart</center>';
                                            }
                                        ?>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="input-group mb-1">
                                                    <input type="text" class="form-control" placeholder="Search ...">
                                                    <span class="input-group-text btn btn-primary">Apply Coupon</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 text-end"><a href="javascript:void(0)" class="btn btn-default disabled btn-md">Update Cart</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Cart Totals</div>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-nowrap mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-start">Sub Total</td>
                                                        <td class="text-end"><span class="fw-bold  ms-auto">$568</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Additional Discount</td>
                                                        <td class="text-end"><span class="fw-bold text-success">- $55</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Delivery Charges</td>
                                                        <td class="text-end"><span class="fw-bold text-green">0 (Free)</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Tax</td>
                                                        <td class="text-end"><span class="fw-bold text-danger">+ $39</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Coupon Discount</td>
                                                        <td class="text-end"><span class="fw-bold text-success">- $15%</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">Vat Tax</td>
                                                        <td class="text-end"><span class="fw-bold">+ $9</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start fs-18">Total Bill</td>
                                                        <td class="text-end"><span class="ms-2 fw-bold fs-23">$568</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-list">
                                            <a href="shop.html" class="btn btn-primary"><i class="fa fa-arrow-left me-1"></i>Continue Shopping</a>
                                            <a href="checkout.html" class="btn btn-success float-sm-end">Check out<i class="fa fa-arrow-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ROW-1 CLOSED -->
                    </div>
                    <!-- CONTAINER CLOSED -->
                </div>
            </div>
            <!--app-content closed-->
        </div>