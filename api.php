<?php

	require_once './vendor/autoload.php';	
	
	//Server Soap	
	function serverSoap(){
		$rutaSoap = "http://localhost/billetera-virtual/backend-new/WebSoap.php";	
		$cliente  = new nusoap_client($rutaSoap);	
		return $cliente;
	}

	function registrarCliente($documento, $primerNombre, $primerApellido, $email, $celular){		
		try{
			$client = serverSoap();			
			$resp = $client->call("registrarCliente", array("documento"=>$documento, "primerNombre"=>$primerNombre, "primerApellido"=>$primerApellido, "email"=>$email, "celular"=>$celular));
			return $resp;
		}catch(Exception $e){
			return $e->getMessage();
		}
	}

	function consultarCliente($id){		
		$cliente = serverSoap();
		if($id > 0){
			$clientes = $cliente->call("consultarCliente", array("id"=>$id));
			return $clientes;
		}else{			
			$clientes = $cliente->call("consultarCliente", array("id"=>0));			
			return $clientes;
		}
	}

?>