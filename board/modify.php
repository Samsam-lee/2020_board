<?php
require_once('board_addr.php');
require_once('connectDB.php');

// 게시글 번호
$num = $_POST['board_id'];

// 디비 연결
$conn   = DB_Connect();

// 수정 할 게시글의 내용 가져오기
$sql    = "SELECT board_id, title, user_name, user_passwd, contents FROM mybulletin WHERE board_id = $num";
$result = $conn->query($sql);
$record = $result->fetch_object("board_info");

// 가져온 데이터
class board_info
{
    // board_id    :
    // user_name   : ""
    // user_passwd : ""
    // title       : ""
    // contents    : ""
}
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
            text-align: center;
        }
        form{
            display: inline;
        }
        #temp{
            width: 100px;
        }
        #content{
            margin-top: 5px;
            width: 690px;
            height: 300px;
        }
        #in{
            width: 500px;
        }
    </style>
</head>
<body>


<fieldset>
    <legend> 글수정 : 글 번호 <?php echo $num;?></legend>

    <form method="post" action=<?php echo boardAddr::MODIFY_PROCESS;?>>
        <table>
            <tr>
                <td id="temp"> 제목 </td>
                <td> <input type="text" name="title" id="in" value="<?php echo $record->title; ?>"/> </td>
            </tr>
            <tr>
                <td id="temp"> 작성자 </td>
                <td> <input type="text" name="user_name" id="in" value="<?php echo $record->user_name; ?>"/> </td>
            </tr>
            <tr>
                <td id="temp"> 비밀번호 </td>
                <td> <input type="text" name="password" id="in"/> </td>
            </tr>
        </table>

        <textarea name="content" id="content"><?php echo $record->contents; ?></textarea><br>

        <input type="hidden" name="board_id" value="<?php echo $record->board_id; ?>"/>

        <input type="submit" name="submit" id="modify" value="글수정"/>
    </form>

    <form method="post" action=<?php echo boardAddr::LIST;?>>
        <input type="submit" name="submit" id="list" value="글목록"/>
    </form>
</fieldset>

</body>
</html>