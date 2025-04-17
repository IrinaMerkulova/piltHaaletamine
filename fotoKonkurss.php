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
    header("Location:$_SERVER[PHP_SELF]");

}
// update - lisa kommentaar
if(isSet($_REQUEST["uus_komment"]) && !empty($_REQUEST["komment"])){
    $paring=$yhendus->prepare('UPDATE fotokonkurss SET 
                        kommentaarid=Concat(kommentaarid, ?) WHERE id=?');
    $komment2=$_REQUEST["komment"]."\n";
    $paring->bind_param("si", $komment2, $_REQUEST["uus_komment"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}


//lisamine andmetabelisse
if(isSet($_REQUEST["nimetus"]) && !empty($_REQUEST["nimetus"])) {
    $paring = $yhendus->prepare("INSERT INTO fotokonkurss (fotoNimetus, autor, pilt, lisamisAeg)
VALUES (?, ?, ?, NOW())");
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
<h1>Fotokonkurss</h1>
<h2>Foto lisamine hääletamisele</h2>
<form action="?" method="post">
    <label for="nimetus">FotoNimetus</label>
    <input type="text" id="nimetus" name="nimetus" placeholder="Kirjuta ilus fotonimetus">
    <br>
    <label for="autor">Autor</label>
    <input type="text" id="autor" name="autor" placeholder="Autori nimi">
    <br>
    <label for="pilt">Pildifoto</label>
    <textarea name="pilt" id="pilt" cols="30" rows="10">
        Kopeeri kujutise aadress
    </textarea>
    <br>
    <input type="submit" value="Lisa">

</form>


<table>
    <tr>
        <th>Foto nimetus</th>
        <th>Pilt</th>
        <th>Autor</th>
        <th>Punktid</th>
        <th>Lisamisaeg</th>
        <th>+1 punkt</th>
    </tr>
    <?php
    global $yhendus;
    //ab tabeli kuvamine lehel
    $paring=$yhendus->prepare('SELECT id, fotoNimetus, pilt, autor, punktid, lisamisAeg, kommentaarid from fotokonkurss');
    $paring->bind_result($id, $fotoNimetus, $pilt, $autor, $punktid, $aeg, $kommentaarid);
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        echo "<td>".htmlspecialchars($fotoNimetus)."</td>";
        echo "<td><img src='$pilt' alt='fotoPilt'></td>";
        echo "<td>".$autor."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$aeg."</td>";
        echo "<td>".nl2br(htmlspecialchars($kommentaarid))."
<form action='?' method='POST'>
<input type='hidden' name='uus_komment' value='$id'>
<input type='text' name='komment'>
<input type='submit' value='ok'>
</form></td>";
        echo "<td><a href='?lisa1punkt=$id'>+1 punkt</a></td>";
        echo "<td><a href='?kustuta=$id'>xxx</a></td>";
        echo "</tr>";
    }
   ?>
</table>
<?php
$yhendus->close();/*
* Ülesanne.
1. Näita php lehel ainult fotoNimetused
2. Kui klickida fotoNimetusel siis kuvatakse info: pilt, punktid, kommentaarid jne,
punktid ja kommentaarid saab lisada ja kustutada ka.*/
?>

</body>
</html>
