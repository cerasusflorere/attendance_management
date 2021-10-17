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
    session_start();
    header('Content-type: application/json; charset=utf-8');
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    
    $json = file_get_contents('php://input');
    $logs_origin = array(json_decode($json, true));
    

    $logs = array();
    
    foreach($logs_origin[0]['logs'] as $log_origin){
        $logs[] = $log_origin;
    }

    // ライブラリ読込
    require '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
    use PhpOffice\PhpSpreadsheet\Style;
    use PhpOffice\PhpSpreadsheet\Style\Border;
 
    // Spreadsheetオブジェクト生成
    $objSpreadsheet = new Spreadsheet();
    // シート設定
    $objSheet = $objSpreadsheet->getActiveSheet();
    
    // ウィンドウ固定
    $objSheet->freezePane('B3');

    // スタイルオブジェクト取得([A2:S2]セル)
    $objStyle = $objSheet->getStyle('A2:S2');
    $objBorders = $objStyle->getBorders();
    $objBorders->getBottom()->setBorderStyle(Border::BORDER_THICK);

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
    $objSheet -> getStyle('A') -> getAlignment()-> setWrapText(true);
    $objSpreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objSheet->getStyle('A') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('A') ->getAlignment() ->setVertical('center');

    // [B2]セルに 日付
    $objSheet->setCellValue('B2', '日付');
    $objSpreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
    $objSheet->getStyle('B') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('B') ->getAlignment() ->setVertical('center');

    // [C2]セルに 健康チェック
    $objSheet->setCellValue('C2', '健康チェック');
    $objSpreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(13);
    $objSheet->getStyle('C') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('C') ->getAlignment() ->setVertical('center');

    // [D2]セルに その日医心館に最初に入館した時間
    $objSheet->setCellValue('D2', 'その日医心館に最初に入館した時間');
    $objSheet -> getStyle('D2') -> getAlignment()-> setWrapText(true);
    $objSpreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(14.14);
    $objSheet->getStyle('D') ->getAlignment() ->setHorizontal('center');    
    $objSheet->getStyle('D') ->getAlignment() ->setVertical('center');
    $objSheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);

    // [E2]セルに 帰宅のために医心館から退館した時間
    $objSheet->setCellValue('E2', '帰宅のために医心館から退館した時間');
    $objSheet -> getStyle('E2') -> getAlignment()-> setWrapText(true);
    $objSpreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(14.14);
    $objSheet->getStyle('E') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('E') ->getAlignment() ->setVertical('center');
    $objSheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);

    // [F2]セルに IN401N
    $objSheet->setCellValue('F2', 'IN401N');
    $objSheet->getStyle('F') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('F') ->getAlignment() ->setVertical('center');

    // [G2]セルに IN501N
    $objSheet->setCellValue('G2', 'IN501N');
    $objSheet->getStyle('G') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('G') ->getAlignment() ->setVertical('center');

    // [H2]セルに IN505N
    $objSheet->setCellValue('H2', 'IN505N');
    $objSheet->getStyle('H') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('H') ->getAlignment() ->setVertical('center');

    // [I2]セルに IN418N
    $objSheet->setCellValue('I2', 'IN418N   （小早川）');
    $objSheet -> getStyle('I2') -> getAlignment()-> setWrapText(true);
    $objSpreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(12);
    $objSheet->getStyle('I') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('I') ->getAlignment() ->setVertical('center');

    // [J2]セルに IN419N
    $objSheet->setCellValue('J2', 'IN419N（早見）');
    $objSheet -> getStyle('J2') -> getAlignment()-> setWrapText(true);
    $objSheet->getStyle('J') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('J') ->getAlignment() ->setVertical('center');

    // [K2]セルに コウモリ舎
    $objSheet->setCellValue('K2', 'コウモリ舎');
    $objSpreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);
    $objSheet->getStyle('K') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('K') ->getAlignment() ->setVertical('center');

    // [L2]セルに サル・ネズミ舎
    $objSheet->setCellValue('L2', 'サル・ネズミ舎');
    $objSpreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(14.5);
    $objSheet->getStyle('L') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('L') ->getAlignment() ->setVertical('center');

    // [M2]セルに IN409N
    $objSheet->setCellValue('M2', 'IN409N');
    $objSheet->getStyle('M') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('M') ->getAlignment() ->setVertical('center');

    // [N2]セルに IN412N
    $objSheet->setCellValue('N2', 'IN412N');
    $objSheet->getStyle('N') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('N') ->getAlignment() ->setVertical('center');

    // [O2]セルに その他
    $objSheet->setCellValue('O2', 'その他');
    $objSheet -> getStyle('O') -> getAlignment()-> setWrapText(true);
    $objSpreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(25);
    $objSheet->getStyle('O') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('O') ->getAlignment() ->setVertical('center');

    // [P2]セルに 紫苑館
    $objSheet->setCellValue('P2', '紫苑館');
    $objSheet->getStyle('P') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('P') ->getAlignment() ->setVertical('center');

    // [Q2]セルに 購買部
    $objSheet->setCellValue('Q2', '購買部');
    $objSheet->getStyle('Q') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('Q') ->getAlignment() ->setVertical('center');

    // [R2]セルに 図書館/LC
    $objSheet->setCellValue('R2', '図書館/LC');
    $objSpreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(12);
    $objSheet->getStyle('R') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('R') ->getAlignment() ->setVertical('center');

    // [S2]セルに その他
    $objSheet->setCellValue('S2', 'その他');
    $objSheet -> getStyle('S') -> getAlignment()-> setWrapText(true);
    $objSpreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(25);
    $objSheet->getStyle('S') ->getAlignment() ->setHorizontal('center');
    $objSheet->getStyle('S') ->getAlignment() ->setVertical('center');

    // データを表示
    $objSheet->fromArray($logs, null, 'A3');
 
    // XLSX形式オブジェクト生成
    $objWriter = new Xlsx($objSpreadsheet);
    // ファイル書込み
    $objWriter->save('test1-4.xlsx');
    exit();
?>
