<?php
include('common.php');init_database_connection();
echo "<HTML><HEAD>
<meta http-equiv=\"Content-Type\" content=text/html; charset=windows-1251>
<STYLE TYPE=text/css>
<!--
TD{font-family: arial;font-size:8pt;color: black};
TH{font-family: arial;font-size:10pt;color: brown};
.hi{font-family: Arial;font-size: 10pt;color: red;background: transparent}
A {font-family: Arial Cyr;font-size:10pt;font-style:none;text-decoration: none;color:black}
A:hover{color:blue;}
-->
</STYLE>

</HEAD>
<BODY BGCOLOR=\"#E8E3BE\" TOPMARGIN=0 LEFTMARGIN=5>";
echo "<DD><span class=\"hi\">Печать предупреждений</span>";
echo "<FORM action=\"print.php\" method=\"get\">
<table cellpadding=\"1\" cellspacing=\"10\" width=\"60%\" border=\"0\" align=\"middle\">
<tr>
<TH>Введите номер приказа :</TH>
  <TD><INPUT MAXLENGTH=6 TYPE=text NAME=nom_pr SIZE=6></TD>
  <TD><INPUT  TYPE=submit NAME=print_nom_pr VALUE=\"искать\"></TD>
  </TR>
  </TABLE>
  </FORM>";


if ( isset($print_nom_pr) )
{Unset($print_nom_pr);
$ot="$nom_pr"."%";//       echo "$ot<BR>";
$result=mysql_query("SELECT * FROM base_inf LEFT JOIN kontr ON base_inf.num_id=kontr.num_id where base_inf.nom_doc LIKE '$ot'");
echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"99%\" border=\"0\" align=\"middle\">";
while($row = mysql_fetch_array($result))
{        $nom_doc=$row["nom_doc"];
         $date_post=$row["date_post"];
         $sod=$row["sod"];
         $korr=$row["korr"];
        $nom_cex=$row["nom_cex"];
         $date_isp=$row["date_isp"];
         $new_date_isp=$row["new_date_isp"];
         $nom_cex_kontr=$row["nom_cex_kontr"];
         //$all_zd=$row["all_zd"];

       //{
       if ( isset($date_isp)){$date_isp=inversdate($date_isp);};
       $date_post=inversdate($date_post);
       if ( $date_real_isp=="0000-00-00" )
       {$date_real_isp="не исп.";} else {$date_real_isp=inversdate($date_real_isp);};
         echo "<TR>".
           "<TD>&nbsp;&nbsp;". $nom_doc."</TD><TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           </TD>";
             if ( $new_date_isp=="1" )
             {echo "<TD>&nbsp;&nbsp;". $date_isp."</TD>";}else{echo"<TD>&nbsp;</TD>";};
               echo"<TR><TD>&nbsp;&nbsp;". $date_post."</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;&nbsp;". $nom_cex."</TD></TR>".
               "<TR><TD>&nbsp;&nbsp;". $date_isp."</TD></TR>".
               "<TR><TD>&nbsp;&nbsp;".$nom_cex_kontr ."</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;&nbsp;".$sod."</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;&nbsp;".$korr."</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>".
               "<TR><TD>&nbsp;</TD></TR>"."<TR><TD>&nbsp;</TD></TR>";
               echo"</TR>";Unset($pr);
         }
}
echo "
</TABLE>
</BODY>
</HTML>";




?>