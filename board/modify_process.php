<?php
require_once('board_addr.php');
require_once('connectDB.php');

// 수정할 값들 받아오기 (modify.php)
$board_id    = $_POST['board_id'];
$title       = htmlspecialchars($_POST['title']);
$user_name   = htmlspecialchars($_POST['user_name']);
$user_passwd = htmlspecialchars($_POST['password']);
$contents    = htmlspecialchars($_POST['content']);

// 디비 연결
$conn   = DB_Connect();

// 수정 할 게시글의 비밀번호 가져오기
$sql    = "SELECT user_passwd FROM mybulletin WHERE board_id = $board_id";
$result = $conn->query($sql);
$record = $result->fetch_assoc();

// 비밀번호가 일치 할 때
if(password_verify($user_passwd, $record['user_passwd'])){
    $sql = "UPDATE mybulletin SET title = '$title', user_name = '$user_name', contents = '$contents' 
            WHERE board_id = $board_id";
    $conn->query($sql);

    echo "
        <script>
            alert('게시글 수정이 완료 되었습니다.');
            document.location.href='".boardAddr::LIST."';
        </script>
        ";
}
// 빈 값이 들어왔을 때
else if($contents == null || $title == null || $user_name == null || $user_passwd == null){
    echo "
        <script> 
            alert('입력란을 다 채워주세요.\\n 리스트로 돌아갑니다.');
            document.location.href='".boardAddr::LIST."';
        </script>
        ";
}
// 비밀번호가 일치하지 않을 때
else{
    echo "
        <script> 
            alert('비밀번호가 일치하지 않습니다.\\n 리스트로 돌아갑니다.');
            document.location.href='".boardAddr::LIST."';
        </script>
        ";
}
