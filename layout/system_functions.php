<?
	function loginScreen($id="ID",$idLabel="",$idSize=30,$pass="SENHA",$passLabel='',$passSize=10,$passEdit=true,$imguser="images/icon_login.jpg",$imgpass="images/icon_password.jpg",$error=false){
	
	echo
	'<div id="borderfield" >';
		if($error){
			$texto = $id ." ou ".$pass." incorretos";
			echo "<div id='mensagemError' width='150px'>$texto</div>";
		}
		echo '<table id="table1" border="0" width="150px">
		<tbody>
			<tr>
				<td rowspan="2" width="11%">
					<p align="left">';
					if($imguser!='')
						echo '<img src="'.$imguser.'" border="0" width="29" height="35">';
					echo
					'</p>
				</td>
				<td width="85%">
					<p align="left">
					<font size="2">'.$id.'</font>
					<br><label><font size=1>'.$idLabel.'</font></label>
					</p>
				</td>
			</tr>
			<tr>
				<td width="85%">
					<p align="left">
						<input name="id"  id="normalEdit" size="15" maxlength="'.$idSize.'" type="text">
					</p>
				</td>
			</tr>
			<tr>
				<td rowspan="2" width="11%">
					<p align="left">';
					if($imguser!='')
						echo '<img src="'.$imgpass.'" border="0" width="29" height="35">';
					echo
					'
					</p>
				</td>
				<td width="85%">
					<p align="left"><font size="2">'.$pass.'</font>
						<br><label><font size=1>'.$passLabel.'</font></label>
					</p>
				</td>
			</tr>
			<tr>
				<td width="85%">
					<p align="left">';
						if($passEdit) $typeEdit="password";
						else $typeEdit="text";
						echo '<input name="password" id="normalEdit" size="15" maxlength="'.$passSize.'" type="'.$typeEdit.'">';
					echo '</p>
				</td>
			</tr>
		</tbody></table>

		<p align="center">
			<input value="ENTRAR" name="B1" id="normalButton" type="submit">
		</p>
	</div>';
	}
	
	function calculaIdade($data_nasc) {
		$data_nasc=explode("/",$data_nasc);
		$data=date("d/m/Y");
		$data=explode("/",$data);
		$anos=$data[2]-$data_nasc[2];
		if ($data_nasc[1] > $data[1]) {
			return $anos-1;
		} 
		if ($data_nasc[1] == $data[1]) {
			if ($data_nasc[0] <= $data[0]) {
				return $anos;
				break;
			} else {
				return $anos-1;
				break;
			}
		} 
		if ($data_nasc[1] < $data[1]) {
			return $anos;
		}
	}
	
	function toMask($campo){
		$numbers='0123456789';
		$cpfinal='';
		for($i=0;$i=strlen($campo);$i++){
			$flag=false;
			for($j=0;$j<strlen($numbers);$j++){
				if($campo[$i]==$numbers[$j]){
					$cpfinal.='0'; $flag=true;
				}
			}
			if(!$flag) $cpfinal.=$campo[$i];
		}
		return $cpfinal;
		
	}


	function valida_tel($tel){
		$ntel='';
		for($i=0;$i<=strlen($tel);$i++){
			if($tel[$i]=='X') $ntel .= 'A';
			else if($tel[$i]=='0'||$tel[$i]=='1'||$tel[$i]=='2'||$tel[$i]=='3'||$tel[$i]=='4'||$tel[$i]=='5'||
				$tel[$i]=='6'||$tel[$i]=='7'||$tel[$i]=='8'||$tel[$i]=='9') $ntel .= 'X';
			else $ntel .=$tel[$i];

		}

		return $ntel=="(XX)XXXXXXXX"||$ntel=="(XX)XXXXXXXXX";
	}

	function isentos_cod($char){
		$c="ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
		$l=2;
		$u = FALSE;
		if (!$u) for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c{$x}, $i++);
		else for ($i = 0, $z = strlen($c)-1, $s = $c{rand(0,$z)}, $i = 1; $i != $l; $x = rand(0,$z), $s .= $c{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s));
		
		$letras=$char.$s;
		
		$nums='';
		for($i=0;$i<5;$i++){
			$n=rand(0,9);
			$nums.=$n;
		}
		
		return $letras.$nums;
	}
	
	function sonumeros($x){
		$num="0123456789";
		for($i=0;$i<strlen($x);$i++){
			$flag=false;
			for($j=0;$j<strlen($num);$j++){
				if($x[$i]==$num[$j]){
					$flag=true;
				}
			}
			if(!$flag){
				return false;
			}
		}
		return true;
	}
	
	
	function get_ip()
	{
		$variables = array('REMOTE_ADDR',
                       'HTTP_X_FORWARDED_FOR',
                       'HTTP_X_FORWARDED',
                       'HTTP_FORWARDED_FOR',
                       'HTTP_FORWARDED',
                       'HTTP_X_COMING_FROM',
                       'HTTP_COMING_FROM',
                       'HTTP_CLIENT_IP');

		$return = 'Unknown';

		foreach ($variables as $variable)
		{
			if (isset($_SERVER[$variable]))
			{
				$return = $_SERVER[$variable];
				break;
			}
		}
    
		return $return;
	}
	
	function dma2amd($dt){
		$data=explode('/',$dt);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
	function amd2dma($dt){
		$data=explode('-',$dt);
		return $data[2].'/'.$data[1].'/'.$data[0];
	}
	
	function amd2dmatime($dt){
		if($dt=='') return '';
		$data=explode('-',$dt);
		
		$time=explode(' ',$data[2]);
		$data[2]=$time[0];
		return $data[2].'/'.$data[1].'/'.$data[0].'('.$time[1].')';
	}
	
	function amdt2dmat($dt){
		$datat=explode(' ',$dt);
		$data=explode('-',$datat[0]);
		$data=$data[2].'/'.$data[1].'/'.$data[0];
		return $data.' '.$datat[1];
	}
	
	function redirect($url){
		echo  "<META HTTP-EQUIV=Refresh CONTENT='1; URL=$url'>";
	}
	
function ValidaData($dat){

	$data = explode("/",$dat); // fatia a string $dat em pedados, usando / como referência
	$d = $data[0];
	$m = $data[1];
	$y = $data[2];
	
	if($dat=='') return false;
	if($d<1||$d>31||$m>12||$m<1||$y<1) return false;

	// verifica se a data é válida!
	// 1 = true (válida)
	// 0 = false (inválida)
	$res1 = checkdate($m,$d,$y);
	
	$ano_banco = $y;
	$mes_banco = $m;
	$dia_banco = $d;

	$difano=date("Y")-$y;
	
	
	if ($res1 == 1 ){
	   return true;
	} else {
	   return false;
	}
}

function validaCep($cep)
{
	$cep = trim($cep);
	$avaliaCep = ereg("^[0-9]{5}[0-9]{3}$", $cep);
	if($avaliaCep != true)
	{
		return false;
	}
	else
	{
		return true;
	}
} 



function removeAcentos($str)
{



	$res='';
	$from = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç';
    $to   = 'AAAAEEIOOOUUCaaaaeeiooouuc';

	
	if(strlen($from)==26){
		return strtr(utf8_decode($input),'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç','AAAAEEIOOOUUCaaaaeeiooouuc');
	}
	

	

	for($i=0;$i<strlen($str)+1;$i++){
		$marca=true;
		if(ord($str[$i])==195){
			$i++;
			$k=0;
			for($j=1;$j<strlen($from);$j=$j+2){
				
				if(ord($str[$i])==ord($from[$j])){ 
					$res.= $to[$k];
					$marca=false;
					break;
				}
				$k++;
			}
			if($marca)$i--;
		}
		
		if($marca) $res.=$str[$i];
	}
	

    return $res;

}

function validaCPF($cpf)
{	// Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
	{
	return false;
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}

function update_sql($tabela,$updates,$where){
	if($where=='') return null;
	$comando = "UPDATE $tabela SET ";
	$campos=array_keys($updates);
	
	for($i=0;$i<count($campos);$i++){
		if($i!=0) $comando.=',';
		$comando.=$campos[$i].'="'.$updates[$campos[$i]].'"'; ;
	}
	
	$campos=array_keys($where);
	
	$comando.=" WHERE ";
	
	for($i=0;$i<count($campos);$i++){
		if($i!=0) $comando.=' AND ';
		$comando.=$campos[$i].'="'.$where[$campos[$i]].'"'; ;
	}
	
	return $comando;
	
	
	
}
function duascasas($valor){
	$valor=explode('.',$valor);
	$casas='.';
	for($i=0;$i<2;$i++){
		if($valor[1][$i]=='') $valor[1][$i]='0';
		$casas.=$valor[1][$i];
	}
	return $valor[0].$casas;
}

?>