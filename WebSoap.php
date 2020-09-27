<?php	

	//require_once './vendor/autoload.php';
	require_once 'config/bootstrap.php';
	require_once __DIR__.'\src\Entities\Cliente.php';	
	require_once __DIR__.'\src\Entities\Billetera.php';	
	require_once __DIR__.'\src\Entities\Compra.php';
	require_once __DIR__.'\src\Entities\TokenPago.php';


	$GLOBALS['entityManager'];	
	function entityManager(){
		global $entityManager;	
		$em = $entityManager;
		return $em;
	}

	##FUNCION CARGAR CONFIGURACIÓN DEL CORREO##
	function connectEmail(){
		$data = file_get_contents("config/email/email.json");		
		$products = json_decode($data, true);
		$con_email = (object) $products["email"];
		return $con_email;
	}

	##REGISTAR CLIENTE
	function registrarCliente($documento, $primerNombre, $primerApellido, $email, $celular){				
		$em = entityManager();
		try{		

			$cliente = new Cliente();
			$cliente->setDocumento($documento);
			$cliente->setPrimerNombre($primerNombre);
			$cliente->setPrimerApellido($primerApellido);
			$cliente->setEmail($email);
			$cliente->setCelular($celular);

			$em->persist($cliente);
			$em->flush($cliente);

			//echo "Se ha realizado el registro con éxito. ".$cliente->getId();
			registrarBilletera($cliente);			
			return "Ok";
		}catch(Exception $e){
			return $e->getMessage();
		}
			
	}
	##REGISTRAR BILLETERA
		function registrarBilletera($data){			
			try{
				$em = entityManager();

				$billetera = new Billetera();
				$billetera->setDocumentoId($data->getDocumento());
				$billetera->setSaldo(0.00);

				$em->persist($billetera);
				$em->flush($billetera);
			}catch(Exception $e){
				return $e->getMessage();
			}
						
		}

	##CONSULTAR CLIENTES
	function consultarCliente($id){		
		$em = entityManager();

		if($id > 0){			
			$cliente = $em->find('Cliente', $id);
				$clienteA['id']     	    = $cliente->getId();
				$clienteA['documento']		= $cliente->getDocumento();
				$clienteA['primerNombre']   = $cliente->getPrimerNombre();
				$clienteA['primerApellido'] = $cliente->getPrimerApellido();
				$clienteA['email']    		= $cliente->getEmail();
				$clienteA['celular']  		= $cliente->getCelular();				
				return $clienteA;			
		}else{
			try{		
				
				$entity = $em->getRepository('Cliente');				
				$clientes = $entity->findAll();
				
				foreach ($clientes as $cliente) {
					$clienteX[] = [
							'id'       		 => $cliente->getId(),
							'documento'		 => $cliente->getDocumento(),
							'primerNombre'   => $cliente->getPrimerNombre(),
							'primerApellido' => $cliente->getPrimerApellido(),
							'email'    		 => $cliente->getEmail(),
							'celular'  		 => $cliente->getCelular()
					];
				}				
				return $clienteX;
			}catch(Exception $e){
				return $e->getMessage();
			}
				
		}

	}

	##RECARGAR BILLETERA
	function recargarBilletera($data){
		$em = entityManager();

		//Buscar el documento según el número de celular
		$cliente = $em->getRepository('Cliente')->findOneBy(array("documento"=>$data[0], "celular"=>$data[1]));		
		//Recargar el saldo
		$billetera = $em->getRepository('Billetera')->findOneBy(array("documentoId"=>$cliente->getDocumento()));
			$saldoAnterior = $billetera->getSaldo();
			$billetera->setSaldo($saldoAnterior + $data[2]);
			$em->persist($billetera);
			$em->flush($billetera);

		$respuesta = [];
			$respuesta['respuesta']			= 'Ok';
			$respuesta['documento']			= $cliente->getDocumento();
			$respuesta['celular']  			= $cliente->getCelular();
			$respuesta['saldoAnterior']    	= $saldoAnterior;
			$respuesta['saldo']    			= $billetera->getSaldo();

		return $respuesta;
		
	}

	##CONSULTAR SALDO
	function consultarSaldo($data){		
		$em = entityManager();

		$entity = $em->getRepository('Billetera');
		$billetera = $entity->findBy(array("documentoId" => $data[0]));	
			$billeteraX = [];
			$billeteraX['documento'] = $billetera[0]->getDocumentoId();		
			$billeteraX['saldo']     = $billetera[0]->getSaldo();	
			$billeteraX['divisa']	 = "Pesos";
		return $billeteraX;		
	}


	##PAGAR
	function pagarProducto($cliente, $monto, $iva){			
		$em = entityManager();
		//Buscar billetera según documento del cliente
		$cliente = $em->getRepository('Cliente')->findOneBy(array("id"=>$cliente));
		$documento = $cliente->getDocumento();
		$billetera = $em->getRepository('Billetera')->findOneBy(array("documentoId"=>$documento));
		//Si tiene saldo
		$total = $monto + ($monto * ($iva/100));
		if($billetera->getSaldo()>($total)){					
			//Generar token						
			$hora = date('H').date('i');
			$token = md5($documento.$monto.$hora);
			$token = substr($token, 0, 6);
			//Generar compra			
			$fecha = new DateTime("now");
			$compra = new Compra();
				$compra->setBilleteraId($billetera->getId());
				$compra->setSubtotal($monto);
				$compra->setIva($monto*($iva/100));
				$compra->setTotal($total);
				$compra->setFecha($fecha);
				$compra->setStatus(false);
			$em->persist($compra);
			$em->flush($compra);
				$compraId = $compra->getId();			
			//Generar token_pagos
			$tokenPago = new TokenPago();
				$tokenPago->setBilleteraId($billetera->getId());
				$tokenPago->setEmail($cliente->getEmail());
				$tokenPago->setToken($token);
				$tokenPago->setCompraId($compraId);			
				//24 horas más
				$fecha = new DateTime('tomorrow');				
				$tokenPago->setExpiracion($fecha);
			$em->persist($tokenPago);
			$em->flush($tokenPago);
			//Enviar a correo el token			
				enviarEmailTokenPago($cliente->getEmail(), $documento, $compra, $tokenPago);
			//Devolver aviso para confirmación			
			$respuesta = [];
				$respuesta['documento'] = $documento;
				$respuesta['email'] = $cliente->getEmail();
			return ($respuesta);
		}else{
			return "No tiene saldo suficiente.";
		}
	}			

			##Funcion para enviar a los email de los clientes el token generado##
			function enviarEmailTokenPago($email, $documento, $compra, $tokenPago){
				$con_email = connectEmail();
				try {		    
				    $transport = (new Swift_SmtpTransport())
				        ->setHost($con_email->host)
				        ->setPort($con_email->port)
				        ->setEncryption($con_email->ency)
				        ->setUsername($con_email->user)
				        ->setPassword($con_email->pass);		        
				    $mailer = new Swift_Mailer($transport);		 		    
				    $message = new Swift_Message();
				    $message->setSubject("Token confirmación pago Epayco.");
				    $message->setFrom([$con_email->user => 'Waller Technology']);
				    $message->addTo($email,'destinatario');
				    $body="<html><body>";
				    	$body.="<h2>Token para confirmación de compra</h2>";		    					    	
				    	$body.="<p>Token: ".$tokenPago->getToken()."</p>";
				    	$body.="<p>Documento: ".$documento."</p>";
				    	$body.="<p>SubTotal: ".$compra->getSubtotal()."</p>";
				    	$body.="<p>Iva: ".$compra->getIva()."</p>";
				    	$body.="<p>Total: ".$compra->getTotal()."</p>";
				    	$body.="</body></html>";
				    //$message->setBody($body);
				    $message->addPart($body, 'text/html');
				    $result = $mailer->send($message);
				    if($result){
				        //echo "Enviado correctamente";
				    }
				} catch (Exception $e) {
				    echo "<pre>";
				        var_dump($e->getMessage());
				    echo "</pre>";
				    return $e->getMessage();
				    die();
				  
				}
			}	
	

	if(!isset($HTTP_RAW_POST_DATA)){
		$HTTP_RAW_POST_DATA = file_get_contents('php://input');
	}
	$server = new soap_server();
		//$server->configureWSDL("Info Blog", "urn:infoBlog");		
		$server->register("registrarCliente");		
		$server->register("registrarBilletera");
		$server->register("consultarCliente");
		$server->register("recargarBilletera");
		$server->register("consultarSaldo");
		$server->register("pagarProducto");
		$server->register("enviarEmailTokenPago");
		
		
		$server->service($HTTP_RAW_POST_DATA);
		exit;

?>