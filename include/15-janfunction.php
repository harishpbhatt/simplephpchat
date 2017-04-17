<?php
	include_once("csl_specific_functions.php");
	//.............Required Functions..............
	
	//..........Login Check.............
	function is_admin_login()
	{
		if (!isset ($_SESSION[Seb_Adm_UsErId]))
		{
			print '<script language="javascript">window.location.href="index.php"</script>';
		}
	}
	
	function is_user_login($page,$flag=true)
	{
		if (!isset ($_SESSION[USERID]))
		{
			print '<script language="javascript">window.location.href="'.$page.'"</script>';
		}
	}
	
	
	function hb_return_latitude_longitude($address)
	{
	
	
		if($address != "")
		{
		
			$latitude_longitude = array();
			//$address = hb_filter_csl_for_query($address);		
			$address = hb_urlencode($address);
			
			 $url = "http://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false";
			$ggoogle_response=file_get_contents($url);
			
			
			//$google_response = hb_file_get_contents($url);
		
			//print_r( hb_json_decode($ggoogle_response));
		$google_response_object = hb_json_decode($ggoogle_response);
		 $google_response_object->status;
		
			if($google_response_object->status == "OK")
			{ // echo "<pre>";
			//print_r($google_response_object->results[0]->formatted_address);
			
				$google_latitude = $google_response_object->results[0]->geometry->location->lat;
				$google_longitude = $google_response_object->results[0]->geometry->location->lng;
				$google_formatted_address = $google_response_object->results[0]->formatted_address;
			
				$latitude_longitude["latitude"] = $google_latitude;
				$latitude_longitude["longitude"] = $google_longitude;
				$latitude_longitude["address"] = $google_formatted_address;
			}
			else
			{
				$latitude_longitude["error"] = $google_response_object;
			}
			//$latitude_longitude);
		
			return $latitude_longitude;
		}
	}
	function hb_json_decode($variable)
	{
		global $_SERVER;
		$HOST = $_SERVER['HTTP_HOST'];
		if($HOST == 'server' || $HOST == '192.168.0.2')
		{
			return;
		}
		else
		{
			return json_decode($variable);
		}
	}
	function hb_urlencode($url)
	{
		if($url != "")
		{
			return urlencode(str_replace("%","pErCeNtAgE",$url));
		}
	}
	function sub_string($str,$max)
	{
		if (strlen($str) > $max)
			return substr($str,0,$max)."...";
		else
			return $str;
	}
	
	function get_pagetitle($str)
	{
		return str_replace("_"," ",strtoupper(substr($str,0,1)).substr($str,1));
	}
	
	function get_pagename()
	{
		$arr=explode("/",$_SERVER['PHP_SELF']);
		return $arr[count($arr)-1];
	}
	
	//................ get random password start...........
	function generate_password($len)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		for($i=0; $i<$len; $i++) $r_str .= substr($chars,rand(0,strlen($chars)),1);
		return $r_str;
	}
	//................ get random password end...........
	
	
	//................ get random password start...........
	function generate_randomnumber($len)
	{
		$chars = "0123456789";
		for($i=0; $i<$len; $i++) 
			$r_str .= substr($chars,rand(0,strlen($chars)),1);
		return $r_str;
	}
	//................ get random password end...........

	function upload_image($files, $dir, $oldfile ,$prefix)
	{
		if (!hb_is_dir ($dir))
		{
			mkdir($dir,0777);
			chmod($dir,0777);	
		}
	
		if ($oldfile != "" && hb_is_file($dir.$oldfile))
		{
			unlink($dir.$oldfile);
		}
		
		$filename = $prefix."".rand(0,999999999999)."-".$files[name];
		
		if (hb_is_file($dir.$filename))
			$filename = $prefix."".rand(0,999999999999)."-".rand(0,999999999999)."-".$files[name];
		
		//echo 	$dir.$filename; exit;
		if (move_uploaded_file($files[tmp_name],$dir.$filename))
			return $filename;
		else
			return false;
	}

	function getModifiedUrlNamechange($catnm)
	{
		$catnm1=ereg_replace("[^A-Za-z0-9]","-",$catnm);
		return $catnm1;
	}



	function getmetadate($table,$where,$disp=false)
	{
		$metaarray = array();
		$sel = "select title,meta_title,meta_keyword,meta_description,seo_detail from $table where ".$where;
		if ($disp==true)
			echo $sel;
		$sel_qur = hb_mysql_query($sel);
		$totrows = hb_mysql_num_rows($sel_qur);
		if($totrows > 0)
		{
			$sel_obj = hb_mysql_fetch_array($sel_qur);
			array_push($metaarray,$sel_obj['title']); 
			array_push($metaarray,$sel_obj['meta_title']); 		
			array_push($metaarray,$sel_obj['meta_keyword']); 
			array_push($metaarray,$sel_obj['meta_description']); 		
			array_push($metaarray,$sel_obj['seo_detail']); 		
		}
		return $metaarray;
	}
	function getglobalmetadata($table,$where='1=1')
	{
		$metaarray = array();
		$sel = "select title,meta_title,meta_keyword,meta_description,seo_detail from global_meta_tag where ".$where;
		$sel_qur = hb_mysql_query($sel);
		$totrows = hb_mysql_num_rows($sel_qur);
		if($totrows > 0)
		{
			$sel_obj = hb_mysql_fetch_array($sel_qur);
			array_push($metaarray,$sel_obj['title']); 
			array_push($metaarray,$sel_obj['meta_title']); 		
			array_push($metaarray,$sel_obj['meta_keyword']); 
			array_push($metaarray,$sel_obj['meta_description']); 		
			array_push($metaarray,$sel_obj['seo_detail']); 		
		}
		return $metaarray;
	}


	function getCreditCard($card="")
	{
		$card_arr = array(	"AmEx"	=>	"American Express",
							"MasterCard"=>	"MasterCard",
							"Visa" 	=> 	"Visa",
							"Dino" 	=> 	"Discover",						
							);
		$cardopt="";					
		foreach($card_arr as $key1=>$valu){
			if($key1==$card)
				$cardopt .= "<option value=".$key1." selected>$valu</option>";
			else
				$cardopt .= "<option value=".$key1.">$valu</option>";
		}
		return $cardopt;
	}

	function getMonth($id="")
	{	
			
		$cur_mn = date("m");
	
		$mon=array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		
		$tMonth=$mon;
		$actMonth=$tMonth;
		$motmOption="";
		for($m=1; $m < count($actMonth); $m++)
		{
			if($m == $id)
				$motmOption .="<option value='$m' selected=selected>$actMonth[$m]</option>";
			else
				$motmOption .="<option value='$m'>$actMonth[$m]</option>";						
		}
		
		return $motmOption;
	}
	
	function exporttocsv($tab,$fields='*',$where='1=1',$orderby='1',$order='desc',$filename='export.csv')
	{
		$csv_terminated = "\n";
		$csv_separator = ",";
		$csv_enclosed = '"';
		$csv_escaped = "\\";
		$sql_query = "select $fields from $tab where $where order by $orderby $order";
	 
		// Gets the data from the database
		$result = hb_mysql_query($sql_query);
		$fields_cnt = hb_mysql_num_fields($result);
	 
		$schema_insert = '';
		for ($i = 0; $i < $fields_cnt; $i++)
		{
			$l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
				stripslashes(hb_mysql_field_name($result, $i))) . $csv_enclosed;
			$schema_insert .= $l;
			$schema_insert .= $csv_separator;
		} // end for
	 
		$out = trim(substr($schema_insert, 0, -1));
		$out .= $csv_terminated;

		// Format the data
		while ($row = hb_mysql_fetch_array($result))
		{
			$schema_insert = '';
			for ($j = 0; $j < $fields_cnt; $j++)
			{
				if ($row[$j] == '0' || $row[$j] != '')
				{
					if ($csv_enclosed == '')
					{
						$schema_insert .= $row[$j];
					} else
					{
						$schema_insert .= $csv_enclosed .
						str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
					}
				} else
				{
					$schema_insert .= '';
				}
	 
				if ($j < $fields_cnt - 1)
				{
					$schema_insert .= $csv_separator;
				}
			} // end for
	 
			$out .= $schema_insert;
			$out .= $csv_terminated;
		} // end while
	 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($out));
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		//header("Content-type: text/csv");
		//header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=$filename");
		echo $out;
		exit;
	}
	
	function add_slashes($var)
	{
		if (is_array($var))
		{
			if (count($var) > 0)
			{
				foreach ($var as $k=>$v)	
				{
					$var[$k] = addslashes($v);
				}
			}
			return $var;
		}
		else
			return addslashes($var);
	}
	
	function strip_slashes($var)
	{
		if (is_array($var))
		{
			if (count($var) > 0)
			{
				foreach ($var as $k=>$v)	
				{
					$var[$k] = stripslashes($v);
				}
			}
			return $var;
		}
		else
			return stripslashes($var);
	}
	
	
	function FunUserTimezones($tid)
	{
		$cntQuery="select * from userstimezones";
		$cntResult=hb_mysql_query($cntQuery);
		if($cntResult)
		{
			while($cntRow=hb_mysql_fetch_object($cntResult))
			{
				if($tid == $cntRow->id)
					$cntOption .="<option value='$cntRow->id' selected>".stripslashes($cntRow->tname)."</option>";
				else
					$cntOption .="<option value='$cntRow->id'>$cntRow->tname</option>";
			}
			return $cntOption;
		}
		else
			echo hb_mysql_error();
	}



