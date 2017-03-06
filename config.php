<?php

$conn = mysqli_connect('localhost', 'root', '', 'apiexercise');
$imagepath = 'upload';

$db = new PDO('mysql:host=localhost;dbname=apiexercise;charset=utf8mb4', 'root', '');