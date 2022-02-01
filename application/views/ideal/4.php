<?php

//Название вида совпадает с названием актиона
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<h1>Главная страница</h1>
<div class = "result">
<table border="1">
    <thead>
    <tr>
        <td>id</td>
        <td>Email</td>
        <td>Имя</td>
        <td>Фамилия</td>
        <td>Телефон</td>
        <td>Адрес</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($vars['users'] as $user): ?>
        <tr>
            <td><?= $user->id ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->name ?></td>
            <td><?= $user->surname ?></td>
            <td><?= $user->phone ?></td>
            <td><?= $user->address ?></td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>
</div>

<div class = "page">
    <form>
        <?for($i=1;$i<=$totalPage;$i++):?>
            <button type = "submit" id="pag" class = "page" name="page"  >   <a href="/?page=<?=$i;?>"><?=$i;?></a>  </button>
 
        <?endfor?>
    </form>


</div>
<script>
    $(document).ready(function(){
        $(document).on('click','submit.page',function(event){ //посто обработчик события на клик на элемент с классом .page-link
            event.preventDefault();
            let form = $(this).serialize();
            $.ajax({
                url: $(this).attr("page"), //вставляем данные из свойства data-page (они у нас в ссылках, там и формируем url)
                data: form,
                success: function(data)
                {
                    $("div.result").html(data);
                },
            });
        });
    });
</script>

