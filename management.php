<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
    <link rel="icon" href="img_news_00.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" rel="stylesheet">
    <title>management</title>
</head>

<?php
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    $mysqli = new mysqli('***', '***', '***', '***');
    if($mysqli->connect_error){
        echo $mysqli->connect_error;
        exit();
    } else {
        $mysqli->set_charset("utf8mb4");
    }
    
     // 成功・エラーメッセージの初期化
     $errors = array();

    // テーブルから情報を取得
    // logsは今後の出力の元、連想配列
    $logs = array();
     try{
        $result = $mysqli->query("SELECT name, date, health, arrival_time, departure_time, IN401N, IN501N, IN505N, IN418N, IN419N, IN603N, IN601N, IN409N, IN412N, IN_other, dining, purchasing, library, other FROM time_and_place");
        while ($row = $result->fetch_assoc()){
            $logs[] = $row;
        }
        $result->close();
    }catch(PDOException $e){
        //トランザクション取り消し
        $pdo -> rollBack();
        $errors['error'] = "もう一度やり直してください。";
        print('Error:'.$e->getMessage());
    }

    // 並び替えたいキーを抽出
    foreach($logs as $key => $value){
        $sort_keys[$key] = $value['date'];
    }

    // 並び替え
    array_multisort($sort_keys, SORT_DESC, $logs);

    $logs = json_encode($logs);
?>

<body>
    <!-- 検索期間選択部分 -->
    <div class='duration-select-area' id='duration-area'>
        <select id='duration'>
            <option value='all'>全期間</option>
            <option value='week2'>2週間</option>
            <option value='month1'>1ヵ月</option>
        </select>
    </div>

    <!-- ダウンロードボタン -->
    <div class='download-button-area'>
        <button class='download-button'>ダウンロード</button>
    </div>

    <!-- ログ表示部分 -->
    <table border="1" id='table'>
        <tr>
            <th colspan="2"></th>
            <th colspan="3"></th>
            <th colspan="10">医心館</th>
            <th colspan="4">その他</th>
        </tr>
        <tr>
            <th>名前</th>
            <th>日付</th>
            <th>健康チェック</th>
            <th>その日医心館に最初に入館した時間</th>
            <th>帰宅のために医心館から退館した時間</th>
            <th>IN401N</th>
            <th>IN501N</th>
            <th>IN505N</th>
            <th>IN418N（小早川）</th>
            <th>IN419N（早見）</th>
            <th>コウモリ舎</th>
            <th>サル・ネズミ舎</th>
            <th>IN409N</th>
            <th>IN412N</th>
            <th>その他</th>
            <th>紫苑館</th>
            <th>購買部</th>
            <th>図書館/LC</th>
            <th>その他</th>
        </tr>
        
    </table>

    
    <script>
        let select_duration = document.getElementById('duration');
        console.log(select_duration);
        let table = document.getElementById('table');
        select_duration.onchange = changeDuration;
        const cell = document.getElementById('cell');

        const logs = JSON.parse('<?php echo $logs; ?>');

        // 期間が変更されたときの動作
        function changeDuration(){
            if(cell){
                cell.remove();
            }
            // 変更後の期間を取得
            var changeDuration = select_duration.value;
            console.log(select_duration.value);
            console.log(changeDuration);

            // 期間によって関数を切り替え
            if(changeDuration == "all"){
                setAllduration();
            }else if(changeDuration == 'week2'){
                set2weeks();
            }else if(changeDuration == 'month1'){
                set1month();
            }
        }

        // 全期間が選択されたとき
        function setAllduration(){
            console.log(logs);
            logs.forEach((log) => {
                console.log(log);
                var cellsTr = document.createElement('tr');
                cellsTr.id = 'cell';

                table.appendChild(cellsTr);

                var nameTd = document.createElement('td');
                var nameText = document.createTextNode(log[0]);
                nameTd.appendChild(nameText);
                cellsTr.appendChild(nameTd);
                
            })
        }


    </script>
</body>
</html>
