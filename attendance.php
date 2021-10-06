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
    $mysqli = new mysqli('***', '***', '***', '***');
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
