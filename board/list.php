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
        table {
            border: 1px solid #444444;
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #444444;
            padding: 10px;
        }

        p, #find {
            text-align: center;
        }

        #red {
            color: red;
        }

        #write, #back {
            float: right;
            margin-top: 10px;
            margin-right: 10px;
            width: 90px;
            height: 30px;
        }

        #table_title {
            width: 60%;
        }

        #search {
            width: 300px;
        }
    </style>
</head>
<body>

<table id="myTable">
    <tr>
        <th colspan="5" style="background-color: #c9c9c9"> SH Lee 게시판</th>
    </tr>
    <tr>
        <th> 번호</th>
        <th id="table_title"> 제목</th>
        <th> 작성자</th>
        <th> 조회수</th>
        <th> 날짜</th>
    </tr>

    <?php
    // 페이지, default 값 1
    $page = ($_GET['page']) ? $_GET['page'] : 1;


    // 검색해서 받아온 값
    $opt = $_GET['opt'];
    $searchText = $_GET['search'];

    // sql문에 추가할 문장
    $plusSql = "";

    // 검색 옵션에 따라 sql 문 지정
    switch ($opt) {
        case "title":
            $plusSql = "AND title LIKE '%$searchText%'";
            break;
        case "contents":
            $plusSql = "AND contents LIKE '%$searchText%'";
            break;
        case "name":
            $plusSql = "AND user_name LIKE '%$searchText%'";
            break;
        case "titleContents":
            $plusSql = "AND title LIKE '%$searchText%' OR contents LIKE '%$searchText%'";
            break;
        default:
            break;
    }

    // 첫 게시글 시작 지점
    $messageNum = (($page - 1) * boardAddr::BOARD_NUM);

    // 총 게시글 갯수
    $num = 0;

    // 디비 사용
    $conn = DB_Connect();

    // 총 게시글 갯수
    $sql = "SELECT board_id FROM mybulletin WHERE board_pid = 0 $plusSql";
    $data = $conn->query($sql);
    while ($data->fetch_assoc()) {
        $num++;
    }

    // 페이지 갯수
    $pageNum = ceil($num / boardAddr::BOARD_NUM);

    // sql문 ( 테이블 출력 용 )
    $sql = "SELECT board_id, title, user_name, hits, DATE_FORMAT(reg_date,'%Y-%m-%d') 'reg_date'
            FROM mybulletin WHERE board_pid = 0 $plusSql 
            ORDER BY board_id DESC LIMIT $messageNum, " . boardAddr::BOARD_NUM;

    // sql문 사용
    $result = $conn->query($sql);

    // 테이블 출력
    while ($record = $result->fetch_object("board_info")) {
        echo "
            <tr style='text-align:center'>
                <td id='board_id'> $record->board_id </td>
                <td> <a href='".boardAddr::VIEW."?board_id=".$record->board_id."&opt=".$opt."&search=".$searchText."&page=".$page."'> $record->title </a></td>
                <td> $record->user_name </td>
                <td> $record->hits </td>
                <td> $record->reg_date </td>
            </tr>    
            ";
    }

    // DB 에서 받아온 정보
    class board_info
    {
        // board_id    :
        // board_pid   :
        // user_name   : ""
        // user_passwd : ""
        // title       : ""
        // contents    : ""
        // hits        :
        // reg_date    : ""
    }

    ?>
</table>


<!--검색 기능 구현-->
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="find">
    <span> 검색 키워드 </span>
    <select name="opt">
        <option value="title"> 제목</option>
        <option value="contents"> 내용</option>
        <option value="name"> 작성자</option>
        <option value="titleContents"> 제목+내용</option>
    </select>
    <input type="search" name="search" id="search"/>
    <input type="submit" name="submit" id="submit" value="검색"/>
</form>

<!--페이지-->
<p>
    <!--이전 블럭으로 넘어가기-->
    <?php if ($page > boardAddr::PAGE_BLOCK) { ?>
        <a href="<?php echo $_SERVER['$PHP_SELF']; ?>
        ?page=<?php echo floor(($page - 1) / boardAddr::PAGE_BLOCK - 1) * boardAddr::PAGE_BLOCK + 1; ?>&opt=<?php echo $opt; ?>&search=<?php echo $searchText; ?>"
        ><<&nbsp</a>
        <?php
    }
    // 페이지 넘어가는 하이퍼링크 만들기
    for ($i = floor(($page - 1) / boardAddr::PAGE_BLOCK) * boardAddr::PAGE_BLOCK + 1;
    $i <= floor(($page - 1) / boardAddr::PAGE_BLOCK) * boardAddr::PAGE_BLOCK + boardAddr::PAGE_BLOCK;
    $i++){
    if ($i == $pageNum + 1) break;
    if ($page == $i){ ?>
        <!--누른 페이지의 색상이 빨간색으로 되게 css 설정 (id 값으로)-->
        <a id="red" href="<?php echo $_SERVER['$PHP_SELF'];?>
        ?page=<?php echo $i;?>&opt=<?php echo $opt;?>&search=<?php echo $searchText;?>">
            <?php echo $i; ?>&nbsp
        </a>
        <?php }
    else { ?>
    <a href="<?php echo $_SERVER['$PHP_SELF']; ?>
    ?page=<?php echo $i; ?>&opt=<?php echo $opt; ?>&search=<?php echo $searchText; ?>">
        <?php echo $i; ?>&nbsp
        <?php }
        } ?>
        <!--다음 블럭으로 넘어가기-->
        <?php if ($page <= boardAddr::PAGE_BLOCK * floor(($pageNum - 1) / boardAddr::PAGE_BLOCK)) { ?>
            <a href="<?php echo $_SERVER['$PHP_SELF']; ?>
        ?page=<?php echo floor(($page - 1) / boardAddr::PAGE_BLOCK + 1) * boardAddr::PAGE_BLOCK + 1; ?>&opt=<?php echo $opt; ?>&search=<?php echo $searchText; ?>"
            >>></a>
        <?php } ?>
</p>

<!--글쓰기 버튼-->
<form method="post" action=<?php echo boardAddr::WRITE; ?>>
    <input type="submit" name="write" id="write" value="글쓰기"/>
</form>
<!--리스트 돌아가기 버튼-->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="submit" name="back" id="back" value="리스트" style="visibility: hidden"/>
</form>


</body>
</html>

<?php
// 검색 버튼을 눌렀을 때 리스트 버튼 보이기
if ($opt != "") {
    echo "
            <script>
                let temp = document.getElementById('back');
                temp.style.visibility = 'visible';
            </script>
            ";
}
?>
