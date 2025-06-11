(function () {
    "use strict";
    
    console.log(sessionStorage.getItem('sSidebarStatus'))
    if (sessionStorage.getItem('sSidebarStatus') == 'o' || sessionStorage.getItem('sSidebarStatus') == null) {
        sessionStorage.setItem('sSidebarStatus', 'o');
        $('.app').removeClass('sidenav-toggled');
    }
    else{
        $('.app').toggleClass('sidenav-toggled');
    }

    $(document).on('click', '[data-bs-toggle="sidebar"]', function (event) {
        if(sessionStorage.getItem('sSidebarStatus') == 'o'){
            sessionStorage.setItem('sSidebarStatus', 'c');
        }
        else sessionStorage.setItem('sSidebarStatus', 'o');
    });
    
})();

    // function orderSelectCheck(){
    //     var numberOfChecked = $('input#orderSelect:checked').length;
    
    //     if( numberOfChecked < 1){
    //         $("div[name=action]").fadeOut('fast');
    //     }
    //     else{
    //         if($("div[name=action]:hidden")){
    //             $("div[name=action]").fadeIn('fast');
    //         }
    //         $("span[name=numofselect]").html("เลือก "+numberOfChecked+" รายการ");
    //     }
    // }
    
    // function orderSelectFocus(select){
    //     if ($(select).prop('checked') === true) {
    //         $(select).closest('tr').addClass("order_selected");
    //     }
    //     else {
    //         $(select).closest('tr').removeClass("order_selected");
    //     }
    // }
    
    // function checkAllSelectIsChecked(){
    //     var selectAll = $('input#orderSelectAll'),
    //         CountSelect = $('input#orderSelect').length,
    //         CountSelected = $('input#orderSelect:checked').length;
    
    //     if(CountSelect != CountSelected && CountSelected != 0){
    //         selectAll.prop({
    //             checked: false,
    //             indeterminate: true
    //         });
    //     }
    
    //     else if(CountSelect == CountSelected && CountSelect != 0){
    //         selectAll.prop({
    //             checked: true,
    //             indeterminate: false
    //         });
    //     }
    //     else{
    //         selectAll.prop({
    //             checked: false,
    //             indeterminate: false
    //         });
    //     }
    // }

    function checkSearchInput(target){
        if(target.val()){
            target.addClass('is-valid');
        }
        else{
            target.removeClass('is-valid');
        }
    }

    function orderSelectCheck(){
        let numberOfChecked = $('input#orderSelect:checked').length;

        if( numberOfChecked < 1){
            $("div[name=action]").fadeOut('fast');
        }
        else{
            if($("div[name=action]:hidden")){
                $("div[name=action]").fadeIn('fast');
            }
            $("span[name=numofselect]").html("เลือก "+numberOfChecked+" รายการ");
        }
    }

    function orderSelectFocus(select){
        if ($(select).prop('checked') === true) {
            $(select).closest('tr').addClass("order_selected");
        }
        else {
            $(select).closest('tr').removeClass("order_selected");
        }
    }

    function checkAllSelectIsChecked(){
        let selectAll = $('input#orderSelectAll'),
            CountSelect = $('input#orderSelect').length,
            CountSelected = $('input#orderSelect:checked').length;

        if(CountSelect != CountSelected && CountSelected != 0){
            selectAll.prop({
                checked: false,
                indeterminate: true
            });
        }

        else if(CountSelect == CountSelected && CountSelect != 0){
            selectAll.prop({
                checked: true,
                indeterminate: false
            });
        }
        else{
            selectAll.prop({
                checked: false,
                indeterminate: false
            });
        }
    }
    