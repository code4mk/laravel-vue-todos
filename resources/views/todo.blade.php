<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Todos</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="https://laravel-vue-todos.herokuapp.com/css/app.css">
    </head>
    <body>
        <div class="" id="app">
            <todo-component></todo-component>
        </div>
        <script src="https://laravel-vue-todos.herokuapp.com/js/app.js"></script>
    </body>
</html>
