$(document).ready(function(){
    $('.btn-delete').click(function(e){
        
        var row = $(this).parents('tr');
        var id = row.data('id');
        
        var form = $('#form-delete');
        var url = form.attr('action').replace(':ID', id);
        var data = form.serialize();
        
        bootbox.confirm(message, function(res){
            if(res==true){
                $.post(url,data,function(result){
                   if(result.removed ==1){
                       row.fadeOut(200);
                   }
                }).fail(function(){
                    alert("Error");
                    row.show();
                });
            }
        });
        
        
    });
 });