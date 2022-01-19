<?php
//echo "Сегодня\t". date("d.m.Y")."<BR>";
include('common.php');
if (empty($years)){$years=date("Y");$table_name="year$years";};
mysql_pconnect($hostname, $username, $password) or die("Не могу найти базу данных!");
        mysql_select_db($db_name) or die("Can't select database $db_name!");
//$dates="$years.01.01";
//$dates1=date("Ymd",mktime(0,0,0,1,1,$years);echo $dates1;
head_html("КАЛЕНДАРЬ");
echo "<CENTER>";
if ( isset($deleting) )
{       $dates_t=inversdate($category);
        //$table_name=$table;

        $res=mysql_query("SELECT * FROM $table WHERE dates = '$dates_t'");
        $row = mysql_fetch_array($res);
        $kal=$row['kal_num'];
        if ($kal==0){mysql_query("UPDATE $table SET kal_num='1' WHERE dates = '$dates_t'");}
        	else {mysql_query("UPDATE $table SET kal_num='0' WHERE dates = '$dates_t'");};

        //mysql_query("UPDATE $table SET kal_num='0' WHERE dates = '$dates_t'");
        $result=mysql_query("SELECT * FROM $table WHERE kal_num != '0' ORDER BY kal_num" );
        $i=1;
        while(list($kal_num, $dates) = mysql_fetch_row($result))
                {
                mysql_query("UPDATE $table SET kal_num='$i' WHERE dates = '$dates'");
                //echo $i."&nbsp&nbsp".$kal_num."&nbsp&nbsp".$dates."<br>";
                $i++;
                }
Unset($deleting);
}
if (isset($set_year))
{
//$sel=mysql_query("select * from $years");
//echo $sel;
//============проверка таблица есть ?
        $years=$years_sel;
        $table_name="year$years";//echo "<BR>имя ".$table_name;
        $date_end=mktime(0,0,0,12,31,$years); //echo "<BR>кон. дата ".date("d.m.Y",$date_end);
        $result1 = mysql_listtables ("$db_name");
        $i = 0;
        while ($i < mysql_num_rows ($result1))
        {
            $tb_names[$i] = mysql_tablename ($result1, $i);
            //echo "<BR>".$tb_names[$i] ;
            if ($tb_names[$i]==$table_name){$tableexist=1; break;} else {$tableexist=0;};
            $i++;
        }
        Unset($result1); //echo "<BR>табл сущ  ".$tableexist;
      if ($tableexist==0)
      {
        $table = "CREATE TABLE $table_name"."( kal_num int(3), dates date NOT NULL)";
        mysql_query($table) or die("Не могу создать таблицу $table_name или она уже существует");
        //$dates="$years.01.01";
        $dates=mktime(0,0,0,1,1,$years);
      //echo $dates1;
      $i=1; $k=0;
        while($dates<=$date_end)
        {
        $dates_tmp=date("Ymd",$dates);
        if ( (date("D",$dates)=="Sat") OR (date("D",$dates)=="Sun") ){$k=$i-1; $i=0;} else {$k=$i;};
        $res=mysql_query("INSERT INTO $table_name (kal_num,dates) VALUES ('$i','$dates_tmp')") or die(mysql_error());
        $dates=mktime(0,0,0,date("m.",$dates), date("d.",$dates)+1,date("Y",$dates));
        $i=$k;
        $i++;
        }
        echo "Таблица за $table_name сформирована"."<BR>";
      } else {echo "Таблица за $table_name уже существует"."<BR>";};
 }
 //$table_name="year2002";
 $result=mysql_query("select * from $table_name");
