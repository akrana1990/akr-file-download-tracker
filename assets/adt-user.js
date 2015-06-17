jQuery(function ($) {


    /*$("a.adt_link").click(function(){

        $("form#adt_form").show();

    });

    $("form#adt_form").submit(function(event){
        event.preventDefault();
        event.stopPropagation();

        var params = $("form#adt_form").find('input').find('select').serialize();
        var results = $("#adt_form_response");
        $.ajax({
            url:_adt_opt.ajax_url + '?action='+_adt_opt.ajax_action,
            data:params,
            method: 'POST',
            beforeSend:function(){
                //loader image shown
            },
            success:function(data,status){
                if(data=='1' && status=='success'){
                    console.log('test');
                    results.html('thank you');
                }
            },error:function(a,b){
                var err='Error Saving Options';
                if (a.status === 0) {
                    err="Could not connect to server ! Try Again..";
                }else{
                    if (b === "timeout") {
                        err="Connection Timeout ! Try Again..";
                    }
                    else {
                        err="Unknown error.";
                    }
                }
                results.html('&#10008; '+err).show();
            }
        });

        return false;
    });*/


});