function getdayofweek($no)
{
	switch ($no)
	{
		
		case 0:
			$day="Monday"; break;
		case 1:
			$day="Tuesday"; break;
		case 2:
			$day="Wednesday"; break;
		case 3:
			$day="Thursday"; break;
		case 4:
			$day="Friday"; break;
		case 5:
			$day="Saturday"; break;	
		case 6:
			$day="Sunday"; break;					
	}
	return $day;
}

function sub_words($bsdesc,$length=100)
{
	$bphrase = $bsdesc; 
	$babody = str_word_count($bphrase,2);
	if(count($babody) > $bthreshold_length)
	{ 
		$btbody = array_keys($babody);
		$bpro_sdesc= substr($bphrase,0,$btbody[$bthreshold_length]) . "...";	 		  
	} 
	else
	{ 
		$bpro_sdesc=$bsdesc;
	}	
	return $bpro_sdesc;
}

function smartCopy($source, $dest, $folderPermission=0755,$filePermission=0644){
# source=file & dest=dir => copy file from source-dir to dest-dir
# source=file & dest=file / not there yet => copy file from source-dir to dest and overwrite a file there, if present

# source=dir & dest=dir => copy all content from source to dir
# source=dir & dest not there yet => copy all content from source to a, yet to be created, dest-dir
    $result=false;
   
    if (hb_is_file($source)) { # $source is file
        if(hb_is_dir($dest)) { # $dest is folder
            if ($dest[strlen($dest)-1]!='/') # add '/' if necessary
                $__dest=$dest."/";
            $__dest .= basename($source);
            }
        else { # $dest is (new) filename
            $__dest=$dest;
            }
        $result=copy($source, $__dest);
        chmod($__dest,$filePermission);
        }
    elseif(hb_is_dir($source)) { # $source is dir
        if(!hb_is_dir($dest)) { # dest-dir not there yet, create it
            @mkdir($dest,$folderPermission);
            chmod($dest,$folderPermission);
            }
        if ($source[strlen($source)-1]!='/') # add '/' if necessary
            $source=$source."/";
        if ($dest[strlen($dest)-1]!='/') # add '/' if necessary
            $dest=$dest."/";

        # find all elements in $source
        $result = true; # in case this dir is empty it would otherwise return false
        $dirHandle=opendir($source);
        while($file=readdir($dirHandle)) { # note that $file can also be a folder
            if($file!="." && $file!="..") { # filter starting elements and pass the rest to this function again
#                echo "$source$file ||| $dest$file<br />\n";
                $result=smartCopy($source.$file, $dest.$file, $folderPermission, $filePermission);
                }
            }
        closedir($dirHandle);
        }
    else {
        $result=false;
        }
    return $result;
    }
function makedir($dirpath,$permission="0777")
{
	if(!hb_is_dir($dirpath))
	{
		mkdir($dirpath);
		chmod($dirpath,$permission);		
	}	
}
function unzip($zipfile,$foldernm)
{
    $zip = zip_open($zipfile);
    while ($zip_entry = zip_read($zip))    {
        zip_entry_open($zip, $zip_entry);
        if (substr(zip_entry_name($zip_entry), -1) == '/') {
            $zdir = substr(zip_entry_name($zip_entry), 0, -1);
            if (file_exists($foldernm."/".$zdir)) {
               // trigger_error('Directory "<b>' . $zdir . '</b>" exists', E_USER_ERROR);
                return false;
            }
            mkdir($foldernm."/".$zdir);
        }
        else {
            $name = zip_entry_name($zip_entry);
            if (file_exists($name)) {
               // trigger_error('File "<b>' . $name . '</b>" exists', E_USER_ERROR);
               // return false;
            }
            $fopen = fopen($foldernm."/".$name, "w");
            fwrite($fopen, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)), zip_entry_filesize($zip_entry));
        }
        zip_entry_close($zip_entry);
    }
    zip_close($zip);
    return true;
}

function delTree($dir) {
    $files = glob( $dir . '*', GLOB_MARK );
    foreach( $files as $file ){
        if( hb_is_dir( $file ) )
            delTree( $file );
        else
            unlink( $file );
    }
  
    if (hb_is_dir($dir)) rmdir( $dir );
  
}

function del_file($path)
{
	if (hb_is_file($path))
	{
		unlink($path);
		return true;
	}
	else
		return false;
}
function getLeadCategory($cid="")
{
	$selsql="select * from category  order by  catid";
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	while($selcntRow=hb_mysql_fetch_object($selrs))
	{
		if($stid == $selcntRow->catid)
			$cntOption .="<option value='$selcntRow->catid' selected>$selcntRow->category</option>";
		else
			$cntOption .="<option value='$selcntRow->catid'>$selcntRow->category</option>";
	}
	return $cntOption;
}

function getLeadMainCategory($cid="")
{
	$selsql="select * from category where maincatid='0' order by  catid";
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	while($selcntRow=hb_mysql_fetch_object($selrs))
	{
		if($cid == $selcntRow->catid)
			$cntOption .="<option value='$selcntRow->catid' selected>$selcntRow->category</option>";
		else
			$cntOption .="<option value='$selcntRow->catid'>$selcntRow->category</option>";
	}
	return $cntOption;
}

function getLeadSubCategory($catid="",$stid="")
{
	$selsql="select * from category where maincatid='".$catid."' order by  catid";
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	while($selcntRow=hb_mysql_fetch_object($selrs))
	{
		if($stid == $selcntRow->catid)
			$cntOption .="<option value='$selcntRow->catid' selected>".$selcntRow->category."</option>";
		else
			$cntOption .="<option value='$selcntRow->catid'>".$selcntRow->category."</option>";
	}
	return $cntOption;
}



function getLeadsBudget($cid="")
{
	$cntOption="<option value='Under $500' ";
		if ($cid=="Under $500") {$cntOption.= " selected ";} 
		$cntOption.=" >Under $500</option>";
	
	for($i=500;$i<=9500;$i+=500)
	{		
		$lasval=$i+500;
		$value="$".$i." - $".$lasval;
		if($cid==$value)
			$cntOption .="<option value='$value' selected>$value</option>";
		else
			$cntOption .="<option value='$value'>$value</option>";		
	}
	$cntOption.="<option value='$10000+' ";
		if($cid=="$10000+") {$cntOption.=" selected ";  }
		$cntOption.=" >$10000+</option>";
	
	return $cntOption;
}
function getLeadsBids($cid="")
{
	$cntOption="";
	for($i=1;$i<=6;$i++)
	{		
		if($cid==$i)
			$cntOption .="<option value='$i' selected>$i</option>";
		else
			$cntOption .="<option value='$i'>$i</option>";		
	}
	
	return $cntOption;
}
function dateDiff($date1, $date2) {
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
     $secs = $date1 - $date2;
     if ($secs < 60) 
	 {
	 	$second=$secs." seconds";
	 }
     $minutes = round($secs / 60);
   	 if ($minutes < 60) 
	 {
	 	$minute=$minutes." min.";		
	 }
     $hours = round($minutes / 60);
     if ($hours < 60) 
	 {
	 	if($hours==1)
			$hour=$hours." hour";
		else
			$hour=$hours." hours";
	 }
    	$days = round($hours / 24);
    if ($days > 0) 
	{
	 	if($days==1)
			$cont=$days." day";
		else
			$cont=$days." days";		  
	}
	elseif($hours > 0)
		$cont=$hour;
	elseif($minutes > 0)
		$cont=$minute;
	//elseif($secs > 0)
		//$cont=$second;
	else
		$cont="closed";
	return $cont;
		
	 
}
function getStateTot($cid)
{
	$selsql="select * from state where id_country='$cid'";
	$selrs=hb_mysql_query($selsql);
	$seltotal=hb_mysql_num_rows($selrs);
	return $seltotal;
}
function getCountry($cid="")
{	
	$cntOption="";	
	$cntQuery1="select * from country where id_country='170'";	
	$cntResult1=hb_mysql_query($cntQuery1);
	$cntRow1=hb_mysql_fetch_object($cntResult1);	
	
	if($cid == $cntRow1->id_country)
		$cntOption .="<option value='$cntRow1->id_country' selected>$cntRow1->country_name</option>";
	else
		$cntOption .="<option value='$cntRow1->id_country'>$cntRow1->country_name</option>";		
	
	$cntQuery="select * from country where id_country!='170' order by id_country";
	
	$cntResult=hb_mysql_query($cntQuery);
	//echo hb_mysql_error();
	if($cntResult)
	{
		while($cntRow=hb_mysql_fetch_object($cntResult))
		{
			$conid=$cntRow->id_country;
			$statetot=getStateTot($conid);
			if($statetot > 1)
			{
				if($cid == $cntRow->id_country)
					$cntOption .="<option value='$cntRow->id_country' selected>$cntRow->country_name</option>";
				else
					$cntOption .="<option value='$cntRow->id_country'>$cntRow->country_name</option>";
			}
		}
		return $cntOption;
	}
	else
		echo hb_mysql_error();
}

