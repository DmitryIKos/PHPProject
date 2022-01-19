<?php
include('common.php');
//echo "Сегодня \t". date("d.m.Y");
$d=date("d.m.Y");Unset($result);
//echo $d;
init_database_connection();
 if ( isset($find_nom_doc) )
{
settype($nom_doc,"string");
$tmp="000000";$tmp1="999999";
$ot="$nom_doc$tmp";			echo "$ot\t";
$do="$nom_doc$tmp1";                        echo "$do\t";
        $result=mysql_query("SELECT * FROM base_inf WHERE nom_doc BETWEEN '$ot' AND '$do'");
//$result = mysql_query("SELECT * FROM base_inf WHERE nom_doc=$nom_doc ORDER BY nom_doc") or die(mysql_error());
Unset($find_nom_doc);
}
if ( isset($find_nom_cex) )
{ $result = mysql_query("SELECT * FROM base_inf WHERE nom_cex=$nom_cex ORDER BY nom_doc") or die(mysql_error());Unset($find_nom_cex);
}
if ( isset($find_ovz) )
{  $result = mysql_query("SELECT * FROM base_inf WHERE ovz='1' ORDER BY nom_doc") or die(mysql_error());Unset($find_ovz);
}
if ( isset($find_date_isp) )
{
switch ($date_isp)
    {
      case 0:$zn="="; break;
      case 1:$zn=">"; break;
    };
  if (($date_isp==0) OR ($date_isp==1))
	{
        $result = mysql_query("SELECT * FROM base_inf WHERE date_real_isp $zn'0000-00-00' ORDER BY date_real_isp") or die(mysql_error());
        Unset($find_date_isp);
        }
        else {$date_isp=date("Ymd",$date_isp);
$result = mysql_query("SELECT * FROM base_inf WHERE date_isp<=$date_isp ORDER BY date_isp") or die(mysql_error());
}
Unset($find_date_isp);
}
if ( isset($find_kontr) )
{  $result = mysql_query("SELECT * FROM base_inf,kontr WHERE kontr.num_id=base_inf.num_id") or die(mysql_error());
}
head_html("ПОИСК");
?>
<form action="find.php" method="get">
<table cellpadding="1" cellspacing="10" width="100" border="0" align="middle">
<tr>
  <td nowrap>номеру документа</td>
  <td width="30%"><INPUT MAXLENGTH=11 TYPE=text NAME=nom_doc SIZE=11></td>
  <td><INPUT  TYPE=submit NAME=find_nom_doc VALUE="искать"></td>
  <td nowrap>коду исполнителя</td>
  <td><select NAME="nom_cex" >
  <?php
$result_menu = mysql_query("SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex FROM sprav_podrazd ") or die(mysql_error());
while($row = mysql_fetch_array($result_menu))
{
$menu_nom_cex=$row["nom_cex"]; $menu_name_cex=$row["name_cex"];
?>
    <OPTION value="<?php echo $menu_nom_cex; ?>"><?php echo $menu_nom_cex."&nbsp&nbsp".$menu_name_cex;?></OPTION>
<?php
}
?>
  </td>
  <td><INPUT  TYPE=submit NAME=find_nom_cex VALUE="искать"></td>
</tr>
<tr>
  <td nowrap>особо важные задания</td>
  <td><INPUT  TYPE=submit NAME=find_ovz VALUE="искать"></td>
  <td></td>
  <td nowrap>сроку исполнения</td>
  <td><select NAME="date_isp">
<OPTION value=1>исполненные</OPTION>
<OPTION value=<?php $tmp_date=mktime(0,0,0,date("m."), date("d.")+5,date("Y")); echo $tmp_date; ?>> < 5 дней до исполнения</OPTION>
<OPTION value=0>не исполненные</OPTION>
</td>
  <td><INPUT  TYPE=submit NAME=find_date_isp VALUE="искать"></td>
  <td></td>
</tr>
<tr>
  <td nowrap>взятые на контроль</td>
  <td><INPUT  TYPE=submit NAME=find_kontr VALUE="искать"></td>
  <td><INPUT TYPE=HIDDEN NAME=find_all VALUE='1'></td><td></td>
  </tr>
</table>
</form>
<?php
if ( isset($result) ){
?>
<table cellpadding="1" width="200" border="0" align="middle" >
<tr align="center" bgcolor="#66cc99">
  <td bgcolor="#66cc99" nowrap>Номер докум.</td>
  <td bgcolor="#66cc99" >дата постановки</td>
  <td bgcolor="#66cc99" >содержание</td>
  <td bgcolor="#66cc99" nowrap>корреспондент</td>
  <td bgcolor="#66cc99" >код исполн.</td>
  <td bgcolor="#66cc99" >Исполнить до</td>
  <td bgcolor="#66cc99" >Выполнено</td>
  </tr>
<?php
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
  $color='#99cc99';
  $color1='#99cc99';
if ( $ovz=="1" )
{$color='#ffb6c1';$color1='#ffb6c1';}
if ( $new_date_isp=="1" )
{$color1='#ffb9c1';}
if ( $date_real_isp=="0000-00-00" )
{$date_real_isp="не исп.";} else {$date_real_isp=inversdate($date_real_isp);};
  echo "<tr align=\"center\" bgcolor=$color>".
  " <td align=\"center\">". $nom_doc."</td>".
  " <td align=\"center\">". $date_post."</td>".
  " <td align=\"center\" nowrap>". $sod."</td>".
  " <td align=\"center\">". $korr."</td>".
  " <td align=\"center\">". $nom_cex."</td>".
  " <td align=\"center\">". $date_isp."</td>".
  " <td align=\"center\">". $date_real_isp."</td>".
  "</TR>\n";
}
Unset($result);Unset($find_all);
}
?>
</TABLE>
</BODY>
</HTML>