<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
    <link rel="icon" href="img_news_00.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" rel="stylesheet">
    <title>Setting</title>
</head>
<?php
    session_start();
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
    // logsは今後の出力の元、オブジェクトが入った配列
    $logs_positions = array();
    $positions = array();
    $logs_members = array();

    // positionを取得
    try{
        $result = $mysqli->query("SELECT id, name, position, studentID, year FROM member");
        while ($row = $result->fetch_assoc()){
            $logs_position[] = $row;
        }
        $result->close();
    }catch(PDOException $e){
        //トランザクション取り消し
        $pdo -> rollBack();
        $errors['error'] = "もう一度やり直してください。";
        print('Error:'.$e->getMessage());
    }

    // 名前を取得
    try{
        $result = $mysqli->query("SELECT id, name, position, studentID, year FROM member");
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
    
    $logs_position = json_encode($logs_position);
    $logs = json_encode($logs);
?>

<body>
    <!-- タブ表示 -->
    <div class='tabs'>
        <input id='active' type='radio' name='tab_item' onclick='findActivemembers()'checked>
        <label class='tab_item' for='active'>現在のメンバー</label>
        <input id='former' type='radio' name='tab_item'onclick='findFormermembers()'>
        <label class='tab_item' for='former'>過去のメンバー</label>
        
        <!-- タブ中身 -->
        <!-- 現在のメンバー -->
        <div class='tab_content' id='active_members'></div>
        <!-- 過去のメンバー -->
        <div class='tab_content former_content' id='former_members'>
            <table class='setting-tab' id='table_former' border='1' style='border-collapse: collapse'></table>
        </div>
    </div>

    <!-- 編集画面 -->
    <div class='edit-modal-wrapper' id='edit-modal'>
        <a href='#!' class='edit-overlay'></a>      
        <div class='edit-window'>
            <a href="#!" class="edit-close">×</a>
            <div class='edit-text' id='editlist'>
                <!-- 編集情報 -->
            </div>
        </div>      
    </div>
</body>

<script>
    window.onload = findActivemembers;
    var members = JSON.parse('<?php echo $logs; ?>'); //JSONデコード
    const active_members = document.getElementById('active_members');      
    const table_former = document.getElementById('table_former');
    const edit_modal = document.querySelector('.edit-modal-wrapper');
    const editArea = document.getElementById('editlist');

    // members配列を各position毎に分割
    var members_Staff = [];
    var members_Postdoc = [];
    var members_Doctor = [];
    var members_Master2 = [];
    var members_Master1 = [];
    var members_Bachelor = [];
    var members_Researcher  = [];
    var members_Collab = [];
    var members_Former = [];
    members.forEach((member) => {
        if(member['position'] == 'Staff'){
            members_Staff.push(member);
        }else if(member['position'] == '博士研究員'){
            members_Postdoc.push(member);
        }else if(member['position'] == 'D'){
            members_Doctor.push(member);
        }else if(member['position'] == 'M2'){
            members_Master2.push(member);
        }else if(member['position'] == 'M1'){
            members_Master1.push(member);
        }else if(member['position'] == 'B4'){
            members_Bachelor.push(member);
        }else if(member['position'] == '研究生'){
            members_Researcher.push(member);
        }else if(member['position'] == '共同研究員'){
            members_Collab.push(member);
        }else if(member['position'] == '過去メンバー'){
            members_Former.push(member);
        }
    })
    
    // members配列を並び替え
    function array_members_ID(a, b){
        return (a.studentID < b.studentID) ? - 1 : 1;
    }
    members_Doctor.sort(array_members_ID);
    members_Master2.sort(array_members_ID);
    members_Master1.sort(array_members_ID);
    members_Bachelor.sort(array_members_ID);
    members_Former.sort((a, b) => b.year - a.year);

    // 表示する
    function addToList(new_members, table){
        let new_number = 1;

        new_members.forEach((member) => {
            // 1行追加
            const cellsTr = document.createElement('tr');
            cellsTr.id = 'cell-' + member.id;

            // 番号
            const numberTd = document.createElement('td');
            numberTd.className = 'numberTd';
            const numberDiv = document.createElement('div');
            numberDiv.innerText = new_number;

            // 名前
            const nameTd = document.createElement('td');
            nameTd.className = 'nameTd';
            const nameDiv = document.createElement('div');
            nameDiv.innerText = member.name;

            // 編集ボタン
            const editTd = document.createElement('td');
            editTd.className = 'buttonTd';
            const editForm = document.createElement('form'); // 編集ボタン
            editForm.className = 'edit-show-button';
            editForm.action = '#edit-modal'
            editForm.method = 'post';
        
            const editA = document.createElement('a'); 
            editA.href = "#edit-modal";
            editA.setAttribute('name', 'edit_button');
            editA.onclick = function() {
                showEditData(member.id, member.name, member.position, member.studentID, member.year, new_members);
            }
        
            const editI = document.createElement('i'); // 編集ボタンアイコン
            editI.className = 'fas fa-edit worksicon fa-fw'; 
            
            // 表示させる
            numberTd.appendChild(numberDiv);
            cellsTr.appendChild(numberTd); // 番号

            nameTd.appendChild(nameDiv);
            cellsTr.appendChild(nameTd);  // 名前

            editTd.appendChild(editForm);
            editForm.appendChild(editA);
            editA.appendChild(editI); 
            cellsTr.appendChild(editTd); // 編集ボタン
            
            table.appendChild(cellsTr);
            new_number ++;
        })             
    }

    // 現在のメンバーを探す
    function findActivemembers(){    
        active_members.innerHTML ='';
       
        const staffDiv = document.createElement('div');
        staffDiv.className = 'position_name';
        staffDiv.innerText = 'Staff';

        const staffTable = document.createElement('table');
        staffTable.className = 'setting-tab';
        staffTable.border = '1';
        staffTable.style = "border-collapse: collapse";
        
        active_members.appendChild(staffDiv);
        active_members.appendChild(staffTable);
        addToList(members_Staff, staffTable);

        // 博士研究員      
        const postdocDiv = document.createElement('div');
        postdocDiv.className = 'position_name';
        postdocDiv.innerText = '博士研究員';

        const postdocTable = document.createElement('table');
        postdocTable.className = 'setting-tab';
        postdocTable.border = '1';
        postdocTable.style = "border-collapse: collapse";
        
        active_members.appendChild(postdocDiv);
        active_members.appendChild(postdocTable);
        addToList(members_Postdoc, postdocTable);

        // Doctor      
        const doctorDiv = document.createElement('div');
        doctorDiv.className = 'position_name';
        doctorDiv.innerText = '博士後期課程';

        const doctorTable = document.createElement('table');
        doctorTable.className = 'setting-tab';
        doctorTable.border = '1';
        doctorTable.style = "border-collapse: collapse";
        
        active_members.appendChild(doctorDiv);
        active_members.appendChild(doctorTable);
        addToList(members_Doctor, doctorTable);

        // Master2      
        const master2Div = document.createElement('div');
        master2Div.className = 'position_name';
        master2Div.innerText = '博士前期課程2年生';

        const master2Table = document.createElement('table');
        master2Table.className = 'setting-tab';
        master2Table.border = '1';
        master2Table.style = "border-collapse: collapse";
        
        active_members.appendChild(master2Div);
        active_members.appendChild(master2Table);
        addToList(members_Master2, master2Table);

        // Master1      
        const master1Div = document.createElement('div');
        master1Div.className = 'position_name';
        master1Div.innerText = '博士前期課程1年生';

        const master1Table = document.createElement('table');
        master1Table.className = 'setting-tab';
        master1Table.border = '1';
        master1Table.style = "border-collapse: collapse";
        
        active_members.appendChild(master1Div);
        active_members.appendChild(master1Table);
        addToList(members_Master1, master1Table);

        // 学部生      
        const bachelorDiv = document.createElement('div');
        bachelorDiv.className = 'position_name';
        bachelorDiv.innerText = '学部生';

        const bachelorTable = document.createElement('table');
        bachelorTable.className = 'setting-tab';
        bachelorTable.border = '1';
        bachelorTable.style = "border-collapse: collapse";
        
        active_members.appendChild(bachelorDiv);
        active_members.appendChild(bachelorTable);
        addToList(members_Bachelor, bachelorTable);

        // 研究生      
        const researcherDiv = document.createElement('div');
        researcherDiv.className = 'position_name';
        researcherDiv.innerText = '研究生';

        const researcherTable = document.createElement('table');
        researcherTable.className = 'setting-tab';
        researcherTable.border = '1';
        researcherTable.style = "border-collapse: collapse";
        
        active_members.appendChild(researcherDiv);
        active_members.appendChild(researcherTable);
        addToList(members_Researcher, researcherTable);

        // 共同研究員     
        const collabDiv = document.createElement('div');
        collabDiv.className = 'position_name';
        collabDiv.innerText = '共同研究員';

        const collabTable = document.createElement('table');
        collabTable.className = 'setting-tab';
        collabTable.border = '1';
        collabTable.style = "border-collapse: collapse";
        
        active_members.appendChild(collabDiv);
        active_members.appendChild(collabTable);
        addToList(members_Collab, collabTable);
    }

    // 過去のメンバーを探す
    function findFormermembers(){

        table_former.innerHTML ='';  
        
        addToList(members_Former, table_former);
    }

    // 編集画面表示
    function showEditData(id, name, position, studentID, year, new_members){
        editArea.innerHTML = ''; // 複数表示されるのを防ぐ
        const edit_id = id;

        const memberDiv = document.createElement('div');
        memberDiv.className = 'edit-text';
        memberDiv.id = edit_id;

        const nameP = document.createElement('p');
        nameP.className = 'edit-holder';
        nameP.innerText = 'name';

        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.className = 'edit-content';
        nameInput.id = 'edit-name';
        nameInput.value= name;

        const positionP = document.createElement('p');
        positionP.className = 'edit-holder';
        positionP.innerText = 'position';

        const positionInput = document.createElement('input');
        positionInput.type = 'text';
        positionInput.className = 'edit-content';
        positionInput.id = 'edit-position';
        positionInput.value = position;

        const studentIDP = document.createElement('p');
        studentIDP.className = 'edit-holder';
        studentIDP.innerText = 'studentID';

        const studentIDInput = document.createElement('input');
        studentIDInput.type = 'text';
        studentIDInput.className = 'edit-content';
        studentIDInput.id = 'edit-studentID';
        studentIDInput.value = studentID;

        const yearP = document.createElement('p');
        yearP.className = 'edit-holder';
        yearP.innerText = '卒業年';

        const yearInput = document.createElement('input');
        yearInput.type = 'text';
        yearInput.className = 'edit-content';
        yearInput.id = 'edit-position';
        yearInput.value = year;

        const buttonP = document.createElement('p');
        buttonP.className = 'buttonP';
        
        const editInput = document.createElement('input');
        editInput.type = 'button';
        editInput.className = 'submit-button edit-button';
        editInput.value = 'Edit';
        editInput.onclick = function(){
            edit(edit_id, nameInput.value, positionInput.value, studentIDInput.value, yearInput.value, new_members);
        }

        const delButton = document.createElement('button'); // 削除ボタン
        delButton.className = 'del-button';
        delButton.onclick = function() {
          disp(edit_id, new_members);
        }
        
        const delI = document.createElement('i'); // 削除ボタンアイコン
        delI.className = 'fas fa-trash worksicon fa-fw';

        memberDiv.appendChild(nameP);
        memberDiv.appendChild(nameInput);
        memberDiv.appendChild(positionP);
        memberDiv.appendChild(positionInput);
        memberDiv.appendChild(studentIDP);
        memberDiv.appendChild(studentIDInput);
        memberDiv.appendChild(yearP);
        memberDiv.appendChild(yearInput);
        buttonP.appendChild(editInput);
        delButton.appendChild(delI);
        buttonP.appendChild(delButton);
        memberDiv.appendChild(buttonP);
        editArea.appendChild(memberDiv);
    }

    function edit(edit_id, name, position, studentID, year, new_members){
        if(name != '' && position != ''){
            if(confirm('これで登録して良いですか？') == true){
                // 完成していない
                const url = './editData.php'; // 通信先
                const req = new XMLHttpRequest(); // 通信用オブジェクト
            
                if(studentID == ''){
                    studentID = null;
                }
                if(year == ''){
                    year = null;
                }
                const data = {id: parseInt(edit_id, 10), name: name, position: position, studentID: parseInt(studentID, 10), year: parseInt(year, 10)};
    
                req.onreadystatechange = function() {
                  if(req.readyState == 4 && req.status == 200) {
                    alert("更新しました");
                    
                    new_members.forEach((member) =>{
                        if(member['id'] == edit_id){
                            member['name'] = name;
                            member['position'] = position;
                            member['studentID'] = studentID;
                            member['year'] = year;
                        }
                    })
                    const str = getComputedStyle(document.querySelector('.edit-modal-wrapper'), ':not(:target)').visibility;
                    console.log(str);
                  }else if(req.readyState == 4 && req.status != 200) {
                    alert(req.response);
                  }
                }
                req.open('POST', url, true);
                req.setRequestHeader('Content-Type', 'application/json');
                req.send(JSON.stringify(data)); // オブジェクトを文字列化して送信
            }else{
               alert('キャンセルが押されました。');
            }
        }else{
            alert('名前もしくはpositionが空欄です。');
        }        
    }
       
</script>
</html>