/*function create_combo($name,$id,$table,$where="1=1",$value="",$dispval="",$default="",$class="")
{
	$combo = '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">
				<option value="">select</option>';
	
	$sel = "select $value,$dispval from $table where $where";
	$res = hb_mysql_query($sel);
	
	if (hb_mysql_num_rows($res))
	{
		while ($val = hb_mysql_fetch_array($res))
		{
			$combo .= '<option value="'.$val[$value].'"';
			if ($default==$val[$value])
				$combo .= ' selected="selected" ';
			$combo .='>'.stripslashes($val[$dispval]).'</option>';
					
		}
	}
	
	
	$combo .='</select>';
	return $combo; 
}*/
function create_combo_id($name,$id,$table,$where="1=1",$value="",$dispval="",$default="",$class="")
{
	$combo = '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">
				<option value="">select</option>';
	
	$sel = "select $value,$dispval from $table where $where";
	$res = hb_mysql_query($sel);
	
	if (hb_mysql_num_rows($res))
	{
		while ($val = hb_mysql_fetch_array($res))
		{
			$combo .= '<option value="'.$val[$value].'"';
			if ($default==$val[$value])
				$combo .= ' selected="selected" ';
			$combo .='>'.stripslashes($val[$dispval]).'</option>';
					
		}
	}
	
	
	$combo .='</select>';
	return $combo; 
}

function create_combo ($name,$table,$where,$field,$value,$defult="",$class="",$onchange="")
{

	$sel= "select $field,$value from $table where $where";
	$res = hb_mysql_query($sel);
	
	if (!$res)
		echo hb_mysql_error();
		
	$combo="";
	$combo = '<select class="'.$class.'" name="'.$name.'" id="'.$name.'" onchange="'.$onchange.'">
		<option value="">Select</option>';	
	if (hb_mysql_num_rows($res) > 0)
	{
		while ($val = hb_mysql_fetch_object($res))
		{
			
			$combo .= '<option ';
			
			if ($val->$field==$defult)
				$combo .='selected="selected"';
				
			$combo .= ' value="'.$val->$field.'">'.$val->$value.'</option>';
		}
	}
	$combo .= '</select>';
	return $combo; 
}

function getCategory($cid="")
{	
	$cntQuery="select * from category where maincatid='0' and status='1' order by category";
	$cntResult=hb_mysql_query($cntQuery);
	if($cntResult)
	{
		while($cntRow=hb_mysql_fetch_object($cntResult))
		{
			if($cid == $cntRow->catid)
				$cntOption .="<option value='$cntRow->catid' selected>$cntRow->category</option>";
			else
				$cntOption .="<option value='$cntRow->catid'>$cntRow->category</option>";
		}
		return $cntOption;
	}
	else
		echo hb_mysql_error();
}
function getSubCategory($cid="",$subid="")
{	
	$subcntQuery="select * from category where maincatid='$cid' and maincatid!=0 and status='1'";
	$subcntResult=hb_mysql_query($subcntQuery);
	
	if($subcntResult)
	{
		while($subcntRow=hb_mysql_fetch_object($subcntResult))
		{
			if($subid == $subcntRow->catid)
				$subcntOption .="<option value='$subcntRow->catid' selected>$subcntRow->category</option>";
			else
				$subcntOption .="<option value='$subcntRow->catid'>$subcntRow->category</option>";
		}
		return $subcntOption;
	}
	else
		echo hb_mysql_error();
}
function getleads($whr="",$cond="dateleft",$type="latest",$order="datedif",$orderby="desc",$user="all",$limit="0,15")
{
	$where = "status='1'";
	
	if ($whr != "")
		$where .= " ".$whr;
	if($cond=="dateleft")
		$where .= " and datediff(leads_enddate, now()) >= 0";
		
	if ($type=="ending")
	{
		$where .= " and datediff(leads_enddate, now()) < 7 ";
	}
	elseif($type=="just_sold")
	{
		$where .= " and (leads_id in (select distinct(leads_id) from lead_mng where p_date < date_sub(curdate(),interval 1 DAY)))";
	}
	elseif($type=="unsold")
	{
		$where .= " and (leads_id not in (select distinct(leads_id) from lead_mng where p_date < date_sub(curdate(),interval 1 DAY))) and leads_enddate < curdate()";
	}
	if($user!="all")
	{
		$where .= " and seller='$user'";
	}		
	$selsql="select *,datediff(leads_enddate, now()) as datedif from leads where $where order by $order $orderby limit $limit"; 
	$selrs=hb_mysql_query($selsql);
		echo hb_mysql_error();
	$total=hb_mysql_affected_rows();
	$arr="";
	$curdate=date('Y-m-d');
	if (hb_mysql_num_rows($selrs) > 0)
	{
		$date2=date("Y-m-d H:i");
		while($selrows=hb_mysql_fetch_array($selrs))
		{
			$lead_date=$selrows[leads_enddate];
			//$arr[dayleft][]=dateDiff($lead_date,$curdate);					
			//$arr[datedif][]=$selrows[datedif];
			$arr[leads_id][]=$selrows[leads_id];
			$arr[leads_bids][] = $selrows[leads_bids];
			$arr[leads_charge][] = $selrows[leads_charge];
			$arr[leads_profile][] = $selrows[leads_profile];
			$arr[leads_enddate][]= date("Y-m-d",strtotime($selrows[leads_enddate]));
			$arr1[leads_enddate][]=$selrows[leads_enddate];			
			$datediff=dateDiff($lead_date,$date2);
			$arr[datedif][]=$datediff;
			$arr[leads_title][] = stripslashes($selrows[leads_title]);
			$arr[leads_category][] = $selrows[leads_category];
			$arr[leads_subcategory][] = $selrows[leads_subcategory];
			$arr[leads_description][] = stripslashes($selrows[leads_description]);
			$arr[leads_budget][] = $selrows[leads_budget];
			$arr[fullname][] = stripslashes($selrows[fullname]);	
			$arr[phone][] = $selrows[phone];
			$arr[website][] = $selrows[website];
			$arr[email][] = $selrows[email];
			$arr[city][] = stripslashes($selrows[city]);
			$arr[country][] = $selrows[country];
			$arr[contact_email][] = $selrows[contact_email];
			$arr[contact_phone][] = $selrows[contact_phone];
			$arr[skype][] = $selrows[skype];
			$arr[msn][] = $selrows[msn];
			$arr[yahoo][] = $selrows[yahoo];
			$arr[other_chat][] = $selrows[other_chat];
			$arr[prefer_work][] = $selrows[prefer_work];
			$arr[local_city][] = $selrows[local_city];
			$arr[local_state][] = $selrows[local_state];
			$arr[skill_level][] = $selrows[skill_level];
			$arr[my_budget][] = $selrows[my_budget];
			$arr[maximum_budget][] = $selrows[maximum_budget];
			$arr[other_site][] = $selrows[other_site];
			$arr[similar_site][] = $selrows[similar_site];
			$arr[my_competition][] = $selrows[my_competition];
			$arr[reach_time][] = stripslashes($selrows[reach_time]);	
		}
	}
	else
	{
		$arr=false;
	}
	return $arr;

}
function getselleraccleads($seller,$type="all")
{
	if($type=="active")
	{
		$where=" and pstatus='0' and curdate() <= leads_enddate";		
	}
	elseif($type=="sold")
	{
		$where=" and (pstatus='1' or leads_id in(select distinct(leads_id) from lead_mng))";
	}
	elseif($type=="history")
	{
		$where=" and leads_enddate < date_sub(curdate(),interval 1 MONTH)";
	}
	$selsql="select *,datediff(leads_enddate, now()) as datedif from leads where seller='$seller' $where  order by leads_enddate";
	
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	$total=hb_mysql_affected_rows();
	$arr="";
	$curdate=date('Y-m-d');
	if (hb_mysql_num_rows($selrs) > 0)
	{
		$date2=date("Y-m-d H:i");
		while($selrows=hb_mysql_fetch_array($selrs))
		{
			$lead_date=$selrows[leads_enddate];
			//$arr[dayleft][]=dateDiff($lead_date,$curdate);					
			//$arr[datedif][]=$selrows[datedif];
			$arr[leads_id][]=$selrows[leads_id];
			$arr[leads_bids][] = $selrows[leads_bids];
			$arr[leads_enddate][]= date("Y-m-d",strtotime($selrows[leads_enddate]));
			$datediff=dateDiff($selrows[leads_enddate],$date2);
			$arr[datedif][]=$datediff;
			$arr1[leads_enddate][]=$selrows[leads_enddate];
			$arr[leads_title][] = stripslashes($selrows[leads_title]);
			$arr[leads_budget][] = $selrows[leads_budget];			
			$arr[wid][]=$selrows[wid];	
		}
	}
	else
	{
		$arr=false;
	}
	return $arr;
}
function getwatchlistleads($buyer)
{
	
	$selsql="select *,datediff( l.leads_enddate, now()) as datedif from leads l, watchlist w where l.leads_id=w.leads_id and w.buyerid='$buyer' order by datedif ";
	$selrs=hb_mysql_query($selsql);
	$total=hb_mysql_affected_rows();
	$arr="";
	$curdate=date('Y-m-d');
	if (hb_mysql_num_rows($selrs) > 0)
	{
		$date2=date("Y-m-d H:i");
		while($selrows=hb_mysql_fetch_array($selrs))
		{
			$lead_date=$selrows[leads_enddate];
			//$arr[dayleft][]=dateDiff($lead_date,$curdate);					
			//$arr[datedif][]=$selrows[datedif];
			$arr[leads_id][]=$selrows[leads_id];
			$arr[leads_bids][] = $selrows[leads_bids];
			$arr[leads_enddate][]= date("Y-m-d",strtotime($selrows[leads_enddate]));
			$datediff=dateDiff($selrows[leads_enddate],$date2);
			$arr[datedif][]=$datediff;
			$arr1[leads_enddate][]=$selrows[leads_enddate];
			$arr[leads_title][] = stripslashes($selrows[leads_title]);
			$arr[leads_budget][] = $selrows[leads_budget];			
			$arr[wid][]=$selrows[wid];	
		}
	}
	else
	{
		$arr=false;
	}
	return $arr;

}
function getpurchasedleads($buyer,$hist="all")
{

	if($hist=="my_leads")
	{
		$where=" and w.p_date >= date_sub(curdate(),interval 1 MONTH)";
	}
	elseif($hist=="history_leads")
	{
		$where=" and w.p_date < date_sub(curdate(),interval 1 MONTH)";
	}
	else
	{
		$where="";
	}
	
	$selsql="select *,datediff( l.leads_enddate, now()) as datedif from leads l, lead_mng w where l.leads_id=w.leads_id and w.buyerid='$buyer' $where order by w.lid ";
	$selrs=hb_mysql_query($selsql);
	$total=hb_mysql_affected_rows();
	$arr="";
	$curdate=date('Y-m-d');
	if (hb_mysql_num_rows($selrs) > 0)
	{
		$date2=date("Y-m-d H:i");
		while($selrows=hb_mysql_fetch_array($selrs))
		{
			$lead_date=$selrows[leads_enddate];
			//$arr[dayleft][]=dateDiff($lead_date,$curdate);					
			//$arr[datedif][]=$selrows[datedif];
			$arr[leads_id][]=$selrows[leads_id];
			$arr[leads_bids][] = $selrows[leads_bids];
			$arr[leads_enddate][]= date("Y-m-d",strtotime($selrows[leads_enddate]));
			$datediff=dateDiff($selrows[leads_enddate],$date2);
			$arr[datedif][]=$datediff;
			$arr1[leads_enddate][]=$selrows[leads_enddate];
			$arr[leads_title][] = stripslashes($selrows[leads_title]);
			$arr[leads_budget][] = $selrows[leads_budget];	
			$arr[p_date][] = $selrows[p_date];			
			$arr[lid][]=$selrows[lid];	
			
		}
	}
	else
	{
		$arr=false;
	}
	return $arr;

}
function word_wrap($desc,$length="30")
{
	$phrase = $desc; 
	$abody = str_word_count($phrase,2);
	if(count($abody) > $length)
	{ 
		$tbody = array_keys($abody);
		$short_desc1= substr($phrase,0,$tbody[$length]) . "...";	 		
	}
	else
	{ 
		$short_desc1=$desc;
	}		
	return $short_desc1; 
}	
function getleadslist()
{
	$selsql="select cat.catid, cat.category ,count(leads_id) from category cat left outer join leads l on (l.leads_category=cat.catid) where cat.status='1' group by  cat.catid order by  cat.category";
	$selrs=hb_mysql_query($selsql);
	while($selrows=hb_mysql_fetch_array($selrs))
	{
		$category=$selrows[0];
		$arr[category][]=$selrows[1];
		$arr[catid][]=$selrows[0];
		$arr[leads][]=$selrows[2];
		
	}
	return $arr;
}
function getleadsCategory($cid="")
{	
	$selsql="select category,catid from ".CATEGORY." where status='1'";
	$selrs=hb_mysql_query($selsql);
	$cntOption="";
	while($selrows=hb_mysql_fetch_array($selrs))
	{
			if($cid == $selrows[catid])
				$cntOption .="<option value='$selrows[catid]' selected>$selrows[category]</option>";
			else
				$cntOption .="<option value='$selrows[catid]'>$selrows[category]</option>";
		
		
	}
	return $cntOption;
	
}
function getleadsbudget1($cid)
{
	$selsql="select distinct(leads_budget) as leadsbudget from leads where status='1'";
	$selrs=hb_mysql_query($selsql);
	$cntOption="";
	while($selrows=hb_mysql_fetch_array($selrs))
	{
		if($cid == $selrows[leadsbudget])
				$cntOption .="<option value='$selrows[leadsbudget]' selected>$selrows[leadsbudget]</option>";
			else
				$cntOption .="<option value='$selrows[leadsbudget]'>$selrows[leadsbudget]</option>";
		
		
	}
	return $cntOption;
}
function getLeadEndDateCombo($lead_enddays,$class="jumpmanu1",$style="background-color:#e4e4e4")
{
	$combo = '<select class="'.$class.'" style="'.$style.'" name="lead_enddays" id="$lead_enddays">
	<option  value="">Select Days</option>
    <option '.($lead_enddays=="3"?"selected":"").' value="3" >3 Days</option>
    <option '.($lead_enddays=="5"?"selected":"").' value="5">5 Days</option>
    <option '.($lead_enddays=="7"?"selected":"").' value="7">7 Days</option>
    <option '.($lead_enddays=="15"?"selected":"").' value="15">15 Days</option>
    <option '.($lead_enddays=="30"?"selected":"").' value="30">30 Days</option>
</select>'	;
return $combo;
}

