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

<div>
   <form id = "pag" method ="POST" class = "page"><?for($i=1;$i<=$totalPage;$i++):?>


           <button type = "submit" id="pag" name="page" value = <?=$i;?>><?=$i;?></button>
       <?endfor?>
   </form>

</div>

<script>
    $(document).ready(function(){
        $(document).on('submit','#pag',function(event){
            event.preventDefault();
            let form = $(this).serialize();
            $.ajax({
                url:$(this).attr("page"),
                method:"post",
                data:{
                    page:page
                }
                success: function(data){
                    $("div.result").html(data);
                },
            });
        });
    });
</script>

