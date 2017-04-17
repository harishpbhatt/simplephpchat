<?php
	function hb_array_trim($a) 
	{
		if(is_array($a))
		{
			$new_array = array();
			foreach($a as $element)
			{
				if($element != '')
				{
					$new_array[] = $element;
				}
			}
			return $new_array;
		}
		else
		{
			echo "Warning: hb_array_trim() [function.array-trim]: The argument should be an array";
		}
	}
	function hb_our_format($csl)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		if($csl != '')
		{
			$csl = ",".$csl.",";
		}
		return $csl;
	}
	function hb_count_csl($csl)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		// Now create array
		if($csl != "")
		{
			$csl_array = explode(",",$csl);
		}
		else
		{
			$csl_array = array();
		}
		$csl_total = count($csl_array);
		return $csl_total;
	}
	function hb_insert_into_csl($csl,$new_value)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		// Now create array
		$csl_array = explode(",",$csl);
		$csl_array = hb_array_trim($csl_array);
		// Now insert the new value into the array
		$csl_array[] = $new_value;
		// Now create csl
		$csl = hb_our_format(implode(",",$csl_array));
		return $csl;
	}
	function hb_update_csl($csl,$old_value,$new_value)
	{
		$csl = hb_our_format($csl);
		$old_value = ",".$old_value.",";
		$new_value = ",".$new_value.",";
		$csl = str_replace($old_value,$new_value,$csl);
		$csl = hb_our_format($csl);
		return $csl;
	}
	function hb_delete_from_csl($csl,$old_value)
	{
		$csl = hb_our_format($csl);
		$old_value = ",".$old_value;
		$new_value = "";
		$csl = str_replace($old_value,$new_value,$csl);
		$csl = hb_our_format($csl);
		return $csl;
	}
	function hb_select_from_csl($csl,$old_value)
	{
		$csl = hb_our_format($csl);
		$old_value = ",".$old_value.",";
		$csl = strstr($csl,$old_value);
		return $csl;
	}
	function hb_insert_into_csl_for_category_mapping($csl,$new_value)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		// Now create array
		$csl_array = explode(",",$csl);
		$csl_array = hb_array_trim($csl_array);
		// Now insert the new value into the array
		$csl_array[] = trim($new_value,",");
		// Now create csl
		$csl = hb_our_format(implode(",",$csl_array));
		return $csl;
	}
	function hb_update_csl_recursively($csl,$old_value,$new_value)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		// Now create array
		$csl_array = explode(",",$csl);
		$csl_array = hb_array_trim($csl_array);
		$new_csl_array = array();
		$is_replaced = false;
		foreach($csl_array as $element)
		{
			if($element == trim($old_value,','))
			{
				$new_csl_array[] = trim($new_value,',');
			}
			else
			{
				$new_csl_array[] = $element;
			}
		}
		// Now create csl
		$csl = hb_our_format(implode(",",$new_csl_array));
		return $csl;
	}
	function hb_delete_from_csl_recursively($csl,$old_value)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		// Now create array
		$csl_array = explode(",",$csl);
		$csl_array = hb_array_trim($csl_array);
		$new_csl_array = array();
		$is_deleted = false;
		$old_value = hb_filter_csl_for_query($old_value);
		$old_valu_array = explode(",",$old_value);
		
		foreach($old_valu_array as $old_value)
		{
			foreach($csl_array as $key => $element)
			{
				if($element == $old_value)
				{
					unset($csl_array[$key]);
				}
			}
		}
		// Now create csl
		$csl = hb_filter_csl_for_query(implode(",",$csl_array));
		$csl = hb_our_format($csl);
		return $csl;
	}
	function hb_filter_csl_for_query($csl)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		$csl = str_replace(", ",",",$csl);
		// Now create array
		$csl_array = explode(",",$csl);
		$csl_array = hb_array_trim($csl_array);
		$csl_array = array_unique($csl_array);
		$csl = implode(",",$csl_array);
		return $csl;
	}
	function hb_unique_csl($csl)
	{
		$csl = ltrim($csl,",");
		$csl = rtrim($csl,",");
		// Now create array
		$csl_array = explode(",",$csl);
		$csl_array = array_unique($csl_array);
		$csl = implode(",",$csl_array);
		return $csl;
	}
	function hb_get_csl_for_a_column($table_name,$value_field,$condition_field,$condition_value)
	{
		if($table_name != '' && $value_field!= '' && $condition_field != '' && $condition_value != '')
		{
			$select_query = "SELECT
								`".$value_field."`
							 FROM
							 	`".$table_name."`
							 WHERE
							 	`".$condition_field."` = '".$condition_value."'";
			$select_result = hb_mysql_query($select_query);
			$select_total = hb_mysql_num_rows($select_result);
			if($select_total > 0)
			{
				$csl_array = array();
				while($select_data = hb_mysql_fetch_array($select_result))
				{
					$csl_array[] = $select_data[$value_field];
				}
				// Now create csl
				if(!(empty($csl_array)))
				{
					$csl = hb_our_format(implode(",",$csl_array));
				}
			}
			return hb_filter_csl_for_query($csl);
		}
	}
	function hb_get_csl_for_a_column_like($table_name,$value_field,$condition_field,$condition_value)
	{
		if($table_name != '' && $value_field!= '' && $condition_field != '' && $condition_value != '')
		{
			$select_query = "SELECT
								`".$value_field."`
							 FROM
							 	`".$table_name."`
							 WHERE
							 	`".$condition_field."` like '".$condition_value."'";
			$select_result = hb_mysql_query($select_query);
			$select_total = hb_mysql_num_rows($select_result);
			if($select_total > 0)
			{
				$csl_array = array();
				while($select_data = hb_mysql_fetch_array($select_result))
				{
					$csl_array[] = $select_data[$value_field];
				}
				// Now create csl
				if(!(empty($csl_array)))
				{
					$csl = hb_our_format(implode(",",$csl_array));
				}
			}
			return hb_filter_csl_for_query($csl);
		}
	}
	
	function hb_get_list($table_name,$value_field,$condition_field,$condition_value,$result_type = "normal_list")
	{
		if($table_name != '' && $value_field!= '' && $condition_field != '' && $condition_value != '')
		{
			if($result_type == "normal_list")
			{
				return GetValue($table_name,$value_field,$condition_field,$condition_value);
			}
			else if($result_type = "csl_list")
			{
				$condition_value = hb_filter_csl_for_query($condition_value);
				if($condition_value != '')
				{
					$select_query = "SELECT
										`".$value_field."`
									 FROM
										`".$table_name."`
									 WHERE
										`".$condition_field."` in (".$condition_value.")";
					$select_result = hb_mysql_query($select_query);
					$select_total = hb_mysql_num_rows($select_result);
					if($select_total > 0)
					{
						$csl_array = array();
						while($select_data = hb_mysql_fetch_array($select_result))
						{
							$csl_array[] = $select_data[$value_field];
						}
						// Now create csl
						if(!(empty($csl_array)))
						{
							$csl = hb_our_format(implode(",",$csl_array));
						}
					}
					return hb_filter_csl_for_query($csl);
				}
			}
			else if($result_type = "array_list")
			{
				$condition_value = hb_filter_csl_for_query($condition_value);
				if($condition_value != '')
				{
					$select_query = "SELECT
										`".$value_field."`
									 FROM
										`".$table_name."`
									 WHERE
										`".$condition_field."` in (".$condition_value.")";
					$select_result = hb_mysql_query($select_query);
					$select_total = hb_mysql_num_rows($select_result);
					if($select_total > 0)
					{
						$csl_array = array();
						while($select_data = hb_mysql_fetch_array($select_result))
						{
							$csl_array[] = $select_data[$value_field];
						}
						// Now create csl
						if(!(empty($csl_array)))
						{
							$csl = hb_our_format(implode(",",$csl_array));
						}
					}
					return hb_filter_csl_for_query($csl);
				}
			}
		}
	}
	
	function hb_get_csl_for_first_name_last_name($table_name,$condition_field,$condition_value)
	{
		if($table_name != '' && $condition_field != '' && $condition_value != '')
		{
			$condition_value = hb_filter_csl_for_query($condition_value);
			$select_query = "SELECT
								`first_name`,`last_name`
							 FROM
							 	`".$table_name."`
							 WHERE
							 	`".$condition_field."` in (".$condition_value.")";
			$select_result = hb_mysql_query($select_query);
			$select_total = hb_mysql_num_rows($select_result);
			if($select_total > 0)
			{
				$csl_array = array();
				while($select_data = hb_mysql_fetch_array($select_result))
				{
					$csl_array[] = $select_data["first_name"]." ".$select_data["last_name"];
				}
				// Now create csl
				if(!(empty($csl_array)))
				{
					$csl = hb_our_format(implode(",",$csl_array));
				}
			}
			return hb_filter_csl_for_query($csl);
		}
	}
?>