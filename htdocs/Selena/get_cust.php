<link href="selena.css" rel="stylesheet" type="text/css" />
<? require_once("for_form.php"); 
  check_valid_user();
  $conn = db_connect();
  if (!$conn) return 0;
    header("ETag: PUB" . time());
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()-10) . " GMT");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 5) . " GMT");
    header("Pragma: no-cache");
    header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    session_cache_limiter("nocache");

    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
return;

	$id_Podjezd = $_REQUEST ["id_p"];
	$fl = $_REQUEST ["fl"];
	if (isset($_REQUEST ["Bill_Dog"])){ $Bill_Dog = $_REQUEST ["Bill_Dog"];} // else { $Bill_Dog = 1; }
//	if (isset($_REQUEST ["Num_PC"])){ $Num_PC = $_REQUEST ["Num_PC"];} else { $Num_PC = 1; }
//	echo "id_Podjezd=$id_Podjezd, fl=$fl, Num_PC=$Num_PC";
// if (isset($Podjezd)) { 
	$q_customer = "SELECT * FROM v_customer where id_Podjezd=".$id_Podjezd." and Flat =".$fl.
		((isset($_REQUEST ["Bill_Dog"])) ? " and Bill_Dog=".$Bill_Dog : "");//Num_PC =".$Num_PC; 
//if (isset($_REQUEST ["Bill_Dog"])) { echo $_REQUEST ["Bill_Dog"];} else {echo "не задан № договора";}
//echo $q_customer;
	$s_customer =  mysql_query($q_customer) or die(mysql_error());
	$row_customer = mysql_fetch_assoc($s_customer);
	$totalRows_customer = mysql_num_rows($s_customer); 

  	echo '<input name="hid_Rows" type="hidden" value="'.$totalRows_customer.'" />';
	//}

 if (isset($row_customer['Nic'])) {
	$q_login = "SELECT * FROM `logins` where Nic='".$row_customer['Nic']."'"; //v_logins4nics
	$s_login =  mysql_query($q_login) or die(mysql_error());
	$row_login = mysql_fetch_assoc($s_login);
	$totalRows_login = mysql_num_rows($s_login); /*/ $totalRows_login = 0; $row_login['Login']="";	/**/
	if ($totalRows_login > 0) {
		$Logins1 = $row_login['Login'];
		$id_tarif3w1 = $row_login['id_tarif3w'];
		$tarif3w_date1 = $row_login['tarif3w_date'];
	} else { 
		$Logins1 = '';
		$id_tarif3w1 = 0;
		$tarif3w_date1 = '';
	}
		for ($i=1; $i <= $totalRows_login; $i++) {
			$Logins[$i] = $row_login; //['Login']
//print_r $Logins[$i];
echo $Logins[$i]['Login']."</br>";
//			$id_tarif3w[$i] = $row_login['id_tarif3w'];
//			$tarif3w_date[$i] = $row_login['tarif3w_date'];
//			$[$i] = $row_login[''];
			$row_login = mysql_fetch_assoc($s_login);
		}; //		print_r($Logins);
/*	if ($totalRows_login < 2) {echo '<input name="hid_Login" type="hidden" value="'.$row_login['Login'].'" />';
	} else { echo '<select name="hid_Login" class="navText" type="hidden">'; $i=0;
		do {
			$i++;
	  		echo '<option value="'.$i.'">'.$row_login['Login'].'</option>';
		} while ($row_login = mysql_fetch_assoc($s_login));
  		echo '</select>';
	}; */
	} else { echo "не определён Nic"; };
	
  	echo '<input name="hid_Cod_flat" type="hidden" value="'. $row_customer['Cod_flat'].'" />';
  	echo '<input name="hid_Nic" type="hidden" value="'. $row_customer['Nic'].'" />';
  	echo '<input name="hid_Bill_Dog" type="hidden" value="'. $row_customer['Bill_Dog'].'" />';//
	echo '<input name="hid_floor" type="hidden" value="'. $row_customer['floor'].'" />';
  	echo '<input name="hid_Fam" type="hidden" value="'. $row_customer['Fam'].'" />';
  	echo '<input name="hid_Name" type="hidden" value="'. $row_customer['Name'].'" />';
  	echo '<input name="hid_Father" type="hidden" value="'. $row_customer['Father'].'" />';
  	echo '<input name="hid_pasp_Ser" type="hidden" value="'. $row_customer['pasp_Ser'].'" />';
  	echo '<input name="hid_pasp_Num" type="hidden" value="'. $row_customer['pasp_Num'].'" />';
  	echo '<input name="hid_pasp_Date" type="hidden" value="'. $row_customer['pasp_Date'].'" />';
  	echo '<input name="hid_pasp_Uvd" type="hidden" value="'. $row_customer['pasp_Uvd'].'" />';
   	echo '<input name="hid_pasp_Adr" type="hidden" value="'. $row_customer['pasp_Adr'].'" />';
 	echo '<input name="hid_phone_Home" type="hidden" value="'. $row_customer['phone_Home'].'" />';
  	echo '<input name="hid_phone_Cell" type="hidden" value="'. $row_customer['phone_Cell'].'" />';
  	echo '<input name="hid_phone_Work" type="hidden" value="'. $row_customer['phone_Work'].'" />';
  	echo '<input name="hid_Birthday" type="hidden" value="'. $row_customer['Birthday'].'" />';
  	echo '<input name="hid_From_Net" type="hidden" value="'. $row_customer['From_Net'].'" />';
  	echo '<input name="hid_id_tarifab" type="hidden" value="'. $row_customer['id_tarifab'].'" />';
  	echo '<input name="hid_tarifab_date" type="hidden" value="'. $row_customer['tarifab_date'].'" />';
  	echo '<input name="hid_Jur" type="hidden" value="'. $row_customer['Jur'].'" />';
  	echo '<input name="hid_Comment" type="hidden" value="'. $row_customer['Comment'].'" />';
	echo '<input name="hid_Row_Logins" type="hidden" value="'.$totalRows_login.'" />';//
  	echo '<input name="hid_Login" type="hidden" value="'.$Logins[1]['Login'].'" />';
  	echo '<input name="hid_id_tarif3w" type="hidden" value="'. $iLogins[1]['d_tarif3w'].'" />';
  	echo '<input name="hid_tarif3w_date" type="hidden" value="'. $Logins[1]['tarif3w_date'].'" />';
  	echo '<input name="hid_connect" type="hidden" value="'.'2'.'" />';
?>