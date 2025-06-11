<?php
    require_once 'source/model/table_class.php';
    $objTable = new queryTable;
    
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
                        <div class="row row-cards">
                            <div class="col-xl-3 col-lg-4">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Categories</div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group">
                                                <?php 
                                                    $category_data = $objTable->queryByName('product_category');
                                                    
                                                    while($category_row = $category_data->fetch_assoc()){
                                                        $count_category = $objTable->count('product','id_pcate',$category_row['id_pcate']);
                                                        echo'<li class="list-group-item border-0 p-0"> <a href="javascript:void(0)"><i class="fe fe-chevron-right"></i> '.$category_row["category"].' </a><span class="product-label">'.$count_category.'</span> </li>';
                                                    }
                                                ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- COL-END -->
                            <div class="col-xl-9 col-lg-8">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card p-0">
                                            <div class="card-body p-4">
                                                <div class="row">
                                                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4">
                                                        <div class="form-group">
                                                            <div class="row mb-4">
                                                                <label class="col form-label align-middle">Show:</label>
                                                                <div class="col-8">
                                                                    <select name="itemPerPage" class="form-control form-select select2">
                                                                        <option value="12" selected>12</option>
                                                                        <option value="24">24</option>
                                                                        <option value="48">48</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-5 col-md-5 col-sm-5">
                                                        <div class="input-group d-flex w-100 float-start">
                                                            <input type="text" class="form-control border-end-0" placeholder="Search ...">
                                                            <button class="btn input-group-text bg-transparent border-start-0 text-muted>
                                                                <i class="fe fe-search text-muted" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                        <ul class="nav item2-gl-menu float-end">
                                                            <li class="border-end"><a href="#tab-11" class="show active" data-bs-toggle="tab" title="List style"><i class="fa fa-th"></i></a></li>
                                                            <li><a href="#tab-12" data-bs-toggle="tab" class="" title="Grid"><i class="fa fa-list"></i></a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-12 col-lg-12 hide">
                                                        <a href="add-product.html" class="btn btn-primary btn-block float-end"><i class="fa fa-plus-square me-2"></i>New Product</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-11">
                                        <div id="show-product" class="row">
                                            <!-- Product Content -->
                                        </div>
                                    </div>
                                    <div class="row addtocart_row">
                                        <a id="addtocart_button" class="btn btn-primary addtocart_button" href="#">
                                            <span><i class="fa fa-shopping-cart"></i> Add to Cart</span>
                                        </a>
                                        <a id="clearselectcart_button" class="btn btn-danger clearselectcart_button" href="#">
                                            <span><i class="fa fa-close"></i> Clear</span>
                                        </a>
                                    </div>
                                </div>
                                <!-- COL-END -->
                            </div>
                            <!-- ROW-1 CLOSED -->
                        </div>
                        <!-- ROW-1 END -->
                    </div>
                    <!-- CONTAINER CLOSED -->
                </div>
            </div>
            <!--app-content closed-->
        </div>