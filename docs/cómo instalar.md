# Cómo instalar?

## Índice:

1. [Subiendo los archivos al servidor]()
2. [Accediendo al asistente de instalación]()
3. [Etapa de comprobación del sistema]()
  i. [¿Qué hacer si la primera comprobación, la versión de Php falla?]()
  ii. [¿Qué hacer si la comprobación de la función `mail` falla?]()
  iii. [¿Qué hacer si la comprobación de la extensión mysqli falla?]()
  iv. [La comprobación de *instalación en directorio raíz* falla]()
4. [Reuniendo los datos del servidor]()
  i. [Base de datos]()
  ii. [Datos de la página]()
5. [Datos de la cuenta para el Administrador]()
6. [Habilitando reCAPTCHA]()
7. [Personalización de módulos]()
8. [Instalación]()
  i. [Posibles errores en la pantalla de instalación]()

## Subiendo los archivos al servidor

Descargar el zip proveniente de este repositorio (`Clone or Download` > `Download Zip`). Descomprimir y luego subir todos los archivos provenientes de la carpeta `public_html` en el directorio raíz del servidor. Se púede utilizar [Fillezilla Client](https://www.google.com/search?q=fillezilla+client) para realizar la operación.

Es escencial subir todos los archivos y carpetas al directorio raíz del servidor (normalmente `/public_html`, a veces también identificado como `/htdocs`) para que pueda funcionar correctamente. Si se desea instalar en un sub-directorio dentro del directorio raíz (ej: `/raiz/subcarpeta`), entonces es necesario [asignar un subdominio](https://www.google.com/search?q=cpanel+crear+subdminio) que convierta ese subdirectorio en un directorio raíz, para luego acceder a la página desde este subdominio.

## Accediendo al asistente de instalación

Una vez hayan sido subidos todos los archivos al servidor, se podrá acceder al asistente de instalación
desde la url que haya sido asignada para el proyecto `http(s)://mi-dominio-o-subdominio`, y ésta
redirigirá al asistente de instalación (alternativamente se puede acceder directamente a éste desde
`http(s)://mi-dominio-o-subdominio/instalador`).

![Asistente de Instalación](https://i.imgur.com/oFb8Y4u.png)

## Etapa de comprobación del sistema

Luego de hacer click en `Continuar`, lo primero que se verá es la pantalla donde se realizará la comprobación de los requerimientos del sistema.

Si su server cumple todos los requerimientos, verá una pantalla como la mostrada a continuación:

![Comprobación del sistema](https://i.imgur.com/wbTCEAA.png)

### ¿Qué hacer si la primera comprobación, la versión de Php falla?

Este sistema requiere como mínimo de Php versión 5 para poder funcionar. Cambie [la versión de Php](https://www.google.com/search?q=cpanel+cambiar+la+versi%C3%B3n+de+php) desde el panel de administración de su servidor.

### ¿Qué hacer si la comprobación de la función `mail` falla?

La comprobación de la funcionalidad mail es lo que más a menudo puede dar problemas, mostrándonos durante esta etapa una pantalla como la que sigue:

![Error en comprobación de función mail](https://i.imgur.com/ZT60YmV.png)

Para poder realizar una mejor comprobación de la función `mail`, es necesario ingresar un correo electrónico que haya sido creado y habilitado desde el servidor donde se esté realizando la instalación (en opción `Emails`/`Administración de Correos electrónicos`). Este correo será el `Correo de Remitente` que usted utilizará en el sistema, y es el único correo que podrá utilizar como tal. No puede utilizar direcciones de correo @gmail @hotmail o cualquier otro de los servicios convencionales. Puede habilitar tantos correos que desee desde su servidor para poderlos utilizar como `Correo de Remitente`.

Si aún así, no es posible poder realizar la comprobación de la función `mail`, es posible que haya algo en el servidor que no esté adecuadamente configurado, o que la función `mail` haya sido permanentemente deshabilitada por el servidor, como medida de seguridad. Póngase en contacto con los administradores de su servidor para más ayuda.

### ¿Qué hacer si la comprobación de la extensión mysqli falla?

Probablemente está usted usando un servidor que no tiene incorporado mysqli. Póngase en contacto con los administradores de su servidor para más ayuda.

### La comprobación de *instalación en directorio raíz* falla

Dirigirse al punto [Subiendo los archivos al servidor](#Subiendo-los-archivos-al-servidor)

**Importante:** Es necesario aclarar que esta etapa se encuentra en desarrollo y necesita ser probada en diversos sistemas para ir expandiendo todos parámetros que se necesitan comprobar para el correcto funcionamiento del sistema. Es posible que tu instalación apruebe todos los pasos realizados durante la comprobación y aún así, el server donde se instale arroje nuevos problemas.

## Reuniendo los datos del servidor


![Datos de Servidor](https://i.imgur.com/6s2VpfY.png)

Aqui los datos se dividen en 2 categorías: base de datos y página.

### Base de datos:

Para recaudar los datos que se necesita para la base de datos, si no se tienen a mano es necesario consultarlos desde el
panel de control del servidor (sección `base de datos`).

* **Host** (servidor): normalmente es `localhost`. No se recomienda modificar ese dato a menos que se sepa lo que se hace.
* **Base de datos**: el nombre de la base de datos que será usada por el sistema que vamos a instalar. No es necesario
tenerla creada, en cuyo caso, en este campo se puede ingresar el nombre que se desee.
* **Usuario**: el nombre del usuario para hacer login en la base de datos.
* **Contraseña**: la contraseña para hacer login en la base de datos.

Los datos de usuario y contraseña para la base de datos serán validados en esta etapa. Si son ingresados datos erróneos,
serán notificados antes de avanzar a la siguiente etapa.

### Datos de la página:

* **Nombre de la página**: El nombre que tendrá la página. Aparecerá en los títulos en todas las páginas (pestaña del
navegador), y en todas las áreas de la página donde corresponda.

## Datos de la cuenta para el Administrador

Luego de haber reunido los datos del servidor y haber hecho click en `Siguiente`, nos encontraremos con la pantalla de
los datos para la cuenta de administrador. Aquí crearemos el usuario administrador del sistema, junto con sus datos
de login:

![Datos de cuenta de admin](https://i.imgur.com/vgZb6kJ.png)

* **Nombre**: El nombre del usuario administrador.
* **Apellido**: El apellido del usuario administrador.
* **Correo**: El correo del usuario administrador.
* **Usuario**: El dato `usuario` para hacer login.
* **Contraseña**: El dato `contraseña` para hacer login. Hay un campo adicional *Confirmar Contraseña* para poder validarla.

## Habilitando reCAPTCHA

El reCAPTCHA es un tipo especial de captcha que aparecerá en la página de login al sistema. Pero es necesario realizar un
registro de nuestro dominio para que se nos permita habilitar el reCAPTCHA en nuestra página. En esta etapa validaremos
una cuenta reCAPTCHA para poder utilizarla en nuestro sistema:

![Habilitando reCAPTCHA](https://i.imgur.com/oKQSyEC.png)

En esta pantalla se nos suministra el enlace https://www.google.com/recaptcha/admin/create para que desde allí podamos
registrar una cuenta de reCAPTCHA, desde la cual se nos proveerá de 2 claves para habilitar el reCAPTCHA. **Nota**: es
necesario escoger la opción `reCAPTCHA v2 > Casilla No soy un robot`.

Si ya realizamos este paso para instalar nuestro sistema en un dominio, y queremos hacerlo en otro dominio, no es necesario
registrar otra cuenta de reCAPTCHA, todo lo podríamos hacer con una sola cuenta. Simplemente ingresar a
https://www.google.com/recaptcha/admin, escoger la cuenta, y agregar tantos dominios como se desee, tal y como se puede
apreciar en las imágenes:

![Añadiendo Múltiples Dominios 1](https://i.imgur.com/NpAkZ08.jpg)

![Añadiendo Múltiples Dominios 2](https://i.imgur.com/Y8Qrm7U.png)

Una vez se tenga acceso a las 2 claves, se ingresan en la pantalla de habilitar reCAPTCHA, para poder realizar la
validación. Si ésta se ha hecho bien, se nos permitirá avanzar a la siguiente etapa.

## Personalización de módulos

![Personalizad módulos](https://i.imgur.com/gahMxjL.png)

Se eligen los módulos que se desee instalar.

## Instalación

Al llegar a este punto solo es cuestión de esperar a que finalice la instalación.

![Realizando Instalación](https://i.imgur.com/uxHAdwn.png)

### Posibles errores en la pantalla de instalación

- Errores relacionados con problemas de conexión (con la base de datos).

Estos errores pueden estar relacionados a su conexión a internet. Revísela y reintente la instalación.

- Falta el archivo `/instalador/config.json`.

Reinicie la instalación.

- Datos requeridos para la instalación faltantes.

Reinicie la instalación.

- Base de datos no ha podido ser creada automáticamente por falta de permisos.

Debe crear la base de datos manualmente desde `phpMyAdmin`. Asegúrese que el nombre coincida con el nombre previamente ingresado en la etapa de los [datos del servidor](#Reuniendo-los-datos-del-servidor). Si eso no es posible, reinicie la instalación e ingrese correctamente el nombre de la base de datos ya creada.

- La instalación no avanza, pero ningún mensaje de error es mostrado.

Se trata de algún error inesperado. Desde su navegador, presione las teclas `CTRL`+`SHIFT`+`I`, se abrirá el inspeccionador de elementos del navedor. Diríjase a la pestaña `Cónsola`, tome una captura de pantalla (Tecla `ImprPant` o `PrintScr`), y abra un nuevo `Issue` en este repositorio con su captura:

![Crear un Issue](https://i.imgur.com/ZBnZRd4.png)

Una vez haya concluida la instalación, aparecerá la pantalla donde se notifica que la instalación ha concluido, y se suministra el enlace donde se puede hacer el primer login al sistema.

![Instalación Finalizada](https://i.imgur.com/8c1w8oa.png)
