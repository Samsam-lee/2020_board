<?php
require_once('board_addr.php');

$num = $_POST['board_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>main</title>
    <style>
        fieldset{
            background-color: #eeeeee;
            width: 400px;
        }
        legend{
            background-color: gray;
            color: white;
            padding: 5px 10px;
        }
        input{
            margin: 5px;
        }
    </style>
</head>
<body>

<fieldset>
    <legend> 글삭제 : 글 번호 <?php echo $_POST['board_id'];?></legend>

    <form method="post" action=<?php echo boardAddr::DELETE_PROCESS;?>>
        <label for="user_passwd"> 비밀번호 : </label>
        <input type="text" name="user_passwd" id="user_passwd"/><br>
        <input type="hidden" name="board_id" id="board_id" value="<?php echo $num;?>">
        <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_POST['parent_id']; ?>"/>
        <input type="submit" name="submit" id="submit" value="글 삭제하기"/>
    </form>
</fieldset>

</body>
</html>