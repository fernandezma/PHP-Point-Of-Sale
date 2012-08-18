<?php $this->load->view("partial/header"); ?>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}

if (isset($warning))
{
	echo "<div class='warning_mesage'>".$warning."</div>";
}

if (isset($success))
{
	echo "<div class='success_message'>".$success."</div>";
}
?>
<div id="register_wrapper">
<?php echo form_open("sales/change_mode",array('id'=>'mode_form')); ?>
<span><?php echo $this->lang->line('sales_mode') ?></span>
<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
</form>
<?php echo form_open("sales/add",array('id'=>'add_item_form')); ?>
<label id="item_label" for="item">

<?php
if($mode=='sale')
{
	echo $this->lang->line('sales_find_or_scan_item');
}
else
{
	echo $this->lang->line('sales_find_or_scan_item_or_receipt');
}
?>
</label>
<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'60'));?>
</form>
<table id="register">
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='8'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);
		echo form_open("sales/edit_item/$line");
	?>

<div style="width:100%;height:50px;border-top-color:#CCCCCC;border-top-style:solid;border-top-width:1px;">
	<div style="width:80%;float:left;">
		<h1><?php echo $item['name']; ?></h1>
	</div>
        <div style="width:100px;float:right;color:#646464">
		<h1><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></h1>
        </div>
	<div style="width:300px;float:left;">
		<?php echo anchor("sales/delete_item/$line",'['.$this->lang->line('common_delete').']');?> CodBar:<?php echo $item['item_number']; ?>  Stock: <?php echo $cur_item_info->quantity; ?>
	</div>
	<div style="width:300px;float:right;text-align:right;">

			&#36;<?php if ($items_module_allowed){?>
			<?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?>
			<?php }else{?>
			<?php echo $item['price']; ?>
			<?php echo form_hidden('price',$item['price']); ?>
			<?php } ?>x<?php if($item['is_serialized']==1) {
                        echo $item['quantity'];
                        echo form_hidden('quantity',$item['quantity']);
                }else{
                        print form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
                }?> <?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'));?>
	</div>
</div>
		</form>
	<?php
	}
}
?>
</tbody>
</table>
</div>


<div id="overall_sale">
<div class="float_left" style="width:45%;">
<?php echo $this->lang->line('sales_total'); ?> </BR>
<h1><?php echo to_currency($total); ?></h1>
</div>

	<?php
	// Only show this part if there are Items already in the sale.
	if(count($cart) > 0)
	{
	?>

    	<div id="Cancel_sale">
		<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form')); ?>
		<div class='small_button' id='cancel_sale_button' style='margin-top:5px;'>
			<span><?php echo $this->lang->line('sales_cancel_sale'); ?></span>
		</div>
    	</form>
    	</div>
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
			<div id="finish_sale">
				<?php echo form_open("sales/complete",array('id'=>'finish_sale_form')); ?>
				<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?>:</label>
				<?php echo form_textarea(array('name'=>'comment','value'=>'','rows'=>'4','cols'=>'23'));?>
				<br /><br />

				<?php echo "<div class='small_button' id='finish_sale_button' style='float:left;margin-top:5px;'><span>".$this->lang->line('sales_complete_sale')."</span></div>";
				echo "<div class='small_button' id='suspend_sale_button' style='float:right;margin-top:5px;'><span>".$this->lang->line('sales_suspend_sale')."</span></div>";
				?>
			</div>
			</form>
		<?php
		}
		?>


	<div id="Payment_Types" >

		<div style="height:100px;">

			<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form')); ?>
			<table width="100%">
			<tr>
			<td>
				<?php echo $this->lang->line('sales_payment').':   ';?>
			</td>
			<td>
				<?php echo form_dropdown('payment_type',$payment_options,array(), 'id="payment_types"');?>
			</td>
			</tr>
			<tr>
			<td>
				<span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered').': ';?></span>
			</td>
			<td>
				<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>to_currency_no_money($amount_due),'size'=>'10'));	?>
			</td>
			</tr>
        	</table>
			<div class='small_button' id='add_payment_button' style='float:left;margin-top:5px;'>
				<span><?php echo $this->lang->line('sales_add_payment'); ?></span>
			</div>
		</div>
		</form>

		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
	    	<table id="register">
	    	<thead>
			<tr>
			<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
			<th style="width:60%;"><?php echo 'Type'; ?></th>
			<th style="width:18%;"><?php echo 'Amount'; ?></th>


			</tr>
			</thead>
			<tbody id="payment_contents">
			<?php
				foreach($payments as $payment_id=>$payment)
				{
				echo form_open("sales/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
				?>
	            <tr>
	            <td><?php echo anchor("sales/delete_payment/$payment_id",'['.$this->lang->line('common_delete').']');?></td>


				<td><?php echo  $payment['payment_type']    ?> </td>
				<td style="text-align:right;"><?php echo  to_currency($payment['payment_amount'])  ?>  </td>


				</tr>
				</form>
				<?php
				}
				?>
			</tbody>
			</table>
		    <br />
		<?php
		}
		?>



	</div>

	<?php
	}
	?>
</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
    $("#item").autocomplete('<?php echo site_url("sales/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
    	selectFirst: false,
       	delay:10,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });

	$('#item').focus();

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#customer').click(function()
    {
    	$(this).attr('value','');
    });

    $("#customer").autocomplete('<?php echo site_url("sales/customer_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#customer").result(function(event, data, formatted)
    {
		$("#select_customer_form").submit();
    });

    $('#customer').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
    });

    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_finish_sale"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

	$("#suspend_sale_button").click(function()
	{
		if (confirm('<?php echo $this->lang->line("sales_confirm_suspend_sale"); ?>'))
    	{
			$('#finish_sale_form').attr('action', '<?php echo site_url("sales/suspend"); ?>');
    		$('#finish_sale_form').submit();
    	}
	});

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_cancel_sale"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });

	$("#add_payment_button").click(function()
	{
	   $('#add_payment_form').submit();
    });

	$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard)
});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#customer").attr("value",response.person_id);
		$("#select_customer_form").submit();
	}
}

function checkPaymentTypeGiftcard()
{
	if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_giftcard'); ?>")
	{
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_giftcard_number'); ?>");
		$("#amount_tendered").val('');
		$("#amount_tendered").focus();
	}
	else
	{
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");		
	}
}

</script>
