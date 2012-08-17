	<div id="item_view" class="mainContent">
	<?php if(isset($returnMessage)) echo $returnMessage; ?><br />

	<?php 
	$countNew = count($newOrders);
	$countSend = count($dispatchOrders);	
	if($countNew > 0){?>
	<a href="#payment">Waiting Payment (<?=$countNew;?>)</a> | 
	<?php  }

	if($countSend > 0){?>
	<a href="#dispatch">For Dispatch (<?=$countSend;?>)</a> | 
	<?php } ?>
	<?=anchor("listings/create","Create a new listing"); ?>

	
	<br /><br />

	<?php
	if(count($items) > 0){
		 foreach ($items as $item): ?>
		<div class="productBox" id="prod_<?=$item['itemHash']; ?>">
			<h3><?=anchor('item/'.$item['itemHash'], $item['name']);?></h3><hr/>
			<?=anchor("listings/edit/".$item['itemHash'], 'Edit');?> | 
			<?=anchor("listings/remove/".$item['itemHash'], 'Remove');?> |
			<?=anchor("listings/images/".$item['itemHash'], 'Images');?><br />
			<!--<div class="rating">item Rating: <?=$item['rating'];?>/5</div>-->
			 <div class="itemImg">
			  <?=anchor('item/'.$item['itemHash'], "<img src='data:image/jpeg;base64,{$item['itemImgs']['encoded']}' title='{$item['name']}' height='70' width='100'>"); ?>
			 </div>
		</div>
		<?php endforeach;
	} else {
		echo "You have no listings!";
	} ?>
		<div class="clear"></div>

	<?php if(count($newOrders) > 0){ ?>
		<a name="payment">Currently awaiting payment:</a> 

		<?php foreach($newOrders as $order): ?>
			<br />
			<?=anchor("user/".$order['buyer']['userHash'], $order['buyer']['userName']); ?> (<?=$order['dispTime'];?>)<br />
			<?php foreach($order['items'] as $item):?>
				<?=$item['quantity']." x ".$item['name'];?><br />
			<?php endforeach; ?>
			Total Price: <?=$order['currencySymbol'].$order['totalPrice'];?><br />
			<?=anchor('payment/confirm/'.$order['buyer']['userHash'], 'Click to confirm payment.');?><br />
		<?php endforeach;

	}
	?>
		<div class="clear"></div>
	<?php if(count($dispatchOrders) > 0){ ?>
		<br />
		<a name="dispatch">Orders for dispatch:</a> 		

		<?php foreach($dispatchOrders as $order): ?>
			<br />
			<?=anchor("user/".$order['buyer']['userHash'], $order['buyer']['userName']); ?> (<?=$order['dispTime'];?>)<br />
			<?php foreach($order['items'] as $item):?>
				<?=$item['quantity']." x ".$item['name'];?><br />
			<?php endforeach; ?>
			Total Price: <?=$order['currencySymbol'].$order['totalPrice'];?><br />
			<?=anchor('dispatch/confirm/'.$order['buyer']['userHash'], 'Click to confirm dispatch.');?>
		<?php endforeach;

	}
	?>

	</div>