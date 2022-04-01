<?
/**
 * Classe Template é uma classe que separa o código php do Html
 * 
 * <i>Criada em: 05/08/2006
 * Última Alteração: 05/10/2006</i>
 * 
 * @author Zaib Tecnologia
 * @version 1.0
 * @copyright Copyright © 2006, Zaib Tecnologia
 * @package Class
 */
class cTemplate{
   
   /**
    * Seta na Variável o valor
    */
   private $var;
   
	/**
	 * Construtor que verifica se o arquivo (parametro)
	 * foi especificado
	 */
    public function __construct($file=""){
		if ($file != "") {
			if (file_exists("templates/".$file)) $this->file = "templates/".$file;
			else $this->file = $file;
		} else {
			print "Especifique o arquivo";
		}	
    }

	/**
	 * Gera as variáveis da Página
	 * @param $var string » Nome da Variável template
	 * @param $value string » Valor da variável template
	 * 
	 * Ecemplo de Uso: 
	 * Php » $tpl->Set("variavel", "olá mundo")
	 * Html » {%variavel%}
	 */
    function mSet($var, $value){
    	$this->$var = $value;
    }
	
	/**
	 * Mostra a página, pode conter ou não incíos
	 * @param string $indent » Identificação (<!-- %inicio% -->)
	 * @param string $tipo » se == 1 ele retorna o template senão imprimi na tela
	 * 
	 * Modo de Usar:
	 * $tpl->Show();
	 * $tpl->Show("marcação");
	 */
    function mShow ($ident="", $tipo=""){
		
		$arr = file($this->file);
		if ($ident == ""){
			$c = 0;
			$len = count ($arr);
			while($c < $len) {
				$temp = str_replace ("{%","$"."this->", $arr[$c]);
				$temp = str_replace ("%}", "", $temp);
				$temp = addslashes($temp);
				eval("\$x=\"$temp\";");
				if ($tipo) $acum .= $x;
				else print str_replace("\'", "'", $x);
				
				$c++;
			}
		} else {
			$c = 0;
			$len = count ($arr);
			$tag = "<!-- %".$ident."% -->";
			while($c < $len){
				if (trim($arr[$c]) == $tag){
					$c++;
					while((substr( $arr[$c], 0 , 6) != "<!-- %") && ($c < $len)){
						$temp = str_replace ("{%", "$"."this->", $arr[$c]);
						$temp = str_replace ("%}", "", $temp);
						$temp = addslashes($temp);
						eval("\$x = \"$temp\";");
						if ($tipo) $acum .= $x;
						else print str_replace("\'", "'", $x);
						$c++;
					}
					$c = $len;
				}
				$c++;
			}
		}
		if ($tipo == "1") return $acum;
    }
}
?>