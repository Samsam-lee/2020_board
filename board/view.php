<?php
require_once('board_addr.php');
require_once('connectDB.php');

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
        table {
            border: 1px solid #444444;
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }
        th, td {
            border: 1px solid #444444;
            padding: 10px;
        }
        input{
            margin: 5px;
        }
        form{
            display: inline;
        }
        #content{
            margin-top: 5px;
            width: 690px;
            height: 300px;
        }
        #temp{
            width: 100px;
        }
        #comment, #name, #password {
            width: 400px;
        }
    </style>
</head>
<body>

<?php
// 디비연결
$conn   = DB_Connect();

// 게시글 넘버 받아오기
if($_GET['board_id'] != null){
    // 리스트에서 받아옴
    $num = $_GET['board_id'];

    // 조회 수 1 증가
    $sql    = "UPDATE mybulletin SET hits = hits + 1 WHERE board_id = $num";
    $conn->query($sql);

} else{
    $num = $_POST['id'];
}

// 보여줄 데이터 가져오기
$sql    = "SELECT title, user_name, reg_date, hits, contents FROM mybulletin WHERE board_id = $num";
$result = $conn->query($sql);
$record = $result->fetch_object("board_info");

// 가져온 데이터
class board_info
{
    // user_name   : ""
    // title       : ""
    // contents    : ""
    // hits        :
    // reg_date    : ""
}

?>

<fieldset>
    <legend> 글보기 글 번호 <?php echo $num;?> </legend>
        <table>
            <tr>
                <td id="temp"> 제목 </td>
                <td> <?php echo $record->title; ?> </td>
            </tr>
            <tr>
                <td id="temp"> 작성자 </td>
                <td> <?php echo $record->user_name; ?> </td>
            </tr>
            <tr>
                <td id="temp"> 작성시간 </td>
                <td> <?php echo $record->reg_date; ?> </td>
            </tr>
            <tr>
                <td id="temp"> 조회수 </td>
                <td> <?php echo $record->hits; ?> </td>
            </tr>
        </table>
        <textarea name="content" id="content" readonly><?php echo $record->contents;?></textarea><br>

    <form method="get" action="<?php echo boardAddr::LIST;?>">
        <input type="hidden" name="opt" id="opt" value="<?php echo $_GET['opt'];?>"/>
        <input type="hidden" name="search" id="search" value="<?php echo $_GET['search'];?>"/>
        <input type="hidden" name="page" id="page" value="<?php echo $_GET['page'];?>"/>
        <input type="submit" name="submit" id="list" value="글목록"/>
    </form>

    <form method="post" action="<?php echo boardAddr::DELETE;?>">
        <input type="hidden" name="board_id" value="<?php echo $num; ?>"/>
        <input type="submit" name="submit" id="delete" value="글삭제"/>
    </form>

    <form method="post" action="<?php echo boardAddr::MODIFY;?>">
        <input type="hidden" name="board_id" value="<?php echo $num; ?>"/>
        <input type="submit" name="submit" id="modify" value="글수정"/>
    </form>

    <p> </p>

<!--댓글 구현-->
    <table>
        <tr><th colspan="4"> 댓글 </th></tr>
        <tr>
            <td> 코멘트 </td>
            <td colspan="3">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                    <input type="text" name="comment" id="comment" /></td>
        </tr>
        <tr>
            <td> 작성자 </td>
            <td colspan="3"> <input type="text" name="name" id="name" /></td>
        </tr>
        <tr>
            <td> 비밀번호 </td>
            <td colspan="3"> <input type="text" name="password" id="password" /></td>
        </tr>
        <tr> <td colspan="4">
                <input type="hidden" name="id" id="id" value="<?php echo $num; ?>"/>
                <input type="submit" name="writeComment" id="writeComment" value="댓글쓰기"/>
                </form></td></tr>

        <tr>
            <th> 작성자 </th>
            <th> 코멘트 </th>
            <th> 작성일 </th>
            <th> 삭제 </th>
        </tr>

<!-- 댓글 불러오기 -->
<?php
$comment  = htmlspecialchars($_POST['comment']);
$name     = htmlspecialchars($_POST['name']);
$password = htmlspecialchars($_POST['password']);

$passwd   = password_hash($password, PASSWORD_DEFAULT);

// 댓글 디비에 추가
if($comment != null || $name != null || $password != null) {
    $sql = "INSERT INTO mybulletin VALUES(0, $num, '$name', '$passwd', '', '$comment', 0, now())";
    $conn->query($sql);
}

// 보여줄 데이터 가져오기
$sql    = "SELECT board_id, user_name, DATE_FORMAT(reg_date,'%Y-%m-%d') 'reg_date', contents 
            FROM mybulletin WHERE board_pid = $num";
$result = $conn->query($sql);

while($record = $result->fetch_object("comment_info")){
    echo "
        <tr>
            <td> $record->user_name</td>
            <td> $record->contents</td>
            <td> $record->reg_date</td>
            <td> <form method='post' action='".boardAddr::DELETE."'>
            <input type='hidden' name='parent_id' id='parent_id' value='".$num."'/>
            <input type='hidden' name='board_id' id='board_id' value='".$record->board_id."'/>
            <input type='submit' name='del' id='del' value='삭제'/>
            </form> </td>
        </tr>
        ";
}

// 가져온 데이터
class comment_info
{
    // board_id
    // user_name
    // user_passwd
    // contents
    // reg_date
}
?>

    </table>
</fieldset>


</body>
</html>