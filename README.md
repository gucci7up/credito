# Sistema de Inventario y Ventas (Perfumes) - PHP/MySQL (MVC)

Aplicación web sencilla y profesional para **gestión de clientes, ventas (diarias y a crédito), dashboard y facturación térmica 58mm**.

## Requisitos
- Docker + Docker Compose

## Inicio rápido (Docker)
1. Copia el archivo de entorno:
   - `cp .env.example .env`
2. Levanta el proyecto:
   - `docker compose up -d --build`
3. Abre:
   - http://localhost:8080

> La base de datos se inicializa automáticamente con el esquema y valores de configuración por defecto.

## Estructura (MVC)
- `/controllers`
- `/models`
- `/views`
- `/config`
- `/public`

## Notas
- **No hay productos predefinidos**: todos los ítems se ingresan manualmente por factura.
- ITBIS se controla **globalmente** en Configuración (ON/OFF + %).
- Factura térmica 58mm: usa CSS específico con `@media print`.

