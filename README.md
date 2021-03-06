# 📖 Librarify. API REST con SYMFONY 5.3

Simple overview of use/purpose.

## Description

API REST empleando FOS Rest Bundle. Esta API nos permitirá gestionar nuestra biblioteca personal, es decir, nos permitirá:

- Realizar operaciones CRUD sobre nuestros libros.
- Realizar operaciones CRUD sobre los autores.
- Realizar operaciones CRUD sobre las categorías que les asignemos.
- Administracion mediante **Sonata Admin**.
- FormTypes, DTO´s, Uuid
- Get books from external API -> ISBN (https://openlibrary.org/isbn/$isbn)
- JWT Auth (JWT LexikJWTAuthenticationBundle)
  

## Getting Started

1. Clona el repositorio.
2. Ejecuta `cd ~/symfony_api_rest_librarify/api && make run` para levantar los contenedores(nginx + php8.0 + MySQL8)
3. Ejecuta `make composer-install` en la raíz del proyecto.
4. Instala las migraciones de base de datos: `make migrations`.
5. Accede el servidor local de desarrollo para comprobar que funciona correctamente: `http://localhost:250`.
6. Happy codding!


## Authors

Contributors names and contact info

 - Francisco Marcet Prieto  
 [Linkedin](https://www.linkedin.com/in/fcomarcetprieto/)

## Version History

* 0.2
    * Various bug fixes and optimizations
    * See [commit change]() or See [release history]()
* 0.1
    * Initial Release

## License

This project is licensed under the [ GPL-3.0 ] License - see the LICENSE.md file for details
