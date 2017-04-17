<?php 
include_once('connect.php');
//This is the main function for click count
function hb_increment_n_days_count_one($table_name,$job_id)
{
  // Get today's date
  $today = hb_today();
  
  // Now check whether any record exists for the passed job if not then create a record for this job
  $job_exists = GetValue($table_name,"count(id)","job_id",$job_id);
  
  if($job_exists <= 0)
  {
   $create_job_query = "insert into `$table_name` set 
        `job_id` = '$job_id',
        `days_counts` = ',',
        `today` = '$today',
        `count_of_today` = '1'";
   hb_mysql_query($create_job_query) or die(hb_mysql_error());
  }
  else
  {
   // Now check whether the today field of table matches with today's date
   $today_exists_query = "select count(id) from `$table_name` where `job_id` = '$job_id' and `today` = '$today'";
   $today_exists_result = hb_mysql_query($today_exists_query) or die(hb_mysql_error());
   $today_exists = hb_mysql_result($today_exists_result,0);
   
   if($today_exists <= 0)
   {
    // If today not exists then the value in `count_of_today` field points to the count of yesterday
    // so append the value of `count_of_today` field to `days_counts`  field at the end
    $yesterday_count = GetValue("$table_name","count_of_today","job_id",$job_id);
    $append_query = "update `$table_name` set 
        `days_counts` = CONCAT(`days_counts`,'$yesterday_count',','),
        `today` = '$today',
        `count_of_today` = '1'
        where `job_id` = '$job_id' limit 1;";
    $append_result = hb_mysql_query($append_query) or die(hb_mysql_error());
    
    // Now check whether the count_of_days is grether then 29 then make it just 29
    $count_for_days = hb_count_job_days($table_name,$job_id);
    if($count_for_days > 29)
    {
     $days_to_be_removed = $count_for_days - 29;
     $old_csl = GetValue("$table_name","days_counts","job_id",$job_id);
     $new_csl = hb_remove_csl_left($old_csl,$days_to_be_removed);
     $new_csl = ",".$new_csl.",";
     
     // Now update the old_csl with the new one
     hb_SetValue("$table_name","days_counts","job_id",$job_id,$new_csl);
    }
   }
   else
   {
    // So now increment the count for today
    hb_increment_by_one("$table_name","count_of_today","job_id",$job_id);
   }
  }
}

/* Returns the date of today */
function hb_today()
{
  $date = date("Y-m-d");
  return $date;
}
//=================================================================
function hb_count_job_days($table_name,$job_id)
{
  $csl_for_days = GetValue("$table_name","days_counts","job_id",$job_id);
  $csl_for_days_total = hb_count_csl($csl_for_days);
  $csl_for_days_total = $csl_for_days_total + GetValue("$table_name","count_of_today","job_id",$job_id);
  return $csl_for_days_total;
}
//=================================================================
/*function hb_count_csl($csl)
{
  $csl = ltrim($csl,",");
  $csl = rtrim($csl,",");
  // Now create array
  $csl_array = explode(",",$csl);
  $csl_total = count($csl_array);
  return $csl_total;
}*/
//=================================================================
function hb_remove_csl_left($csl,$number = 0)
{
  $csl = ltrim($csl,",");
  $csl = rtrim($csl,",");
  // Now create array
  $csl_array = explode(",",$csl);
  $csl_total = count($csl_array);
  foreach($csl_array as $key => $value)
  {
   if($number > 0)
   {
    unset($csl_array[$key]);
    $number--;
   }
   else
   {
    break;
   }
  }
  // Now generate csl
  $new_csl = implode(",",$csl_array);
  return $new_csl;
}
//=================================================================
function hb_sum_csl($csl)
{
  $csl = ltrim($csl,",");
  $csl = rtrim($csl,",");
  $csl_sum = 0;
  // Now create array
  $csl_array = explode(",",$csl);
  $csl_total = count($csl_array);
  foreach($csl_array as $key => $value)
  {
   $csl_sum = $csl_sum + $value;
  }
  return $csl_sum;
}
//=================================================================
function hb_increment_by_one($table,$value_field,$condition_field,$condition_value)
{
  $increment_by_query = "update `$table` set `$value_field` = `$value_field` + 1 where `$condition_field` = '$condition_value' limit 1";
  hb_mysql_query($increment_by_query) or die(hb_mysql_error());
}

 
?>