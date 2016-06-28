SELECT 
	clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, clientes.direccion, clientes.colonia, clientes.telefono, clientes.celular
	, pagos.id as idp, pagos.cuenta, pagos.fecha, pagos.pago, pagos.estado, pagos.reportado 
	FROM clientes, pagos 
	WHERE clientes.id = pagos.cliente 
	AND pagos.fecha < "2015-08-26"
	AND pagos.estado = 1
        AND pagos.reportado = 0
        AND clientes.c_cobrador = "COB1"