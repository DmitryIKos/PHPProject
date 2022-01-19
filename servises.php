<?php
include('common.php');
//echo "Сегодня \t". date("d.m.Y");
$d=date("d.m.Y");Unset($result);
//echo $d;
init_database_connection();
if ( isset($kontr_on) )
{       echo "<P>документ № $f_num_id взят на контроль контроль</P>";
        Unset($kontr_on);  $f_date_kontr=inversdate_chek($f_date_kontr);
        add_kontr($f_num_id,$f_date_kontr,$f_nom_cex_kontr);
}
//=========================
if ( isset($new_dates) )
{   Unset($new_dates); $f_date_isp=inversdate_chek($f_date_isp);
    mysql_query("UPDATE base_inf SET date_isp='$f_date_isp', new_date_isp=1 WHERE num_id = '$f_num_id' ") or die(mysql_error());
    $f_date_isp=inversdate_chek($f_date_isp);
    echo "<P>у документа № $f_num_id перенесен срок исполнения на $f_date_isp</P>";
    //Unset($res);
}
//=========================
if ( isset($ispolneno) )
{   Unset($ispolneno);//echo $f_date_real_isp;
    $f_date_real_isp=inversdate($f_date_real_isp); //echo $f_date_real_isp; //(дата в sql)
    mysql_query("UPDATE base_inf SET date_real_isp='$f_date_real_isp', new_date_isp=1 WHERE num_id='$f_num_id' ") or die(mysql_error());
    mysql_query("DELETE FROM kontr WHERE (num_id = '$f_num_id')") or die(mysql_error());
    // читаем строку
    $res=mysql_query("SELECT date_post,date_isp FROM base_inf WHERE num_id='$f_num_id' ");
    while($row = mysql_fetch_array($res))
        {
        $date_post=$row["date_post"]; $date_isp=$row["date_isp"];
        }
        //echo $date_post."   --  ".$date_isp."  ----  ".$f_date_real_isp;
    $kd=kol_dn($date_post,$date_isp,$f_date_real_isp);
    mysql_query("UPDATE base_inf SET kd='$kd' WHERE num_id='$f_num_id' ");

//
    $f_date_real_isp=inversdate($f_date_real_isp);
    echo "<P>документ с вн. № $f_num_id исполненен  $f_date_real_isp</P>";
    echo "коэффициент исполнения =  $kd";
    //Unset($res);
}
 if ( isset($find_nom_doc) )
{  $result = mysql_query("SELECT * FROM base_inf  LEFT JOIN kontr ON base_inf.num_id=kontr.num_id WHERE nom_doc=$nom_doc") or die(mysql_error());Unset($find_nom_doc);
}
if ( isset($find_nom_pr) )
{
settype($nom_pr,"string");
$tmp="000000";$tmp1="999999";
$ot="$nom_pr$tmp";                     //echo "$ot\t";
$do="$nom_pr$tmp1";                     //   echo "$do\t";
        $result=mysql_query("SELECT * FROM base_inf LEFT JOIN kontr ON base_inf.num_id=kontr.num_id WHERE nom_doc BETWEEN '$ot' AND '$do'");
//$result = mysql_query("SELECT * FROM base_inf WHERE nom_doc=$nom_doc ORDER BY nom_doc") or die(mysql_error());
Unset($find_nom_pr);
}
if ( isset($find_nom_cex) )
{ $result = mysql_query("SELECT * FROM base_inf  LEFT JOIN kontr ON base_inf.num_id=kontr.num_id WHERE nom_cex=$nom_cex") or die(mysql_error());
Unset($find_nom_cex);
}
//==========================
if ( isset($deleting) )
{
  del_base($category);
  unset($category);unset($deleting);
}
//==========================
head_html("СЕРВИС");
?>
<form action="servises.php" method="get">
<table cellpadding="1" cellspacing="10" width="100" border="0" align="middle">
<tr>
<TH>Введите</TH>
</TR>
  <TD nowrap>номер приказа :</TD>
  <td><INPUT MAXLENGTH=6 TYPE=text NAME=nom_pr SIZE=6></td>
  <td><INPUT  TYPE=submit NAME=find_nom_pr VALUE="искать"></td>
  <TD nowrap>номер документа :</TD>
  <td><INPUT MAXLENGTH=11 TYPE=text NAME=nom_doc SIZE=11></td>
  <td><INPUT  TYPE=submit NAME=find_nom_doc VALUE="искать"></td>
</TR><TR>
  <td nowrap>код исполнителя</td>
  <td><select NAME="nom_cex" >
  <?php
