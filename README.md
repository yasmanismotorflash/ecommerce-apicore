# Proeyecto Ecommerce API CORE #

## API central de importación, procesamiento y exportacion de datos entre los   Ecommers y Motorflash ## 


# Como desplegar: 

### 1- Descargar el proyecto desde git [[https://github.com/yasmanismotorflash/api-ecommerce-ve]]

### 2-Entrar a la carpeta del proyecto
ejemplo
 cd Motorflash/Proyectos/apicore

### 3-Desplegar con docker-compose ejecutando el comando
    
    docker-compose up --build 

    la primera vez puede demorar porque se descargaran las imagenes y se cotuiran otras
ya después se puede ejecutar pasándole el parámetro -d para que se quede corriendo en segundo plano.


### 4- Entrar al contenedos de php ejecutando el comando

docker exec -it server-php bash

### 5- Instalar dependencias ejecutando dentro del contenedor de PHp

composer install

### 6-Crear Base de datos (si ya existe omitir este paso)

php bin/console doctrine:database:create

### 7-Crear esquema de tablas dentro de la base de datos

    php bin/console doctrine:schema:create

### 8-Cargar datos iniciales

php bin/console doctrine:fixtures:load --append


### 9-Acceder vía un navegador a localhost:8080

http://localhost:8080/api









