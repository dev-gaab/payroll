export function todas(nominas, empresa) {
	let content = [];
	
	nominas.forEach(nomina => {
		let pago_salario = nmbFormat(nomina.montos.pago_salario);
	  	let he_diurnas = nmbFormat(nomina.montos.he_diurnas);
	  	let he_nocturnas = nmbFormat(nomina.montos.he_nocturnas);
	  	let feriados = nmbFormat(nomina.montos.feriados);
	  	let ivss = nmbFormat(nomina.montos.ivss);
	  	let paro_forzoso = nmbFormat(nomina.montos.paro_forzoso);
	  	let faov = nmbFormat(nomina.montos.faov);
	  	let total_asignaciones = nmbFormat(
	    	nomina.montos.total_asignaciones
	  	);
	  	let total_deducciones = nmbFormat(
	    	nomina.montos.total_deducciones
	  	);
	  	let monto_total = nmbFormat(nomina.montos.monto_total);
	  	let otras_asignaciones = nmbFormat(nomina.otras_asignaciones);

		content.push({
                stack: [
                  `${vm.$store.state.empresa.nombre}`,
                  `RIF V-12312413534`
                ],
                style: "header"
              },
              {
                text: "Recibo de pago",
                bold: true,
                alignment: "center",
                fontSize: 16,
                margin: [0, 0, 0, 4]
              },
              {
                text: `Emitido el ${date}`,
                bold: true,
                alignment: "center",
                fontSize: 10,
                margin: [0, 0, 0, 4]
              },
              {
                text: `Nómina del ${nominaDesde} al ${nominaHasta}`,
                bold: true,
                alignment: "center",
                margin: [0, 0, 0, 15]
              },
              {
                text: `Código: ${res.data.trabajador_id}`,
                bold: true,
                margin: [0, 0, 0, 2]
              },
              {
                text: `${res.data.cedula} - ${res.data.nombre1} ${
                  res.data.apellido1
                }`,
                bold: true,
                margin: [0, 0, 0, 2]
              },
              {
                text: `Cargo: ${res.data.cargo}`,
                bold: true,
                margin: [0, 0, 0, 2]
              },
              {
                text: `Fecha de Ingreso: ${fechaIngreso}`,
                bold: true,
                margin: [0, 0, 0, 10]
              },
              {
                layout: "headerLineOnly", // optional
                table: {
                  // headers are automatically repeated if the table spans over multiple pages
                  // you can declare how many rows should be treated as headers
                  headerRows: 1,
                  widths: [200, "*", "*", "*"],

                  body: [
                    [
                      "Descripción",
                      { text: "D/H/Porc", alignment: "right" },
                      { text: "Asignaciones", alignment: "right" },
                      { text: "Deducciones", alignment: "right" }
                    ],
                    [
                      "Salario",
                      {
                        text: `${res.data.dias_trabajados} Días`,
                        alignment: "right"
                      },
                      {
                        text: `${pago_salario}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "Horas Extras Diurnas",
                      {
                        text: `${res.data.he_diurnas} Horas`,
                        alignment: "right"
                      },
                      {
                        text: `${he_diurnas}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "Horas Extras Nocturnas",
                      {
                        text: `${res.data.he_nocturnas} Horas`,
                        alignment: "right"
                      },
                      {
                        text: `${he_nocturnas}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "Domingo / Feriados",
                      { text: `${res.data.feriados} Días`, alignment: "right" },
                      {
                        text: `${feriados}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" }
                    ],
                    [
                      "IVSS",
                      { text: `4.00 %`, alignment: "right" },
                      { text: `0`, alignment: "right" },
                      { text: `${ivss}`, alignment: "right" }
                    ],
                    [
                      "Paro Forzoso",
                      { text: `0.50 %`, alignment: "right" },
                      { text: `0`, alignment: "right" },
                      {
                        text: `${paro_forzoso}`,
                        alignment: "right"
                      }
                    ],
                    [
                      { text: "FAOV" },
                      { text: `1.00 %`, alignment: "right" },
                      { text: `0`, alignment: "right" },
                      { text: `${faov}`, alignment: "right" }
                    ],
                    [
                      "Otras Asignaciones",
                      { text: ``, alignment: "right" },
                      {
                        text: `${otras_asignaciones}`,
                        alignment: "right"
                      },
                      { text: "0", alignment: "right" },
                    ],
                    [
                      { text: "TOTALES", bold: true },
                      { text: ``, alignment: "right" },
                      {
                        text: `${total_asignaciones}`,
                        bold: true,
                        alignment: "right"
                      },
                      {
                        text: `${total_deducciones}`,
                        bold: true,
                        alignment: "right"
                      }
                    ],
                    [
                      { text: "" },
                      { text: ``, alignment: "right" },
                      {
                        text: `Neto a Cobrar Bs.`,
                        bold: true,
                        alignment: "right"
                      },
                      {
                        text: `${monto_total}`,
                        bold: true,
                        alignment: "right"
                      }
                    ]
                  ]
                }
              },
              {
                text: `Observaciones: ${res.data.observaciones}`,
                fontSize: 10,
                bold: true,
                margin: [0, 20, 0, 50]
              },
              {
                text: `He recibido de la Empresa la cantidad especificada en este recibo, que comprende con la totalidad de mi salario al periodo que se indica en el mismo`,
                fontSize: 10,
                bold: true,
                margin: [0, 20, 0, 50]
              },
              {
                text: `Recibo Conforme`,
                bold: true,
                fontSize: 16,
                alignment: "center",
                margin: [0, 0, 0, 50]
              },
              {
                text: `C.I N° ${res.data.cedula}`,
                fontSize: 16,
                alignment: "center",
                bold: true,
                border: [false, true, false, false],
                margin: [2, 0, 0, 10],
                pageBreak: 'after'
              });
	});
}


function nmbFormat(value) {
  value = new Intl.NumberFormat("es-VE", {
    style: "decimal",
    minimumFractionDigits: 2
  }).format(value);
  return value;
}