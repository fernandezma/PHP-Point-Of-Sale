<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
    init_table_sorting();
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest")?>','<?php echo $this->lang->line("common_confirm_search")?>');
    enable_delete('<?php echo $this->lang->line($controller_name."_confirm_delete")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');
	
	$('#generate_barcodes').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert('<?php echo $this->lang->line('items_must_select_item_for_barcode'); ?>');
    		return false;
    	}

    	$(this).attr('href','index.php/item_kits/generate_barcodes/'+selected.join(','));
    });
    
});

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(
		{
			sortList: [[1,0]],
			headers:
			{
				0: { sorter: false},
				3: { sorter: false}
			}
		});
	}
}

function post_item_kit_form_submit(response)
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
				hightlight_row(response.item_kit_id);
				set_feedback(response.message,'success_message',false);
			});
		}
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
	<li><?php echo anchor("$controller_name/generate_barcodes",$this->lang->line("items_generate_barcodes"),array('id'=>'generate_barcodes', 'target' =>'_blank','title'=>$this->lang->line('items_generate_barcodes'))); ?></li>
    </ul>
    <ul class="der">
	<li><?php echo anchor("$controller_name/view/-1/width:$form_width", $this->lang->line($controller_name.'_new'), array('class'=>'thickbox none','title'=>$this->lang->line($controller_name.'_new'))); ?></li>
    </ul>
</div>



<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>
