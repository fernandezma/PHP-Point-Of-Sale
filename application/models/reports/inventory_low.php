<?php
require_once("report.php");
class Inventory_low extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_item_number'),$this->lang->line('reports_item_name'),  $this->lang->line('reports_category'),  $this->lang->line('reports_description'),$this->lang->line('reports_count'), $this->lang->line('reports_reorder_level'), $this->lang->line('reports_cost'));
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('item_number, name, category,description,quantity,reorder_level ,cost_price');
		$this->db->from('items');
		$this->db->where('quantity <= reorder_level and deleted=0');
		$this->db->order_by('name');
		
		return $this->db->get()->result_array();

	}
	
	public function getSummaryData(array $inputs)
	{
		return array();
	}
}
?>