function getState($cid="",$stid="")
{
	$selsql="select * from state where id_country='$cid' order by state_name";
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	while($selcntRow=hb_mysql_fetch_object($selrs))
	{
		if($stid == $selcntRow->id_state)
			$cntOption .="<option value='$selcntRow->id_state' selected>$selcntRow->state_name</option>";
		else
			$cntOption .="<option value='$selcntRow->id_state'>$selcntRow->state_name</option>";
	}
	return $cntOption;
}

function get_static_pages()
{
	$res_static = sel_rec (tbl_static_page,"*","status='1'");
	if (hb_mysql_num_rows($res_static) > 0)
	{
		while ($val_static=hb_mysql_fetch_array($res_static))
		{
			$links.='<a href="'.$val_static[seo_url].'">'.$val_static[title].'</a>&nbsp;&nbsp;&nbsp;';
		}
	}
	echo rtrim($links,"&nbsp;");
}
function gettotalpurchasedamount($uid,$type)
{
	if($type=="buyer")
		$where=" and buyerid='$uid'";
	elseif($type=="seller")
		$where=" and sellerid='$uid'";	
		
	
	
	$selsql="select leads_id from lead_mng where leads_id > 0 $where";
	$selrs=hb_mysql_query($selsql);
	while($selrows=hb_mysql_fetch_object($selrs))
	{
		$leads_id=$selrows->leads_id;
		$selasql="select leads_charge from leads where leads_id='$leads_id'";
		$selars=hb_mysql_query($selasql);
		$selarows=hb_mysql_fetch_object($selars);
		$leads_charge=$selarows->leads_charge;
		$leads_total+=$leads_charge;	
	}
	return $leads_total;
}
function gettotalpurchasedamounta($diff="")
{
		
	if($diff==7)
	{
		$where=" and p_date > date_sub(curdate(),interval 7 DAY)";
	}
	elseif($diff=30)
	{
		$where=" and p_date > date_sub(curdate(),interval 30 DAY)";
	}
	else
	{
		$where="";
	}
	$selsql="select leads_id from lead_mng where leads_id > 0 $where";
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	while($selrows=hb_mysql_fetch_object($selrs))
	{
		$leads_id=$selrows->leads_id;
		$selasql="select leads_charge from leads where leads_id='$leads_id'";
		$selars=hb_mysql_query($selasql);
		$selarows=hb_mysql_fetch_object($selars);
		$leads_charge=$selarows->leads_charge;
		$leads_total+=$leads_charge;	
	}
	return $leads_total;
}
function getsoldpercentage($day="")
{
	
	if($day==30)
		$total=sel_total_rec("lead_mng","*","p_date > date_sub(curdate(),interval 30 DAY)");
	else
		$total=sel_total_rec("lead_mng","*");
	
	$selsql="select $total*100/sum(leads_bids) as total from leads";
	$selrs=hb_mysql_query($selsql);
	echo hb_mysql_error();
	$selrows=hb_mysql_fetch_object($selrs);
	$total=round($selrows->total);
	
	return $total;

}

function detailid_list($dcatid=0)
{
	unset($arr);
	$res = sel_rec(DETAIL,"detid","dcatid='".$dcatid."'","1","asc",false);
	if($res)
	{
		while ($val=hb_mysql_fetch_array($res))
		{
			$arr[]=$val[detid];
		}
		return $arr;
	}
	return false;
}

function get_product_bread_crum($prodid)
{
	global $SITE_URL;
	$prodname=get_single_value(PRODUCT,"product_name","prodid=".$prodid);
	$catid=get_single_value(PRODUCT,"catid","prodid=".$prodid);
	$catname=get_single_value(CATEGORY,"category","catid=".$catid);
	
	$retval='<a href="index.php" class="bread_crum">Home</a> <img src="'.$SITE_URL.'images/Left Arrow.jpg" border="0"> <a href="product.php" class="bread_crum">Products</a> <img src="'.$SITE_URL.'images/Left Arrow.jpg" border="0"> <a href="product.php?catid='.$catid.'" class="bread_crum">'.$catname.'</a> <img src="'.$SITE_URL.'images/Left Arrow.jpg" border="0"> '.$prodname.'';
	return $retval;
}
function get_category_bread_crum($catid)
{
	global $SITE_URL;
	$catname=get_single_value(CATEGORY,"category","catid=".$catid);
	
	$retval='<a href="index.php" class="bread_crum">Home</a> <img src="'.$SITE_URL.'images/Left Arrow.jpg" border="0"> <a href="product.php" class="bread_crum">Products</a> <img src="'.$SITE_URL.'images/Left Arrow.jpg" border="0"> '.$catname;
	return $retval;
}
function product_view($prodid)
{
	echo $ipaddress = $_SERVER['REMOTE_ADDR'];
	echo $entry=get_single_value(PRODUCT_VIEWS,"count(*)","ipaddress='".$ipaddress."' and sessionid='".$session_id."'",true);
	if($entry<=0)
	{
		$insert="insert into ".PRODUCT_VIEWS."
				set ipaddress='$ipaddress',
				sessionid='".$session_id."',
				prodid='$prodid'";
		$err=hb_mysql_query($insert);
		if(!$err)
			echo hb_mysql_query()."<br>".$insert;
	}
}

