<?php
//=========================================================================
//
// File:    common.php
//
// Project: kid
// Author:  Dmitry I.Kostenko
//
// This file contains set of functions
// and register session variables
//=========================================================================

// initial settings for KID
$db_name = 'kid';
$hostname = 'localhost'; // Your MySQL-Host
$username = '';          // Your MySQL-Username
$password = '';          // Your MySQL-Password
$db_link  = -1;          // link to database

// access control moved to '.htaccess' file
// $admin_password = 'h';
//define( 'PRODUCTS_ON_PAGE', '10');
//define( 'ABSENT', 'Absent in language');
//$results_on_page = 10;
// quantity of additional search links
//$quantity_of_search_links = 10;

$admin_name = 'administrator';

$main_page  = 'index.php';

//===============================
// establish database conncection
//===============================

function init_database_connection()
{
    global $hostname, $username, $password, $db_name, $db_link;
    $db_link = mysql_pconnect($hostname, $username, $password) or
               die("Unable to connect to database");
    mysql_select_db("$db_name") or
      die("Unable to select database");
}
//=============================================
//добавление в таблицу справочник подразделений
//=============================================
function add_sprav_podrazd($nom_cex,$name_cex,$t_cex,$fam_cex,$gr1,$gr2,$nom_zd,$all_zd)
{
   $result = mysql_query("INSERT INTO sprav_podrazd (nom_cex,name_cex,t_cex,fam_cex,gr1,
gr2,nom_zd,all_zd) VALUES ('$nom_cex','$name_cex','$t_cex','$fam_cex','$gr1','$gr2','$nom_zd','$all_zd')") or
               die(mysql_error());
   return $result;
}
//==============================================
//удаление из таблицы "справочник подразделений"
//==============================================
function del_sprav_podrazd($nom_cex,$name_cex)
{
   mysql_query("DELETE FROM sprav_podrazd WHERE name_cex = '$name_cex' AND nom_cex = '$nom_cex'") or
               die(mysql_error());
}
//=============================================
//добавление в таблицу "база"
//=============================================
function add_base($nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp, $kd)
{
   $result = mysql_query("INSERT INTO base_inf(nom_doc, date_post, sod, korr, nom_cex, date_isp, date_real_isp, ovz, new_date_isp, kd) VALUES ('$nom_doc', '$date_post', '$sod', '$korr', '$nom_cex', '$date_isp', '$date_real_isp', '$ovz','$new_date_isp', '$kd')") or
               die("func add_baze\t\t".mysql_error());
   return $result;
}
//==============================================
//удаление из таблицы "база"
//==============================================
function del_base($num_id)
{
   mysql_query("DELETE FROM base_inf WHERE (num_id = '$num_id')") or
               die(mysql_error());
mysql_query("DELETE FROM kontr WHERE (num_id = '$num_id')") or die(mysql_error());
}

//=============================================
//добавление в таблицу "контроль"
//=============================================

function add_kontr($num_id, $date_kontr, $nom_cex_kontr)
{
   $result = mysql_query("INSERT INTO kontr(num_id, date_kontr, nom_cex_kontr) VALUES ('$num_id', '$date_kontr','$nom_cex_kontr')") or
               die("func add_kontr\t\t".mysql_error());
   //return $result;
}

//=============================================
//Преобразование даты ч\м\г в г\м\ч
//=============================================

