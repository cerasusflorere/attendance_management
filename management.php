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
    array_multisort($sort_keys, SORT_ASC, $logs);

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
        window.onload = setAllduration;
        let select_duration = document.getElementById('duration');
        let table = document.getElementById('table');
        select_duration.onchange = changeDuration;
        let cells = [];
        let data_number = 0;        

        const logs = JSON.parse('<?php echo $logs; ?>');

        // 期間が変更されたときの動作
        function changeDuration(){
            for(let i = 0; i < data_number; i++){
                const cell = document.getElementById('cell-' + i);
                cell.remove();  // 表示していた来校ログを削除
            }

            data_number = 0;

            // 変更後の期間を取得
            var changeDuration = select_duration.value;

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
            logs.forEach((log) => {
                // 1行追加
                var cellsTr = document.createElement('tr');
                cellsTr.id = 'cell-' + data_number;
                
                // 名前
                var nameTd = document.createElement('td');
                var nameText = document.createTextNode(log['name']);

                // 日付
                var dateTd = document.createElement('td');
                var dateText = document.createTextNode(log['date']);

                // 健康チェック
                var healthTd = document.createElement('td');
                if(log['health'] == 1){
                    var healthText = document.createTextNode('○');
                }

                // 到着時間
                var arrivalTd = document.createElement('td');
                var arrivalText = document.createTextNode(log['arrival_time']);

                // 出立時間
                var departureTd = document.createElement('td');
                var departureText = document.createTextNode(log['departure_time']);

                // 来校場所チェック
                // IN401N
                var IN401Td = document.createElement('td');
                if(log['IN401N'] == 1){
                    var IN401Text = document.createTextNode('○');
                }

                // IN501N
                var IN501Td = document.createElement('td');
                if(log['IN501N'] == 1){
                    var IN501Text = document.createTextNode('○');
                }

                // IN505N
                var IN505Td = document.createElement('td');
                if(log['IN505N'] == 1){
                    var IN505Text = document.createTextNode('○');
                }

                // IN418N
                var IN418Td = document.createElement('td');
                if(log['IN418N'] == 1){
                    var IN418Text = document.createTextNode('○');
                }

                // IN419N
                var IN419Td = document.createElement('td');
                if(log['IN419N'] == 1){
                    var IN419Text = document.createTextNode('○');
                }

                // IN601N
                var IN601Td = document.createElement('td');
                if(log['IN601N'] == 1){
                    var IN601Text = document.createTextNode('○');
                }

                // IN603N
                var IN603Td = document.createElement('td');
                if(log['IN603N'] == 1){
                    var IN603Text = document.createTextNode('○');
                }

                // IN409N
                var IN409Td = document.createElement('td');
                if(log['IN409N'] == 1){
                    var IN409Text = document.createTextNode('○');
                }

                // IN412N
                var IN412Td = document.createElement('td');
                if(log['IN412N'] == 1){
                    var IN412Text = document.createTextNode('○');
                }

                // 医心館その他
                var INotherTd = document.createElement('td');
                if(log['IN_other'] != null){
                    var INotherText = document.createTextNode(log['IN_other']);
                }
                
                // 紫苑館
                var diningTd = document.createElement('td');
                if(log['dining'] == 1){
                    var diningText = document.createTextNode('○');
                }

                // 購買部
                var purchasingTd = document.createElement('td');
                if(log['purchasing'] == 1){
                    var purchasingText = document.createTextNode('○');
                }

                // 図書館/LC
                var libraryTd = document.createElement('td');
                if(log['library'] == 1){
                    var libraryText = document.createTextNode('○');
                }

                // その他その他
                var otherTd = document.createElement('td');
                if(log['other'] != null){
                    var otherText = document.createTextNode(log['other']);
                }

                nameTd.appendChild(nameText);
                cellsTr.appendChild(nameTd);  // 名前

                dateTd.appendChild(dateText);
                cellsTr.appendChild(dateTd);  // 日付 

                if(healthText){
                    healthTd.appendChild(healthText);
                }                
                cellsTr.appendChild(healthTd);  // 健康チェック

                arrivalTd.appendChild(arrivalText);
                cellsTr.appendChild(arrivalTd);  // 到着時間

                departureTd.appendChild(departureText);
                cellsTr.appendChild(departureTd);  // 出立時間

                if(IN401Text){
                    IN401Td.appendChild(IN401Text);
                }                
                cellsTr.appendChild(IN401Td);  // IN401

                if(IN501Text){
                    IN501Td.appendChild(IN501Text);
                }                
                cellsTr.appendChild(IN501Td);  // IN501

                if(IN505Text){
                    healthTd.appendChild(IN505Text);
                }                
                cellsTr.appendChild(IN505Td);  // IN505

                if(IN418Text){
                    IN418Td.appendChild(IN418Text);
                }                
                cellsTr.appendChild(IN418Td);  // IN418

                if(IN419Text){
                    IN419Td.appendChild(IN419Text);
                }                
                cellsTr.appendChild(IN419Td);  // IN419

                if(IN601Text){
                    IN601Td.appendChild(IN601Text);
                }                
                cellsTr.appendChild(IN601Td);  // IN601

                if(IN603Text){
                    IN603Td.appendChild(IN603Text);
                }                
                cellsTr.appendChild(IN603Td);  // IN603

                if(IN409Text){
                    IN409Td.appendChild(IN409Text);
                }                
                cellsTr.appendChild(IN409Td);  // IN409

                if(IN412Text){
                    IN412Td.appendChild(IN412Text);
                }                
                cellsTr.appendChild(IN412Td);  // IN412

                if(INotherText){
                    INotherTd.appendChild(INotherText);
                }                
                cellsTr.appendChild(INotherTd);  // INその他

                if(diningText){
                    diningTd.appendChild(diningText);
                }                
                cellsTr.appendChild(diningTd);  // 紫苑館

                if(purchasingText){
                    purchasingTd.appendChild(purchasingText);
                }                
                cellsTr.appendChild(purchasingTd);  // 購買部

                if(libraryText){
                    libraryTd.appendChild(libraryText);
                }                
                cellsTr.appendChild(libraryTd);  // 図書館/LC

                if(otherText){
                    otherTd.appendChild(otherText);
                }                
                cellsTr.appendChild(otherTd);  // その他


                table.appendChild(cellsTr);
                data_number++;
            })
        }

        // 2週間前が選択されたとき
        function set2weeks(){
            var date_2weeks = new Date();
            date_2weeks.setDate(date_2weeks.getDate()-14); // 2週間前の日付を取得

            let new_logs = [];

            logs.forEach((log) => {
                let new_date = new Date(log['date']);
                if(date_2weeks <= new_date){
                    new_logs.push(log);  // 2週間前の日付より後だったもので新しい配列を作成
                }
            })

            new_logs.forEach((log) => {
                // 1行追加
                var cellsTr = document.createElement('tr');
                cellsTr.id = 'cell-' + data_number;
                
                // 名前
                var nameTd = document.createElement('td');
                var nameText = document.createTextNode(log['name']);

                // 日付
                var dateTd = document.createElement('td');
                var dateText = document.createTextNode(log['date']);

                // 健康チェック
                var healthTd = document.createElement('td');
                if(log['health'] == 1){
                    var healthText = document.createTextNode('○');
                }

                // 到着時間
                var arrivalTd = document.createElement('td');
                var arrivalText = document.createTextNode(log['arrival_time']);

                // 出立時間
                var departureTd = document.createElement('td');
                var departureText = document.createTextNode(log['departure_time']);

                // 来校場所チェック
                // IN401N
                var IN401Td = document.createElement('td');
                if(log['IN401N'] == 1){
                    var IN401Text = document.createTextNode('○');
                }

                // IN501N
                var IN501Td = document.createElement('td');
                if(log['IN501N'] == 1){
                    var IN501Text = document.createTextNode('○');
                }

                // IN505N
                var IN505Td = document.createElement('td');
                if(log['IN505N'] == 1){
                    var IN505Text = document.createTextNode('○');
                }

                // IN418N
                var IN418Td = document.createElement('td');
                if(log['IN418N'] == 1){
                    var IN418Text = document.createTextNode('○');
                }

                // IN419N
                var IN419Td = document.createElement('td');
                if(log['IN419N'] == 1){
                    var IN419Text = document.createTextNode('○');
                }

                // IN601N
                var IN601Td = document.createElement('td');
                if(log['IN601N'] == 1){
                    var IN601Text = document.createTextNode('○');
                }

                // IN603N
                var IN603Td = document.createElement('td');
                if(log['IN603N'] == 1){
                    var IN603Text = document.createTextNode('○');
                }

                // IN409N
                var IN409Td = document.createElement('td');
                if(log['IN409N'] == 1){
                    var IN409Text = document.createTextNode('○');
                }

                // IN412N
                var IN412Td = document.createElement('td');
                if(log['IN412N'] == 1){
                    var IN412Text = document.createTextNode('○');
                }

                // 医心館その他
                var INotherTd = document.createElement('td');
                if(log['IN_other'] != null){
                    var INotherText = document.createTextNode(log['IN_other']);
                }
                
                // 紫苑館
                var diningTd = document.createElement('td');
                if(log['dining'] == 1){
                    var diningText = document.createTextNode('○');
                }

                // 購買部
                var purchasingTd = document.createElement('td');
                if(log['purchasing'] == 1){
                    var purchasingText = document.createTextNode('○');
                }

                // 図書館/LC
                var libraryTd = document.createElement('td');
                if(log['library'] == 1){
                    var libraryText = document.createTextNode('○');
                }

                // その他その他
                var otherTd = document.createElement('td');
                if(log['other'] != null){
                    var otherText = document.createTextNode(log['other']);
                }

                nameTd.appendChild(nameText);
                cellsTr.appendChild(nameTd);  // 名前

                dateTd.appendChild(dateText);
                cellsTr.appendChild(dateTd);  // 日付 

                if(healthText){
                    healthTd.appendChild(healthText);
                }                
                cellsTr.appendChild(healthTd);  // 健康チェック

                arrivalTd.appendChild(arrivalText);
                cellsTr.appendChild(arrivalTd);  // 到着時間

                departureTd.appendChild(departureText);
                cellsTr.appendChild(departureTd);  // 出立時間

                if(IN401Text){
                    IN401Td.appendChild(IN401Text);
                }                
                cellsTr.appendChild(IN401Td);  // IN401

                if(IN501Text){
                    IN501Td.appendChild(IN501Text);
                }                
                cellsTr.appendChild(IN501Td);  // IN501

                if(IN505Text){
                    healthTd.appendChild(IN505Text);
                }                
                cellsTr.appendChild(IN505Td);  // IN505

                if(IN418Text){
                    IN418Td.appendChild(IN418Text);
                }                
                cellsTr.appendChild(IN418Td);  // IN418

                if(IN419Text){
                    IN419Td.appendChild(IN419Text);
                }                
                cellsTr.appendChild(IN419Td);  // IN419

                if(IN601Text){
                    IN601Td.appendChild(IN601Text);
                }                
                cellsTr.appendChild(IN601Td);  // IN601

                if(IN603Text){
                    IN603Td.appendChild(IN603Text);
                }                
                cellsTr.appendChild(IN603Td);  // IN603

                if(IN409Text){
                    IN409Td.appendChild(IN409Text);
                }                
                cellsTr.appendChild(IN409Td);  // IN409

                if(IN412Text){
                    IN412Td.appendChild(IN412Text);
                }                
                cellsTr.appendChild(IN412Td);  // IN412

                if(INotherText){
                    INotherTd.appendChild(INotherText);
                }                
                cellsTr.appendChild(INotherTd);  // INその他

                if(diningText){
                    diningTd.appendChild(diningText);
                }                
                cellsTr.appendChild(diningTd);  // 紫苑館

                if(purchasingText){
                    purchasingTd.appendChild(purchasingText);
                }                
                cellsTr.appendChild(purchasingTd);  // 購買部

                if(libraryText){
                    libraryTd.appendChild(libraryText);
                }                
                cellsTr.appendChild(libraryTd);  // 図書館/LC

                if(otherText){
                    otherTd.appendChild(otherText);
                }                
                cellsTr.appendChild(otherTd);  // その他


                table.appendChild(cellsTr);
                data_number++;
            })
        }


    </script>
</body>
</html>
