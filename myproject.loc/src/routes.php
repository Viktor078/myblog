<?php

return [
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'delete'],
    '~^users/register$~' => [\MyProject\Controllers\UsersController::class, 'signUp'],
    '~^users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],
    '~^users/login$~' => [\MyProject\Controllers\UsersController::class, 'login'],
    '~^users/logOut$~' => [\MyProject\Controllers\UsersController::class, 'logOut'],
    '~^articles/(\d+)/comments$~' => [\MyProject\Controllers\CommentsController::class, 'add'],
    '~^comments/(\d+)/edit$~'=> [\MyProject\Controllers\CommentsController::class, 'edit'],
    '~comments/(\d+)/delete$~'=>[\MyProject\Controllers\CommentsController::class,'delete'],
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main']
];