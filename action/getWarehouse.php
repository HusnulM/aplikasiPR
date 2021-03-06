<?php
	session_start();
	
	if (!isset($_SESSION['sbeusernamex']) && !isset($_SESSION['sbelevelx'])){
		echo json_encode('You are not Authentication');
	}else{
		/* Set Connection Credentials */
		include "../config/conn.php";
		/* Connect using SQL Server Authentication. */
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
		 
		if( $conn === false ) {
		     echo "Unable to connect.</br>";
		     die( print_r( sqlsrv_errors(), true));
		}
		
		$uid = $_POST['user_id']; 
		/* TSQL Query */
		$tsql = "select a.name, b.dept_name, b.whs_code, c.WhsName from TblUser as a inner join tblDept as b 
					on a.dept_id = b.dept_id inner join OWHS as c on b.whs_code = c.WhsCode where a.id = '$uid'";
		$stmt = sqlsrv_query( $conn, $tsql);

		 
		if( $stmt === false ) {
		     echo "Error in executing query.</br>";
		     die( print_r( sqlsrv_errors(), true));
		}
		 
		$json = array();
		 
		do {
		     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$json[] = $row;
		     }
		} while ( sqlsrv_next_result($stmt) );

		echo json_encode($json);
		/* Free statement and connection resources. */
		sqlsrv_free_stmt( $stmt);
		sqlsrv_close( $conn);
	}

 
?>