$result_menu = mysql_query("SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex FROM sprav_podrazd ") or die(mysql_error());
while($row = mysql_fetch_array($result_menu))
{
$menu_nom_cex=$row["nom_cex"]; $menu_name_cex=$row["name_cex"];
echo "<OPTION value=\"$menu_nom_cex\">".$menu_nom_cex."&nbsp&nbsp".$menu_name_cex."</OPTION>";
}
?>
  </td>
  <td><INPUT  TYPE=submit NAME=find_nom_cex VALUE="искать"></td>

  <TD></TD>
</tr>

</table>
</form>
<?php
if ( $result>0 ){
?>
<TABLE cellpadding="2" CELLSPACING=0 width=60% border="1" align="middle" >
<TR bgcolor="#CCCCAA">
  <th>Номер докум.</th>
  <th>Дата постановки</th>
  <th width="60">Содержание</th>
  <th>Корреспондент</th>
  <th>Код исполн.</th>
  <th>Исполнить до</th>
  <th>Дата исполн.</th>
  <th>Взято на контроль</th>
  <th>Контр. орган</th>
  <TH COLSPAN=4 bgcolor="#ffa500">Сервис</TH>
  </tr>
<?php
$pr="";
while (list($num_id, $nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp, $kd, $num_id_kontr, $date_kontr, $nom_cex_kontr) = mysql_fetch_row($result))
{ $color='#DDEECC';
  $color1='#DDEECC';
if ( isset($date_kontr)){$date_kontr=inversdate($date_kontr);$pr="k";} else {$date_kontr="--";$nom_cex_kontr="--";};
if ( isset($date_isp)){$date_isp=inversdate($date_isp);};
//$date_otpr=inversdate($date_otpr);
$date_post=inversdate($date_post);
if ( $ovz=="1" )
{$color='#FFCCCC';$color1='#FFCCCC';}
if ( $new_date_isp=="1" )
{$color1='#AADDBB';};
if ( $new_date_isp=="1" )
{$color1='#AADDBB';};
//echo $date_real_isp;
//$date_real_isp=inversdate($date_real_isp);
if ( $date_real_isp=="0000-00-00" )
{$date_real_isp="не исп.";} else {$date_real_isp=inversdate($date_real_isp); $color=$color1='#CCCCCC';$pr="i";};
  echo "<tr align=\"center\" bgcolor=$color>".
  "<TD>". $nom_doc."</TD>".
  "<TD>". $date_post."</TD>".
  "<TD bgcolor=$color align=\"center\" width=\"100\">". $sod."</TD>".
  "<TD>". $korr."</TD>".
  "<TD>". $nom_cex."</TD>".
  "<TD bgcolor=$color1>". $date_isp."</TD>".
  "<TD>". $date_real_isp."</TD>".
  "<TD>".$date_kontr ."</TD>".
  "<TD>".$nom_cex_kontr ."</TD>".
  "<TD bgcolor=\"#CCCCAA\"><a href=\"servises_form.php?category=".$num_id."&deleting=1\"TITLE=\"Удалить документ\">удалить</a></TD>";
  if ($pr=="i"){echo "<TD bgcolor=\"#CCCCAA\">взять на контроль</TD>".
                       "<TD bgcolor=\"#CCCCAA\"TITLE=\"Перенести срок исполнения\">перенос сроков</TD>".
                       "<TD bgcolor=\"#CCCCAA\"TITLE=\"Документ исполнен\">выполнено</TD>";}
      elseif ($pr=="k"){echo"<TD bgcolor=\"#CCCCAA\"TITLE=\"Взять на контроль\">взять на контроль</TD>".
                        "<TD bgcolor=\"#CCCCAA\"><a href=\"servises_form.php?category=".$num_id."&new_dates=1&prizn=".$pr."\"TITLE=\"Перенести срок исполнения\">перенос сроков</a></TD>".
                     "<TD bgcolor=\"#CCCCAA\"><a href=\"servises_form.php?category=".$num_id."&isp=1&prizn=".$pr."\"TITLE=\"Документ исполнен\">выполнено</a></TD>";}
                     else {echo
  "<TD bgcolor=\"#CCCCAA\"><a href=\"servises_form.php?category=".$num_id."&kontr=1&prizn=".$pr."\"TITLE=\"Взять на контроль\">взять на контроль</a></TD>".
  "<TD bgcolor=\"#CCCCAA\"><a href=\"servises_form.php?category=".$num_id."&new_dates=1&prizn=".$pr."\"TITLE=\"Перенести срок исполнения\">перенос сроков</a></TD>".
  "<TD bgcolor=\"#CCCCAA\"><a href=\"servises_form.php?category=".$num_id."&isp=1&prizn=".$pr."\"TITLE=\"Документ исполнен\">исп.</a></TD>";
  };
  echo"</TR>";Unset($pr);
}
Unset($result);//Unset($find_all);
}
?>
</TABLE>
</BODY>
</HTML>