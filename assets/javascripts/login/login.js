$(document).ready(function(){
    $(document).on('click', 'button#normal_login', function(e){
        e.preventDefault();
        let loginData = $('form').serialize();
        $.login('source/member_login.php','login_normal',loginData);
    });

    $('input').on('change', function(){
        if($(this).hasClass('is-invalid')){
            $(this).removeClass('is-invalid');
        }
    });

    $.loadding = function(){
        let html = `<div class="wrap-login100 p-6" id="loadding">
                        <div class="dimmer active">
                            <div class="spinner1">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                            </div>
                        </div>
                    </div>`;

        if($("div#loadding").length==0){
            $("div.container-login100").append(html);
            divload = $("div#loadding");
        }else{
            divload = $("div#loadding");
        }
        divload.css({'background-color':'#FFFFFF','opacity':0.7,'position':'absolute','left':'0px','top':'0px','width':'100%','height':'100%','display':'none','z-index':'1000'});
    }

    $.login = function(url,action,data){
        // $("div#show-product").empty();
        $.loadding();
        $.ajax({
            url: url,
            type: "POST",
            dataType: "html",
            data: data+"&action="+action,
            beforeSend :function(){
                divload.fadeIn();
                
            },
            success: function (data) {
                divload.fadeOut();
                $('div#feedback-email-input').empty();
                $('div#feedback-password-input').empty();

                if($('input[name=email]').val() == ''){
                    $('input[name=email]').addClass('is-invalid');
                    $('div#feedback-email-input').append('Please enter your Email');
                }
                
                if($('input[name=password]').val() == ''){
                    $('input[name=password]').addClass('is-invalid');
                    $('div#feedback-password-input').append('Please enter your Password');
                }

                if(data == 'error'){
                    $('input[name=password]').addClass('is-invalid');
                    $('div#feedback-password-input').append('Your Password is wrong. Please check your Password');
                }
                else if(data == 'noemail'){
                    $('input[name=email]').addClass('is-invalid');
                    $('div#feedback-email-input').append('Your Email is wrong. Please check your Email');
                }
                else if(data == 'success'){
                    window.location = "index.php";
                }
                console.log(data);
            }
        });
    }
});