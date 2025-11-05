SELECT
	sol.id,
	sol.estado,
	sh.informacion
FROM
	solicitudes sol INNER JOIN solicitudes_historico sh ON sol.id = sh.fk_solicitudes
WHERE
	sol.fk_tramites = 1
	AND sol.estado = 8
	AND sh.informacion = '{"estado":"7"}'
ORDER BY
	sol.id

UPDATE elementos SET valor_new = valor WHERE id = 9374

/*Calidad de datos*/
SELECT 
	responsable2,
	COUNT(1) AS cantidad 
FROM
	elementos 
WHERE 
	responsable = 1 
	AND responsable2 IS NOT NULL
GROUP BY
	responsable2
ORDER BY
	cantidad DESC

SELECT
	*
FROM
	elementos ele INNER JOIN pivote piv ON ele.responsable2 = piv.id
WHERE
	ele.responsable = 1

SELECT
	piv.id,
	usu.nombre
FROM
	pivote piv LEFT JOIN usuarios usu ON piv.id = usu.registro
WHERE
	usu.nombre IS NULL

UPDATE elementos SET responsable2 = 1 WHERE responsable2 IS NULL


/*Elementos en proceso*/
SELECT
	sub.nombre AS subclase,
	dep.gerencia,
	COUNT(1)
FROM
	(elementos ele INNER JOIN subclases sub ON ele.fk_subclases = sub.id) INNER JOIN dependencias dep ON ele.fk_dependencias = dep.id
WHERE
	ele.fk_contratos = 2
GROUP BY
	subclase,
	gerencia

SELECT
	ele.id,
	ele.fk_tipos,
	ele.fk_clases,
	ele.fk_subclases,
	ele.codigo,
	ele.descripcion AS elemento,
	ele.inventario,
	ele.serie,
	ele.valor,
	ele.responsable,
	ele.responsable2,
	ele.en_tramite,
	ele.estado,
	IFNULL(ele.uso, '') AS uso,
	dep.id AS idDep,
	dep.gerencia,
	dep.dependencia,
	dep.unidad,
	usu.nombre AS trabajador,
	usu.registro,
	usu.login
FROM
	(elementos ele 
		INNER JOIN dependencias dep ON ele.fk_dependencias = dep.id)
		INNER JOIN usuarios usu ON ele.responsable = usu.id
WHERE
	ele.fk_contratos = 2

SELECT
	*
FROM
	(SELECT
		ele.id AS idElemento,
		ele.fk_subclases,
		ele.descripcion AS elemento,
		ele.serie,
		dep.gerencia,
		dep.dependencia,
		dep.unidad,
		usu.nombre AS receptorAsignado,
		usu.registro AS registroAsignado,
		usu.login AS loginAsignado
	FROM
		(elementos ele INNER JOIN dependencias dep ON ele.fk_dependencias = dep.id) INNER JOIN usuarios usu ON ele.responsable = usu.id
	WHERE
		ele.fk_contratos = 2) AS elementos
	LEFT JOIN
	(SELECT
		sol.id AS idSolicitud,
		sol.fk_elementos,
		usu.nombre AS receptorPendiente,
		usu.registro AS registroPendiente,
		usu.login AS loginPendiente
	FROM
		solicitudes sol INNER JOIN usuarios usu ON sol.receptor = usu.id
	WHERE
		sol.fk_tramites = 1
		AND sol.estado IN (1,2)) AS solicitudes
ON
		elementos.idElemento = solicitudes.fk_elementos


SELECT
	sol.*
FROM
	solicitudes sol INNER JOIN elementos ele ON sol.fk_elementos = ele.id
WHERE
	ele.fk_tipos = 1
	AND ele.fk_clases = 3
	AND sol.fk_tramites = 3
	AND sol.estado IN (4,5)

SELECT * FROM elementos WHERE descripcion LIKE 'silla%'
UPDATE elementos SET fk_clases = 8 WHERE descripcion LIKE 'silla%'

SELECT
	sol.id,
	ele.descripcion,
	sol.estado
FROM
	elementos ele INNER JOIN solicitudes sol ON ele.id = sol.fk_elementos
WHERE
	ele.fk_clases = 3
	AND sol.estado = 4

