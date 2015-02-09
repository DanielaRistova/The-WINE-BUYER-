<?php
session_start();
$shoppingCardProducts = 0;
if(isset($_SESSION['shoppingCard'])){
	$countShoppingCard = count($_SESSION['shoppingCard']);
	for($i = 0;$i < $countShoppingCard;$i++){
		$shoppingCardProducts += (int)$_SESSION['shoppingCard'][$i]["Quantity"];
	}
}
$orderTable = '
		<table style="width:100%;border-collapse: collapse;">
        	<thead>
            	<tr>
                	<th align="left" style="font-size: 18px;padding:5px;background-color: #DACCF4;color: #fff;">Бр.</th>
                    <th align="left" style="font-size: 18px;padding:5px;background-color: #DACCF4;color: #fff;">Продукт</th>
                    <th align="left" style="font-size: 18px;padding:5px;background-color: #DACCF4;color: #fff;">Шифра</th>
                    <th align="center" style="font-size: 18px;padding:5px;background-color: #DACCF4;color: #fff;">Количина</th>
                    <th align="right" style="font-size: 18px;padding:5px;background-color: #DACCF4;color: #fff;">Единечна цена</th>
                </tr>
            </thead>
            <tbody>';
			$totalPrice = 0;
			for($i = 0;$i < $countShoppingCard;$i++){
				$orderTable .= '<tr>
                	<td align="left" style="font-size: 16px;padding:10px 5px;color: #333;border-bottom:solid 1px #DACCF4;"><?php echo ($i+1); ?></td>
                    <td align="left" style="font-size: 16px;padding:10px 5px;color: #333;border-bottom:solid 1px #DACCF4;">'.$_SESSION['shoppingCard'][$i]["Name"].'</td>
                    <td align="left" style="font-size: 16px;padding:10px 5px;color: #333;border-bottom:solid 1px #DACCF4;">'.$_SESSION['shoppingCard'][$i]["Code"].'</td>
                    <td align="center" style="font-size: 16px;padding:10px 5px;color: #333;border-bottom:solid 1px #DACCF4;">'.$_SESSION['shoppingCard'][$i]["Quantity"].'</td>
                    <td align="right" style="font-size: 16px;padding:10px 5px;color: #333;border-bottom:solid 1px #DACCF4;">'.$_SESSION['shoppingCard'][$i]["Price"].' MKD</td>
                </tr>';
		        $totalPrice +=(int)$_SESSION['shoppingCard'][$i]["Price"] * (int)$_SESSION['shoppingCard'][$i]["Quantity"];
           }
		   $orderTable .='<tr>
                    <td colspan="5" align="right">
                    	<strong>Вкупна цена:</strong>&nbsp;'.$totalPrice.' MKD
                    </td>
                </tr>
            </tbody>
        </table>';
	
	if(isset($_GET['event']) && !empty($_GET['event'])){
		$event = $_GET['event'];
		
		if($event == "clean"){
			session_destroy();
			header("Location: http://ng-development.com/simka/basket.php");	
			exit();
		}
		
	}
	
	if(isset($_POST['event']) && !empty($_POST['event'])){
		$event = $_POST['event'];
		
		if($event == "makeOrder"){
			$to="arapova_15@hotmail.com";
			//$to="nikola_garvanliev@hotmail.com";
			$subject = "Винарија \"Симка\" Нарачка";
			$header='';
			$header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
			$message = $orderTable;
	
			if(mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header)){
				header("Location: http://ng-development.com/simka/basket.php?action=send");
				exit();
			}
		}	
	}
	

?>
<!DOCUMENT>
<html>
<head>
	<title>Моја кошничка | Винарија</title>
    <?php include("include/htmlHeader.php"); ?>
</head>
<body>
	<?php include("include/nav.php"); ?>
	<div class="container content">
		<div class="mainContentHeader">
            <h4>Моја кошничка (<?php echo $shoppingCardProducts; ?>)</h4>
        </div>
        <?php if(isset($_GET['action']) && $_GET['action'] == "send"){?>
        <strong>Вашата порачка е успешно испратена и во најбрзо време ќе Ви биде доставена. <a href="index.php">Одберете нови продукти?</a></strong>
        <?php }else{ ?>
        <?php if($shoppingCardProducts > 0){
			echo $orderTable;	
		?>
        <div style="margin-top:20px;">
        	<table>
            	<tbody>
                	<tr>
                        <td>
                    		<form id="orderForm" name="orderForm" method="POST" action="basket.php">
                                <input type="hidden" id="event" name="event" value="makeOrder" />
                                <input type="submit" class="btn-vine" value="Нарачај" />
                            </form>
                    	</td>
                        <td>&nbsp;</td>
                        <td>
                    		<a href="basket.php?event=clean">Исчисти кошничка</a>
                    	</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php }else{ ?>
        <strong>Моментално немате продукти во вашата кошнича. <a href="index.php">Одберете продукти</a></strong>
        <?php } ?>
        <?php } ?>
    </div>
</body>
</html>