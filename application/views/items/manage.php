<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
    init_table_sorting();
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo $this->lang->line("common_confirm_search")?>');
    enable_activar('<?php echo $this->lang->line($controller_name."_confirm_activar")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');
    enable_delete('<?php echo $this->lang->line($controller_name."_confirm_delete")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');
    enable_bulk_edit('<?php echo $this->lang->line($controller_name."_none_selected")?>');

    $('#generate_barcodes').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert('<?php echo $this->lang->line('items_must_select_item_for_barcode'); ?>');
    		return false;
    	}

    	$(this).attr('href','index.php/items/generate_barcodes/'+selected.join(','));
    });

    $('#export').click(function()
    {
        var selected = get_selected_values();
        if (selected.length == 0)
        {
                alert('<?php echo $this->lang->line('items_must_select_item_for_barcode'); ?>');
                return false;
        }

        $(this).attr('href','index.php/items/export/'+selected.join(','));
    });
    $("#distribuidora").click(function()
    {
        $('#items_filter_form').submit();
    });

    $("#solo_cd").click(function()
    {
        $('#items_filter_form').submit();
    });
    $("#solo_dvd").click(function()
    {
        $('#items_filter_form').submit();
    });

    $("#low_inventory").click(function()
    {
    	$('#items_filter_form').submit();
    });

});

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length = 3)
	{
		$("#sortable_table").tablesorter(
		{
			sortList: [[3]],
			// sortList: [[1,0]],
			headers:
			{
				10: { sorter: false}
			}

		});
	}
}

function post_item_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);
	}
	else
	{
		//This is an update, just update one row
		if(jQuery.inArray(response.item_id,get_visible_checkbox_ids()) != -1)
	{
			update_row(response.item_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);

		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				//highlight new row
				hightlight_row(response.item_id);
				set_feedback(response.message,'success_message',false);
			});
		}
	}
}

function post_bulk_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);
	}
	else
	{
		var selected_item_ids=get_selected_values();
		for(k=0;k<selected_item_ids.length;k++)
		{
			update_row(selected_item_ids[k],'<?php echo site_url("$controller_name/get_row")?>');
		}
		set_feedback(response.message,'success_message',false);
	}
}


</script>

<div id="table_action_header">
<div style="float:right">
<ul>
<li class="float_left">	<?php echo form_open("$controller_name/refresh",array('id'=>'items_filter_form')); ?></li>
<li class="float_left">	<?php echo form_label($this->lang->line('items_low_inventory_items').' '.':', 'low_inventory');?></li>
<li class="float_left">	<?php echo form_checkbox(array('name'=>'low_inventory','id'=>'low_inventory','value'=>1,'checked'=> isset($low_inventory)?  ( ($low_inventory)? 1 : 0) : 0)).' | ';?></li>

<li class="float_left">	<?php echo form_label('CD', 'solo_cd');?></li>
<li class="float_left">	<?php echo form_checkbox(array('name'=>'solo_cd','id'=>'solo_cd','value'=>1,'checked'=> isset($solo_cd)?  ( ($solo_cd)? 1 : 0) : 0)).' | ';?></li>

<li class="float_left">        <?php echo form_label('DVD', 'solo_dvd');?></li>
<li class="float_left">        <?php echo form_checkbox(array('name'=>'solo_dvd','id'=>'solo_dvd','value'=>1,'checked'=> isset($solo_dvd)?  ( ($solo_dvd)? 1 : 0) : 0));?> </li>

<li class="float_left"> <?php echo form_label('distribuidora', 'distribuidora');?> <select id="distribuidora" name="distribuidora">
<option value="Milk">DBN</option>
<option value="Cheese">LEF</option>
<option value="Bread">OMA</option>
</select> </li>
	<?php echo $distribuidora ?>
</ul>

	<input type="hidden" name="search_section_state" id="search_section_state" value="<?php echo isset($search_section_state)?  ( ($search_section_state)? 'block' : 'none') : 'none';?>" />
	</form></div>
<div style="float:left">		
	<ul>
		<li class="float_left"><span><?php echo anchor("$controller_name/delete",$this->lang->line("common_delete"),array('id'=>'delete')); ?></span></li>
		<li class="float_left"><span><?php echo anchor("$controller_name/activar",$this->lang->line("common_active"),array('id'=>'activar')); ?></span></li>
		<li class="float_left"><span><?php echo anchor("$controller_name/bulk_edit/width:$form_width",$this->lang->line("items_bulk_edit"),array('id'=>'bulk_edit','title'=>$this->lang->line('items_edit_multiple_items'))); ?></span></li>
		<li class="float_left"><span><?php echo anchor("$controller_name/generate_barcodes",$this->lang->line("items_generate_barcodes"),array('id'=>'generate_barcodes', 'target' =>'_blank','title'=>$this->lang->line('items_generate_barcodes'))); ?></span></li>

		<li class="float_left"><span><?php echo anchor("$controller_name/export",$this->lang->line("items_generate_export"),array('id'=>'export', 'target' =>'_blank','title'=>$this->lang->line('items_generate_export'))); ?></span></li>
		<li class="float_left"><span><?php echo anchor("$controller_name/view/-1/width:$form_width",$this->lang->line($controller_name.'_new'),array('class'=>'thickbox none','title'=>$this->lang->line($controller_name.'_new'))); ?>  </span></li>

		<li class="float_right">
		<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
		<?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
		<input type="text" name ='search' id='search'/>
		</form>
		</li>
	</ul>
</div>
</div>
<div> <?php echo $this->pagination->create_links();?></div>
<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>
