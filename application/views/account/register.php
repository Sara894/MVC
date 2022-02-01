<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
<link rel="stylesheet" href="css/style.css">
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<p><h1>Регистрация</h1></p>
<div class = 'container'>
<form  class = "form" id = "reg" >
<p>Имя: 
<input type = 'text' name = 'name' id = 'name' class = 'form-control'></p>
<p id='error-name' class="error-container"></p>
<p>Фамилия: 
<input type = 'text' name = 'surname' id = 'surname' class = 'form-control'></p>
<p id='error-surname' class="error-container"></p>
<p>email: 
<input type = 'email' name = 'email' id = 'email' class ='form-control'></p>
<p id='error-email' class="error-container"></p>
<p>Телефон: 
<input type = 'phone' name = 'phone' id = 'phone' class = 'form-control'></p>
<p id='error-phone' class="error-container"></p>
<p>Адрес: 
<input type = 'text' name = 'address' id = 'address' class ='form-control'></p>
<p id='error-address' class="error-container"></p>
<p>Пароль: 
<input type = 'text' name = 'password' id = 'password' class ='form-control' ></p>
<p id='error-password' class="error-container"></p>
<p>Повторно введите пароль: 
<input type = 'text' name = 'password_2' id = 'password_2' class ='form-control' ></p>
<p id='error-password_2' class="error-container"></p>
<input type = "submit" name = "submit" class = "btn btn-success">Регистрация</button>
<p id='result' class = 'c'></p>
</form>
</div>


<script>

$(document).ready(function(){
    $(document).on("submit","#reg", function(event){

        $('.error-container').html('');
         $('.result-c').html('');
        event.preventDefault();
        let form = $(this).serialize();//serialize — Генерирует пригодное для хранения представление переменной
        $.ajax({
            url:"",
            method: "post",
            data:form,
            dataType: "json",
            success: function(data){
            if (data.status){
                $('form.form').trigger('reset');
                    $('#result').html(data.message);
                window.location = '/';

            } else {
                Object.entries(data.errors).forEach(function(elem){
                    $('#error-'+elem[0]).html(elem[1]);
                    $('#result').html('');

                });

            }

           // $('#result').html('');
            },

        });
    });
});
</script>



