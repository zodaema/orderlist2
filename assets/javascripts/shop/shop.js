var divload;
var itemPerPage = $('select[name=itemPerPage]').val();
var currentPage = 1;
var cart = {};

if(localStorage.cart != null){
    if(localStorage.cart != '{}'){
        cart = JSON.parse(localStorage.cart);
        showAddToCartButton();
    }
}

$(document).ready(function(){
    $.showproduct('source/shop.php','product_array','');

    $('select[name=itemPerPage]').on('change', function(){
        // Need to Reset Current Page
        currentPage = 1;
        itemPerPage = $('select[name=itemPerPage]').val();
        $.showproduct('source/shop.php','product_array','itemPerPage='+itemPerPage+'&currentPage='+currentPage);
    });
});

$(document).on('change', 'input[name=amount_spin]', function(){
    let thisValue = $(this).val();
    let thisID = $(this).attr('product-id');

    if(thisValue == '0'){
        $('div.card[product-id="'+thisID+'"]').removeClass('selected')
    }
    else{
        $('div.card[product-id="'+thisID+'"]').addClass('selected');
    }
    $.cartProductOnSelect(thisID, thisValue);
    showAddToCartButton();
});

$(document).on('click', 'a.page-link', function(e){
    e.preventDefault();
    let goToPage = $(this).attr("pd-page");
    if(goToPage == 'next'){
        currentPage = parseInt(currentPage)+1;
    }
    else if(goToPage == 'prev'){
        currentPage = parseInt(currentPage)-1;
    }
    else{
        currentPage = $(this).attr("pd-page");
    }
    $.showproduct('source/shop.php','product_array','itemPerPage='+itemPerPage+'&currentPage='+currentPage);
});

$(document).on('click', 'a#addtocart_button', function(e){
    e.preventDefault();

    let cartdata = {};
    let i = 0;
    $.each(cart,function(id_product,amount){
        cartdata[i] = {
            "id_product": id_product,
            "amount": amount.amount
        }
        i++;
    });

    if(cart != null){
        $.ajax({
            url: 'source/shop.php',
            type: "POST",
            data: {'action': 'addtocart', 'cartdata': JSON.stringify(cartdata)},
            dataType: 'html',
            beforeSend :function(){
                $('a#addtocart_button').addClass('btn-loading');
            },
            success: function (data) {
                if(data == 'success'){
                    $('a#addtocart_button').removeClass('btn-loading');
                    $.clearAllSelect();
                    $.updateMiniCart();
    
                    $('div#navbarSupportedContent-4').collapse('show');
                    $('a#toggle_mini_cart').dropdown('toggle');
                }
            }
        });
    }
});

$(document).on('click', 'a#clearselectcart_button', function(e){
    e.preventDefault();
    $.clearAllSelect();
});

$.cartProductOnSelect = function(id_product,amount){
    let cartItem = {
        'amount': amount
    };
    cart[id_product] = cartItem;

    if(amount > 0){
        localStorage.setItem('cart', JSON.stringify(cart));
    }
    else{
        delete cart[id_product];
        localStorage.setItem('cart', JSON.stringify(cart));
    }
}

$.clearAllSelect = function(){
    let cartdata = localStorage.getItem("cart");
    $.each(JSON.parse(cartdata),function(id_product,amount){
        $('input[product-id="'+id_product+'"]').val(0);
        $('div[product-id="'+id_product+'"]').removeClass('selected');
    });
    $("html, body").animate({
        scrollTop: 0
    }, 0);
    $('div.addtocart_row').hide();
    localStorage.removeItem('cart');
    cart = {};
}

function showAddToCartButton(){
    if(localStorage.cart != '{}' ){
        $('div.addtocart_row').show();
    }
    else{
        $('div.addtocart_row').hide();
    }
}

$.loading = function(){
    let html = `<div class="card-body" id="loading">
                    <div class="dimmer active">
                        <div class="spinner1">
                            <div class="double-bounce1"></div>
                            <div class="double-bounce2"></div>
                        </div>
                    </div>
                </div>`;

    if($("div#loading").length==0){
        $("div#show-product").append(html);
        divload = $("div#loading");
    }else{
        divload = $("div#loading");
    }
}

