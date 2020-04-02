<?php

$PASSWORD = 'SECRET';

if ( $_GET['password'] == $PASSWORD ) {
    copy('folio/albums/' . $_GET['album'] . '/' . $_GET['file'], 'folio/saved/' . $_GET['file']);
    header("Location: folio/saved/{$_GET['file']}");
    exit;
}
header("Location: folio/albums/{$_GET['album']}/{$_GET['file']}");
