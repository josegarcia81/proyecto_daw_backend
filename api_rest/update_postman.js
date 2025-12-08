import fs from 'fs';

const commonFolder = {
    name: "Common",
    item: [
        {
            name: "Get Provincias - Obtener Provincias",
            request: {
                method: "GET",
                header: [],
                url: {
                    raw: "{{base_url}}/getProvincias",
                    host: ["{{base_url}}"],
                    path: ["getProvincias"]
                }
            },
            response: []
        },
        {
            name: "Get Poblaciones - Obtener Poblaciones",
            request: {
                method: "GET",
                header: [],
                url: {
                    raw: "{{base_url}}/getPoblaciones",
                    host: ["{{base_url}}"],
                    path: ["getPoblaciones"]
                }
            },
            response: []
        },
        {
            name: "Get Poblaciones by Provincia - Filtrar por Provincia",
            request: {
                method: "GET",
                header: [],
                url: {
                    raw: "{{base_url}}/getPoblaciones?provincia_id=1",
                    host: ["{{base_url}}"],
                    path: ["getPoblaciones"],
                    query: [
                        {
                            key: "provincia_id",
                            value: "1"
                        }
                    ]
                }
            },
            response: []
        }
    ]
};

const files = [
    "storage/api-docs/Proyecto_DAW_API.postman_collection.json",
    "storage/api-docs/Proyecto_DAW_API_PRODUCCION.postman_collection.json"
];

files.forEach(filePath => {
    try {
        const data = JSON.parse(fs.readFileSync(filePath, 'utf8'));

        const exists = data.item.some(item => item.name === "Common");

        if (!exists) {
            data.item.push(commonFolder);
            fs.writeFileSync(filePath, JSON.stringify(data, null, 4));
            console.log(`✓ Actualizado: ${filePath}`);
        } else {
            console.log(`⊗ Ya existe carpeta Common en: ${filePath}`);
        }
    } catch (error) {
        console.log(`✗ Error en ${filePath}: ${error.message}`);
    }
});

console.log("\n✓ Proceso completado");
