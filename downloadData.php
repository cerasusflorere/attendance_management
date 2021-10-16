<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
    <link rel="icon" href="img_news_00.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" rel="stylesheet">
</head>

<?php
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    $logs = json_decode($_POST['logs'], true);
    
    // ライブラリ読込
    require '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
    // Spreadsheetオブジェクト生成
    $objSpreadsheet = new Spreadsheet();
    // シート設定
    $objSheet = $objSpreadsheet->getActiveSheet();
 
    // [A1:E1]セルを結合
    $objSheet->mergeCells('A1:E1');

    // [F1:O1]セルを結合 医心館
    $objSheet->mergeCells('F1:O1');
    $objSheet->setCellValue('F1', '医心館');
    $objSheet->getStyle('F1') ->getAlignment() ->setHorizontal('center');

    // [P1:S1]セルを結合 その他
    $objSheet->mergeCells('P1:S1');
    $objSheet->setCellValue('P1', 'その他');
    $objSheet->getStyle('P1') ->getAlignment() ->setHorizontal('center');

    // [A2]セルに 名前
    $objSheet->setCellValue('A2', '名前');
    $objSheet->getStyle('A2') ->getAlignment() ->setHorizontal('center');

    // [B2]セルに 日付
    $objSheet->setCellValue('B2', '日付');
    $objSheet->getStyle('B2') ->getAlignment() ->setHorizontal('center');

    // [C2]セルに 健康チェック
    $objSheet->setCellValue('C2', '健康チェック');
    $objSheet->getStyle('C2') ->getAlignment() ->setHorizontal('center');

    // [D2]セルに その日医心館に最初に入館した時間
    $objSheet->setCellValue('D2', 'その日医心館に最初に入館した時間');
    $objSheet->getStyle('D2') ->getAlignment() ->setHorizontal('center');

    // [E2]セルに 帰宅のために医心館から退館した時間
    $objSheet->setCellValue('E2', '帰宅のために医心館から退館した時間');
    $objSheet->getStyle('E2') ->getAlignment() ->setHorizontal('center');

    // [F2]セルに IN401N
    $objSheet->setCellValue('F2', 'IN401N');
    $objSheet->getStyle('F2') ->getAlignment() ->setHorizontal('center');

    // [G2]セルに IN501N
    $objSheet->setCellValue('G2', 'IN501N');
    $objSheet->getStyle('G2') ->getAlignment() ->setHorizontal('center');

    // [H2]セルに IN505N
    $objSheet->setCellValue('H2', 'IN505N');
    $objSheet->getStyle('H2') ->getAlignment() ->setHorizontal('center');

    // [I2]セルに IN418N
    $objSheet->setCellValue('I2', 'IN418N（小早川）');
    $objSheet->getStyle('I2') ->getAlignment() ->setHorizontal('center');

    // [J2]セルに IN419N
    $objSheet->setCellValue('J2', 'IN419N（早見）');
    $objSheet->getStyle('J2') ->getAlignment() ->setHorizontal('center');

    // [K2]セルに コウモリ舎
    $objSheet->setCellValue('K2', 'コウモリ舎');
    $objSheet->getStyle('K2') ->getAlignment() ->setHorizontal('center');

    // [L2]セルに サル・ネズミ舎
    $objSheet->setCellValue('L2', 'サル・ネズミ舎');
    $objSheet->getStyle('L2') ->getAlignment() ->setHorizontal('center');

    // [M2]セルに IN409N
    $objSheet->setCellValue('M2', 'IN409N');
    $objSheet->getStyle('M2') ->getAlignment() ->setHorizontal('center');

    // [N2]セルに IN412N
    $objSheet->setCellValue('N2', 'IN412N');
    $objSheet->getStyle('N2') ->getAlignment() ->setHorizontal('center');

    // [O2]セルに その他
    $objSheet->setCellValue('O2', 'その他');
    $objSheet->getStyle('O2') ->getAlignment() ->setHorizontal('center');

    // [P2]セルに 紫苑館
    $objSheet->setCellValue('P2', '紫苑館');
    $objSheet->getStyle('P2') ->getAlignment() ->setHorizontal('center');

    // [Q2]セルに 購買部
    $objSheet->setCellValue('Q2', '購買部');
    $objSheet->getStyle('Q2') ->getAlignment() ->setHorizontal('center');

    // [R2]セルに 図書館/LC
    $objSheet->setCellValue('R2', '図書館/LC');
    $objSheet->getStyle('R2') ->getAlignment() ->setHorizontal('center');

    // [S2]セルに その他
    $objSheet->setCellValue('S2', 'その他');
    $objSheet->getStyle('S2') ->getAlignment() ->setHorizontal('center');

    // [A3]セル
    $objSheet->setCellValue('A3', $logs);
    $objSheet->getStyle('A3') ->getAlignment() ->setHorizontal('center');
 
    // XLSX形式オブジェクト生成
    $objWriter = new Xlsx($objSpreadsheet);
    // ファイル書込み
    $objWriter->save('test1-4.xlsx');
    exit();
?>
