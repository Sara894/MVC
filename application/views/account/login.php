


<form method = "POST" class = "form" id = "log">
<p><h1>Вход</h1></p>
<div class = "container">

<p>email: 
<input type = 'email' name = 'email' id= 'email' class = "form-control"></p>
<p>Пароль: 
<input type = 'password' name = 'password' id= 'password' class = "form-control"></p>
<b><button type = "submit" class = "btn btn-success">Вход</button></b>
</div>
<p id="errors"></p>
</form>

<script>

$(document).ready(function(){

    $(document).on("submit","#log",function(event){

        event.preventDefault();//останавливаем обработку кнопки(вообще отмена действия браузера)

        let form = $(this).serialize();//serialize — Генерирует пригодное для хранения представление переменной
        
        $.ajax({
            url:'',
            method:'post',
            data:form,
            dataType: "json",
            success: function(data){
                if(data.status) {
                    window.location = '/';
                } else {
                    Object.entries(data.errors).forEach(function(elem) {
                        $('#errors').html(elem[1]);
                    });
                     $('#errors').html(data.message);
                }
            },
        });
    });
});
</script>