function delete_file ($dir,$file)
{
	if (hb_is_file ($dir.$file))
	{
		unlink ($dir.$file);
		return true;
	}
	else
	{
		return false;
	}
}

function xml_category()
{
	$sel = "select * from ".GALLERY_CATEGORY." where status='1' ";
	$res = hb_mysql_query($sel);
	
	if (hb_mysql_num_rows($res) > 0)
	{
		$file_content='<?phpxml version="1.0" encoding="ISO-8859-1" ?>

<adWall3D>';
		while($val = hb_mysql_fetch_array($res))
		{
			$flag = get_single_value (GALLERY,"count(*)","galcatid='".$val[galcatid]."'");
			
			if ($flag>0)
			{
				$file_content .= '<category title="'.$val[category].'"> 
		<xmlPath src="'.$val[xmlfilename].'"/>
		<image src="../'.CATEGORY_DIR.$val[image].'"/>
		<description><![CDATA['.$val[description].']]> </description>
	</category>';
			}
		}
		$file_content.='</adWall3D>';
	}
	
	$path = "../gallery1/";
	$filename = "adWall3D.xml";
	$fp=fopen($path.$filename,"w+");
	fwrite($fp,$file_content);
}

function xml_gallery($galcatid)
{
	$sel = "select * from ".GALLERY." where status='1' and galcatid='".$galcatid."' "; 
	$res = hb_mysql_query($sel);
	
	if (hb_mysql_num_rows($res) > 0)
	{
		$file_content='<?phpxml version="1.0" encoding="ISO-8859-1" ?>

<animals rows="1" columns="15">
	<items>';
		while($val = hb_mysql_fetch_array($res))
		{
			$flag = get_single_value (GALLERY,"count(*)","galcatid='".$val[galcatid]."'");
			
			if ($flag>0)
			{
				/*$file_content .= '<category title="'.$val[category].'"> 
		<xmlPath src="'.$val[xmlfilename].'"/>
		<image src="../'.CATEGORY_DIR.$val[image].'"/>
		<description><![CDATA['.$val[description].']]> </description>
	</category>';*/
	
	
				$file_content .= '<item>
				<image src="../'.GALLERY_DIR.$val[image].'" />
				<description> <![CDATA[<title>'.$val[title].'</title>'.$val[description].']]> </description>
			</item>';
			}
		}
		$file_content.='</items>
</animals>';

	$path = "../gallery1/";
	$filename = get_single_value(GALLERY_CATEGORY,"xmlfilename","galcatid='".$galcatid."'");
	$fp=fopen($path.$filename,"w+");
	fwrite($fp,$file_content);

	}
	
	
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
  //if the zip file already exists and overwrite is false, return false
  if(file_exists($destination) && !$overwrite) { return false; }
  //vars 
  $valid_files = array();
  //if files were passed in...
  if(is_array($files)) {
    //cycle through each file
    foreach($files as $file) {
      //make sure the file exists
      if(file_exists($file)) {
        $valid_files[] = $file;
      }
    }
  }
  //if we have good files...
  if(count($valid_files)) {
    //create the archive
    $zip = new ZipArchive();
    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
    }
    //add the files
    foreach($valid_files as $file) {
      $zip->addFile($file,$file);
    }
    //debug
    //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
    
    //close the zip -- done!
    $zip->close();
    
    //check to make sure the file exists
    return file_exists($destination);
  }
  else
  {
    return false;
  }
}

