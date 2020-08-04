<?php
require_once('board_addr.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>main</title>
    <style>
        fieldset{
            background-color: #eeeeee;
            width: 700px;
        }
        legend{
            background-color: gray;
            color: white;
            padding: 5px 10px;
        }
        input{
            margin: 5px;
        }
        #temp{
            width: 100px;
        }
        #content{
            margin-top: 5px;
            width: 690px;
            height: 300px;
        }
        #submit{
            width: 690px;
        }
    </style>
</head>
<body>

<fieldset>
    <legend> 글쓰기 </legend>
    <form method="post" action=<?php echo boardAddr::WRITE_PROCESS;?>>
        <table>
            <tr>
                <td id="temp"> 제목 </td>
                <td> <input type="text" name="title"/> </td>
            </tr>
            <tr>
                <td id="temp"> 작성자 </td>
                <td> <input type="text" name="username"/> </td>
            </tr>
            <tr>
                <td id="temp"> 비밀번호 </td>
                <td> <input type="text" name="password"/> </td>
            </tr>
        </table>

    <textarea name="content" id="content"></textarea><br>

    <input type="submit" name="submit" id="submit" value="글 쓰 기"/>
    </form>
</fieldset>

</body>
</html>


