<?php
ini_set ( 'display_errors', 'On');


$config = parse_ini_file('../conf/config.ini');

$sgldep = $argv[1];  // a sigla do departamento eh um parametro de linha de comando do script
print_r($config);


// busca os docentes no fobos webservice


// Busca os docentes de um dado departamento no fobos webservice
function busca_docentes ($sgldep) {

	$ws_endpoint = $config->ws_endpoint . $sgldep; // aqui vai a URL do webservice
	
	// Inicia conexão
	$curl = curl_init ( $config->ws_endpoint );
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 );
	curl_setopt ( $curl, CURLOPT_USERAGENT, $url );
	$headers = array (
		'Content-Type:application/json',
		'Authorization: Basic ' . base64_encode ( "$config->ws_user:$config->ws_pass" )
	);

	curl_setopt ( $curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$resp = utf8_encode(curl_exec( $curl ));
	// Exceção caso não haja resposta
	if ( $resp == null ) {
		throw new Exception ( '<br></br>Serviço indisponível no momento' );
	}
	// Fecha conexão
	curl_close ( $curl );
	$docentes = json_decode( preg_replace('/[\x00-\x1F\xFF]/', '', $resp), true );
	return $docentes;

}


// para cada docente, cria ou atualiza um post no wordpress
function update_post ($conteudo, $postID){
	
}



function utf8json($inArray) {
	static $depth = 0;
	/* our return object */
	$newArray = array();
	/* safety recursion limit */
	$depth ++;
	if($depth >= '30') {
		return false;
    }
    
	/* step through inArray */
	foreach($inArray as $key=>$val) {
	if(is_array($val)) {
			/* recurse on array elements */
			$newArray[$key] = utf8json($val);
		} else {
			/* encode string values */
			$newArray[$key] = utf8_encode($val);
		}
	}
	/* return utf8 encoded array */
	return $newArray;
}





?>
