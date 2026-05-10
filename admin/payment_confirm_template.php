<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:30px;background:#f4f8ff;font-family:Arial,sans-serif;color:#0f172a;">

<div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.10);">

  <!-- HEADER -->
  <div style="background:linear-gradient(135deg,<?= $status==='paid' ? '#16a34a,#15803d' : '#d97706,#b45309' ?>);padding:36px 40px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td valign="top">
          <img src="https://pintsite.si/Logopravitext.png" height="42" style="display:block;filter:brightness(0) invert(1);">
          <div style="margin-top:12px;font-size:12px;line-height:1.9;color:rgba(255,255,255,.85);">
            <strong style="font-size:14px;color:#ffffff;">PintSite</strong><br>
            Jure Pintar<br>
            info@pintsite.si<br>
            +386 XX XXX XXX
          </div>
        </td>
        <td valign="top" align="right">
          <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:rgba(255,255,255,.75);margin-bottom:6px;">
            <?= $status==='paid' ? 'POTRDITEV PLAČILA' : 'OBVESTILO O DELNEM PLAČILU' ?>
          </div>
          <div style="font-size:26px;font-weight:800;color:#ffffff;margin-bottom:10px;">
            <?= $status==='paid' ? '✓ PLAČANO' : '◑ DELNO PLAČANO' ?>
          </div>
          <div style="font-size:12px;color:rgba(255,255,255,.9);line-height:1.9;">
            <strong>Račun: <?= htmlspecialchars($invoice) ?></strong><br>
            Datum potrditve: <?= $date ?>
          </div>
        </td>
      </tr>
    </table>
  </div>

  <!-- VSEBINA -->
  <div style="padding:36px 40px;">

    <!-- Nagovor -->
    <p style="font-size:16px;line-height:1.7;margin-bottom:28px;">
      Spoštovani <?= htmlspecialchars($company) ?>,<br><br>
      <?php if($status==='paid'): ?>
      z veseljem vam potrjujemo, da smo prejeli <strong>celotno plačilo</strong> za račun <strong><?= htmlspecialchars($invoice) ?></strong>. Vaš račun je v celoti poravnan.
      <?php else: ?>
      obveščamo vas, da smo prejeli <strong>delno plačilo</strong> za račun <strong><?= htmlspecialchars($invoice) ?></strong>. Za dokončno poravnavo računa je treba poravnati še preostali znesek.
      <?php endif; ?>
    </p>

    <!-- Povzetek plačila -->
    <div style="background:linear-gradient(135deg,<?= $status==='paid' ? '#f0fdf4,#dcfce7' : '#fffbeb,#fef3c7' ?>);border-radius:20px;padding:26px;margin-bottom:24px;">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:<?= $status==='paid' ? '#16a34a' : '#d97706' ?>;font-weight:700;margin-bottom:16px;">
        Povzetek plačila
      </div>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="font-size:14px;color:#0f172a;padding-bottom:10px;">Skupna vrednost računa</td>
          <td align="right" style="font-size:14px;font-weight:700;color:#0f172a;padding-bottom:10px;"><?= number_format($price, 2, ',', '.') ?> €</td>
        </tr>
        <tr>
          <td style="font-size:14px;color:#0f172a;padding-bottom:10px;">Plačano skupaj</td>
          <td align="right" style="font-size:14px;font-weight:700;color:<?= $status==='paid' ? '#16a34a' : '#d97706' ?>;padding-bottom:10px;"><?= number_format($pricePaid, 2, ',', '.') ?> €</td>
        </tr>
        <?php if($status !== 'paid'): ?>
        <tr>
          <td style="font-size:14px;color:#0f172a;padding-bottom:10px;border-top:1px solid rgba(15,23,42,.08);padding-top:10px;">Preostalo za plačilo</td>
          <td align="right" style="font-size:14px;font-weight:800;color:#dc2626;padding-bottom:10px;border-top:1px solid rgba(15,23,42,.08);padding-top:10px;"><?= number_format($remaining, 2, ',', '.') ?> €</td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="2" style="border-top:1px solid rgba(15,23,42,.08);padding-top:14px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="font-size:12px;text-transform:uppercase;letter-spacing:1px;color:#64748b;">
                  <?= $status==='paid' ? 'Status računa' : 'Trenutni status' ?>
                </td>
                <td align="right">
                  <span style="display:inline-block;padding:5px 16px;border-radius:999px;font-size:12px;font-weight:800;
                    background:<?= $status==='paid' ? '#dcfce7' : '#fef3c7' ?>;
                    color:<?= $status==='paid' ? '#16a34a' : '#d97706' ?>;">
                    <?= $status==='paid' ? '✓ PORAVNANO' : '◑ DELNO PLAČANO' ?>
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>

    <?php if($status !== 'paid'): ?>
    <!-- Podatki za preostalo plačilo -->
    <div style="background:#fff8f0;border:1px solid #fed7aa;border-radius:18px;padding:22px;margin-bottom:24px;">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#d97706;margin-bottom:12px;font-weight:700;">Podatki za preostanek</div>
      <div style="font-size:14px;line-height:1.9;color:#0f172a;">
        <strong>Prejemnik:</strong> Jure Pintar – PintSite<br>
        <strong>TRR:</strong> SI56 XXXX XXXX XXXX XXX<br>
        <strong>BIC/SWIFT:</strong> XXXXXXXX<br>
        <strong>Namen:</strong> Doplačilo računa <?= htmlspecialchars($invoice) ?><br>
        <strong>Znesek:</strong> <span style="color:#dc2626;font-weight:800;"><?= number_format($remaining, 2, ',', '.') ?> €</span>
      </div>
    </div>
    <?php endif; ?>

    <!-- Zahvala -->
    <div style="background:#f8fbff;border-radius:16px;padding:22px;margin-bottom:8px;">
      <p style="font-size:14px;line-height:1.8;color:#374151;margin:0;">
        <?php if($status==='paid'): ?>
        Zahvaljujemo se vam za zaupanje in pravočasno plačilo. Z veseljem bomo tudi v prihodnje skrbeli za vas. Če imate kakršna koli vprašanja, se nam prosim obrnite.
        <?php else: ?>
        Zahvaljujemo se za opravljeno delno plačilo. Prosimo, da preostali znesek poravnate v dogovorjenem roku. Za morebitna vprašanja smo vam na voljo.
        <?php endif; ?>
      </p>
    </div>

    <p style="font-size:14px;color:#374151;line-height:1.7;margin-top:20px;">
      S spoštovanjem,<br>
      <strong style="color:#0f172a;">Jure Pintar</strong><br>
      PintSite
    </p>

  </div>

  <!-- FOOTER -->
  <div style="padding:22px 40px;border-top:1px solid rgba(15,23,42,.08);background:#f8fbff;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="font-size:12px;color:#64748b;line-height:1.8;">
          <strong style="color:#0f172a;">PintSite – Jure Pintar</strong><br>
          info@pintsite.si &nbsp;|&nbsp; +386 XX XXX XXX<br>
          <span style="font-size:11px;">To sporočilo je samodejno generirano s strani PintSite Invoice System.</span>
        </td>
        <td align="right" valign="middle">
          <span style="display:inline-block;padding:8px 14px;background:#dbeafe;border-radius:999px;font-size:11px;font-weight:800;color:#1d4ed8;">PintSite</span>
        </td>
      </tr>
    </table>
  </div>

</div>

</body>
</html>
