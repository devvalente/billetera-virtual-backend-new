<?php

	
	require_once 'api.php';

	header('Access-Control-Allow-Origin: *');
	

	if($_SERVER['REQUEST_METHOD']=='POST'){	
		$accion 		= $_POST['accion'];			
		
		//REGISTRAR CLIENTE - REGISTRAR BILLETERA
		if($accion == 'registrar_cliente'){	
			$documento 		= $_POST['documento'];			
			$primerNombre 	= $_POST['primerNombre'];
			$primerApellido = $_POST['primerApellido'];
			$email 			= $_POST['email'];
			$celular 		= $_POST['celular'];			
			$respuesta = registrarCliente($documento, $primerNombre, $primerApellido, $email, $celular);	
			echo json_encode($respuesta);
		}	

		//RECARGAR BILLETERA
		if($accion == 'recargar_billetera'){
			$data = [];
				$data[0]= $_POST['documento'];				
				$data[1]= $_POST['celular'];
				$data[2]= $_POST['valor'];
			$respuesta = recargarBilletera($data);
			echo json_encode($respuesta, JSON_NUMERIC_CHECK);
		}

		//CONSULTAR SALDO
		if($accion == 'consultar_saldo'){			
			$data = [];
				$data[0] = $_POST['documento'];			
				$data[1] = $_POST['celular'];			
			$saldo 		 = consultarSaldo($data);
			echo json_encode($saldo);
		}

	}
	
	if($_SERVER['REQUEST_METHOD']=='GET'){
		
		//CONSULTAR CLIENTE		
			if(isset($_GET['id'])){		
				$id = $_GET['id'];						
				$respuesta = consultarCliente($id);
				echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);			
			}else{
				$respuesta = consultarCliente(0);
				echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
			}		
	}


	

?>