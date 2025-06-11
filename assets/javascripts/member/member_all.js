$(document).on('click', '#refresh_button', function(){
    datatableInit.ajax.reload(null,false);
});