<?php
include('common.php');
//echo "Сегодня\t". date("d.m.Y");
$ot="2001-01-01";$do="2001-12-31";
$year="2001";
init_database_connection();
if ( isset ($kdgo))
{
if ($month>12)
   {
    switch ($month)
    {
      case 41:$ot="$year-01-01"; $date_end_tmp=mktime(0,0,0,4,1,$year);
        $do=date("Y-m-d",mktime(0,0,0,date("m",$date_end_tmp),date("d",$date_end_tmp)-1,$year)); break;
      case 42: $ot="$year-04-01"; $date_end_tmp=mktime(0,0,0,7,1,$year);
        $do=date("Y-m-d",mktime(0,0,0,date("m",$date_end_tmp),date("d",$date_end_tmp)-1,$year)); break;
      case 43: $ot="$year-07-01"; $date_end_tmp=mktime(0,0,0,10,1,$year);
        $do=date("Y-m-d",mktime(0,0,0,date("m",$date_end_tmp),date("d",$date_end_tmp)-1,$year)); break;
      case 44:  $ot="$year-10-01"; $do="$year-12-31"; break;
      case 55:$ot="$year-01-01";$do="$year-12-31"; break;
    };
   }else {$ot="$year-$month-01"; $date_end_tmp=mktime(0,0,0,1+$month,1,$year);
        $do=date("Y-m-d",mktime(0,0,0,date("m",$date_end_tmp),date("d",$date_end_tmp)-1,$year));
            };
        //echo $ot."   ".$do;
        $result=mysql_query("SELECT * FROM base_inf WHERE date_isp BETWEEN '$ot' AND '$do'");
 }
head_html("РАСЧЕТ КОЭФФИЦИЕНТОВ");
?>
<FORM ACTION='kofficient.php' METHOD='get'>
<table cellpadding="1" cellspacing="2" width="100" border="0" align="middle">
<TR>
        <TD><select NAME='month' >
       <OPTION value="01">январь</OPTION>
       <OPTION value="02">февраль</OPTION>
       <OPTION value="03">март</OPTION>
       <OPTION value="04">апрель</OPTION>
       <OPTION value="05">май</OPTION>
       <OPTION value="06">июнь</OPTION>
       <OPTION value="07">июль</OPTION>
       <OPTION value="08">август</OPTION>
       <OPTION value="09">сентябрь</OPTION>
       <OPTION value="10">октябрь</OPTION>
       <OPTION value="11">ноябрь</OPTION>
       <OPTION value="12">декабрь</OPTION>
       <OPTION value="41">1 квартал</OPTION>
       <OPTION value="42">2 квартал</OPTION>
       <OPTION value="43">3 квартал</OPTION>
       <OPTION value="44">4 квартал</OPTION>
       <OPTION value="55">год</OPTION>
       </TD>
        <TD><INPUT MAXLENGTH=4 TYPE=text NAME='year' SIZE=4 VALUE='2001'></TD>
        <TD><INPUT  TYPE=submit NAME='kdgo' VALUE="показать"></TD>
        <TD></TD>
        <TD></TD>
</TR>
</table>
</form>
  <TABLE cellpadding="2" CELLSPACING=0 width=99% border="1" align="middle" >
<TR bgcolor="#CCCCAA">
  <th><span class="lo">Номер докум.</th>
  <th><span class="lo">Дата постановки</th>
  <th><span class="lo">Содержание</th>
  <th><span class="lo">Корреспондент</th>
  <th><span class="lo">Код исполн.</th>
  <th><span class="lo">Исполнить до</th>
  <th><span class="lo">Дата исполн.</th>
  <th><span class="lo">Коэффициент исполнения</th>
  </tr>
