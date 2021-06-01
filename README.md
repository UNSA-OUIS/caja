# Sistema de Caja

[![SISCAJA](https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png)](https://desarrollo.unsa.edu.pe/siscaja/public/login)

## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y pruebas._

## Pre-requisitos 📋

_Que cosas necesitas para instalar el software y como instalarlas_

-   [NODE.JS](https://nodejs.org/es/) - Gestor npm
-   [COMPOSER](https://getcomposer.org/) - Gestor de paquetes de laravel

## Instalación 🔧

_Una serie de ejemplos paso a paso que te dice lo que debes ejecutar para tener un entorno de desarrollo ejecutandose_

_Dí cómo será ese paso_

```
- Describir proceso de instalacion
```

_Y repite_

```
hasta finalizar
```

_Finaliza con un ejemplo de cómo obtener datos del sistema o como usarlos para una pequeña demo_

## Poblar Base de Datos 🔩

_En laravel se hacen uso de los seeders y factorys para crear datos de prueba para poblar nuestra base de datos y asi poder ejecutar y ver el funcionamiento con cierta cantidad de datos de nuestro sistema_

_Para esto podemos correr todos los seeders y factrys que tengamos con un comando que es el siguiente_

```
php artisan migrate:fresh --seed
```

_Esto ejecutara todos los seeders y los factorys asociados a estos mismos que se encuentran en el archivo DatabaseSeeder.php en la direccion de nuestro proyecto database/seeders_

_Por otro lado podemos ejecutar seeders con sus factorys asociados de manera individual usando los siguientes comandos_

```
php artisan db:seed --class=PermisoSeeder
php artisan db:seed --class=DataTestSeeder
php artisan db:seed --class=ComprobanteSeeder
```
## Construido con 🛠️

_Menciona las herramientas que utilizaste para crear tu proyecto_

-   [Laravel](https://laravel.com/) - El framework web usado para backend
-   [Bootstrap vue](https://bootstrap-vue.org/) - El framework css
-   [Vue.js](https://vuejs.org/) - Framework web usado para frontend

## Autores ✒️

_Menciona a todos aquellos que ayudaron a levantar el proyecto desde sus inicios_

-   **Renzo Siza Tejada** - _Programador_
-   **Jesus Ortiz Chavez** - _Programador_ 
-   **Gary Farid Nuñez** - _Programador_


