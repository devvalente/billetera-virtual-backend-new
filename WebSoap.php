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

	function probar(){
		return "Probando";
	}

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

	

	if(!isset($HTTP_RAW_POST_DATA)){
		$HTTP_RAW_POST_DATA = file_get_contents('php://input');
	}
	$server = new soap_server();
		//$server->configureWSDL("Info Blog", "urn:infoBlog");
		$server->register("consultarCliente");
		$server->register("registrarCliente");		
		$server->register("registrarBilletera");

		$server->register("probar");
		
		$server->service($HTTP_RAW_POST_DATA);
		exit;

?>