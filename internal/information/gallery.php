<?php
require_once("../logic/db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}
?>
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
      <?php
      if(isset($_SESSION['pupil'])){?>
          <button id="add-image-btn" onclick="upload();">Neues Bild hinzufügen</button>
          <?php
      } else {
		  ?>
		  <button id="add-image-btn" onclick="page_navigate('/account/login');" style="width: 100%; height: 25px; ">Anmelden</button>
		  <?php  
	  }
      ?>
    </header>
    <main>
      <div class="image-gallery" id="pictures">
		<?php
		$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".pictures ORDER BY id DESC;");
		$stmtData->execute();
		while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
			<img loading="lazy" src="<?php echo htmlspecialchars($row['path']); ?>" alt="Bild aus der MEG Chat Gallerie">
	    <?php } ?>
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
		  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
		  gap: 20px;
		}
		
		.image-gallery img {
		  width: 100%;
		  height: auto;
		  border-radius: 5px;
		  transition: all 0.2s;
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
  <script>
      window.upload = function(){
		  var e = document.createElement("input");
		  e.type = "file";
		  e.accept = "image/*";
		  e.onchange = async function(){
			var file = e.files[0];
			if(!file) return;
		    var dataUrl = await new Promise(resolve => {
		      let reader = new FileReader();
		      reader.onload = () => resolve(reader.result);
		      reader.readAsDataURL(file);
		    });
		    document.getElementById("add-image-btn").innerText = "Hochladen...";
		    post_request("/ajax/picture_upload.php", {data: dataUrl}, function(data){
				document.getElementById("add-image-btn").innerText = "Neues Bild hinzufügen";
		        if(data.length > 2){
			        popup("Fehler!", data);
			    }
			    page_navigate(window.location.href, "#pictures");
		    });
		  };
		  e.click();
	  };
  </script>
</html>
