<?php
class Item extends Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*
	Returns all the items
	*/
	function get_all($limit, $offset)
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	function distri()
	{
                $this->db->from('suppliers');
                $this->db->where('deleted',0);
                $this->db->order_by("name", "asc");
                return $this->db->get();
	}
	function count_all()
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}

	function get_all_filtered($limit, $offset,$solo_dvd,$solo_cd,$dist_AFC,$dist_DBN,$dist_GUS,$dist_IND,$dist_LEF,$dist_MAR,$dist_OMA,$dist_PRC,$dist_PIN,$low_inventory=0,$is_serialized=0,$no_description)
	{
		$this->db->from('items');
		if ($solo_dvd !=0 )
                {
                        $this->db->where('category =','DVD');
		}
		if ($solo_cd !=0 )
                {
                        $this->db->where('category =','CD');
                }
		if ($dist_AFC !=0 )
                {
                        $this->db->where('supplier_id =','3');
                }
                if ($dist_DBN !=0 )
                {
                        $this->db->where('supplier_id =','2');
                }
                if ($dist_GUS !=0 )
                {
                        $this->db->where('supplier_id =','15');
                }
                if ($dist_IND !=0 )
                {
                        $this->db->where('supplier_id =','7');
                }
                if ($dist_LEF !=0 )
                {
                        $this->db->where('supplier_id =','8');
                }
                if ($dist_MAR !=0 )
                {
                        $this->db->where('supplier_id =','5');
                }
                if ($dist_OMA !=0 )
                {
                        $this->db->where('supplier_id =','4');
                }
                if ($dist_PTC !=0 )
                {
                        $this->db->where('supplier_id =','6');
                }
                if ($dist_PIN !=0 )
                {
                        $this->db->where('supplier_id =','9');
                }		

		if ($low_inventory !=0 )
		{
//			$fixwhere="quantity<reorder_level or quantity<=reorder_level";
			$this->db->where('quantity <= reorder_level');
//			$this->db->where($fixwhere);
//			$this->db->or_where(
		}
		if ($is_serialized !=0 )
		{
			$this->db->where('is_serialized',1);
		}
		if ($no_description!=0 )
		{
			$this->db->where('description','');
		}
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
//                $this->db->limit($limit);
//                $this->db->offset($offset);

		return $this->db->get();
	}

	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}

	/*
	Get an item id given an item number
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->where('item_number',$item_number);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->where_in('item_id',$item_ids);
		$this->db->order_by("item", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data,$item_id=false)
	{
		if (!$item_id or !$this->exists($item_id))
		{
			if($this->db->insert('items',$item_data))
			{
				$item_data['item_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('item_id', $item_id);
		return $this->db->update('items',$item_data);
	}

	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items',$item_data);
	}

	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items', array('deleted' => 1));
 	}
        /*
        Activar Lista de articulos
        */
        function activar_list($item_ids)
        {
                $this->db->where_in('item_id',$item_ids);
                return $this->db->update('items', array('deleted' => 0));
        }

	

 	/*
	Get search suggestions to find items
	*/


	function get_search_suggestions($search,$limit=25)
	{
//		$suggestions = array();
//                $this->db->select('category');
//                $this->db->from('items');
//              $this->db->where('deleted',0);
//                $this->db->distinct();
//                $this->db->like('category', $search);
//                $this->db->order_by("category", "asc");
//                $this->db->group_by("category");
//                $by_category = $this->db->get();
//                foreach($by_category->result() as $row)
//                {
//                        $suggestions[]=$row->category;
//                }





		$this->db->from('items');
		$this->db->like('name', $search);
//		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}
                $this->db->from('items');
//                $this->db->where('deleted',0);
                $this->db->like('location', $search);
                $this->db->order_by("location", "asc");
		$this->db->group_by("location");
                $by_location = $this->db->get();
                foreach($by_location->result() as $row)
                {
                        $suggestions[]=$row->location;
                }

//                $this->db->from('items');
//                $this->db->where('deleted',0);
//                $this->db->distinct();
//                $this->db->like('description', $search);
//		$this->db->order_by("description", "asc");
//                $this->db->group_by("description");
//                $by_description = $this->db->get();
//                foreach($by_description->result() as $row)
//                {
//                        $suggestions[]=$row->description;
//                }

		$this->db->from('items');
		$this->db->like('item_number', $search);
//		$this->db->where('deleted',0);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_number;
		}


		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}



        function get_item_search_suggestions_sale($search,$limit=50)
        {
                $this->db->from('items');
                $this->db->where('deleted',0);
                $this->db->like('name', $search);
                $this->db->order_by("name", "asc");
                $by_name = $this->db->get();
                foreach($by_name->result() as $row)
                {
                        $suggestions[]=$row->item_id.'|'.$row->name;
                }
                $this->db->from('items');
                $this->db->where('deleted',0);
                $this->db->like('item_number', $search);
                $this->db->order_by("item_number", "asc");
                $by_item_number = $this->db->get();
                foreach($by_item_number->result() as $row)
                {
                        $suggestions[]=$row->item_id.'|'.$row->item_number;
                }

                //only return $limit suggestions
                if(count($suggestions > $limit))
                {
                        $suggestions = array_slice($suggestions, 0,$limit);
                }
                return $suggestions;
	}
	function get_item_search_suggestions($search,$limit=50)
	{

		$this->db->from('items');
//		$this->db->where('deleted',0);
		$this->db->like('name', $search);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->name;
		}
                $this->db->from('items');