if ($result >0)
{
$k=0; $i=1;$month1="01";
echo "<TABLE CELLSPACING=0 CELLPADDING=1 border=1>".
        "<TH COLSPAN=3>Календарь за $years год</TH><TR valign=top>".
        "<TD>";   //начало больш табл.

while ($month1 <= 12)
{
 $ot="$years-$month1-01"; $date_end_tmp=mktime(0,0,0,$month1+1,1,$years);
 $do=date("Y-m-d",mktime(0,0,0,date("m",$date_end_tmp),date("d",$date_end_tmp)-1,date("Y",$date_end_tmp)));
// $result=mysql_query("select * from $table_name WHERE dates='$years-$month1-%'");
list ($year2,$month2,$day2) = split ('[,/.-]', $ot);
$month_tmp=date("M",mktime(0,0,0,$month2,$day2,$year2));
switch ($month_tmp)
    {
      case "Jan":$month_tmp="Январь"; break;
      case "Feb":$month_tmp="Февраль"; break;
      case "Mar":$month_tmp="Март"; break;
      case "Apr":$month_tmp="Апрель"; break;
      case "May":$month_tmp="Май"; break;
      case "Jun":$month_tmp="Июнь"; break;
      case "Jul":$month_tmp="Июль"; break;
      case "Aug":$month_tmp="Август"; break;
      case "Sep":$month_tmp="Сентябрь"; break;
      case "Oct":$month_tmp="Октябрь"; break;
      case "Nov":$month_tmp="Ноябрь"; break;
      case "Dec":$month_tmp="Декабрь"; break;
     };
echo "<TABLE cellpadding=\"2\" CELLSPACING=\"0\" border=\"1\">";               //начало внутр табл.
echo "<TR ALIGN=CENTER><TH COLSPAN=7 ALIGN=CENTER BGCOLOR=\"#CCCCAA\">".$month_tmp."</TH></TR>";
echo "<TR ALIGN=CENTER>";
echo "<th WIDTH=30>Пн.</th>".
  "<th WIDTH=30>Вт.</th>".
  "<th WIDTH=30>Ср.</th>".
  "<th WIDTH=30>Чт.</th>".
  "<th WIDTH=30>Пт.</th>".
  "<th WIDTH=30><FONT COLOR=RED>Сб.</th>".
  "<th WIDTH=30><FONT COLOR=RED>Вс.</th>".
  "</tr>";
$result=mysql_query("SELECT * FROM $table_name WHERE dates BETWEEN '$ot' AND '$do'");

 echo "<TR ALIGN=CENTER>";
 if ($k!=0){    //$i=$k;
                while ($k<7)
                    {
                    echo "<TD>&nbsp;</TD>";$k++;
                    };$i=$k-1;
           } else { $i=1;};
           while(list($num, $dates) = mysql_fetch_row($result))
                {
                $dates_t=inversdate($dates);
                list ($year2,$month2,$day2) = split ('[,/.-]', $dates);
		settype($day2,"integer");
		    if ($num==0){$color="BGCOLOR=\"#EEAAAA\"";}
		                    else {$color="";};
                echo "<TD $color><a href=\"kalendar.php?category=".$dates_t."&table=".$table_name."&deleting=1\">$day2</a></TD>";$i++;
                if (date("D",mktime(0,0,0,$month2,$day2,$year2))=="Sun"){echo "</TR><TR ALIGN=CENTER>";$i=1;};

                 }
                 $k=8-$i;                                        // echo $month1;
                 if (($i>1) AND ($i<8)){
                     while ($i<=7)
                     { echo "<TD>&nbsp</TD>";$i++;};
                     echo "</TR>";
                 };
                 echo "</TABLE>";   //закрываем внутр табл.
                 //Echo "</TD>";    // большая табл.
                 if (($month1=="03") OR ($month1=="06") OR ($month1=="09")){echo "</TD></TR><TR valign=top><TD>";}
				else {echo "</TD><TD>";};
                 $month1++;
                 //echo "</TR>";

}

       }
       //       }
//}
//Unset($years);
?>

</table>
<form action="kalendar.php" method="get">
<INPUT MAXLENGTH=4  TYPE=text NAME=years_sel SIZE=4 VALUE=>
<INPUT  TYPE=submit name=set_year VALUE="сформировать">


</form>
</BODY>
</html>