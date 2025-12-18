import json
import os

# Definiciones de los nuevos módulos
modules = [
    {
        "name": "Transacciones",
        "item": [
            {
                "name": "Get All Transacciones - Obtener Todas",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/transacciones",
                        "host": ["{{base_url}}"],
                        "path": ["transacciones"]
                    }
                },
                "response": []
            },
            {
                "name": "Get Transacciones by User - Por Usuario",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/transacciones/1",
                        "host": ["{{base_url}}"],
                        "path": ["transacciones", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Create Transaccion - Crear",
                "request": {
                    "method": "POST",
                    "header": [{"key": "Content-Type", "value": "application/json"}],
                    "body": {
                        "mode": "raw",
                        "raw": "{\n    \"servicio_id\": 1,\n    \"usuario_solicitante_id\": 1,\n    \"usuario_ofertante_id\": 2,\n    \"horas\": 2,\n    \"estado\": \"pendiente\"\n}"
                    },
                    "url": {
                        "raw": "{{base_url}}/transaccion",
                        "host": ["{{base_url}}"],
                        "path": ["transaccion"]
                    }
                },
                "response": []
            },
            {
                "name": "Update Transaccion - Actualizar",
                "request": {
                    "method": "PUT",
                    "header": [{"key": "Content-Type", "value": "application/json"}],
                    "body": {
                        "mode": "raw",
                        "raw": "{\n    \"estado\": \"confirmado\"\n}"
                    },
                    "url": {
                        "raw": "{{base_url}}/transaccion/1",
                        "host": ["{{base_url}}"],
                        "path": ["transaccion", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Delete Transaccion - Eliminar",
                "request": {
                    "method": "DELETE",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/transaccion/1",
                        "host": ["{{base_url}}"],
                        "path": ["transaccion", "1"]
                    }
                },
                "response": []
            }
        ]
    },
    {
        "name": "Valoraciones",
        "item": [
            {
                "name": "Get All Valoraciones - Obtener Todas",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/valoraciones",
                        "host": ["{{base_url}}"],
                        "path": ["valoraciones"]
                    }
                },
                "response": []
            },
            {
                "name": "Get Valoraciones by User - Por Usuario",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/valoraciones/1",
                        "host": ["{{base_url}}"],
                        "path": ["valoraciones", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Create Valoracion - Crear",
                "request": {
                    "method": "POST",
                    "header": [{"key": "Content-Type", "value": "application/json"}],
                    "body": {
                        "mode": "raw",
                        "raw": "{\n    \"transaccion_id\": 1,\n    \"valorador_id\": 1,\n    \"valorado_id\": 2,\n    \"puntuacion\": 5,\n    \"comentario\": \"Excelente servicio\"\n}"
                    },
                    "url": {
                        "raw": "{{base_url}}/valoracion",
                        "host": ["{{base_url}}"],
                        "path": ["valoracion"]
                    }
                },
                "response": []
            },
            {
                "name": "Update Valoracion - Actualizar",
                "request": {
                    "method": "PUT",
                    "header": [{"key": "Content-Type", "value": "application/json"}],
                    "body": {
                        "mode": "raw",
                        "raw": "{\n    \"puntuacion\": 4,\n    \"comentario\": \"Muy bueno\"\n}"
                    },
                    "url": {
                        "raw": "{{base_url}}/valoracion/1",
                        "host": ["{{base_url}}"],
                        "path": ["valoracion", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Delete Valoracion - Eliminar",
                "request": {
                    "method": "DELETE",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/valoracion/1",
                        "host": ["{{base_url}}"],
                        "path": ["valoracion", "1"]
                    }
                },
                "response": []
            }
        ]
    },
    {
        "name": "Mensajes",
        "item": [
            {
                "name": "Get All Mensajes - Obtener Todos",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/mensajes",
                        "host": ["{{base_url}}"],
                        "path": ["mensajes"]
                    }
                },
                "response": []
            },
            {
                "name": "Get Mensajes by User - Por Usuario",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/mensajes/1",
                        "host": ["{{base_url}}"],
                        "path": ["mensajes", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Get Mensaje by ID - Obtener por ID",
                "request": {
                    "method": "GET",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/mensaje/1",
                        "host": ["{{base_url}}"],
                        "path": ["mensaje", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Create Mensaje - Crear",
                "request": {
                    "method": "POST",
                    "header": [{"key": "Content-Type", "value": "application/json"}],
                    "body": {
                        "mode": "raw",
                        "raw": "{\n    \"emisor_id\": 1,\n    \"receptor_id\": 2,\n    \"servicio_id\": 1,\n    \"mensaje\": \"Hola, me interesa tu servicio\"\n}"
                    },
                    "url": {
                        "raw": "{{base_url}}/mensaje",
                        "host": ["{{base_url}}"],
                        "path": ["mensaje"]
                    }
                },
                "response": []
            },
            {
                "name": "Update Mensaje - Actualizar",
                "request": {
                    "method": "PUT",
                    "header": [{"key": "Content-Type", "value": "application/json"}],
                    "body": {
                        "mode": "raw",
                        "raw": "{\n    \"mensaje\": \"Mensaje editado\",\n    \"leido\": true\n}"
                    },
                    "url": {
                        "raw": "{{base_url}}/mensaje/1",
                        "host": ["{{base_url}}"],
                        "path": ["mensaje", "1"]
                    }
                },
                "response": []
            },
            {
                "name": "Delete Mensaje - Eliminar",
                "request": {
                    "method": "DELETE",
                    "header": [],
                    "url": {
                        "raw": "{{base_url}}/mensaje/1",
                        "host": ["{{base_url}}"],
                        "path": ["mensaje", "1"]
                    }
                },
                "response": []
            }
        ]
    }
]

# Archivos a actualizar
files = [
    r"c:\xampp\htdocs\proyecto_daw\proyecto_daw_backend\api_rest\storage\api-docs\Proyecto_DAW_API.postman_collection.json",
    r"c:\xampp\htdocs\proyecto_daw\proyecto_daw_backend\api_rest\storage\api-docs\Proyecto_DAW_API_PRODUCCION.postman_collection.json"
]

# Actualizar ambos archivos
for file_path in files:
    try:
        if not os.path.exists(file_path):
            print(f"⚠ Archivo no encontrado: {file_path}")
            continue

        # Leer el archivo JSON
        with open(file_path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        
        updated = False
        existing_folder_names = [item.get("name") for item in data.get("item", [])]

        for module in modules:
            if module["name"] not in existing_folder_names:
                # Añadir la nueva carpeta al final de los items
                data["item"].append(module)
                updated = True
                print(f"✓ Añadido módulo {module['name']} a {os.path.basename(file_path)}")
            else:
                print(f"⊗ Ya existe módulo {module['name']} en {os.path.basename(file_path)}")

        if updated:
            # Guardar el archivo actualizado
            with open(file_path, 'w', encoding='utf-8') as f:
                json.dump(data, f, indent=4, ensure_ascii=False)
            print(f"✓ Archivo guardado: {file_path}")
        else:
            print(f"⚠ No hubo cambios en: {file_path}")

    except Exception as e:
        print(f"✗ Error en {file_path}: {str(e)}")

print("\n✓ Proceso completado")
