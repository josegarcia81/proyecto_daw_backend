import json

# Definir las nuevas peticiones para la carpeta Common
common_folder = {
    "name": "Common",
    "item": [
        {
            "name": "Get Provincias - Obtener Provincias",
            "request": {
                "method": "GET",
                "header": [],
                "url": {
                    "raw": "{{base_url}}/getProvincias",
                    "host": ["{{base_url}}"],
                    "path": ["getProvincias"]
                }
            },
            "response": []
        },
        {
            "name": "Get Poblaciones - Obtener Poblaciones",
            "request": {
                "method": "GET",
                "header": [],
                "url": {
                    "raw": "{{base_url}}/getPoblaciones",
                    "host": ["{{base_url}}"],
                    "path": ["getPoblaciones"]
                }
            },
            "response": []
        },
        {
            "name": "Get Poblaciones by Provincia - Filtrar por Provincia",
            "request": {
                "method": "GET",
                "header": [],
                "url": {
                    "raw": "{{base_url}}/getPoblaciones?provincia_id=1",
                    "host": ["{{base_url}}"],
                    "path": ["getPoblaciones"],
                    "query": [
                        {
                            "key": "provincia_id",
                            "value": "1"
                        }
                    ]
                }
            },
            "response": []
        }
    ]
}

# Archivos a actualizar
files = [
    r"c:\xampp\htdocs\proyecto_daw\proyecto_daw_backend\api_rest\storage\api-docs\Proyecto_DAW_API.postman_collection.json",
    r"c:\xampp\htdocs\proyecto_daw\proyecto_daw_backend\api_rest\storage\api-docs\Proyecto_DAW_API_PRODUCCION.postman_collection.json"
]

# Actualizar ambos archivos
for file_path in files:
    try:
        # Leer el archivo JSON
        with open(file_path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        
        # Verificar que no exista ya la carpeta "Common"
        existing_folders = [item.get("name") for item in data.get("item", [])]
        if "Common" not in existing_folders:
            # Añadir la nueva carpeta al final de los items
            data["item"].append(common_folder)
            
            # Guardar el archivo actualizado
            with open(file_path, 'w', encoding='utf-8') as f:
                json.dump(data, f, indent=4, ensure_ascii=False)
            
            print(f"✓ Actualizado: {file_path}")
        else:
            print(f"⊗ Ya existe carpeta Common en: {file_path}")
    except Exception as e:
        print(f"✗ Error en {file_path}: {str(e)}")

print("\n✓ Proceso completado")