function inversdate($date)
{
settype($date,"string");
list ($day,$month,$year) = split ('[,/.-]', $date);
$d[0]=$year;$d[1]=$month;$d[2]=$day;
$date=implode(".",$d);
//$date=("$year"+"."+"$month"+"."+"$day");
return $date;
}
//=============================================
//Преобразование даты ч\м\г в г\м\ч с проверкой
//на правильность ввода и наличие даты в таблице раб. дней
//=============================================
function inversdate_chek($date)
{$db_name = 'kid';
settype($date,"string");
list ($day,$month,$year) = split ('[,/.-]', $date);
$tmp=checkdate( $month, $day, $year);
$table_name="year$year";                 //echo " назв табл $table_name\n";
$result1 = mysql_listtables ("$db_name");
        $i = 0;
        while ($i < mysql_num_rows ($result1))
        {
            $tb_names[$i] = mysql_tablename ($result1, $i);// echo "<BR>".$tb_names[$i] ;
            if ($table_name==$tb_names[$i]){$tableexist=1;break;} else {$tableexist=0;};
            $i++;
        }
        Unset($result1);                 //echo "<BR>табл сущ  ".$tableexist;
if (($tmp!=0) AND ($tableexist==1))
{                                          //$table="year$year";
   $date_tmp="$year-$month-$day";
//проверка на рабочий день
   $off=0;
     while ($off==0)
     {
        $res=mysql_query("SELECT * FROM $table_name WHERE dates = '$date_tmp'");
        while(list($kal_num, $dates) = mysql_fetch_row($res))
                {
                 if ($kal_num==0){$day=1+$day;$date_tmp="$year-$month-$day";$off=0;}
                 else {$off=1;};
                }
     }
        //$d[0]=$year;$d[1]=$month;$d[2]=$day;
        //$date=implode(".",$d);
        $date="$year.$month.$day";
                                        //echo " выходн дата функции $date\n";
} else {$date="-1";};
return $date;
}
//============================================
// формирование таблицы рабочих дней
//============================================
function make_dates($year)
{
$table_name="year$year";                         //echo $table_name;
$date_end=mktime(0,0,0,12,31,2000);                 //echo date("Y.m.d",$date_end);
$table = "CREATE TABLE $table_name ( dates date NOT NULL)";
mysql_query($table) or die("Can't create kalendar or table exist");
//$dates="$years.01.01";
$dates=mktime(0,0,0,1,1,2000);
                                                //echo $dates1;
while($dates<=$date_end)
   {
   $dates_tmp=date("Ymd",$dates);
   $res=mysql_query("INSERT INTO $table_name (dates) VALUES ('$dates_tmp')") or die(mysql_error());
   $dates=mktime(0,0,0,date("m.",$dates), date("d.",$dates)+1,date("Y",$dates));
   if ($dates == $date_end){$off=false;};
   };
}
//======================================================================================================
// Функция определения номера даты в календаре (используется в ф-ции kol_dn) входная дата в формате SQL
//======================================================================================================
 function num_dn($date)
{
  settype($date,"string");
  list ($year,$month,$day) = split ('[,/.-]', $date);
  $table_name="year$year";                                         //echo $table_name;
  $res_num_dn=mysql_query("SELECT * FROM $table_name WHERE dates = '$date'");
  while(list($num, $dates) = mysql_fetch_row($res_num_dn))
        {
        $kal_num=$num;
        }
                                                                //echo " data $date число $kal_num <BR>";
        return $kal_num;
}
//================================================================
// Использовать функцию, только при сушествующей дате real_isp
//================================================================
function kol_dn($date_post,$date_isp,$date_real_isp)
{
$a1=num_dn($date_post);                                 //echo "a1= ".$a1."<BR>";
$a2=num_dn($date_isp);                                         //echo "a2= ".$a2."<BR>";
$a3=num_dn($date_real_isp);                                //echo "a3= ".$a3."<BR>";
/*if ($date_isp==$date_post){$tplan=1;}
        else
        { */
        $tplan=($a2-$a1)+1; //echo "tplan=  ".$tplan."<BR>";
       // };
  //if ( isset ($date_real_isp))
  //{
  //if ($date_real_isp<$date_isp){$treal=1;}
 // else
 // {
   $treal=($a3-$a1)+1; // echo "treal=  ".$treal."<BR>";
 //  }
  // }
                                                          //echo "tplan=  ".$tplan."<BR>";
                                                         //echo "treal=  ".$treal."<BR>";
    if ($treal <= $tplan){$kd=1;/*echo " $date_isp  кд = 1";*/}
    else {$kd=($tplan/$treal);}
   return $kd;
}
//================================================================
// Шапка таблицы
//================================================================
 function head_table()
{
echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"99%\" border=\"0\" align=\"middle\">
<TR bgcolor=\"#CCCCAA\">
  <th>Номер докум.</th>
  <th>Дата постановки</th>
  <th>Содержание</th>
  <th>Корреспондент</th>
  <th>Код исполн.</th>
  <th>Исполнить до</th>
  <th>Дата исполн.</th>
  <th>Взято на контроль</th>
  <th>Контр. орган</th>
  </tr>";
}
//================================================================
// Шапка таблицы
//================================================================
 function head_html($punkt="")
{
echo "<HTML><HEAD>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
<link rel=\"STYLESHEET\" type=\"text/css\" href=\"../kid.css\">
</HEAD>
<BODY BGCOLOR=\"#E8E3BE\" TOPMARGIN=0 LEFTMARGIN=5>";
echo "<DD><span class=\"hi\">$punkt</span>";
}
//while (list($num_id, $nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp, $kd, $num_id_kontr, $date_kontr, $nom_cex_kontr) = mysql_fetch_row($result))