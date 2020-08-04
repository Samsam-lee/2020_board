<?php
require_once('board_addr.php');
require_once('connectDB.php');

// 패스워드 값 받아오기
$user_passwd = htmlspecialchars($_POST['user_passwd']);
$board_id    = $_POST['board_id'];

// view 로 돌아갈 때 원래 게시글 (댓글 삭제 구현 시)
$parent_id   = $_POST['parent_id'];

// 디비 연결
$conn   = DB_Connect();

// 수정 할 게시글의 비밀번호 가져오기
$sql    = "SELECT user_passwd FROM mybulletin WHERE board_id = $board_id";
$result = $conn->query($sql);
$record = $result->fetch_assoc();

// 비밀번호가 일치 할 때
if(password_verify($user_passwd, $record['user_passwd'])){
    $sql = "DELETE FROM mybulletin WHERE board_id = $board_id";
    $conn->query($sql);

    if($parent_id != null){
        echo "
            <script>
                alert('글이 정상적으로 삭제되었습니다.\\n게시글로 돌아갑니다.');
                document.location.href='".boardAddr::VIEW."?board_id=".$parent_id."';
            </script>
            ";
    } else {
        echo "
        <script>
            alert('글이 정상적으로 삭제되었습니다.\\n리스트로 돌아갑니다.');
            document.location.href='" . boardAddr::LIST . "';
        </script>
        ";
    }
}
// 빈 값이 들어왔을 때
else if($user_passwd == null){
    if($parent_id != null){
        echo "
            <script>
                alert('입력란을 다 채워주세요.\\n게시글로 돌아갑니다.');
                document.location.href='".boardAddr::VIEW."?board_id=".$parent_id."';
            </script>
            ";
    } else {
        echo "
        <script>
            alert('입력란을 다 채워주세요.\\n리스트로 돌아갑니다.');
            document.location.href='" . boardAddr::LIST . "';
        </script>
        ";
    }
}
// 비밀번호가 일치하지 않을 때
else {
    if ($parent_id != null) {
        echo "
            <script>
                alert('비밀번호가 일치하지 않습니다.\\n게시글로 돌아갑니다.');
                document.location.href='" . boardAddr::VIEW . "?board_id=" . $parent_id . "';
            </script>
            ";
    } else {
        echo "
        <script>
            alert('비밀번호가 일치하지 않습니다.\\n리스트로 돌아갑니다.');
            document.location.href='" . boardAddr::LIST . "';
        </script>
        ";
    }
}

