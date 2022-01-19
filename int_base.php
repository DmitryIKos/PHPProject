<?php
include('common.php');
$d=date("Y.m.d");
init_database_connection();
//выборка максимального номера документа
/*$sel = mysql_query("SELECT max(base_inf.nom_doc) FROM base_inf");
while($row = mysql_fetch_array($sel)){
$nom_doc_tmp=$row["max(base_inf.nom_doc)"];}
$nom_doc_tmp=$nom_doc_tmp+1;$nom_doc_tmp=0;*/
$ovztmp=3;$nom_cex_sel="";$sod_sel="";
$kd=0;
if ( isset($create_product) )
{ $nom_cex_sel=$nom_cex_kontr;//для сохранения в меню выбранного пункта
  //$nom_doc_tmp=1+$nom_doc;$sel_nom=0;
  $sod_sel=$sod;//для сохранения в меню выбранного пункта
  $kd=0;
  settype($korr,"string");
  //gettype($korr,"string"); echo " корресп передается как $korr";
  if ( !isset($ovz) ){$ovz=0;}
  $ovztmp=$ovz;
  Unset($create_product);
  //=======================================извращение с датами
  if ( isset($date_post) ){$date_post=inversdate_chek($date_post);}
  //if ( isset($date_otpr) ){$date_otpr=inversdate($date_otpr);}
  if ( isset($date_isp) ){$date_isp=inversdate_chek($date_isp);}else{$date_isp=date("Ymd");};
                                  //echo  $date_post."<BR>"; echo $date_isp."<BR>";
  if (($date_isp) < ($date_post)){$date_isp=-1;};
  if ( ($date_isp==-1) OR ($date_post==-1)){$msg="Неправильно введена дата поступления  или исполнения! Повторите ввод"."<BR>";};
  //=======================================
  if (empty($nom_doc) ){$msg="Введите номер документа!";$date_isp=-1;};
  if ($nom_cex=="1111"){$t_cex_raz=1;$sel_nom=1;};//все цеха
  if ($nom_cex=="2222"){$t_cex_raz=2;$sel_nom=1;};//все отделы
  if ($nom_cex=="3333"){$t_cex_raz=3;$sel_nom=1;};//совет управления
  if (($date_isp==-1) OR ($date_post==-1)){$off=1;echo $msg;} else {$off=0;};
                                                          //echo "nom_doc = $nom_doc  nom_cex = $nom_cex  korr = $korr\n";
  if (($nom_cex=="6666") AND ($off!=1))//всем! всем!
  {        Unset($result_raz);Unset($row);
          $result_raz = mysql_query(" SELECT * FROM sprav_podrazd ")or die(mysql_error());
         while($row = mysql_fetch_array($result_raz))
        {
                $nom_cex=$row["nom_cex"]; //echo $nom_cex."<br>";
         $sel_nom==2;
         add_base($nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp,$kd);
         $num_id_kontr=mysql_insert_id();//echo $num_id_kontr;
         if ( isset($kontr) )
         {
         //Unset($kontr);
         $date_kontr=$date_post;
         add_kontr($num_id_kontr,$date_kontr,$nom_cex_kontr);
           }
            }
  }
  else
  {
  if (($sel_nom == 1) and ($sel_nom !== 2)  AND ($off==0))
  { //echo " пункт 2 и 3 <BR>";
   $result_raz = mysql_query(" SELECT nom_cex, t_cex FROM sprav_podrazd WHERE t_cex='$t_cex_raz'")or die(mysql_error());
  while (list($nom_cex,$t_cex) = mysql_fetch_row($result_raz))/*??????*/
    {settype($korr,"string");
    add_base($nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp,$kd);
         $num_id_kontr=mysql_insert_id();//echo $num_id_kontr;
         if ( isset($kontr) )
         {        //Unset($kontr);
                 $date_kontr=$date_post;
                 add_kontr($num_id_kontr,$date_kontr,$nom_cex_kontr);
         }
    };
  }
  else
          { if (($off!==1))
        {settype($korr,"string");
         add_base($nom_doc, $date_post, $sod, $korr, $nom_cex, $date_isp, $date_real_isp, $ovz, $new_date_isp,$kd);
         $num_id_kontr=mysql_insert_id();//echo $num_id_kontr;
                if ( isset($kontr) )
                  {
                  Unset($kontr); $date_kontr=$date_post;
                  add_kontr($num_id_kontr,$date_kontr,$nom_cex_kontr);
                  }
        }
        };
   };

  }
