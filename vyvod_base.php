<?php
include('common.php');
//echo "Сегодня\t". date("d.m.Y");
init_database_connection();

head_html("ПРОСМОТР");
?>
<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=0 WIDTH=750>
<TR>
<TD ROWSPAN=2 ALIGN=CENTER>
<form action="vyvod_base.php" method="get">
Сортировать по
<select NAME="grup">
<OPTION value=nom_doc>номеру документа</OPTION>
<OPTION value=nom_cex>коду исполнителя</OPTION>
<OPTION value=date_isp>дате исполнения</OPTION>
</select>&nbsp;
<INPUT  TYPE=submit NAME=gr VALUE="сортировать">
</form>
</span>
</TD>
<TD BGCOLOR="#DDEECC" ALIGN=CENTER WIDTH=150><span class="lo">обычный документ</span></TD>
<TD BGCOLOR="#FFCCCC" ALIGN=CENTER WIDTH=150><span class="lo">особо важное задание</span></TD></TR>
<TR><TD BGCOLOR="#AADDBB" ALIGN=CENTER><span class="lo">срок был перенесён</span></TD>
<TD BGCOLOR="#CCCCCC" ALIGN=CENTER><span class="lo">документ исполнен</span></TD></TR>
</TABLE>
<?php
head_table();
$croup='nom_doc';
if ( isset($gr) )
{$croup=$grup;}
if ( isset($deleting) )
{
  del_base($category);
  $category=0;$deleting=0;
}
$result = mysql_query("SELECT * FROM base_inf  LEFT JOIN kontr ON base_inf.num_id=kontr.num_id ORDER BY '$croup'") or die(mysql_error());
while (list($num_id, $nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp, $kd, $num_id_kontr, $date_kontr, $nom_cex_kontr) = mysql_fetch_row($result))
{
  $color='#DDEECC';
  $color1='#DDEECC';
if ( isset($date_kontr)){$date_kontr=inversdate($date_kontr);} else {$date_kontr="--"; $nom_cex_kontr="--";};
if ( isset($date_post)){$date_post=inversdate($date_post);};
//$date_otpr=inversdate($date_otpr);
$date_isp=inversdate($date_isp);
if ( $ovz=="1" )
{$color='#FFCCCC';$color1='#FFCCCC';}
if ( $new_date_isp=="1" )
{$color1='#AADDBB';};
//echo $date_real_isp;
//$date_real_isp=inversdate($date_real_isp);
if ( $date_real_isp=="0000-00-00" )
{$date_real_isp="не исп.";} else {$date_real_isp=inversdate($date_real_isp); $color=$color1='#CCCCCC';};
  echo "<tr bgcolor=$color align=\"center\">".
  " <td>". $nom_doc."</td>".
  " <td>". $date_post."</td>".
  " <td align=\"left\">&nbsp;". $sod."</td>".
  " <td align=\"left\">&nbsp;". $korr."</td>".
  " <td>". $nom_cex."</td>".
  " <td bgcolor=$color1 >". $date_isp."</td>".
  " <td>". $date_real_isp."</td>".
  " <td>".$date_kontr ."</td>".
  " <td>".$nom_cex_kontr ."</td>".
  "</TR>\n";
}
?>
</TABLE>
</BODY>
</html>