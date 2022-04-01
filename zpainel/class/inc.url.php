<?
/**
 * Classe de controle de dados via URL
 */
class cUrl {

	//variavel privada para guardar a array gerada na url
	private $urlArray;
	
	/**
	 * Mйtodo construtor para pegar a url e separar em barras para salvar em um array
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
	 * Mйtodo para retornar o nome da variavel solicitada
	 */
	public function mGetParam($numero) {
		return $this->urlArray[$numero];
	}
	
	/**
	 * Mйtodo para retornar uma string tratada para mostra na url
	 */
	public function mTrataString($texto) {
	
		$texto = html_entity_decode($texto);
		
		//remove acentos
		$texto = eregi_replace('[aбагвд]','a',$texto);
		$texto = eregi_replace('[eйикл]','e',$texto);
		$texto = eregi_replace('[iнмоп]','i',$texto);
		$texto = eregi_replace('[oутхфц]','o',$texto);
		$texto = eregi_replace('[uъщыь]','u',$texto);
		
		//trata cedilha З e С
		$texto = eregi_replace('[з]','c',$texto);
		$texto = eregi_replace('[с]','n',$texto);
		
		//substitui os espaзos em branco por hifen
		$texto = eregi_replace('( )','-',$texto);
		
		//trata outros caracteres
		$texto = eregi_replace('[^a-z0-9\-]','',$texto);
		
		//trata duplo espaзo de hifen para um hifen apenas
		$texto = eregi_replace('--','-',$texto);
		
		return strtolower($texto);
	}
}
?>