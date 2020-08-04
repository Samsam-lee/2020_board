<?php
require_once('connectDB.php');
require_once('board_addr.php');

$title = $_POST['title'];
$name = $_POST['username'];
$pwd = $_POST['password'];
$content = $_POST['content'];

// 게시글 무결성 검사
if($title == null || $name == null || $pwd == null || $content == null
    || !isset($title) || !isset($name) || !isset($pwd) || !isset($content)){
    echo "
        <script> 
            alert('게시글 작성을 제대로 완료 하지 않으셨습니다.\\n리스트 페이지로 돌아갑니다.');
            document.location.href='".boardAddr::LIST."';
        </script>
        ";
}
// 게시글이 제대로 작성 되었을 때
else{

    // HTML 태그 제거
    $receivedTitle = htmlspecialchars($title);
    $receivedName = htmlspecialchars($name);
    $receivedPassword = htmlspecialchars($pwd);
    $receivedContent = htmlspecialchars($content);

    // 비밀번호 암호화
    $password = password_hash($receivedPassword, PASSWORD_DEFAULT);

    callDB($receivedTitle, $receivedName, $password, $receivedContent);
    echo "
        <script> 
            alert('게시글 작성 완료!');
            document.location.href='".boardAddr::LIST."';
        </script>
        ";
}



// 디비 사용
function callDB($title, $name, $password, $content)
{
    $conn   = DB_Connect();
    $sql    = "INSERT INTO mybulletin VALUES(0, 0, '$name', '$password', '$title', '$content', 0, now())";

    // sql문 사용
    $conn->query($sql);
}

