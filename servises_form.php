<?php
include('common.php');
//echo "Сегодня \t". date("d.m.Y");
$d=date("d.m.Y");Unset($result);
//echo $d;
init_database_connection();
//$num_id=$category;

head_html(); ?>
<form action="servises.php" method="get">
<table cellpadding="1" cellspacing="10" width="100" border="0" align="middle">
<?php
if ( isset($kontr) )
{        echo "<P>взятие на контроль</P>";
        echo "<tr><td nowrap>дата взятия</td>";
        echo "<td><INPUT MAXLENGTH=\"10\" TYPE=\"text\" NAME=\"f_date_kontr\" SIZE=\"10\" VALUE=$d></td>";
        echo "<td nowrap>контролирующий орган</td>";
        echo "<td><select NAME=\"f_nom_cex_kontr\" >";
        $res = mysql_query(" SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex FROM sprav_podrazd ")or die(        mysql_error());
while($row = mysql_fetch_array($res))
        {
        $nom_cex_menu=$row["nom_cex"]; $name_cex_menu=$row["name_cex"];
        echo  "<option value=$nom_cex_menu>$nom_cex_menu\t\t$name_cex_menu</option>";
        }
        echo "</td>";
        echo "<td><INPUT  TYPE=\"submit\" NAME=\"kontr_on\" VALUE=\"взять на контроль\"></td>";
        echo "<td><INPUT  TYPE=\"hidden\" NAME=\"f_num_id\" VALUE=$category></td>";
        echo "</tr>";
echo "</table>";
echo "</form>";
}
//=========================
if ( isset($new_dates) )
{        echo "<P>перенос сроков</P>";
        echo "<tr><td nowrap>новая дата исполнения</td>";
        echo "<td><INPUT MAXLENGTH=\"10\" TYPE=\"text\" NAME=\"f_date_isp\" SIZE=\"10\" ></td>";
        echo "<td><INPUT  TYPE=\"submit\" NAME=\"new_dates\" VALUE=\"изменить\"></td>";
        echo "<td><INPUT  TYPE=\"hidden\" NAME=\"f_num_id\" VALUE=$category></td>";
        echo "</tr>";
echo "</table>";
echo "</form>";
}
//=========================
if ( isset($isp) )
{        echo "<P>выполнено</P>";
        echo "<tr><td nowrap>дата исполнения</td>";
        echo "<td><INPUT MAXLENGTH=\"10\" TYPE=\"text\" NAME=\"f_date_real_isp\" SIZE=\"10\" VALUE=$d></td>";
        echo "<td><INPUT  TYPE=\"submit\" NAME=\"ispolneno\" VALUE=\"ввести\"></td>";
        echo "<td><INPUT  TYPE=\"hidden\" NAME=\"f_num_id\" VALUE=$category></td>";
        echo "</tr>";
        echo "</table>";
        echo "</form>";
}
if ( isset($deleting) )
{ echo "<P>Удаление</P>";
  echo "<tr><td nowrap>Вы уверены /td>";
        echo "<td><INPUT TYPE=\"submit\" NAME=\"deleting\" VALUE=\"ДА\"></td>";
        echo "<td><INPUT  TYPE=\"submit\" NAME=\"nodeleting\" VALUE=\"НЕТ\"></td>";
  del_base($category);
  unset($category);unset($deleting);
}

$result=mysql_query("SELECT * FROM base_inf WHERE num_id=$category");
echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"99%\" border=\"0\" align=\"middle\">
<TR bgcolor=\"#CCCCAA\">
  <th>Номер докум.</th>
  <th>Дата постановки</th>
  <th>Содержание</th>
  <th>Корреспондент</th>
  <th>Код исполн.</th>
  <th>Исполнить до</th>
  <th>Дата исполн.</th>
  </tr>";
while (list($num_id, $nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp, $kd) = mysql_fetch_row($result))
{
   /*$nom_doc=$row["nom_doc"];
   $date_post=$row["date_post"];
   $date_otpr=$row["date_otpr"];
   $nom_otpr=$row["nom_otpr"];
   $sod=$row["sod"];
   $korr=$row["korr"];
   $nom_cex=$row["nom_cex"];
  $date_isp=$row["date_isp"];
  $ovz=$row["ovz"];  */

$date_post=inversdate($date_post);
//$date_otpr=inversdate($date_otpr);
$date_isp=inversdate($date_isp);
   $color='#DDEECC';
  $color1='#DDEECC';
 if ( $ovz=="1" )
{$color='#FFCCCC';$color1='#FFCCCC';}
if ( $new_date_isp=="1" )
{$color1='#AADDBB';};
if ( $new_date_isp=="1" )
{$color1='#AADDBB';};
//echo $date_real_isp;
//$date_real_isp=inversdate($date_real_isp);
if ( $date_real_isp=="0000-00-00" )
{$date_real_isp="не исп.";} else {$date_real_isp=inversdate($date_real_isp); $color=$color1='#CCCCCC';};
  echo "<tr align=\"center\" bgcolor=$color>".
  " <td>". $nom_doc."</td>".
  " <TD>". $date_post."</td>".
  " <td bgcolor=$color align=\"center\" nowrap>". $sod."</td>".
  " <TD>". $korr."</td>".
  " <TD>". $nom_cex."</td>".
  " <TD bgcolor=$color1>". $date_isp."</td>".
  " <TD>". $date_real_isp."</td>";
}
Unset($result);//Unset($find_all);

?>
</TABLE>
</BODY>
</HTML>