$.showproduct = function(url,action,data){
    $("div#show-product").empty();
    $.loading();
    $.ajax({
        url: url,
        type: "GET",
        data: data+"&action="+action,
        dataType: 'json',
        beforeSend :function(){
            divload.fadeIn();
            $("html, body").animate({
                scrollTop: 0
            }, 0);
        },
        success: function (data) {
            // console.log(data['currentPage']);
            divload.fadeOut();
            
            if(data.data.length != 0){
                for(i=0; i < data.data.length; i++){
                    $('div#show-product').append(makeProductToDiv(data.data[i]));
                }
                $("input[name='amount_spin']").TouchSpin({
                    min: 0,
                    max: 1000,
                    buttonup_txt: '<i class="fa fa-plus"></i>',
                    buttondown_txt:'<i class="fa fa-minus"></i>'
                });
                $('div#show-product').append(makeProductPagination(data.recordsTotal,$('select[name=itemPerPage]').val(),currentPage));    
            }
            else{
                $('div#show-product').append('<center>No Product Data</center>');
            }
        }
    });
}

function makeProductToDiv(data){
    let onSelect = '';
    let amount = '0';
    if(localStorage.cart != null){
        if (JSON.parse(localStorage.cart)[data.id_product] != null){
            onSelect = 'selected';
            amount = JSON.parse(localStorage.cart)[data.id_product]['amount'];
        }
    }
    
    let html = `
        <div class="col-md-6 col-xl-4 col-sm-6">
            <div class="card ${onSelect}" product-id="${data.id_product}">
                <div class="product-grid6">
                    <div class="product-image6 p-5">
                        <ul class="icons">
                            <li>
                                <a href="shop-description.html" class="btn btn-primary"> <i class="fe fe-eye">  </i> </a>
                            </li>
                            <li><a href="add-product.html" class="btn btn-success"><i  class="fe fe-edit"></i></a></li>
                            <li><a href="javascript:void(0)" class="btn btn-danger"><i class="fe fe-x"></i></a></li>
                        </ul>
                        <a href="shop-description.html" >
                            <img class="img-fluid br-7 w-100" src="${$(location).attr('hostname')}/../../img/product/${data.img}" alt="img">
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="product-content text-center">
                            <h1 class="title fw-bold fs-20"><a href="shop-description.html">${data.product}</a></h1>
                            <div class="mb-2 text-warning">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star-half-o text-warning"></i>
                                <i class="fa fa-star-o text-warning"></i>
                            </div>
                            <div class="price">${data.price}<span class="ms-4">${data.price}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <div class="input-group input-group-lg mb-2">
                            <input name="amount_spin" class="form-control shop-input" value="${amount}" style="text-align: center;" product-id="${data.id_product}">
                        </div>
                        <a href="wishlist.html" class="btn btn-outline-primary mb-1"><i class="fe fe-heart mx-2 wishlist-icon"></i>Add to wishlist</a>
                    </div>
                </div>
            </div>
        </div>
    `
    return html;
}

function makeProductPagination(recordsTotal,itemsPerPage, currentPage){
    let numberOfPages = Math.ceil(recordsTotal/itemsPerPage);
    // let offset = (page - 1) * itemsPerPage + 1;
    // console.log('currentPage = '+ currentPage);
    // console.log('numberOfPages = '+numberOfPages);
    let html = `
    <div class="mb-5">
        <div class="float-end">
            <ul id="product-pagination" class="pagination ">
                <li class="page-item page-prev `;if(currentPage == 1){html = html+'disabled';}
                html = html+`">
                    <a class="page-link" pd-page="prev" href="#" tabindex="-1">Prev</a>
                </li>
    `;

    for(i=0; i<numberOfPages; i++){
            html= html+`<li class="page-item `;if(currentPage == (i+1)){html = html+'active';}
            html = html+`"><a class="page-link" pd-page="${i+1}" href="#">${i+1}</a></li>
            `;
    }

    html=html+`
                <li class="page-item page-next `;if(currentPage == numberOfPages){html = html+'disabled';}
                html = html+`">
                    <a class="page-link" pd-page="next" href="#">Next</a>
                </li>
            </ul>
        </div>
    </div>
    `;

    return html;
}
