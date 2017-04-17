<?php
echo __LINE__;exit;
	//...................Database functions start.................
	function dbconnect($dbname,$host="localhost",$user="root",$pass="")
	{
		hb_mysql_connect($host,$user,$pass) or die ("Could not connect with database");
		hb_mysql_select_db($dbname) or die ("Database not available");
	}

	//................insert new record to database [start].................................
	function ins_rec($tab,$array,$disp=false)
	{
		$array = add_slashes($array);

		$qry = "insert into $tab set ";
		if (count($array) > 0)
		{
			foreach ($array as $k=>$v)
			{
				$qry .= "`$k`='".$v."',";
			}
		}

		$qry=trim($qry,",");

		if ($disp)
			echo $qry;

		$err = hb_mysql_query($qry);

		if (!$err)
		{
			echo hb_mysql_error();
			return false;
		}
		else
		{
			return hb_mysql_insert_id();
		}
	}
	//................insert new record to database [end].................................

	//................update record from database [start].................................
	function upd_rec($tab,$array,$where="1=1",$disp=false)
	{
		$array = add_slashes($array);
		$qry = "update $tab set ";
		if (count($array) > 0)
		{
			foreach ($array as $k=>$v)
			{
				$qry .= "$k='".$v."',";
			}
		}

		$qry=trim($qry,",")." where ".$where;
		if ($disp)
			echo $qry;

		$err = hb_mysql_query($qry);

		if (!$err)
		{
			echo hb_mysql_error();
			return false;
		}
		else
			return true;
	}
	//................update record from database [end].................................

	//................delete record from database [start].................................
	function del_rec($tab,$where="1=1",$disp=false)
	{
		$qry = "delete from $tab where $where";
		if ($disp)
			echo $qry;

		$err = hb_mysql_query($qry);
		if (!$err)
		{
			echo hb_mysql_error();
			return false;
		}
		else
			return true;
	}
	//................delete record from database [end].................................

	//...............................select rows from a table [start]................
	function sel_rec ($tab,$fields="*",$where="1=1",$orderby="1",$order="desc",$limit="",$disp=false)
	{
		/*if($fields=="*")
			$fields="*";
		else
			$fields="$fields";*/

        $qry = "select $fields from $tab where $where order by $orderby $order $limit";
		if ($disp)
			echo $qry;

		$res = hb_mysql_query($qry);
		if (!$res)
			echo hb_mysql_error();

		if (hb_mysql_num_rows($res) > 0)
			return $res;
		else
			return false;

	}
	function sel_total_rec ($tab,$fields="*",$where="1=1")
	{
		/*if($fields=="*")
			$fields="*";
		else
			$fields="$fields";*/

		$qry = "select $fields from $tab where $where";

		if ($disp)
			echo $qry;

		$res = hb_mysql_query($qry);
		echo hb_mysql_error();

		if (!$res)
			echo hb_mysql_error();

		if (hb_mysql_num_rows($res) > 0)
			return hb_mysql_num_rows($res);
		else
			return 0;

	}
	//...............................select rows from a table [end]................

	//...............................select  single row from a table [start]................
	function single_row($tab,$fields="*",$where="1=1",$orderby="1",$order="desc",$disp=false)
	{
		 $res = sel_rec($tab,$fields,$where,$orderby,$order,$disp);

		if ($res)
			return strip_slashes(hb_mysql_fetch_array($res));
		else
			return false;
	}
	//...............................select  single row from a table [end]................

	//...............................select single value from a table [start]................
	function get_single_value($tab,$fields,$where="1=1",$orderby="1",$order="desc",$disp=false)
	{
		$res = sel_rec($tab,$fields,$where,$orderby,$order,$disp);
		if ($res)
		{
			$val = hb_mysql_fetch_array($res);
			return strip_slashes($val[$fields]);
		}
		else
			return false;
	}
	//...............................select single value from a table [end]................


	//...............................check for duplication row in a table while adding new row [start]................
	function is_dup_add($table,$field,$value,$disp=false)
	{
		$q = "select ".$field." from ".$table." where ".$field." = '".$value."'";
		if ($disp)
			die($q);
		$r = hb_mysql_query($q);
		if(hb_mysql_num_rows($r) > 0)
			return true;
		else
			return false;
	}
	//...............................check for duplication row in a table while adding new row [end]................

	//...............................check for duplication row in a table while updating any row [start]................
	function is_dup_edit($table,$field,$value,$tableid,$id,$disp=false)
	{
		$q = "select ".$field." from ".$table." where ".$field." = '".$value."' and ".$tableid."!= '".$id."'";
		if ($disp)
			die($q);
		$r = hb_mysql_query($q);
		if(!$r)
			echo hb_mysql_error();
		if(hb_mysql_num_rows($r) > 0)
			return true;
		else
			return false;
	}
	function get_discount($discount_id){
	if($discount_id !="" && $discount_id > 0){
	$select_discount="select * from discount_type1 where discount_id=".$discount_id;
	$discount_result=hb_mysql_query($select_discount)or die(hb_mysql_error());
	$discount_row=hb_mysql_fetch_array($discount_result);
	return $discount_row['name'];
	}
	}

	function get_acctype($accid){
	if($accid !="" && $accid > 0){
	$select_acctype="select * from account_type1 where account_id=".$accid;
	$acctype_result=hb_mysql_query($select_acctype)or die(hb_mysql_error());
	$acctype_row=hb_mysql_fetch_array($acctype_result);
	return $acctype_row['name'];
	}
	}
	//...............................check for duplication row in a table while updating any row [end]................
	//...................Database functions end.................
?>
