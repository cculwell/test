<?php

require "../../resources/config.php";

function FormatDate4Db($date)
{
   if(trim($date) == "")
   {
      return "0000-00-00";
   }

   return  date("Y-m-d", strtotime($date));
}

function FormatTime4Db($time)
{
   if(trim($time) == "")
   {
      return "00:00:00";
   }

   $time=  date("H:i:s", strtotime($time));
   $time= str_replace('pm', '', $time);
   $time= str_replace('am', '', $time);

   return $time;
}

function FormatDate4Report($date)
{
   $date = trim($date);

   if(trim($date) == "0000-00-00")
   {
      return "";
   }

      return date("m/d/Y", strtotime($date));
}

function FormatTime4Report($time)
{
   $time= trim($time);

   if(trim($time) == "00:00:00")
   {
      return "";
   }
   
   return date("h:i A", strtotime($time));
}
?>