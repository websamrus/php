<?session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bankomat</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        <div class="bankomat">
            <?cash();?>
        </div>
    </div>
</body>
</html>

<?php
function cash(){
    if(isset($_SESSION['bank'])){
        $bank = $_SESSION['bank'];
    }else {
        $bank = array(500 => 500, 200 => 500, 100 => 500, 50 => 400, 20 => 300, 10 => 200, 5 => 100);
        $_SESSION['bank'] =$bank;
    }

    if($_POST['money'] != null) {
        $money = (int)$_POST['money'];
        $sumBank=0;
        foreach ($bank as  $key => &$b){
            $sumBank += $key*$b;
        }
        if ($money%5 == 0 && $money <=$sumBank) {
            $bills = array(500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0);
            $rating = array(500, 200, 100, 50, 20, 10, 5);

            $i = 0;
            while ($money>=5){
                if($money/$rating[$i]>=1){
                    $temp = (int)($money/$rating[$i]);
                    $countBank = searchCount($rating[$i], $bank);
                    if($temp >= $countBank) $temp =$countBank;
                    $money -= $rating[$i]*$temp;
                    countBill ($rating[$i],$temp, $bills);
                    editBank ($rating[$i],$temp, $bank);
                }
                $i++;
            }
            $_SESSION['bank'] =$bank;
            $sum=0;
            echo "<table>";
            echo '<tr><td>Номинал купюры</td><td>Количество</td></tr>';
            foreach ($bills as  $key => $b) {
                if ($b != 0) {
                    echo '<tr><td>' . $key . '</td><td>' . $b . '</td></tr>';
                    $sum += $key * $b;
                }
            }
            echo "</table>";
            echo "<h2>Выдано  {$sum} грн. </h2>";
        }else if($money < 5){
            echo "<h3>Выдача невозможна: сумма меньше 5грн.</h3>";
            echo '<h4>Нажмите кнопку "Вернуться" и введите сумма больше 5грн</h4>';
        }else if($money > $sumBank){
            echo "<h3>Ошибка!! Вы заказали: {$money}, в банкомате недостаточно купюр</h3>";
            echo '<h4>Нажмите кнопку "Вернуться" и введите меньшую суму(до '.$sumBank.'грн)</h4>';
        }else if($money%5 != 0){
            echo "<h3>Выдача невозможна: сумма не кратна 5грн.</h3>";
            echo '<h4>Нажмите кнопку "Вернуться" и введите сумма кратной 5грн</h4>';
        }
    }

    echo "<button class='btn'><a href='../index.html'>Вернуться</a></button>";

}
function countBill ($c, $r, &$arrStr){
    foreach ($arrStr as  $key => &$b){
        if($key == $c) $b+=$r;
    }
}

function searchCount ($c, $bank){
    foreach ($bank as $key => $b) {
        if ($key == $c) return $b;
    }
}

function editBank ($c, $r, &$bank){
    foreach ($bank as $key => &$b) {
        if ($key == $c) $b-=$r;
    }
}
?>