<?php
require ('conf.php');

global $yhendus;
//kuvamine
if(isSet($_REQUEST["kuva_id"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET avalik=1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kuva_id"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}

//peitmine
if(isSet($_REQUEST["peida_id"])){
    $paring=$yhendus->prepare("UPDATE fotokonkurss SET avalik=0 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["peida_id"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}


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
    $paring=$yhendus->prepare('SELECT id, fotoNimetus, pilt, autor, punktid, lisamisAeg, kommentaarid, avalik from fotokonkurss');
    $paring->bind_result($id, $fotoNimetus, $pilt, $autor, $punktid, $aeg, $kommentaarid, $avalik);
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
        $tekst="Näita";
        $avaparametr="kuva_id";
        $seis="Peidetud";
        if($avalik==1){
            $tekst="Peida";
            $avaparametr="peida_id";
            $seis="Kuvatud";
        }
        echo "<td><a href='?$avaparametr=$id'>$tekst</a></td>";
        echo "<td>$seis</td>";
        echo "</tr>";
    }
   ?>
</table>
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
</main>
<footer>
    leht tegi õpetaja
</footer>
<?php
$yhendus->close();/*
* Ülesanne.
1. Näita php lehel ainult fotoNimetused
2. Kui klickida fotoNimetusel siis kuvatakse info: pilt, punktid, kommentaarid jne,
punktid ja kommentaarid saab lisada ja kustutada ka.
------------------
3. Admin leht võimaldab - Näita/Peida, rida Kustutamine, Kommentaari kustutamine, Punktid Nulliks
4. Kasutaja leht - näitab fotoNimetused ühe kaupa ja kui klickida fotoNimetusel,
siis saab lisada +1p, -1p, kommentaarid
5. foto Lisamine konkurssi - tee eraldi leht



*/
?>
</body>
</html>
