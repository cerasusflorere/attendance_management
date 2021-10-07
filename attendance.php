<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
    <link rel="icon" href="img_news_00.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" rel="stylesheet">
    <title>Register</title>
</head>

<?php
    $mysqli = new mysqli('localhost', 'phpuser', 'MysqlPhp', 'attendance_management');
    if($mysqli->connect_error){
        echo $mysqli->connect_error;
        exit();
    } else {
        $mysqli->set_charset("utf8mb4");
    }
    
    // position名を取得
    $drop_positions = array();
    $drop_position = '';
    try{
        $result = $mysqli->query("SELECT position FROM position");
        while ($row = $result->fetch_assoc()){
            $drop_positions[] = $row["position"];
        }
        $result->close();
    }catch(PDOException $e){
        //トランザクション取り消し
        $pdo -> rollBack();
        $errors['error'] = "もう一度やり直してください。";
        print('Error:'.$e->getMessage());
    }

    foreach($drop_positions as $position){
        $drop_position .= "<option value='{$position}'>{$position}</option>";
    }
    

    // 名前を取得
    $drop_name_position = array();
    try{
        $result_name = $mysqli->query("SELECT name,position FROM member");
        while ($row_name = $result_name->fetch_assoc()){
            $drop_name_position[] = $row_name["name"];
            $drop_name_position[] = $row_name["position"];
        }
        $result_name->close();
    }catch(PDOException $e){
        //トランザクション取り消し
        $pdo -> rollBack();
        $errors['error'] = "もう一度やり直してください。";
        print('Error:'.$e->getMessage());
    }
    $drop_name_position = json_encode($drop_name_position);
?> 

<body>
    <div class='register-main-area'>
        <form action='' method='post'>
            <!-- 学年や名前を選ぶ -->
            <div class='select-area'>
                <div>
                    <p>学年等選択してください</p>
                    <select name='position' id='position'>
                        <option value=''>選択してください</option>
                        <?php 
                            echo $drop_position; ?>
                    </select>
                </div>
                <div>
                    <p>名前を選択してください</p>
                    <select name='name' id='name'>
                        <option value=''>選択してください</option>
                    </select>
                </div>
            </div>
        
            <!-- 来校情報 -->
            <div class='answers-area'>
                <!-- 来校日時登録 -->
                <div class='answers-each-area'>
                    <input type='date' name='date' value="2021-10-01">
                    <input type='time' name='arrival_time' value='09:00'>
                    <input type='time' name='departure_time' value='17:00'>
                </div>

                <!-- 健康チェック -->
                <div class='answers-each-area'>
                    <div class='answer-each'>
                        <input type='checkbox' id='health' class='attendance-check'>
                        <label for='health' class='attendance-label'>健康（37.5℃以下）</label>
                    </div>
                </div>

                <!-- 来校場所チェック -->
                <div class='answers-each-area'>
                    <span>医心館</span>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN401' class='attendance-check'>
                        <label for='IN401' class='attendance-label'>IN401N</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN501' class='attendance-check'>
                        <label for='IN501' class='attendance-label'>IN501N</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN505' class='attendance-check'>
                        <label for='IN505' class='attendance-label'>IN505N</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN418' class='attendance-check'>
                        <label for='IN418' class='attendance-label'>IN4I8N（小早川）</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN419' class='attendance-check'>
                        <label for='IN419' class='attendance-label'>IN419N（早見）</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN603' class='attendance-check'>
                        <label for='IN603' class='attendance-label'>コウモリ舎</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN601' class='attendance-check'>
                        <label for='IN601' class='attendance-label'>サル・ネズミ舎</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN409' class='attendance-check'>
                        <label for='IN409' class='attendance-label'>IN409N</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='IN412' class='attendance-check'>
                        <label for='IN412' class='attendance-label'>IN412N</label>
                    </div>
                    <div class='answer-each'>
                        <label class='attendance-label'>その他</label>
                        <input type='text' id='IN_other' class='attendance-text'>
                    </div>
                </div>

                <div class='answers-each-area'>
                    <span>その他</span>
                    <div class='answer-each'>
                        <input type='checkbox' id='dining' class='attendance-check'>
                        <label for='dining' class='attendance-label'>紫苑館</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='purchasing' class='attendance-check'>
                        <label for='purchasing' class='attendance-label'>購買部</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='library' class='attendance-check'>
                        <label for='library' class='attendance-label'>図書館</label>
                    </div>
                    <div class='answer-each'>
                        <input type='checkbox' id='other' class='attendance-check'>
                        <label for='other' class='attendance-label'>その他</label>
                    </div>
                </div>
            </div>
            <input type='submit' class='submit-button' value='送信'>
        </form>
    </div>

    <script>
        let select_name = document.getElementById('name');
        let select_position = document.getElementById('position');
        select_position.onchange = changePosition;
        const names_positions = JSON.parse('<?php echo $drop_name_position; ?>');


        // 学年が変更されたときの動作
        function changePosition(){
            // 変更後の学年を取得
            var changePosition = select_position.value;

            // 学年によって関数を切り替え
            if(changePosition == "Staff"){
                setStaff();
            }else if(changePosition == '博士研究員'){
                setPostdoc();
            }else if(changePosition == 'D'){
                setDoctor();
            }else if(changePosition == 'M2'){
                setMaster2();
            }else if(changePosition == 'M1'){
                setMaster1();
            }else if(changePosition == 'B4'){
                setBachelor();
            }else if(changePosition == '研究生'){
                setResearcher();
            }else if(changePosition == '共同研究員'){
                setCollab();
            }else{
                select_name.innerHTML ='';
                var option = document.createElement('option');
                option.value = '';
                option.text = '選択してください';

                select_name.appendChild(option);
            }
        }

        // Staffが選択されたとき
        function setStaff(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == 'Staff'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // PostDocが選択されたとき
        function setPostdoc(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == '博士研究員'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // Doctorが選択されたとき
        function setDoctor(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == 'D'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // M2が選択されたとき
        function setMaster2(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == 'M2'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // M1が選択されたとき
        function setMaster1(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == 'M1'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // B4が選択されたとき
        function setBachelor(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == 'B4'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // 研究生が選択されたとき
        function setResearcher(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == '研究生'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        // 共同研究員が選択されたとき
        function setCollab(){
            // 名前の選択肢を空にする
            select_name.textContent = null;
            
            // セットする
            let names = [];
            let number = 0;
            for(let i in names_positions){
                if(names_positions[i] == '共同研究員'){
                    names[number] = names_positions[i-1];
                    number++;
                }
            }

            names.forEach((name) => {
                var nameOption = document.createElement('option');
                nameOption.value = name;
                nameOption.text = name;
                
                select_name.appendChild(nameOption);
            });
        }

        
    </script>
</body>
</html>   
