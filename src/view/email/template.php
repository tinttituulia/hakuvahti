<!DOCTYPE html>
<html lang="fi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hakuvahti</title>
</head>

<body style="font-family: 'Open Sans', sans-serif; max-width: 600px; margin: 0 auto; height: auto; padding: 15px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin: 10px;">
  <header>
    <h1 style="font-size: 32px; text-align: center;">Hakuvahti ilmoittaa</h1>
  </header>
  <main>
    <?=$this->section('content')?>
  </main>
  <footer>
    <?php if (!empty($muokkauslinkki)): ?>
      <hr style="margin-top: 25px; margin-bottom: 20px; border: 0; border-top: 1px solid #e0e0e0;">
      <p style="text-align: center; font-size: 14px; color: #555;">
        Haluatko muuttaa hakusanojasi tai hallinnoida vahtiasi?<br>
        <a href="<?=$this->e($muokkauslinkki)?>" style="color: #0066cc; text-decoration: underline; font-weight: bold;">
          Siirry muokkaussivulle tästä
        </a>
      </p>
    <?php endif; ?>

    <p style="text-align: center; margin-top: 25px;">Terveisin:</p>
    <p style="text-align: center; font-weight: bold;">Hakuvahtipalvelu</p>
  </footer>
</body>

</html>