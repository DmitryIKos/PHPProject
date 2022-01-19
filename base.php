<?php
include('common.php');
echo "Сегодня\t". date("d.m.Y");
init_database_connection();
if ( isset($create_product) )
{
	$create_product = 0;
	add_base($nom_doc, $date_post, $date_otpr, $nom_otpr, $sod, $korr, $nom_cex, $date_isp, $ovz);
}
if ( isset($kontr) )
{
	add_kontr($nom_doc,date("d.m.Y"),$nom_cex_kontr);
	$kontr=0;
}
?>
