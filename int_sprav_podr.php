<?php
include('common.php');
//echo "�������\t". date("d.m.Y");
init_database_connection();
?>
<html>
<head>
<STYLE TYPE="text/css">
<!--
td
        {font-family: Arial cyr; font-size:9pt; color:#000000; }
.hi{font-family: Arial;font-size: 10pt;color: red;background: transparent}
.lo{font-family: Arial;font-size: 9pt;color: black;background: transparent}
A {font-family: Arial Cyr;font-size:10pt;font-style:none;text-decoration: none;color:black}
A:hover{color:blue}
-->
</STYLE>
<title>���������� �������������</title>
<meta http-equiv="Content-Type" content="text/html; charset=window-1251">

</head>
<BODY bgcolor="#E8E3BE" TOPMARGIN=0 LEFTMARGIN=5>
<DD><span class="hi">���������� �������������</span>
<form action="int_sprav_podr.php" method="get">
<table cellpadding="2" CELLSPACING=0 width=50% border="1" align="middle">
<tr bgcolor="#CCCCAA" >
	<TH><span class="lo">���</TH>
	<TH><span class="lo">��������</TH>
	<TH><span class="lo">�����������</TH>
	<TH><span class="lo">���</TH>
	<TH><span class="lo">������1</TH>
	<TH><span class="lo">������2</TH>
	<TH><span class="lo">�����</TH>
	<TH><span class="lo">�����������</TH>
	<TH bgcolor="#ffa500"><span class="lo">������</TH>
	</tr>
<?php
if ( isset($create_product) )
{
	$create_product = 0;
	add_sprav_podrazd($nom_cex, $name_cex, $t_cex, $fam_cex, $gr1, $gr2, $nom_zd, $all_zd);
}

if ( isset($deleting) )
{
	del_sprav_podrazd($category,$category1);
	$category=0;$category1=0;$deleting=0;
}
$result = mysql_query("SELECT * FROM sprav_podrazd")
								or die(mysql_error());
while($row = mysql_fetch_array($result))
{
 	$nom_cex_t=$row["nom_cex"];
 	$name_cex_t=$row["name_cex"];
	$t_cex_t=$row["t_cex"];
	$fam_cex_t=$row["fam_cex"];
 	$gr1_t=$row["gr1"];
 	$gr2_t=$row["gr2"];
 	$nom_zd_t=$row["nom_zd"];
 	$all_zd_t=$row["all_zd"];
	echo "<tr align=\"center\" bgcolor=\"#DDEECC\">".
	" <td>". $nom_cex_t."</td>".
	" <td align=\"left\">&nbsp;". $name_cex_t."</td>".
	" <td align=\"left\">&nbsp;". $fam_cex_t."</td>".
	" <td>". $t_cex_t."</td>".
	" <td>". $gr1_t."</td>".
	" <td>". $gr2_t."</td>".
	" <td>". $nom_zd_t."</td>".
	" <td>". $all_zd_t."</td>".
	" <td bgcolor=\"#CCCCAA\"><a href=\"int_sprav_podr.php?category=".$nom_cex_t."&category1=".$name_cex_t."&deleting=1\">�������</a></td>\n".
	"</TR>\n";
}
?>
<tr align=center>
	<td ><INPUT MAXLENGTH=3  TYPE=text NAME=nom_cex SIZE=3></td>
	<td><INPUT MAXLENGTH=20 TYPE=text NAME=name_cex SIZE=20></td>
	<td><INPUT MAXLENGTH=15 TYPE=text NAME=fam_cex SIZE=15></td>
	<td><INPUT MAXLENGTH=1 TYPE=text NAME=t_cex SIZE=1></td>
	<td><INPUT MAXLENGTH=1 TYPE=text NAME=gr1 SIZE=1></td>
	<td><INPUT MAXLENGTH=1 TYPE=text NAME=gr2 SIZE=1></td>
	<td><INPUT MAXLENGTH=1 TYPE=text NAME=nom_zd SIZE=1></td>
	<td><INPUT MAXLENGTH=1 TYPE=text NAME=all_zd SIZE=1></td>
        <td>&nbsp;</td>

</tr>
</TABLE>
<table cellpadding="2" CELLSPACING=0 width=99% border="0" align="middle"<span class=st4 >
<tr>
      <td COLSPAN=2><INPUT  TYPE=submit name=create_product VALUE="��������"></td>
	<td COLSPAN=3><INPUT  TYPE=reset  VALUE="�����"></td>
</tr>
</form>
</TABLE>
</BR>
</BODY>
</html>