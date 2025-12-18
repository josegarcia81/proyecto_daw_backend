import fs from 'fs';
import path from 'path';

const modules = [
    {
        name: "Transacciones",
        item: [
            {
                name: "Get All Transacciones - Obtener Todas",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/transacciones",
                        host: ["{{base_url}}"],
                        path: ["transacciones"]
                    }
                },
                response: []
            },
            {
                name: "Get Transacciones by User - Por Usuario",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/transacciones/1",
                        host: ["{{base_url}}"],
                        path: ["transacciones", "1"]
                    }
                },
                response: []
            },
            {
                name: "Create Transaccion - Crear",
                request: {
                    method: "POST",
                    header: [{ "key": "Content-Type", "value": "application/json" }],
                    body: {
                        mode: "raw",
                        raw: JSON.stringify({
                            servicio_id: 1,
                            usuario_solicitante_id: 1,
                            usuario_ofertante_id: 2,
                            horas: 2,
                            estado: "pendiente"
                        }, null, 4)
                    },
                    url: {
                        raw: "{{base_url}}/transaccion",
                        host: ["{{base_url}}"],
                        path: ["transaccion"]
                    }
                },
                response: []
            },
            {
                name: "Update Transaccion - Actualizar",
                request: {
                    method: "PUT",
                    header: [{ "key": "Content-Type", "value": "application/json" }],
                    body: {
                        mode: "raw",
                        raw: JSON.stringify({
                            estado: "confirmado"
                        }, null, 4)
                    },
                    url: {
                        raw: "{{base_url}}/transaccion/1",
                        host: ["{{base_url}}"],
                        path: ["transaccion", "1"]
                    }
                },
                response: []
            },
            {
                name: "Delete Transaccion - Eliminar",
                request: {
                    method: "DELETE",
                    header: [],
                    url: {
                        raw: "{{base_url}}/transaccion/1",
                        host: ["{{base_url}}"],
                        path: ["transaccion", "1"]
                    }
                },
                response: []
            }
        ]
    },
    {
        name: "Valoraciones",
        item: [
            {
                name: "Get All Valoraciones - Obtener Todas",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/valoraciones",
                        host: ["{{base_url}}"],
                        path: ["valoraciones"]
                    }
                },
                response: []
            },
            {
                name: "Get Valoraciones by User - Por Usuario",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/valoraciones/1",
                        host: ["{{base_url}}"],
                        path: ["valoraciones", "1"]
                    }
                },
                response: []
            },
            {
                name: "Create Valoracion - Crear",
                request: {
                    method: "POST",
                    header: [{ "key": "Content-Type", "value": "application/json" }],
                    body: {
                        mode: "raw",
                        raw: JSON.stringify({
                            transaccion_id: 1,
                            valorador_id: 1,
                            valorado_id: 2,
                            puntuacion: 5,
                            comentario: "Excelente servicio"
                        }, null, 4)
                    },
                    url: {
                        raw: "{{base_url}}/valoracion",
                        host: ["{{base_url}}"],
                        path: ["valoracion"]
                    }
                },
                response: []
            },
            {
                name: "Update Valoracion - Actualizar",
                request: {
                    method: "PUT",
                    header: [{ "key": "Content-Type", "value": "application/json" }],
                    body: {
                        mode: "raw",
                        raw: JSON.stringify({
                            puntuacion: 4,
                            comentario: "Muy bueno"
                        }, null, 4)
                    },
                    url: {
                        raw: "{{base_url}}/valoracion/1",
                        host: ["{{base_url}}"],
                        path: ["valoracion", "1"]
                    }
                },
                response: []
            },
            {
                name: "Delete Valoracion - Eliminar",
                request: {
                    method: "DELETE",
                    header: [],
                    url: {
                        raw: "{{base_url}}/valoracion/1",
                        host: ["{{base_url}}"],
                        path: ["valoracion", "1"]
                    }
                },
                response: []
            }
        ]
    },
    {
        name: "Mensajes",
        item: [
            {
                name: "Get All Mensajes - Obtener Todos",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/mensajes",
                        host: ["{{base_url}}"],
                        path: ["mensajes"]
                    }
                },
                response: []
            },
            {
                name: "Get Mensajes by User - Por Usuario",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/mensajes/1",
                        host: ["{{base_url}}"],
                        path: ["mensajes", "1"]
                    }
                },
                response: []
            },
            {
                name: "Get Mensaje by ID - Obtener por ID",
                request: {
                    method: "GET",
                    header: [],
                    url: {
                        raw: "{{base_url}}/mensaje/1",
                        host: ["{{base_url}}"],
                        path: ["mensaje", "1"]
                    }
                },
                response: []
            },
            {
                name: "Create Mensaje - Crear",
                request: {
                    method: "POST",
                    header: [{ "key": "Content-Type", "value": "application/json" }],
                    body: {
                        mode: "raw",
                        raw: JSON.stringify({
                            emisor_id: 1,
                            receptor_id: 2,
                            servicio_id: 1,
                            mensaje: "Hola, me interesa tu servicio"
                        }, null, 4)
                    },
                    url: {
                        raw: "{{base_url}}/mensaje",
                        host: ["{{base_url}}"],
                        path: ["mensaje"]
                    }
                },
                response: []
            },
            {
                name: "Update Mensaje - Actualizar",
                request: {
                    method: "PUT",
                    header: [{ "key": "Content-Type", "value": "application/json" }],
                    body: {
                        mode: "raw",
                        raw: JSON.stringify({
                            mensaje: "Mensaje editado",
                            leido: true
                        }, null, 4)
                    },
                    url: {
                        raw: "{{base_url}}/mensaje/1",
                        host: ["{{base_url}}"],
                        path: ["mensaje", "1"]
                    }
                },
                response: []
            },
            {
                name: "Delete Mensaje - Eliminar",
                request: {
                    method: "DELETE",
                    header: [],
                    url: {
                        raw: "{{base_url}}/mensaje/1",
                        host: ["{{base_url}}"],
                        path: ["mensaje", "1"]
                    }
                },
                response: []
            }
        ]
    }
];

const files = [
    "storage/api-docs/Proyecto_DAW_API.postman_collection.json",
    "storage/api-docs/Proyecto_DAW_API_PRODUCCION.postman_collection.json"
];

files.forEach(filePath => {
    const fullPath = path.resolve(filePath);
    try {
        if (!fs.existsSync(fullPath)) {
            console.log(`⚠ Archivo no encontrado: ${fullPath}`);
            return;
        }

        const data = JSON.parse(fs.readFileSync(fullPath, 'utf8'));

        let updated = false;
        const existingFolderNames = data.item.map(item => item.name);

        modules.forEach(module => {
            if (!existingFolderNames.includes(module.name)) {
                data.item.push(module);
                updated = true;
                console.log(`✓ Añadido módulo ${module.name} a ${path.basename(fullPath)}`);
            } else {
                console.log(`⊗ Ya existe módulo ${module.name} en ${path.basename(fullPath)}`);
            }
        });

        if (updated) {
            fs.writeFileSync(fullPath, JSON.stringify(data, null, 4));
            console.log(`✓ Archivo guardado: ${fullPath}`);
        } else {
            console.log(`⚠ No hubo cambios en: ${fullPath}`);
        }

    } catch (error) {
        console.log(`✗ Error en ${fullPath}: ${error.message}`);
    }
});

console.log("\n✓ Proceso completado");
