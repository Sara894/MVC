<?php

//Название вида совпадает с названием актиона
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class = "result">
    <h1>Главная страница</h1>
    <?for($i=1;$i<=$totalPage;$i++):?>
    <a class = "page" href="/?page=<?=$i;?>" ><?=$i;?>
        <?endfor?></a>
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
<script>
    $(document).ready(function(){
        $(document).on('click','.page',function(event){ //посто обработчик события на клик на элемент с классом .page-link

            event.preventDefault();

            let form = $(this).serialize();
            $.ajax({
                url: $(this).attr("href"), //вставляем данные из свойства data-page (они у нас в ссылках, там и формируем url)
                data: form,
                success: function(data)
                {
                    $("div.result").html(data);
                    window.history.pushState("object or string", "Title", this.url);
                  //  setTimeout(function(){ window.location = '/?page=1'; }, 1000);
                },
            });
        });
    });
</script>

