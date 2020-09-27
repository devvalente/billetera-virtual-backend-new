<?php

	require_once './vendor/autoload.php';	
	
	//Server Soap	
	function serverSoap(){
		$rutaSoap = "http://localhost/billetera-virtual/backend-new/WebSoap.php";	
		$clienteSoap  = new nusoap_client($rutaSoap);	
		return $clienteSoap;
	}

	##REGISTRAR CLIENTE - BILLETERA
	function registrarCliente($documento, $primerNombre, $primerApellido, $email, $celular){		
		try{
			$clienteSoap = serverSoap();			
			$respuesta = $clienteSoap->call("registrarCliente", array("documento"=>$documento, "primerNombre"=>$primerNombre, "primerApellido"=>$primerApellido, "email"=>$email, "celular"=>$celular));
			return $respuesta;
		}catch(Exception $e){
			return $e->getMessage();
		}
	}

	##CONSULTAR CLIENTE
	function consultarCliente($id){		
		$clienteSoap = serverSoap();
		if($id > 0){
			$cliente = $clienteSoap->call("consultarCliente", array("id"=>$id));
			return $clientes;
		}else{			
			$clientes = $clienteSoap->call("consultarCliente", array("id"=>0));			
			return $clientes;
		}
	}

	##RECARGAR BILLETERA
	function recargarBilletera($data){
		$clienteSoap = serverSoap();
		$respuesta = $clienteSoap->call("recargarBilletera", array("data"=>$data));
		return $respuesta;
	}

	##CONSULTAR SALDO
	function consultarSaldo($data){
		$clienteSoap = serverSoap();
		$respuesta = $clienteSoap->call("consultarSaldo", array("data"=>$data));
		return $respuesta;		
	}

	##PAGAR
	function pagarProducto($cliente, $monto, $iva){
		$clienteSoap = serverSoap();
		$respuesta = $clienteSoap->call("pagarProducto", array("cliente"=>$cliente, "monto"=>$monto, "iva"=>$iva));
		return $respuesta;
	}

	##CONFIRMAR PAGO
	function confirmarPago($clienteId, $token){
		$clienteSoap = serverSoap();
		$respuesta = $clienteSoap->call("confirmarPago", array("clienteId"=>$clienteId, "token"=>$token));
		return $respuesta;
	}


?>