//                $this->db->where('deleted',0);
                $this->db->like('location', $search);
                $this->db->order_by("location", "asc");
                $by_location = $this->db->get();
                foreach($by_location->result() as $row)
                {
                        $suggestions[]=$row->item_id.'|'.$row->location;
                }
//                $this->db->from('items');
//                $this->db->where('deleted',0);
//                $this->db->distinct();
//                $this->db->like('description', $search);
//                $this->db->order_by("description", "asc");
//                $by_description = $this->db->get();
//                foreach($by_description->result() as $row)
//                {
//                        $suggestions[]=$row->item_id.'|'.$row->description;
//                }


		$this->db->from('items');
//		$this->db->where('deleted',0);
		$this->db->like('item_number', $search);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->item_number;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	function get_category_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->category;
		}

		return $suggestions;
	}
        function get_location_suggestions($search)
        {
                $suggestions = array();
                $this->db->distinct();
                $this->db->select('location');
                $this->db->from('items');
                $this->db->like('location', $search);
                $this->db->where('deleted', 0);
                $this->db->order_by("location", "asc");
                $by_category = $this->db->get();
                foreach($by_category->result() as $row)
                {
                        $suggestions[]=$row->location;
                }

                return $suggestions;
        }


	/*
	Preform a search on items
	*/
	function search($search)
	{
		$this->db->from('items');
		$this->db->where("(item_number LIKE '".$this->db->escape_like_str($search)."' or name LIKE '%".$this->db->escape_like_str($search)."%')");
		
//		$this->db->where("(item_number LIKE '".$this->db->escape_like_str($search)."' or name LIKE '%".$this->db->escape_like_str($search)."%')");
//                if ($solo_dvd !=0 )
  //              {
//			$this->db->where("(item_number LIKE '".$this->db->escape_like_str($search)."' or name LIKE '%".$this->db->escape_like_str($search)."%') AND category ='DVD' ");
  //              }
    //            if ($solo_cd !=0 )
      //          {
          //              $this->db->where('category =','CD');
        //        }
//		$this->db->from('items');
//		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or item_number LIKE '%".$this->db->escape_like_str($search)."%' or location LIKE '%".$this->db->escape_like_str($search)."%' or description LIKE '%".$this->db->escape_like_str($search)."%' or category LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
//		$this->db->where("(item_number LIKE '".$this->db->escape_like_str($search)."' or name LIKE '%".$this->db->escape_like_str($search)."%')");
		$this->db->order_by("name", "asc");
		return $this->db->get();	
	}

	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get();
	}
}
?>
