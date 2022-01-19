<?php
include('common.php');
echo "Сегодня \t". date("d.m.Y");
$d=date("d.m.Y");Unset($result);
//echo $d;
init_database_connection();
//$num_id=4;
?>
 <HTML>
<HEAD><TITLE>КОНТРОЛЬ</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="STYLESHEET" type="text/css" href="kid.css">
</HEAD>
<BODY bgcolor="#E8E3BE" text="#000000" link="#dc143c" alink="#cc0000" vlink="#660000">
<form action="kontr.php" method="get">
<table cellpadding="1" cellspacing="10" width="100" border="0" align="middle" <span class=st4 >
<?php
if ( isset($kontr) )
{	echo "<P>взятие на контроль</P>";
	echo "<tr><td nowrap>дата взятия</td>";
	echo "<td><INPUT MAXLENGTH=\"10\" TYPE=\"text\" NAME=\"date_kontr\" SIZE=\"10\" VALUE=$d></td>";
	echo "<td nowrap>контролирующий орган</td>";
	echo "<td><select NAME=\"nom_cex_kontr\" >";
	$res = mysql_query(" SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex FROM sprav_podrazd ")or die(	mysql_error());
while($row = mysql_fetch_array($res))
	{
        $nom_cex_menu=$row["nom_cex"]; $name_cex_menu=$row["name_cex"];
        echo  "<option value=$nom_cex_menu> $nom_cex_menu\t\t$name_cex_menu></option>";
	}
	echo "</td>";
	echo "<td><INPUT  TYPE=\"submit\" NAME=\"kontr_on\" VALUE=\"взять на контроль\"></td>";
	echo "</tr>";
echo "</table>";
echo "</form>";
}
//=========================
if ( isset($new_dates) )
{	echo "<P>перенос сроков</P>";
	echo "<tr><td nowrap>новая дата исполнения</td>";
        echo "<td><INPUT MAXLENGTH=\"10\" TYPE=\"text\" NAME=\"date\" SIZE=\"10\" ></td>";
        echo "<td><INPUT  TYPE=\"submit\" NAME=\"new_dates\" VALUE=\"изменить\"></td>";
        echo "</tr>";
echo "</table>";
echo "</form>";
}
//=========================
if ( isset($isp) )
{	echo "<P>выполнено</P>";
	echo "<tr><td nowrap>дата исполнения</td>";
        echo "<td><INPUT MAXLENGTH=\"10\" TYPE=\"text\" NAME=\"date_kontr\" SIZE=\"10\" VALUE=$d></td>";
        echo "<td><INPUT  TYPE=\"submit\" NAME=\"kontr_on\" VALUE=\"ввести\"></td>";
        echo "</tr>";
echo "</table>";
echo "</form>";
}
?>
<BR>
<A href="vyvod_base.php">просмотр введенных данных</A></BR>
<FONT SIZE="+3"><A href="index.html">ГЛАВНОЕ МЕНЮ</A></FONT>
<?php
$result=mysql_query("SELECT * FROM base_inf WHERE num_id=$num_id");
?>
<table cellpadding="1" width="200" border="0" align="middle" >
<tr align="center" bgcolor="#66cc99">
  <td bgcolor="#66cc99" nowrap>Внутр. номер</td>
  <td bgcolor="#66cc99" nowrap>Номер докум.</td>
  <td bgcolor="#66cc99" >дата постановки</td>
  <td bgcolor="#66cc99" >содержание</td>
  <td bgcolor="#66cc99" nowrap>корреспондент</td>
  <td bgcolor="#66cc99" >код исполн.</td>
  <td bgcolor="#66cc99" >Исполнить до</td>
  <td bgcolor="#66cc99" >Выполнено</td>
  </tr>
<?php
while (list($num_id, $nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp) = mysql_fetch_row($result))
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
  $color='#99cc99';
  $color1='#99cc99';
if ( $ovz=="1" )
{$color='#ffb6c1';$color1='#ffb6c1';}
if ( $new_date_isp=="1" )
{$color1='#ffb9c1';}
if ( $date_real_isp="000.00.00" )
{$date_real_isp="не исп.";}
  echo "<tr align=\"center\">".
  " <td bgcolor=$color align=\"center\">". $num_id."</td>".
  " <td bgcolor=$color align=\"center\">". $nom_doc."</td>".
  " <td bgcolor=$color align=\"center\">". $date_post."</td>".
  " <td bgcolor=$color align=\"center\" nowrap>". $sod."</td>".
  " <td bgcolor=$color align=\"center\">". $korr."</td>".
  " <td bgcolor=$color align=\"center\">". $nom_cex."</td>".
  " <td bgcolor=$color align=\"center\">". $date_isp."</td>".
  " <td bgcolor=$color align=\"center\">". $date_real_isp."</td>";
}
Unset($result);//Unset($find_all);

?>
</TABLE>
</BODY>
</HTML>