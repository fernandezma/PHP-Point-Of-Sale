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

<div class="subbar">
    <div class="titulo iz"><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></div> 
    <div class="der"><?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?><input type="text" name ='search' id='search'/></form></div>
</div>
<div class="subbar">
    <ul class="iz">
	<li><?php echo anchor("$controller_name/delete",$this->lang->line("common_delete"),array('id'=>'delete')); ?></li>
	<li><?php echo anchor("$controller_name/activar",$this->lang->line("common_active"),array('id'=>'activar')); ?></li>
	<li><?php echo anchor("$controller_name/bulk_edit/width:$form_width",$this->lang->line("items_bulk_edit"),array('id'=>'bulk_edit','title'=>$this->lang->line('items_edit_multiple_items'))); ?></li>
	<li><?php echo anchor("$controller_name/generate_barcodes",$this->lang->line("items_generate_barcodes"),array('id'=>'generate_barcodes', 'target' =>'_blank','title'=>$this->lang->line('items_generate_barcodes'))); ?></li>
	<li><?php echo anchor("$controller_name/export",$this->lang->line("items_generate_export"),array('id'=>'export', 'target' =>'_blank','title'=>$this->lang->line('items_generate_export'))); ?></li>
    </ul>
    <ul class="der">
        <li><?php echo anchor("$controller_name/view/-1/width:$form_width",$this->lang->line($controller_name.'_new'),array('class'=>'thickbox none','title'=>$this->lang->line($controller_name.'_new'))); ?></li>
        <li> <?php echo form_open("$controller_name/refresh",array('id'=>'items_filter_form')); ?></li>
        <li><a style="<?php if ($low_inventory == 1){ echo "background-color:red;";}  ?>" href="javascript:void(0);" onclick="funcion;"><?php echo form_label($this->lang->line('items_low_inventory_items'), 'low_inventory');?></a></li>
        <li style="display:none"> <?php echo form_checkbox(array('name'=>'low_inventory','id'=>'low_inventory','value'=>1,'checked'=> isset($low_inventory)?  ( ($low_inventory)? 1 : 0) : 0)).' | ';?></li>
        <li><a style="<?php if ($solo_cd == 1){ echo "background-color:red;";}  ?>" href="javascript:void(0);" onclick="funcion;"><?php echo form_label('CD', 'solo_cd');?></a></li>
        <li style="display:none"><?php echo form_checkbox(array('name'=>'solo_cd','id'=>'solo_cd','value'=>1,'checked'=> isset($solo_cd)?  ( ($solo_cd)? 1 : 0) : 0)).' | ';?></li>
        <li><a style="<?php if ($solo_dvd == 1){ echo "background-color:red;";}  ?>" href="javascript:void(0);" onclick="funcion;"><?php echo form_label('DVD', 'solo_dvd');?></a></li>
        <li class="float_left"  style="display:none"><?php echo form_checkbox(array('name'=>'solo_dvd','id'=>'solo_dvd','value'=>1,'checked'=> isset($solo_dvd)?  ( ($solo_dvd)? 1 : 0) : 0));?>
</li>

	<li> </li>
    </ul>
</div>



<div id="table_holder">
<?php echo $manage_table; ?>
<?php echo $this->pagination->create_links();?>
</div>

<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>
