<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:30px;background:#f4f8ff;font-family:Arial,sans-serif;color:#0f172a;">

<div style="max-width:680px;margin:0 auto;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.10);">

  <!-- HEADER -->
  <div style="background:linear-gradient(135deg,#2563eb,#1d4ed8);padding:36px 40px;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td valign="top">
          <img src="https://pintsite.si/Logopravitext.png" height="48" style="display:block;filter:brightness(0) invert(1);">
          <div style="margin-top:14px;font-size:13px;line-height:1.9;color:rgba(255,255,255,.85);">
            <strong style="font-size:15px;color:#ffffff;">PintSite</strong><br>
            Jure Pintar<br>
            info@pintsite.si<br>
            +386 XX XXX XXX
          </div>
        </td>
        <td valign="top" align="right">
          <div style="font-size:28px;font-weight:800;color:#ffffff;letter-spacing:2px;margin-bottom:10px;">RAČUN</div>
          <div style="font-size:13px;color:rgba(255,255,255,.9);line-height:1.9;">
            <strong style="color:#ffffff;">Št: <?= htmlspecialchars($invoice) ?></strong><br>
            Datum: <?= htmlspecialchars($date) ?><br>
            Rok plačila: <?= htmlspecialchars($due) ?><br>
            <span style="font-size:12px;opacity:.75;">Status: <strong>ČAKA NA PLAČILO</strong></span>
          </div>
        </td>
      </tr>
    </table>
  </div>

  <!-- VSEBINA -->
  <div style="padding:36px 40px;">

    <!-- STRANKA + PAKET -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:18px;">
      <tr>
        <td width="50%" valign="top" style="padding-right:10px;">
          <div style="background:#f8fbff;border-radius:18px;padding:22px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-bottom:10px;font-weight:700;">Stranka</div>
            <div style="font-size:14px;line-height:1.9;color:#0f172a;">
              <strong><?= htmlspecialchars($company) ?></strong><br>
              <?= $person  ? htmlspecialchars($person).'<br>'  : '' ?>
              <?= $address ? htmlspecialchars($address).'<br>' : '' ?>
              <?= $city    ? htmlspecialchars($city).'<br>'    : '' ?>
              <?= $phone   ? htmlspecialchars($phone).'<br>'   : '' ?>
              <?= htmlspecialchars($email) ?><br>
              <?= $tax     ? 'DDV: '.htmlspecialchars($tax)    : '' ?>
            </div>
          </div>
        </td>
        <td width="50%" valign="top" style="padding-left:10px;">
          <div style="background:#f8fbff;border-radius:18px;padding:22px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-bottom:10px;font-weight:700;">Paket</div>
            <div style="font-size:14px;color:#0f172a;">
              <strong><?= htmlspecialchars($package) ?></strong>
            </div>
          </div>
        </td>
      </tr>
    </table>

    <!-- OPIS STORITEV -->
    <div style="background:#f8fbff;border-radius:18px;padding:22px;margin-bottom:18px;">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-bottom:10px;font-weight:700;">Opis storitev</div>
      <div style="font-size:14px;line-height:1.9;color:#0f172a;">
        <?= $description ?>
      </div>
    </div>

    <!-- SKUPAJ -->
    <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:20px;padding:26px;margin-bottom:18px;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="font-size:14px;color:#0f172a;padding-bottom:10px;">Način plačila</td>
          <td align="right" style="font-size:14px;font-weight:700;color:#0f172a;padding-bottom:10px;"><?= htmlspecialchars($payment) ?></td>
        </tr>
        <tr>
          <td style="font-size:14px;color:#0f172a;padding-bottom:14px;">DDV</td>
          <td align="right" style="font-size:14px;font-weight:700;color:#0f172a;padding-bottom:14px;">Ni zavezanec</td>
        </tr>
        <tr>
          <td colspan="2" style="border-top:1px solid rgba(15,23,42,.10);padding-top:16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="font-size:12px;text-transform:uppercase;letter-spacing:1px;color:#64748b;">Skupaj (z DDV)</td>
                <td align="right" style="font-size:32px;font-weight:800;color:#2563eb;"><?= htmlspecialchars($price) ?> €</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>

    <!-- PLAČILNI PODATKI -->
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:18px;padding:22px;margin-bottom:18px;">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#16a34a;margin-bottom:10px;font-weight:700;">Podatki za plačilo</div>
      <div style="font-size:14px;line-height:1.9;color:#0f172a;">
        <strong>Prejemnik:</strong> Jure Pintar – PintSite<br>
        <strong>TRR:</strong> SI56 XXXX XXXX XXXX XXX<br>
        <strong>BIC/SWIFT:</strong> XXXXXXXX<br>
        <strong>Namen:</strong> Plačilo računa <?= htmlspecialchars($invoice) ?><br>
        <strong>Rok plačila:</strong> <?= htmlspecialchars($due) ?>
      </div>
    </div>

  </div>

  <!-- FOOTER -->
  <div style="padding:22px 40px;border-top:1px solid rgba(15,23,42,.08);">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="font-size:13px;color:#64748b;line-height:1.8;">
          <strong style="color:#0f172a;">PintSite – Jure Pintar</strong><br>
          info@pintsite.si | +386 XX XXX XXX<br>
          Hvala za vaše zaupanje!
        </td>
        <td align="right" valign="middle">
          <span style="display:inline-block;padding:10px 16px;background:#dbeafe;border-radius:999px;font-size:12px;font-weight:800;color:#1d4ed8;">PintSite Invoice System</span>
        </td>
      </tr>
    </table>
  </div>

</div>

</body>
</html>
