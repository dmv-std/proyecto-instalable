# Proyecto Sistema Instalable

## Cómo instalar?

### Subiendo los archivos al servidor

Es escencial subir todos los archivos y carpetas que forman parte de este proyecto
al directorio raíz de un servidor (normalmente `/public_html`) para que pueda funcionar correctamente.
Si se desea instalar en un sub-directorio dentro del directorio raíz (ej: `/raiz/subcarpeta`), entonces
es necesario asignar un subdominio que convierta ese subdirectorio en un directorio raíz,
para luego acceder a la página desde este subdominio.

### Accediendo al asistente de instalación

Una vez hayan sido subidos todos los archivos al servidor, se podrá acceder al asistente de instalación
desde la url que haya sido asignada para el proyecto `http(s)://mi-dominio-o-subdominio`, y ésta
redirigirá al asistente de instalación (alternativamente se puede acceder directamente a éste desde
`http(s)://mi-dominio-o-subdominio/instalador`).

![Asistente de Instalación](https://i.imgur.com/oFb8Y4u.png)

### Confeccionar los datos del servidor

Luego de hacer click en `Continuar`, lo primero que se verá es la pantalla donde se piden los datos de servidor:

![Datos de Servidor](https://i.imgur.com/6s2VpfY.png)

Aqui los datos se dividen en 2 categorías: base de datos y página.

#### Base de datos:

Para recaudar los datos que se necesita para la base de datos, si no se tienen a mano es necesario consultarlos desde el
panel de control del servidor (sección `base de datos`).

* **Host** (servidor): normalmente es `localhost`. No se recomienda modificar ese dato a menos que se sepa lo que se hace.
* **Base de datos**: el nombre de la base de datos que será usada por el sistema que vamos a instalar. No es necesario
tenerla creada, en cuyo caso, en este campo se puede ingresar el nombre que se desee.
* **Usuario**: el nombre del usuario para hacer login en la base de datos.
* **Contraseña**: la contraseña para hacer login en la base de datos.

Los datos de usuario y contraseña para la base de datos serán validados en esta etapa. Si son ingresados datos erróneos,
serán notificados antes de avanzar a la siguiente etapa.

#### Datos de la página:

* **Nombre de la página**: El nombre que tendrá la página. Aparecerá en los títulos en todas las páginas (pestaña del
navegador), y en todas las áreas de la página donde corresponda.

### Datos de la cuenta para el Administrador

Luego de haber reunido los datos del servidor y haber hecho click en `Siguiente`, nos encontraremos con la pantalla de
los datos para la cuenta de administrador. Aquí crearemos el usuario administrador del sistema, junto con sus datos
de login:

![Datos de cuenta de admin](https://i.imgur.com/vgZb6kJ.png)

* **Nombre**: El nombre del usuario administrador.
* **Apellido**: El apellido del usuario administrador.
* **Correo**: El correo del usuario administrador.
* **Usuario**: El dato `usuario` para hacer login.
* **Contraseña**: El dato `contraseña` para hacer login. Hay un campo adicional *Confirmar Contraseña* para poder validarla.

### Habilitando reCAPTCHA

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

### Personalización de módulos

![Personalizad módulos](https://i.imgur.com/gahMxjL.png)

Se eligen los módulos que se desee instalar.

### Instalación

Al llegar a este punto solo es cuestión de esperar a que finalice la instalación.

![Realizando Instalación](https://i.imgur.com/uxHAdwn.png)

Una vez haya concluido, aparecerá la pantalla donde se notifica que la instalación ha concluido, y se suministra el enlace
donde se puede hacer el primer login al sistema.

![Instalación Finalizada](https://i.imgur.com/8c1w8oa.png)