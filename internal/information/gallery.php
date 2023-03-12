<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bildergalerie</title>
    <meta name="description" content="Bildersammulg des MEG-Chats. Schüler können hier Ihre Bilder veröffentlichen und mit anderen teilen. Den Spam gibts jetzt nicht mehr nur auf den Schul-Ipads sondern auch hier!">
    <meta name="keywords" lang="de" content="meg, max, ernst, gymnasium, max ernst gymnasium, brühl, chat, bilder, galerie, schüler, veröffentlichungen, porträs, schnappschüsse, fotos, peinliche, eindrücke">
    <?php require('../middleware/head.php'); ?>
  </head>
  <body>
    <header>
	  <div>
      <h1>MEG - Bildergalerie</h1>
      <h4>Wie auf den IPads nur in Besser!</h4>
      </div>
      <button id="add-image-btn">Neues Bild hinzufügen</button>
    </header>
    <main>
      <div class="image-gallery">
        <img src="bild1.jpg" alt="Bild 1">
        <img src="bild2.jpg" alt="Bild 2">
        <img src="bild3.jpg" alt="Bild 3">
        <img src="bild4.jpg" alt="Bild 4">
      </div>
    </main>
  </body>
  <style>
      body {
  background-color: #292929;
  color: #ffffff;
  font-family: Arial, sans-serif;
  font-size: 16px;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #1f1f1f;
}

h1 {
  margin: 0;
  font-size: 32px;
}

button {
  padding: 10px 20px;
  background-color: #eeb111;
  border: none;
  border-radius: 5px;
  color: #ffffff;
  font-size: 16px;
  cursor: pointer;
}

button:hover {
  background-color: #d7a009;
}

main {
  padding: 20px;
}

.image-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.image-gallery img {
  width: 100%;
  height: auto;
  border-radius: 5px;
}

.image-gallery img:hover {
  transform: scale(1.05);
}

@media screen and (max-width: 600px) {
  .image-gallery {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }
}
  </style>
</html>