<?php
$color='#DDEECC';
if ( empty($result)){$result=mysql_query("SELECT * FROM base_inf WHERE date_isp BETWEEN '$ot' AND '$do'");};
//$result=mysql_query("SELECT * FROM base_inf WHERE date_isp BETWEEN '$ot' AND '$do'");
$sel_cex=mysql_query("SELECT * FROM sprav_podrazd ");
        while($row1 = mysql_fetch_array($sel_cex))
{
        $nom_cex_t=$row1["nom_cex"];
        $name_cex_t=$row1["name_cex"];
        //$t_cex_t=$row1["t_cex"];
        $fam_cex_t=$row1["fam_cex"];
        //$gr1_t=$row1["gr1"];
        //$gr2_t=$row1["gr2"];
        //$nom_zd_t=$row1["nom_zd"];
        //$all_zd_t=$row1["all_zd"];
        $ot_viv=inversdate($ot);
        $do_viv=inversdate($do);
        echo "<tr bgcolor=\"#CCCCCC\" align=\"left\">".
          " <td COLSPAN=\"8\">"."<B>Цех  $nom_cex_t   $name_cex_t </B> исполнитель $fam_cex_t "."</td></tr>";
        $resultall=mysql_query("SELECT * FROM base_inf WHERE (nom_cex=$nom_cex_t) AND (date_isp BETWEEN '$ot' AND '$do')");
        $skd=0;$k=0;
        //$row = mysql_fetch_array($result);
//        $row=false;
//$tmp=mysql_num_rows($resultall);
        if ( mysql_num_rows($resultall) >0)
        {
          while($row = mysql_fetch_array($resultall))
           {
           $t_nom_doc=$row["nom_doc"];
           $t_date_post=$row["date_post"];
           $t_date_isp=$row["date_isp"];
           $t_date_real_isp=$row["date_real_isp"];
           $t_kd=$row["kd"];
           $t_nom_cex=$row["nom_cex"];
           $t_sod=$row["sod"];
           $t_korr=$row["korr"];
           $t_nom_cex=$row["nom_cex"];
          //расчет задержки исполнения
          if ( ($t_kd==0) AND ($t_date_real_isp=="0000-00-00")){$t_kd=kol_dn($t_date_post,$t_date_isp,$do); $skd=$skd+$t_kd;$k=1+$k;}
                  else {$skd=$skd+$t_kd;$k=1+$k;};
          $t_date_post=inversdate($t_date_post);
          if ( $t_date_real_isp=="0000-00-00"){$t_date_real_isp="не исп.";}else{$t_date_real_isp=inversdate($t_date_real_isp);};
          $t_date_isp=inversdate($t_date_isp);
           $t_kd=sprintf("%01.4f",$t_kd);
          echo "<tr bgcolor=$color align=\"center\">".
          " <TD>". $t_nom_doc."</td>".
          " <TD>". $t_date_post."</td>".
          " <TD>". $t_sod."</td>".
          " <TD>". $t_korr."</td>".
          " <TD>". $t_nom_cex."</td>".
          " <TD>". $t_date_isp."</td>".
          " <TD>". $t_date_real_isp."</td>".
          " <TD>". $t_kd."</td>"."<TR>";
          }
          $message="Средний коэффициент за период с $ot_viv по $do_viv"; //Unset($row);
        }
         else {$ot1=$do; $do1="$year-12-31"; $result1=mysql_query("SELECT * FROM base_inf WHERE (nom_cex=$nom_cex_t) AND (date_isp BETWEEN '$ot1' AND '$do1')");

                          if ( mysql_num_rows($result1) <=0){$skd=0.8; $k=1;$message="нет заданий"; }
                            else {$skd=1; $k=1;$e_kd=1; $message="задания выполняются";
                                while($row2 = mysql_fetch_array($result1))
                             {
                            $e_nom_doc=$row2["nom_doc"];
                               $e_date_post=$row2["date_post"];
                            $e_date_isp=$row2["date_isp"];
                           $e_date_real_isp=$row2["date_real_isp"];
                           $e_kd=$row2["kd"];
                           $e_nom_cex=$row2["nom_cex"];
                           $e_sod=$row2["sod"];
                           $e_korr=$row2["korr"];
                           $e_nom_cex=$row2["nom_cex"];
                              if ($e_date_real_isp=="0000-00-00"){$e_date_real_isp="не исп.";}else{$e_date_real_isp=inversdate($e_date_real_isp);};
                                  $e_date_isp=inversdate($e_date_isp);
                                $e_date_post=inversdate($e_date_post);
                               echo "<tr bgcolor=$color align=\"center\">".
          " <TD>". $e_nom_doc."</td>".
          " <TD>". $e_date_post."</td>".
          " <TD>". $e_sod."</td>".
          " <TD>". $e_korr."</td>".
          " <TD>". $e_nom_cex."</td>".
          " <TD>". $e_date_isp."</td>".
          " <TD>". $e_date_real_isp."</td>".
          " <TD>". $e_kd."</td>"."<TR>";
                                  }
                                $message="задания $ot1 -- $do1 выполняются";Unset($row2);
                            };
               //Unset($row);
               };





        if ($k!==0){$skd1=$skd/$k;$skd1=sprintf("%01.4f",$skd1);} else {$skd1=0;};
        echo "<tr align=\"center\">".
          " <td COLSPAN=\"7\">"."$message  </td>"."<td>".$skd1."</td></tr>";
      }
         //}
?>

</table>
</BODY>
</HTML>