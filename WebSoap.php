<?php	

	//require_once './vendor/autoload.php';
	require_once 'config/bootstrap.php';
	require_once __DIR__.'\src\Entities\Cliente.php';	
	require_once __DIR__.'\src\Entities\Billetera.php';	


	$GLOBALS['entityManager'];	
	function entityManager(){
		global $entityManager;	
		$em = $entityManager;
		return $em;
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
		
		
		$server->service($HTTP_RAW_POST_DATA);
		exit;

?>