// Delete Parent As well as child node upto "N" Level record
function remove_all_cat($id,$tab) 
{
	
	$qlist=hb_mysql_query("SELECT * FROM ".$tab." WHERE id='$id'");
	if (hb_mysql_num_rows($qlist)>0) {
		
		 while($curitem=hb_mysql_fetch_array($qlist)) 
		 {
			 
			  remove_all_cat($curitem['id'],$tab);
		 
		 }
	
	}
	
	hb_mysql_query("DELETE FROM ".$tab." WHERE id='$id'");
}
function hb_prepare_new_critria_or($string,$array)
 {
  foreach($array as $critria)
  {
   if($critria != '')
   {
    $new_critria_array[] = str_replace("OR_CRITRIA",$critria,$string);
   }
  }
  // Now implode that array with word " OR "
  if(is_array($new_critria_array))
  {
   $new_critria = " ( ".implode(" OR ",$new_critria_array)." ) ";
  }
  else
  {
   $new_critria = $string;
  }
  return $new_critria;
 }
 
 	function hb_filter_var($variable,$validation_rule)
	{
		global $live;
		if($live)
		{
			return filter_var($variable,$validation_rule);
		}
		else
		{
			// This function should always return true in local server, so that we can have all interfaces working correctly.
			return true;
		}
	}
	function hb_php_validate($variable,$message,$back_url = '')
	{
		if(empty($variable))
		{
		?>
			<script language="javascript">
				alert("<?php echo $message;?>");
				<?php
					if($back_url != '')
					{
				?>
					window.location.href = '<?php echo $back_url;?>';
				<?php
					}
					else
					{
				?>
					window.history.go(-1);
				<?php
					}
				?>
			</script>
		<?php
		exit;
		return;
		}
	}
	function hb_php_email_validate($variable,$message,$back_url = '')
	{
		if((!(preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$variable))) || (!(hb_filter_var($variable, 274)))){
		?>
			<script language="javascript">
				alert("<?php echo $message;?>");
				<?php
					if($back_url != '')
					{
				?>
					window.location.href = '<?php echo $back_url;?>';
				<?php
					}
					else
					{
				?>
					window.history.go(-1);
				<?php
					}
				?>
			</script>
		<?php
		exit;
		return;
		}
	}
	function hb_php_query_validate($array,$message,$back_url = '')
	{
		foreach($array as $variable)
		{
			if(empty($variable))
			{
				?>
					<script language="javascript">
						alert("<?php echo $message;?>");
						<?php
							if($back_url != '')
							{
						?>
							window.location.href = '<?php echo $back_url;?>';
						<?php
							}
							else
							{
						?>
							window.history.go(-1);
						<?php
							}
						?>
					</script>
				<?php
				exit;				
				return;
			}
		}
		return;
	}
	
	function get_vorpit_date($database_date)
	{
		$vorp_date = "";
		if($database_date != "" && $database_date != '0000-00-00')
		{
			$vorp_date = explode(" ",$database_date);
			$vorp_time = $vorp_date[1];
			$vorp_date = explode("-",$vorp_date[0]);
			$vorp_date = $vorp_date[2]."/".$vorp_date[1]."/".$vorp_date[0]." ".$vorp_time;
		}
		return $vorp_date;
	}
	function get_database_date($vorpit_date)
	{
		$database_date = explode(" ",$vorpit_date);
		$database_time = $database_date["1"];
		$database_date = explode("/",$database_date[0]);
		$database_date = $database_date[2]."-".$database_date[1]."-".$database_date[0]." ".$database_time;
		return $database_date;
	}
	function get_static_page($id)
	{ 
	if($id!="" && $id > 0)
		{
			$selct_static_page="select * from tbl_static_page where id=".$id;
			$result_static_page=hb_mysql_query($selct_static_page);
			$num_rows=hb_mysql_num_rows($result_static_page);
			if($num_rows > 0)
			{
				return mysql_fetch_array($result_static_page);
			}
		}
	}
	// New Functions Added on 12-jan By Hasmukh //
	
	function phb_is_get_user_status($event_id,$user_id)
	{
		  if($event_id!="" && $user_id!="")
		  {
		  $select_status="select * from tbl_invitation_responce where user_id='$user_id' and event_id='$event_id'";
		  $status_result=hb_mysql_query($select_status);
		  $row_status=hb_mysql_num_rows($status_result);
			  if($row_status > 0)
			  {
				   $status_data=hb_mysql_fetch_array($status_result);
				$rid=$status_data['id'];
				$uid=$status_data['user_id'];
				   if($status_data['status']=="1")
				   {
					 return "<span class='green'>Joining</span> <br> <span class='white'>Change :</span> <a href='join_event.php?event_id=$event_id&status=maybe&type=direct&rid=$rid&u=$uid'>May Be</a> | <a href='join_event.php?event_id=$event_id&status=declined&type=direct&rid=$rid&u=$uid'>Not Joining</a>" ;
				   }else if($status_data['status']=="2")
				   {
					 return "<span class='green'>Maybe</span><br> <span class='white'>Change :</span><a href='join_event.php?event_id=$event_id&status=active&type=direct&rid=$rid&u=$uid'>Join</a>" ;
				   }else{
					 return "<span class='green'>Not Joining</span> <Br><span class='white'> Change :</span> <a href='join_event.php?event_id=$event_id&status=maybe&type=direct&rid=$rid&u=$uid'>Join</a> | <a href='join_event.php?event_id=$event_id&status=maybe&type=direct&rid=$rid&u=$uid'>May Be</a> ";
				   }
			  }
		  
		  }
	
	}
	
	function phb_is_get_event_data($event_id)
	{
	
		$select_event_data="select * from tbl_events where id='$event_id'";
		$event_result=hb_mysql_query($select_event_data);
		$event_rows=hb_mysql_num_rows($event_result);
		if($event_rows > 0)
		{
		$event_row=hb_mysql_fetch_array($event_result);
		return $event_row;
		}
		
	}
	function phb_is_get_user_data($user_id)
	{
	
		$select_user_data="select * from tbl_customers where id='$user_id'";
		$user_result=hb_mysql_query($select_user_data);
		$user_rows=hb_mysql_num_rows($user_result);
		if($user_rows > 0)
		{
		$user_row=hb_mysql_fetch_array($user_result);
		return $user_row;
		}
		
	}

	function GetValue($table,$field,$where,$condition)
	{
		if($table != '' && $field != '' && $where != '' && $condition != '')
		{
			$qry="SELECT $field from $table where $where='$condition'";
			$res=hb_mysql_query($qry);
			if(mysql_affected_rows()>0)
			{
					$row=hb_mysql_fetch_array($res);
					return htmlspecialchars_decode(stripslashes($row[$field]));
			}
			else
			{
					return "";
			}
		}
	}	

	function hb_does_record_exist($table_name,$field_key,$field_value)
	{
		global $tables_with_status_column;
		$field_value = hb_mysql_real_escape_string(hb_mysql_real_escape_string(intval($field_value)));
		$does_record_exist_query = "select `$field_key` from `$table_name` where `$field_key` = '$field_value'";
		$does_record_exist_result = hb_mysql_query($does_record_exist_query) or die(hb_mysql_error());
		$does_record_exist_answer = hb_mysql_num_rows($does_record_exist_result);
		if($does_record_exist_answer > 0)
		{
			// If the control comes here then it doesn't mean that the recored surely exists 
			// For Example pass this 500000' or 1='1 and you'll see that control comes here
			// So again check here that whether the passed id and fetched id is same 
			// If the passed id and fetched id are equal then and then the user should be able to enter in the page elst not 
			$fetched_id = hb_mysql_result($does_record_exist_result,0);
			if($fetched_id == $field_value)
			{
				if(in_array($table_name,$tables_with_status_column))
				{
					return hb_does_record_exist_with_status($table_name,$field_key,$field_value);
				}
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}	
	
	/* Functions for the following system ended */
	/* Functions for the contact system started*/
	function hb_is_contact($sender_id,$sender_type,$receiver_id,$receiver_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`sender_id` = '$sender_id' and `sender_type` = '$sender_type' and `receiver_id` = '$receiver_id' and `receiver_type` = '$receiver_type') or (`sender_id` = '$receiver_id' and `sender_type` = '$receiver_type' and `receiver_id` = '$sender_id' and `receiver_type` = '$sender_type')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function hb_is_sender_of_contact($contact_id,$user_id,$user_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`sender_id` = '$user_id' and `sender_type` = '$user_type' and `id` = '$contact_id')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function hb_is_receiver_of_contact($contact_id,$user_id,$user_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`receiver_id` = '$user_id' and `receiver_type` = '$user_type' and `id` = '$contact_id')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function hb_get_contact_id($sender_id,$sender_type,$receiver_id,$receiver_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`sender_id` = '$sender_id' and `sender_type` = '$sender_type' and `receiver_id` = '$receiver_id' and `receiver_type` = '$receiver_type' and `contact_status` = 'Accepted') or (`sender_id` = '$receiver_id' and `sender_type` = '$receiver_type' and `receiver_id` = '$sender_id' and `receiver_type` = '$sender_type' and `contact_status` = 'Accepted')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			$hb_is_contact_data = hb_mysql_fetch_array($hb_is_contact_result);
			return $hb_is_contact_data['id'];
		}
		else
		{
			return false;
		}
	}
	function hb_is_accepted($sender_id,$sender_type,$receiver_id,$receiver_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`sender_id` = '$sender_id' and `sender_type` = '$sender_type' and `receiver_id` = '$receiver_id' and `receiver_type` = '$receiver_type' and `contact_status` = 'Accepted') or (`sender_id` = '$receiver_id' and `sender_type` = '$receiver_type' and `receiver_id` = '$sender_id' and `receiver_type` = '$sender_type' and `contact_status` = 'Accepted')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function hb_is_pending($sender_id,$sender_type,$receiver_id,$receiver_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`sender_id` = '$sender_id' and `sender_type` = '$sender_type' and `receiver_id` = '$receiver_id' and `receiver_type` = '$receiver_type' and `contact_status` = 'Pending') or (`sender_id` = '$receiver_id' and `sender_type` = '$receiver_type' and `receiver_id` = '$sender_id' and `receiver_type` = '$sender_type' and `contact_status` = 'Pending')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function hb_is_declined($sender_id,$sender_type,$receiver_id,$receiver_type)
	{
		$hb_is_contact_query = "select id from tbl_contacts where (`sender_id` = '$sender_id' and `sender_type` = '$sender_type' and `receiver_id` = '$receiver_id' and `receiver_type` = '$receiver_type' and `contact_status` = 'Declined') or (`sender_id` = '$receiver_id' and `sender_type` = '$receiver_type' and `receiver_id` = '$sender_id' and `receiver_type` = '$sender_type' and `contact_status` = 'Declined')";
		$hb_is_contact_result = hb_mysql_query($hb_is_contact_query) or die(hb_mysql_error());
		$hb_is_contact_answer = hb_mysql_num_rows($hb_is_contact_result);
		if($hb_is_contact_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function hb_new_line(){ 
		echo chr(10);
	} 
	/* Functions for the contact system ended*/
	
	
	function hb_curl($link_url)
	{
		$ch = curl_init("$link_url");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$output = curl_exec($ch);
		curl_error($ch);
		curl_close($ch);
		return $output;
	}
	
	function hb_SetValue($table,$value_field,$condition_field,$condition_value,$new_value)
	{
		$setvalue_query = "update `$table` set `$value_field` = '$new_value' where `$condition_field` = '$condition_value'";
		hb_mysql_query($setvalue_query) or die(hb_mysql_error());
	}
	function hb_SetValueLimit1($table,$value_field,$condition_field,$condition_value,$new_value)
	{
		$setvalue_query = "update `$table` set `$value_field` = '$new_value' where `$condition_field` = '$condition_value' limit 1";
		hb_mysql_query($setvalue_query) or die(hb_mysql_error());
	}
	
	function hb_send_vorpit_mail($line_no,$file_name,$template_id,$receiver_id,$receiver_type,$subject,$message,$sender_id,$sender_type,$related_job_id,$table_affected,$operation_performed,$id_affected,$ccd_to_idtypestring = "")
	{
		if($receiver_id > 0 && $receiver_type!= "" && $sender_id > 0 && $sender_type != "" && $table_affected != "" && $operation_performed != "" && $id_affected > 0)
		{
			global $GBV_ED_KEY;
			if($ccd_to_idtypestring != "")
			{
				$ccd_to_idtypestring = $receiver_type."-".$receiver_id.",".$ccd_to_idtypestring;
			}
			else
			{
				$ccd_to_idtypestring = $receiver_type."-".$receiver_id;
			}
			$ccd_to_array = explode(",",$ccd_to_idtypestring);
			$ccd_to_array = array_unique($ccd_to_array);
			
			foreach($ccd_to_array as $ccd_to_list)
			{
				$ccd_to_list_array = explode("-",$ccd_to_list);
				$receiver_id = $ccd_to_list_array["1"];
				$receiver_type = $ccd_to_list_array["0"];
				
				$sender_user_name = GetValue($sender_type,"username","id",$sender_id);
				$receiver_user_name = GetValue($receiver_type,"username","id",$receiver_id);
				
				$message_query = "INSERT INTO 
										`messages`
								  SET
										`sender_id`				=	'$sender_id'
									,	`receiver_id`			=	'$receiver_id'
									,	`sender_user_name`		=	aes_encrypt('$sender_user_name','$GBV_ED_KEY')
									,	`receiver_user_name`	=	aes_encrypt('$receiver_user_name','$GBV_ED_KEY')
									,	`subject`				=	'".addslashes($subject)."'
									,	`message`				=	'".addslashes($message)."'
									,	`reply_id`				=	'$reply_id'
									,	`sender_type`			=	'$sender_type'
									,	`receiver_type`			=	'$receiver_type'
									,	`table_affected`		=	'$table_affected'
									,	`operation_performed`	=	'$operation_performed'
									,	`id_affected`			=	'$id_affected'
									,	`related_job_id`		=	'$related_job_id'
									,	`ccd_to` 				=	'$ccd_to_idtypestring'
									,	`script_filename`		= 	'$file_name'
									,	`line_no` 				=	'$line_no'
									,	`template_id` 			=	'$template_id'";
				hb_mysql_query($message_query) or die(hb_mysql_error());
				$last_message_id = hb_mysql_insert_id();
				
				if($last_message_id == "" || $last_message_id <= 0)
				{
					hb_record_vorpit_mail_errors($line_no,$file_name,$template_id,$receiver_id,$receiver_type,$subject,$message,$sender_id,$sender_type,$related_job_id,$table_affected,$operation_performed,$id_affected);
				}
			}
		}
		else
		{
			hb_record_vorpit_mail_errors($line_no,$file_name,$template_id,$receiver_id,$receiver_type,$subject,$message,$sender_id,$sender_type,$related_job_id,$table_affected,$operation_performed,$id_affected);
		}
	}
	
	function hb_record_vorpit_mail_errors($line_no,$file_name,$template_id,$receiver_id,$receiver_type,$subject,$message,$sender_id,$sender_type,$related_job_id,$table_affected,$operation_performed,$id_affected)
	{
		/* We should not only record the error but should also assign the user which experienced the user */
		global $global_user_id,$global_user_type;
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$error_on = date('Y-m-d H:i');
		$hb_record_query = "INSERT INTO
								`tbl_messages_errors`
						  SET								
								`sender_id`				=	'$sender_id'
							,	`receiver_id`			=	'$receiver_id'
							,	`sender_user_name`		=	aes_encrypt('$sender_user_name','$GBV_ED_KEY')
							,	`receiver_user_name`	=	aes_encrypt('$receiver_user_name','$GBV_ED_KEY')
							,	`subject`				=	'".addslashes($subject)."'
							,	`message`				=	'".addslashes($message)."'
							,	`reply_id`				=	'$reply_id'
							,	`sender_type`			=	'$sender_type'
							,	`receiver_type`			=	'$receiver_type'
							,	`table_affected`		=	'$table_affected'
							,	`operation_performed`	=	'$operation_performed'
							,	`id_affected`			=	'$id_affected'
							,	`related_job_id`		=	'$related_job_id'
							,	`ccd_to` 				=	'$ccd_to'
							,	`file_name` 			= 	'$file_name'
							,	`ip_address` 			= 	'$ip_address'
							,	`error_on` 				= 	'$error_on'
							,	`user_id` 				= 	'$global_user_id'
							,	`user_type` 			= 	'$global_user_type'
							,	`line_no` 				= 	'$line_no'
							,	`script_filename`		= 	'$hp_file_name'
							,	`template_id` 			= 	'$template_id'";								 
		hb_mysql_query($hb_record_query) or die(hb_mysql_error());
		//die(stripslashes($sql_error));
	}
	function location($path)
	{
		header("Location: ".$path."");
	}
	
	function hb_replace_replaceables($string)
	{
		while($string_with_unreplaced_global_url = hb_get_string_between($string,'<replaceable>','</replaceable>'))
		{
			$global_url_name = hb_get_string_between($string,'<replaceable>','</replaceable>');
			$$global_url_name = $GLOBALS[$global_url_name];
			$string = str_replace("<replaceable>$global_url_name</replaceable>",$$global_url_name,$string);
		}
		return $string;
	}
	function hb_replace_replaceable_names($string)
	{
		while($string_with_unreplaced_global_url = hb_get_string_between($string,'customers_','_customers'))
			{
				$global_id = hb_get_string_between($string,'customers_','_customers');
				if($global_id == $_SESSION['SEB_SUPAR_GLOBAL_USER_ID'] && "tbl_customers" == $_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'])
				{
					$name = "You";
				}
				else
				{
					$name = ucfirst(GetValue("tbl_customers","first_name","id",$global_id))." ".ucfirst(GetValue("tbl_customers","last_name","id",$global_id));
				}
				$string = str_replace('customers_'.$global_id.'_customers',$name,$string);
				$string = str_replace('You has','You have',$string);
			}
		while($string_with_unreplaced_global_url = hb_get_string_between($string,'customers_','_customers'))
			{
				$global_id = hb_get_string_between($string,'customers_','_customers');
				if($global_id == $_SESSION['SEB_SUPAR_GLOBAL_USER_ID'] && "tbl_customers" == $_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'])
				{
					$parent_company_id = GetValue("tbl_customers","company_id","id",$global_id);
					$string = str_replace('customers_'.$global_id.'_customers','customers_'.$parent_company_id.'_customers',$string);
				}
				if($global_id == $_SESSION['SEB_SUPAR_GLOBAL_USER_ID'] && "tbl_customers" == $_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'])
				{
					$name = "You";
				}
				else
				{
					$name = ucfirst(GetValue("tbl_customers","first_name","id",$global_id))." ".ucfirst(GetValue("tbl_customers","last_name","id",$global_id));
				}
				$string = str_replace('customers_'.$global_id.'_customers',$name,$string);
				$string = str_replace('You has','You have',$string);
			}
		while($string_with_unreplaced_global_url = hb_get_string_between($string,'customers_','_customers'))
			{
				$global_id = hb_get_string_between($string,'customers_','_customers');
				if($global_id == $_SESSION['SEB_SUPAR_GLOBAL_USER_ID'] && "tbl_customers" == $_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'])
				{
					$name = "You";
				}
				else
				{
					$name = ucfirst(GetValue("tbl_customers","company_name","id",$global_id));
				}
				$string = str_replace('customers_'.$global_id.'_customers',$name,$string);
				$string = str_replace('You has','You have',$string);
			}
		return $string;
	}
	
	function hb_replace_mail_replaceables($string)
	{
		while($string_with_unreplaced_global_url = hb_get_string_between($string,'replaceable_','_replaceable'))
		{
			$global_url_name = hb_get_string_between($string,'replaceable_','_replaceable');
			$$global_url_name = get_receiver_urls($_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'],$global_url_name);
			if($$global_url_name == "")
			{
				$$global_url_name = get_receiver_urls($_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'],"global_welcome_file_name");
			}
			$string = str_replace("replaceable_".$global_url_name."_replaceable",$$global_url_name,$string);
		}
		// If the user is already logged in and our variables doesnt find anything for that user then we should just have him redirect to his welcome page
		if($string == "")
		{
			$string = get_receiver_urls($_SESSION['SEB_SUPAR_GLOBAL_USER_TYPE'],"global_welcome_file_name");
		}
		return $string;
	}
	function hb_replace_basic_replaceables($string)
	{
		while($string_with_unreplaced_global_url = hb_get_string_between($string,'<basic_replaceable>','</basic_replaceable>'))
		{
			$global_url_name = hb_get_string_between($string,'<basic_replaceable>','</basic_replaceable>');
			$$global_url_name = $GLOBALS[$global_url_name];
			$string = str_replace("<basic_replaceable>$global_url_name</basic_replaceable>",$$global_url_name,$string);
		}
		return $string;
	}
	function hb_get_replaceable_detail_url($receiver_type,$receiver_id)
	{
		if($receiver_type != "" && $receiver_id > 0)
		{
			$url_text = "<basic_replaceable>replaceable_".$receiver_type."_url</basic_replaceable>".$receiver_id;
			return $url_text;
		}
	}
	function hb_get_detail_url($receiver_type,$receiver_id)
	{
		if($receiver_type != "" && $receiver_id > 0)
		{
			$replaceable_detail_url = hb_get_replaceable_detail_url($receiver_type,$receiver_id);
			$basic_replaced_but_still_replaceable_detail_url = hb_replace_basic_replaceables($replaceable_detail_url);
			$detail_url = hb_replace_replaceables($basic_replaced_but_still_replaceable_detail_url);
			return $detail_url;
		}
	}
	
	function hb_replace_string_between($string, $replace_string, $start, $end){ 
		$string = " ".$string; 
		$ini = strpos($string,$start); 
		if ($ini == 0) return ""; 
		$ini += strlen($start); 
		$len = strpos($string,$end,$ini) - $ini; 
		return substr_replace($string,$replace_string,$ini,$len); 
	} 
	function hb_get_string_between($string, $start, $end){ 
		$string = " ".$string; 
		$ini = strpos($string,$start); 
		if ($ini == 0) return ""; 
		$ini += strlen($start); 
		$len = strpos($string,$end,$ini) - $ini; 
		return substr($string,$ini,$len); 
	}

	function hb_is_sender($message_id,$user_id,$user_type)
	{
		$sender_query = "select * from `tbl_messages` where `id` = '$message_id' and `sender_id` = '$user_id' and `sender_type` = '$user_type'";
		$sender_result = hb_mysql_query($sender_query) or die(hb_mysql_error());
		$sender_answer = hb_mysql_num_rows($sender_result);
		if($sender_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function hb_is_receiver($message_id,$user_id,$user_type)
	{
		$receiver_query = "select * from `tbl_messages` where `id` = '$message_id' and `receiver_id` = '$user_id' and `receiver_type` = '$user_type'";
		$receiver_result = hb_mysql_query($receiver_query) or die(hb_mysql_error());
		$receiver_answer = hb_mysql_num_rows($receiver_result);
		if($receiver_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function hb_is_in_inbox($message_id,$user_id)
	{
		$inbox_query = "select * from `tbl_messages` where `id` = '$message_id' and `receiver_id` = '$user_id' and `receiver_archived` = '0' and `receiver_trash` = '0' and `receiver_delete` = '0'";
		$inbox_result = hb_mysql_query($inbox_query) or die(hb_mysql_error());
		$inbox_answer = hb_mysql_num_rows($inbox_result);
		if($inbox_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function hb_is_in_sentbox($message_id,$user_id)
	{
		$sentbox_query = "select * from `tbl_messages` where `id` = '$message_id' and `sender_id` = '$user_id'  and `sender_archived` = '0' and `sender_trash` = '0' and `sender_delete` = '0'";
		$sentbox_result = hb_mysql_query($sentbox_query) or die(hb_mysql_error());
		$sentbox_answer = hb_mysql_num_rows($sentbox_result);
		if($sentbox_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function hb_is_in_archivedbox($message_id,$user_id)
	{
		$archivedbox_query = "select * from `tbl_messages` where 
						(`id` = '$message_id' and `receiver_id` = '$user_id' and `receiver_archived` = '1' and `receiver_trash` = '0' and `receiver_delete` = '0') or
						(`id` = '$message_id' and `sender_id` = '$user_id' and `sender_archived` = '1' and `sender_trash` = '0' and `sender_delete` = '0')";
		$archivedbox_result = hb_mysql_query($archivedbox_query) or die(hb_mysql_error());
		$archivedbox_answer = hb_mysql_num_rows($archivedbox_result);
		if($archivedbox_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function hb_is_in_deletebox($message_id,$user_id)
	{
		$deletebox_query = "select * from `tbl_messages` where
						(`id` = '$message_id' and `receiver_id` = '$user_id' and `receiver_trash` = '1' and `receiver_delete` = '0') or
						(`id` = '$message_id' and `sender_id` = '$user_id' and `sender_trash` = '1' and `sender_delete` = '0')";
		$deletebox_result = hb_mysql_query($deletebox_query) or die(hb_mysql_error());
		$deletebox_answer = hb_mysql_num_rows($deletebox_result);
		if($deletebox_answer > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function receiver_url_for_receiver_message($sender_id,$sender_type,$receiver_id,$receiver_type)
	{
		$professionals_company_link = "professionals_company_profile.php?company_id=";
		$subaccount_company_link = "subaccount_company_profile.php?company_id=";
		$employers_company_link = "employer_company_profile.php?company_id=";
		
		$professionals_subaccount_link = "professional_subaccount_profile.php?subaccount_id=";
		$subaccount_subaccount_link = "subaccount_subaccount_profile.php?subaccount_id=";
		$employers_subaccount_link = "employer_subaccount_profile.php?subaccount_id=";
		
		$professionals_user_link = "professionals_user_profile.php?users_id=";
		$subaccount_user_link = "subaccount_user_profile.php?users_id=";
		$employers_user_link = "employer_user_profile.php?users_id=";
		
		if($receiver_type == 'prn_companies')
		{
			if($sender_type == 'prn_companies')
				$receiver_url = $employers_company_link;
			else if($sender_type == 'tbl_customers')
				$receiver_url = $employers_subaccount_link ;
			else if($sender_type == 'prn_users')
				$receiver_url = $employers_user_link ;
		}
		else if($receiver_type == 'tbl_customers')
		{
			if($sender_type == 'prn_companies')
				$receiver_url = $subaccount_company_link;
			else if($sender_type == 'tbl_customers')
				$receiver_url = $subaccount_subaccount_link ;
			else if($sender_type == 'prn_users')
				$receiver_url = $subaccount_user_link ;
		}
		else if($receiver_type == 'prn_users')
		{
			if($sender_type == 'prn_companies')
				$receiver_url = $professionals_company_link;
			else if($sender_type == 'tbl_customers')
				$receiver_url = $professionals_subaccount_link ;
			else if($sender_type == 'prn_users')
				$receiver_url = $professionals_user_link ;
		}
		// Receiver will always see the sender and objects created by the sender so we will attach the sender id to receiver url
		$receiver_url = $receiver_url.$sender_id;
		return $receiver_url;
	}
	function get_receiver_urls($receiver_type,$url_variable)
	{
		if($receiver_type == "prn_companies")
		{
			include("employer_join_me_urls.php");
		}
		else if($receiver_type == "tbl_customers")
		{
			include("subaccount_join_me_urls.php");
		}
		else if($receiver_type == "prn_users")
		{
			include("professional_join_me_urls.php");
		}
		return $$url_variable;
	}	
	
	function get_sender($message_id)
	{
		$sender_type = GetValue("tbl_messages","sender_type","id",$message_id);
		$sender_user_name = GetValue("tbl_messages","sender_user_name","id",$message_id);
		if($sender_type == 'prn_companies')
		{
			$sender = GetValue($sender_type,"company_name","username",$sender_user_name);
		}
		else if($sender_type == 'tbl_customers')
		{
			$sender = GetValue($sender_type,"first_name","username",$sender_user_name);
			$sender .= " ";
			$sender .= GetValue($sender_type,"last_name","username",$sender_user_name);
		}
		else if($sender_type == 'prn_users')
		{
			$sender = GetValue($sender_type,"first_name","username",$sender_user_name);
			$sender .= " ";
			$sender .= GetValue($sender_type,"last_name","username",$sender_user_name);
		}
		return $sender;
	}
	function get_sender_link($message_id)
	{
		global $global_company_detail_url,$global_subaccount_detail_url,$global_user_detail_url;
		$sender_type = GetValue("tbl_messages","sender_type","id",$message_id);
		$sender_id = GetValue("tbl_messages","sender_id","id",$message_id);
		if($sender_type == 'prn_companies')
		{
			$sender_link = $global_company_detail_url."?company_id=".$sender_id;
		}
		else if($sender_type == 'tbl_customers')
		{
			$sender_link = $global_subaccount_detail_url."?subaccount_id=".$sender_id;
		}
		else if($sender_type == 'prn_users')
		{
			$sender_link = $global_user_detail_url."?users_id=".$sender_id;
		}
		return $sender_link;
	}
	function hb_get_sender_wall_link($wall_id)
	{
		global $global_company_detail_url,$global_subaccount_detail_url,$global_user_detail_url;
		$sender_type = GetValue("tbl_prn_vorpit_wall","sender_type","id",$wall_id);
		$sender_id = GetValue("tbl_prn_vorpit_wall","sender_id","id",$wall_id);
		if($sender_type == 'prn_companies')
		{
			$sender_link = $global_company_detail_url."?company_id=".$sender_id;
		}
		else if($sender_type == 'tbl_customers')
		{
			$sender_link = $global_subaccount_detail_url."?subaccount_id=".$sender_id;
		}
		else if($sender_type == 'prn_users')
		{
			$sender_link = $global_user_detail_url."?users_id=".$sender_id;
		}
		return $sender_link;
	}
	function get_receiver($message_id)
	{
		$receiver_type = GetValue("tbl_messages","receiver_type","id",$message_id);
		$receiver_user_name = GetValue("tbl_messages","receiver_user_name","id",$message_id);
		if($receiver_type == 'prn_companies')
		{
			$receiver = GetValue($receiver_type,"company_name","username",$receiver_user_name);
		}
		else if($receiver_type == 'tbl_customers')
		{
			$receiver = GetValue($receiver_type,"first_name","username",$receiver_user_name);
			$receiver .= " ";
			$receiver .= GetValue($receiver_type,"last_name","username",$receiver_user_name);
		}
		else if($receiver_type == 'prn_users')
		{
			$receiver = GetValue($receiver_type,"first_name","username",$receiver_user_name);
			$receiver .= " ";
			$receiver .= GetValue($receiver_type,"last_name","username",$receiver_user_name);
		}
		return $receiver;
	}
	function get_receiver_link($message_id)
	{
		global $global_company_detail_url,$global_subaccount_detail_url,$global_user_detail_url;
		$receiver_type = GetValue("tbl_messages","receiver_type","id",$message_id);
		$receiver_id = GetValue("tbl_messages","receiver_id","id",$message_id);
		if($receiver_type == 'prn_companies')
		{
			$receiver_link = $global_company_detail_url."?company_id=".$receiver_id;
		}
		else if($receiver_type == 'tbl_customers')
		{
			$receiver_link = $global_subaccount_detail_url."?subaccount_id=".$receiver_id;
		}
		else if($receiver_type == 'prn_users')
		{
			$receiver_link = $global_user_detail_url."?users_id=".$receiver_id;
		}
		return $receiver_link;
	}
	function hb_get_receiver_wall_link($wall_id)
	{
		global $global_company_detail_url,$global_subaccount_detail_url,$global_user_detail_url;
		$receiver_type = GetValue("tbl_prn_vorpit_wall","receiver_type","id",$wall_id);
		$receiver_id = GetValue("tbl_prn_vorpit_wall","receiver_id","id",$wall_id);
		if($receiver_type == 'prn_companies')
		{
			$receiver_link = $global_company_detail_url."?company_id=".$receiver_id;
		}
		else if($receiver_type == 'tbl_customers')
		{
			$receiver_link = $global_subaccount_detail_url."?subaccount_id=".$receiver_id;
		}
		else if($receiver_type == 'prn_users')
		{
			$receiver_link = $global_user_detail_url."?users_id=".$receiver_id;
		}
		return $receiver_link;
	}
	
	
	function get_message_date($database_date)
	{
		$message_date = date('M. j, Y H:i a',strtotime($database_date));
		return $message_date;
	}
	
	function pis_get_user_data($id)
	{
		$selsql="select * from tbl_customers where id='".$id."'";
		$selrs=hb_mysql_query($selsql);
		echo hb_mysql_error();
		
		$selcntRow=hb_mysql_fetch_array($selrs);
		
		return $selcntRow;
	}
	// This function is what our entire notification system is based on so please be aware of what you are doing whey you do any changes in it
	function hb_set_vorpit_entire_front_then_go($vorpit_entire_front_then_go)
	{	
		$_SESSION['vorpit_entire_front_then_go'] = $vorpit_entire_front_then_go; 
	}
	
	function hb_get_vorpit_entire_front_then_go()
	{	
		return $_SESSION['vorpit_entire_front_then_go']; 
	}
	
	function hb_use_and_clear_vorpit_entire_front_then_go($change_parent = false)
	{
		$vorpit_entire_front_then_go = $_SESSION['vorpit_entire_front_then_go'];
		unset($_SESSION['vorpit_entire_front_then_go']);
		if($change_parent)
		{
			hb_change_parent_location($vorpit_entire_front_then_go);
		}
		else
		{
			hb_change_location($vorpit_entire_front_then_go);
		}
		exit;
	}
	function hb_change_location($url,$change_parent = false)
	{
		if($change_parent)
		{
			?>
				<script language="javascript">
					parent.window.location.href = "<?php echo $url; ?>";
				</script>
			<?
		}
		else
		{
			?>
				<script language="javascript">
					window.location.href = "<?php echo $url; ?>";
				</script>
			<?
		}
		exit;
	}
?>