SELECT
	sol.id AS solicitud,
	ele.fk_tipos,
	ele.codigo,
	ele.descripcion,
	ele.serie,
	usu.nombre,
	usu.registro,
	sol.estado
FROM
	elementos ele INNER JOIN (solicitudes sol INNER JOIN usuarios usu ON sol.solicitante = usu.id) ON ele.id = sol.fk_elementos
WHERE
	ele.fk_clases = 4
	AND sol.fk_tramites = 4

SELECT
	sol.id AS solicitud,
	ele.fk_tipos,
	ele.codigo,
	ele.descripcion,
	ele.serie,
	usu.nombre,
	usu.registro,
	sol.estado,
	sh.creado_por,
	sh.fecha_creacion
FROM
	elementos ele INNER JOIN ((solicitudes sol INNER JOIN solicitudes_historico sh ON sol.id = sh.fk_solicitudes) INNER JOIN usuarios usu ON sol.solicitante = usu.id) ON ele.id = sol.fk_elementos
WHERE
	ele.fk_clases = 4
	AND sol.fk_tramites = 4
	AND sh.informacion->>'$.estado' = '5'

/**Gestión de bajas*/
/*IRMA*/
SELECT
	*
FROM
	(SELECT
		sol.id AS solicitud,
		sol.fecha_creacion,
		CASE
			WHEN ele.fk_tipos = 1 THEN 'Activo'
			WHEN ele.fk_tipos = 2 THEN 'Controlado'
			WHEN ele.fk_tipos = 3 THEN 'AO'
		END AS tipo,
		ele.codigo,
		ele.descripcion,
		ele.serie,
		usu.nombre,
		usu.registro,
		sol.estado,
		CASE
			WHEN sol.estado = 4 THEN 'Realizar inspección'
			WHEN sol.estado = 5 THEN 'Aprobar inspección'
			WHEN sol.estado = 6 THEN 'Llevar a almacen'
			WHEN sol.estado = 7 THEN 'Actualizar SAP'
			WHEN sol.estado = 8 THEN 'Actualizar carpeta'
			WHEN sol.estado = 9 THEN 'Reposición'
			WHEN sol.estado = 10 THEN 'Ejecutada'
		END AS estadoTexto
	FROM
		elementos ele INNER JOIN (solicitudes sol INNER JOIN usuarios usu ON sol.solicitante = usu.id) ON ele.id = sol.fk_elementos
	WHERE
		ele.fk_clases = 4
		AND sol.fk_tramites = 4
		AND sol.estado IN (4,5,6,7,8,9,10)) AS base
	LEFT JOIN
	(SELECT
		sh.fk_solicitudes,
		GROUP_CONCAT(CONCAT(usu.nombre,';',sh.fecha_creacion) SEPARATOR ';') AS fechas
	FROM
		solicitudes_historico sh INNER JOIN usuarios usu ON sh.creado_por = usu.id
	WHERE
		JSON_UNQUOTE(sh.informacion->'$.estado') IN ('4','5')
	GROUP BY
		sh.fk_solicitudes
	ORDER BY
		fk_solicitudes) AS historico
ON
	base.solicitud = historico.fk_solicitudes

/*Ruben calvo*/
SELECT
	sol.id AS solicitud,
	ele.id AS codigoGCP,
	ele.descripcion,
	ele.serie,
	usu.nombre,
	usu.registro,
	sol.estado
FROM
	elementos ele INNER JOIN (solicitudes sol INNER JOIN usuarios usu ON sol.receptor = usu.id) ON ele.id = sol.fk_elementos
WHERE
	ele.fk_tipos = 3
	AND sol.fk_tramites = 1
	AND sol.estado IN (8,10)

/*Reporte equipo de computo pendientes de entregar a almacen*/
SELECT
	dep.gerencia,
	dep.dependencia,
	dep.unidad,
	usu.nombre,
	usu.registro,
	usu.correo,
	ele.descripcion,
	ele.serie,
	pla.nombre AS planta,
	sol.id
FROM
	elementos ele INNER JOIN (solicitudes sol INNER JOIN ((usuarios usu INNER JOIN plantas pla ON usu.fk_plantas = pla.id) INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) ON sol.solicitante = usu.id) ON ele.id = sol.fk_elementos
WHERE
	ele.fk_clases = 4
	AND sol.estado = 6