head_html("РЕГИСТРАЦИЯ ДОКУМЕНТА");
?>
<form action="int_base.php" method="get">
<table cellpadding="1" cellspacing="2" width="100" border="0" align="middle" <span class=st4 >
<tr>
  <td nowrap>номер документа</td>
  <td><INPUT MAXLENGTH=11 TYPE=text NAME='nom_doc' SIZE=11></td>
  <td nowrap>код исполнителя</td>
  <td><select NAME='nom_cex' >
       <OPTION value="1111">все цеха</OPTION>
       <OPTION value="2222">все отделы</OPTION>
       <OPTION value="3333">совет управления</OPTION>
       <OPTION value="6666">всем! всем! всем!</OPTION>
<?php
$result = mysql_query("SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex FROM sprav_podrazd ") or die(mysql_error());
while($row = mysql_fetch_array($result))
{
$nom_cex_menu=$row["nom_cex"]; $name_cex_menu=$row["name_cex"];
echo "<OPTION value=\"$nom_cex_menu\">$nom_cex_menu&nbsp&nbsp$name_cex_menu</OPTION>";
}
?>
</td>

</tr>
<tr>
  <td nowrap>дата поступления</td>
  <td><INPUT MAXLENGTH=10 TYPE=text NAME=date_post SIZE=10 VALUE=<?php echo date("d.m.Y"); ?>></td>
  <td nowrap>срок исполнения</td>
  <td><INPUT  TYPE=text MAXLENGTH=12 NAME=date_isp SIZE=12></td>

</tr>
<tr>
  <td></td>
  <td></td>
  <td nowrap>особо важное задание</td>
  <td><INPUT  TYPE=checkbox NAME=ovz value="1"></td>


</tr>
<tr>
        <td nowrap>взять на контроль</td>
          <td><INPUT  TYPE=checkbox NAME=kontr value="1"></td>
        <td nowrap>контролирующий орган</td>
        <td><select NAME='nom_cex_kontr' >
<?php
$result = mysql_query(" SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex FROM sprav_podrazd ")or die(mysql_error());
while($row = mysql_fetch_array($result))
{
        $nom_cex_menu=$row["nom_cex"]; $name_cex_menu=$row["name_cex"];
        if ($nom_cex_menu==$nom_cex_sel)
                {$sel_menu="selected";}
        else         {$sel_menu="";};

         echo "<option $sel_menu value='$nom_cex_menu'>".$nom_cex_menu."&nbsp;&nbsp;".$name_cex_menu."</option>";

}
?>
</td>

<tr>
  <td nowrap>содержание</td>
  <td COLSPAN=3><INPUT MAXLENGTH=80 TYPE=text NAME='sod' VALUE="<?php echo $sod_sel?>" SIZE=70 ></td>
  <td></td>
  <td><INPUT TYPE=HIDDEN NAME='date_real_isp' VALUE='000.00.00'></td>
  <td><INPUT TYPE=HIDDEN NAME='new_date_isp' VALUE='0'></td>
</tr>
<tr>
  <td >корреспондент</td>
  <td><select NAME='korr'>
<?php
$result = mysql_query("SELECT sprav_podrazd.nom_cex, sprav_podrazd.name_cex, sprav_podrazd.fam_cex FROM sprav_podrazd ")
                or die(mysql_error());
while($row = mysql_fetch_array($result))
{$nom_cex_menu=$row["nom_cex"]; $name_cex_menu=$row["name_cex"];$fam_cex_menu=$row["fam_cex"];
if ($nom_cex_menu==$nom_cex_sel)
                {$sel_menu="selected";}
        else         {$sel_menu="";};
  //settype($fam_cex_menu,"string");
  echo "<option $sel_menu value='$fam_cex_menu'>".$fam_cex_menu."</option>";
  //<option <?php echo $sel_menu; ?> value="<?php echo $fam_cex_menu ?>"><?php echo $fam_cex_menu/*."&nbsp&nbsp".$name_cex_menu*/;
}
?>
</td>

  <td><INPUT  TYPE=reset  VALUE="СБРОС"></td>
  <td><INPUT  TYPE=submit NAME=create_product VALUE="ввести"</td>
</tr>
</table>
</form>
</BODY>
</HTML>