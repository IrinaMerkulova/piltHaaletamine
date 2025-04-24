<?php
require ('conf.php');

global $yhendus;
//delete
if(isSet($_REQUEST["kustuta"])){
    $paring=$yhendus->prepare("Delete from fotokonkurss WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");

}

//update + 1 punkt
if(isSet($_REQUEST["lisa1punkt"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET punktid=punktid+1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]?id=$_REQUEST[lisa1punkt]");

}
// update - lisa kommentaar
if(isSet($_REQUEST["uus_komment"]) && !empty($_REQUEST["komment"])){
    $paring=$yhendus->prepare('UPDATE fotokonkurss SET 
                        kommentaarid=Concat(kommentaarid, ?) WHERE id=?');
    $komment2=$_REQUEST["komment"]."\n";
    $paring->bind_param("si", $komment2, $_REQUEST["uus_komment"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]?id=$_REQUEST[uus_komment]");
}


//lisamine andmetabelisse
if(isSet($_REQUEST["nimetus"]) && !empty($_REQUEST["nimetus"])) {
    $paring = $yhendus->prepare("INSERT INTO fotokonkurss (fotoNimetus, autor, pilt, lisamisAeg, kommentaarid)
VALUES (?, ?, ?, NOW(),'')");
    $paring->bind_param("sss", $_REQUEST["nimetus"], $_REQUEST["autor"], $_REQUEST["pilt"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}


?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Foto konkurss</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Fotokonkurss</h1>
</header>
<nav>
    <ul class="nav-menu">
        <li class="nav-list">
            <a href="fotoKonkurss.php">Adminileht</a>
        </li>
        <li class="nav-list">
            <a href="fotoKonkurss2.php">Kasutaja leht</a>
        </li>
    </ul>
</nav>

<main>
    <?php

        $paring = $yhendus->prepare('SELECT id, fotoNimetus from fotokonkurss WHERE avalik=1');
        $paring->bind_result($id, $fotoNimetus);
        $paring->execute();
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<li><a href='$_SERVER[PHP_SELF]?id=$id'>" . htmlspecialchars($fotoNimetus) . "</a></li>";

    }
    if(isset($_REQUEST["id"])) {
        global $yhendus;
        //ab tabeli kuvamine lehel
        $paring = $yhendus->prepare('SELECT id, fotoNimetus, pilt, autor, punktid, lisamisAeg, kommentaarid from fotokonkurss Where id=?');
        $paring->bind_param("i", $_REQUEST["id"]);
        $paring->bind_result($id, $fotoNimetus, $pilt, $autor, $punktid, $aeg, $kommentaarid);
        $paring->execute();
        if ($paring->fetch()) {

            echo $fotoNimetus;
            echo "<img src='$pilt' alt='fotoPilt'>";
            echo "<br>" . $autor;
            echo "<br>" . $punktid;
            echo "<br>" . $aeg;
            echo "<br>" . nl2br(htmlspecialchars($kommentaarid)) . "
<form action='?' method='POST'>
<input type='hidden' name='uus_komment' value='$id'>
<input type='text' name='komment'>
<input type='submit' value='ok'>
</form></br>";
            echo "<br><a href='?lisa1punkt=$id'>+1 punkt</a>";
            echo "<br><a href='?kustuta=$id'>xxx</a>";

        }
    }

   ?>
</main>
<footer>
    leht tegi õpetaja
</footer>

<?php
$yhendus->close();/*
* Ülesanne.
1. Näita php lehel ainult fotoNimetused
2. Kui klickida fotoNimetusel siis kuvatakse info: pilt, punktid, kommentaarid jne,
punktid ja kommentaarid saab lisada ja kustutada ka.*/
?>

</body>
</html>
