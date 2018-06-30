<? 
echo date("H:m:s"), " ", $s = "? (172.18.0.103) at 00:13:77:5b:ca:62 [ether] on vlan999", "<br>"; //$_REQUEST ["s"]
echo $r = substr($s, strpos($s, "at" )+3, 17);
?>