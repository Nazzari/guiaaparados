<?
/**
 * Classe de controle de dados via URL
 */
class cUrl {

	//variavel privada para guardar a array gerada na url
	private $urlArray;
	
	/**
	 * M�todo construtor para pegar a url e separar em barras para salvar em um array
	 */
	public function __construct() {
		//Pega a url do browser
		$url = $_SERVER['REQUEST_URI'];
		
		//separa a url em barras /
		$separa = explode("/", $url);
		
		//salva a array em uma variavel privada
		$this->urlArray = $separa;
	}
	
	/**
	 * M�todo para retornar o nome da variavel solicitada
	 */
	public function mGetParam($numero) {
		return $this->urlArray[$numero];
	}
	
	/**
	 * M�todo para retornar uma string tratada para mostra na url
	 */
	public function mTrataString($texto) {
	
		$texto = html_entity_decode($texto);
		
		//remove acentos
		$texto = eregi_replace('[a�����]','a',$texto);
		$texto = eregi_replace('[e����]','e',$texto);
		$texto = eregi_replace('[i����]','i',$texto);
		$texto = eregi_replace('[o�����]','o',$texto);
		$texto = eregi_replace('[u����]','u',$texto);
		
		//trata cedilha � e �
		$texto = eregi_replace('[�]','c',$texto);
		$texto = eregi_replace('[�]','n',$texto);
		
		//substitui os espa�os em branco por hifen
		$texto = eregi_replace('( )','-',$texto);
		
		//trata outros caracteres
		$texto = eregi_replace('[^a-z0-9\-]','',$texto);
		
		//trata duplo espa�o de hifen para um hifen apenas
		$texto = eregi_replace('--','-',$texto);
		
		return strtolower($texto);
	}
}
?>