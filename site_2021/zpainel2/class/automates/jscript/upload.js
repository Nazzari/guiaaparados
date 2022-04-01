$(document).on('change','#arquivo',function(){
    var form_data = new FormData();                  
    form_data.append('file', $('#arquivo').prop('files')[0]);
    form_data.append('nome_campo', $("#nome_campo").val());
    $.ajax({
        url:'../class/inc.upload.php',
          method:'POST',
          dataType: 'text',
          data:form_data,
          contentType:false,
          cache:false,
          processData:false,
          beforeSend:function(){
            $('#msg').html('Enviando......');
          },
          success:function(data){
            console.log(data);
            var msg = "Enviado com sucesso!";
            document.getElementById("msg").style.color = "#039c38";
            document.getElementById("msg").style.fontWeight = "700";
            $('#msg').html(data.concat(msg));
          }
     });
});