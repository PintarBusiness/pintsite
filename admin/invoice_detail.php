<?php
require_once "config.php";
require_once "invoices_db.php";

if(!isset($_SESSION["admin_logged"])){
    header("Location: admin.php");
    exit;
}

$invoiceId = urldecode($_GET['id'] ?? '');
$inv = getInvoiceByNumber($invoiceId);

if (!$inv) {
    header("Location: invoices.php");
    exit;
}

$error = '';
$message = '';

// --- Obdelaj POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action     = $_POST['action'] ?? '';
    $paidAmount = floatval($_POST['price_paid'] ?? 0);
    $notes      = trim($_POST['notes'] ?? '');

    if ($action === 'update_payment') {
        $price = floatval($inv['price']);

        if ($paidAmount >= $price) {
            $newStatus = 'paid';
            $paidAmount = $price;
        } elseif ($paidAmount > 0) {
            $newStatus = 'partial';
        } else {
            $newStatus = 'unpaid';
        }

        $sendMail = isset($_POST['send_mail']);

        updateInvoice($invoiceId, [
            'status'     => $newStatus,
            'price_paid' => $paidAmount,
            'notes'      => $notes,
            'paid_at'    => $newStatus === 'paid' ? date("d.m.Y") : ($inv['paid_at'] ?? null),
        ]);

        // Ponovno naloži
        $inv = getInvoiceByNumber($invoiceId);

        if ($sendMail) {
            sendPaymentConfirmation($inv);
            header("Location: invoices.php?updated=1&mail=1");
        } else {
            header("Location: invoices.php?updated=1");
        }
        exit;
    }
}

$statusLabels = ['unpaid'=>'Čaka na plačilo','partial'=>'Delno plačano','paid'=>'Plačano'];
$statusColors = ['unpaid'=>'#dc2626','partial'=>'#d97706','paid'=>'#16a34a'];
$statusBg     = ['unpaid'=>'#fee2e2','partial'=>'#fef3c7','paid'=>'#dcfce7'];

$status      = $inv['status'] ?? 'unpaid';
$price       = floatval($inv['price']);
$pricePaid   = floatval($inv['price_paid'] ?? 0);
$remaining   = $price - $pricePaid;

// -------------------------------------------------------
function sendPaymentConfirmation(array $inv): void {
    $company     = $inv['company'];
    $email       = $inv['email'];
    $invoice     = $inv['invoice'];
    $price       = floatval($inv['price']);
    $pricePaid   = floatval($inv['price_paid'] ?? 0);
    $remaining   = $price - $pricePaid;
    $date        = date("d.m.Y");
    $status      = $inv['status'];

    ob_start();
    include __DIR__ . "/payment_confirm_template.php";
    $html = ob_get_clean();

    $boundary = "==PS_" . md5(uniqid());
    $subject  = "Potrditev plačila – Račun " . $invoice . " | PintSite";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $headers .= "From: PintSite <info@pintsite.si>\r\n";
    $headers .= "Reply-To: info@pintsite.si\r\n";

    $body  = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($html)) . "\r\n";
    $body .= "--$boundary--\r\n";

    mail($email, $subject, $body, $headers);
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Račun <?= htmlspecialchars($inv['invoice']) ?> – PintSite</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="admin.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
.detail-page { max-width: 960px; margin: 0 auto; padding: 40px 28px; }
.page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 32px; }
.back-btn {
  display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
  border-radius: 999px; background: rgba(37,99,235,.1); color: #2563eb;
  text-decoration: none; font-weight: 600; font-size: 14px;
}
.back-btn:hover { background: rgba(37,99,235,.18); }
.page-header h1 { font-size: 24px; font-weight: 800; }

.detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 24px; }

.card {
  background: #fff; border-radius: 22px;
  box-shadow: 0 4px 28px rgba(37,99,235,.08);
  padding: 28px;
  margin-bottom: 20px;
}
.card-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700; margin-bottom: 18px; }

.info-row { display: flex; justify-content: space-between; align-items: baseline; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.info-row:last-child { border-bottom: none; }
.info-label { color: #64748b; }
.info-val { font-weight: 700; }

.status-badge {
  display: inline-block; padding: 5px 14px; border-radius: 999px;
  font-size: 12px; font-weight: 700; letter-spacing: .5px;
}

.form-group { margin-bottom: 18px; }
.form-group label { display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 7px; text-transform: uppercase; letter-spacing: .5px; }
.form-group input[type="number"],
.form-group textarea,
.form-group select {
  width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0;
  border-radius: 12px; font-family: inherit; font-size: 14px;
  color: #0f172a; background: #f8fbff;
  transition: border-color .15s;
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
  outline: none; border-color: #2563eb; background: #fff;
}

.range-row { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
.range-row input[type="range"] { flex: 1; accent-color: #2563eb; }
.range-val { font-size: 16px; font-weight: 800; color: #2563eb; min-width: 80px; text-align: right; }

.checkbox-row {
  display: flex; align-items: center; gap: 10px;
  padding: 14px; border-radius: 14px;
  border: 1.5px solid #e2e8f0; background: #f8fbff;
  margin-bottom: 18px; cursor: pointer;
}
.checkbox-row input[type="checkbox"] { accent-color: #2563eb; width: 17px; height: 17px; cursor: pointer; }
.checkbox-row label { font-size: 14px; font-weight: 600; cursor: pointer; }
.checkbox-row .sub { font-size: 12px; color: #64748b; }

.primary-btn {
  width: 100%; padding: 14px; border-radius: 14px;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: #fff; font-family: inherit; font-size: 15px; font-weight: 700;
  border: none; cursor: pointer; transition: opacity .15s;
}
.primary-btn:hover { opacity: .9; }

.progress-bar { height: 10px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin: 10px 0; }
.progress-fill { height: 100%; border-radius: 999px; transition: width .3s; }

.invoice-preview-mini { font-size: 12px; line-height: 1.8; color: #64748b; }
</style>
</head>
<body class="dashboard-body">

<div class="detail-page">

  <div class="page-header">
    <a href="invoices.php" class="back-btn">← Nazaj</a>
    <div>
      <h1>Račun <?= htmlspecialchars($inv['invoice']) ?></h1>
      <span class="status-badge" style="background:<?= $statusBg[$status] ?>;color:<?= $statusColors[$status] ?>;">
        <?= $statusLabels[$status] ?>
      </span>
    </div>
  </div>

  <div class="detail-grid">

    <!-- LEVA STRAN: podatki o računu -->
    <div>

      <div class="card">
        <div class="card-title">Podatki o stranki</div>
        <div class="info-row"><span class="info-label">Podjetje</span><span class="info-val"><?= htmlspecialchars($inv['company']) ?></span></div>
        <?php if($inv['person']): ?>
        <div class="info-row"><span class="info-label">Kontakt</span><span class="info-val"><?= htmlspecialchars($inv['person']) ?></span></div>
        <?php endif; ?>
        <div class="info-row"><span class="info-label">Email</span><span class="info-val"><?= htmlspecialchars($inv['email']) ?></span></div>
        <?php if($inv['phone']): ?>
        <div class="info-row"><span class="info-label">Telefon</span><span class="info-val"><?= htmlspecialchars($inv['phone']) ?></span></div>
        <?php endif; ?>
        <?php if($inv['address']): ?>
        <div class="info-row"><span class="info-label">Naslov</span><span class="info-val"><?= htmlspecialchars($inv['address']) . ($inv['city'] ? ', '.$inv['city'] : '') ?></span></div>
        <?php endif; ?>
        <?php if($inv['tax']): ?>
        <div class="info-row"><span class="info-label">Davčna</span><span class="info-val"><?= htmlspecialchars($inv['tax']) ?></span></div>
        <?php endif; ?>
      </div>

      <div class="card">
        <div class="card-title">Podatki o računu</div>
        <div class="info-row"><span class="info-label">Številka</span><span class="info-val" style="color:#2563eb;"><?= htmlspecialchars($inv['invoice']) ?></span></div>
        <div class="info-row"><span class="info-label">Datum</span><span class="info-val"><?= htmlspecialchars($inv['date']) ?></span></div>
        <div class="info-row"><span class="info-label">Rok plačila</span><span class="info-val"><?= htmlspecialchars($inv['due']) ?></span></div>
        <div class="info-row"><span class="info-label">Paket</span><span class="info-val"><?= htmlspecialchars($inv['package']) ?></span></div>
        <div class="info-row"><span class="info-label">Način plačila</span><span class="info-val"><?= htmlspecialchars($inv['payment']) ?></span></div>
        <div class="info-row">
          <span class="info-label">Skupaj</span>
          <span class="info-val" style="font-size:18px;color:#2563eb;"><?= number_format($price, 2, ',', '.') ?> €</span>
        </div>
      </div>

      <?php if($inv['description']): ?>
      <div class="card">
        <div class="card-title">Opis storitev</div>
        <div style="font-size:14px;line-height:1.8;color:#374151;"><?= $inv['description'] ?></div>
      </div>
      <?php endif; ?>

    </div>

    <!-- DESNA STRAN: upravljanje plačila -->
    <div>

      <div class="card">
        <div class="card-title">Stanje plačila</div>

        <div style="text-align:center;padding:10px 0 18px;">
          <div style="font-size:13px;color:#64748b;margin-bottom:4px;">Plačano</div>
          <div style="font-size:30px;font-weight:800;color:#16a34a;"><?= number_format($pricePaid, 2, ',', '.') ?> €</div>
          <div style="font-size:13px;color:#64748b;margin-top:2px;">od <?= number_format($price, 2, ',', '.') ?> €</div>
        </div>

        <div class="progress-bar">
          <div class="progress-fill" style="width:<?= $price > 0 ? min(100, round($pricePaid/$price*100)) : 0 ?>%;background:<?= $status==='paid' ? '#16a34a' : ($status==='partial' ? '#d97706' : '#e2e8f0') ?>;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:12px;color:#64748b;margin-bottom:4px;">
          <span><?= round($pricePaid/$price*100, 1) ?>% plačano</span>
          <span>Ostane: <?= number_format($remaining, 2, ',', '.') ?> €</span>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Posodobi plačilo</div>

        <form method="POST">
          <input type="hidden" name="action" value="update_payment">

          <div class="form-group">
            <label>Znesek plačila (€)</label>
            <div class="range-row">
              <input type="range" min="0" max="<?= $price ?>" step="0.01"
                value="<?= $pricePaid ?>"
                oninput="document.getElementById('paidNum').value=parseFloat(this.value).toFixed(2);document.getElementById('paidRange').value=this.value;">
              <span class="range-val" id="rangeDisplay"><?= number_format($pricePaid, 2, ',', '.') ?> €</span>
            </div>
            <input type="number" name="price_paid" id="paidNum"
              min="0" max="<?= $price ?>" step="0.01"
              value="<?= $pricePaid ?>"
              oninput="syncRange(this.value)">
          </div>

          <div style="display:flex;gap:8px;margin-bottom:18px;">
            <button type="button" onclick="setAmount(0)" class="action-btn" style="flex:1;padding:8px;border-radius:10px;background:#fee2e2;color:#dc2626;border:none;font-family:inherit;font-size:12px;font-weight:700;cursor:pointer;">0 €</button>
            <button type="button" onclick="setAmount(<?= $price/2 ?>)" class="action-btn" style="flex:1;padding:8px;border-radius:10px;background:#fef3c7;color:#d97706;border:none;font-family:inherit;font-size:12px;font-weight:700;cursor:pointer;">50%</button>
            <button type="button" onclick="setAmount(<?= $price ?>)" class="action-btn" style="flex:1;padding:8px;border-radius:10px;background:#dcfce7;color:#16a34a;border:none;font-family:inherit;font-size:12px;font-weight:700;cursor:pointer;">Vse</button>
          </div>

          <div class="form-group">
            <label>Interne opombe</label>
            <textarea name="notes" rows="3" placeholder="Npr. plačilo 15.6.2025 – bank. izpisek..."><?= htmlspecialchars($inv['notes'] ?? '') ?></textarea>
          </div>

          <div class="checkbox-row">
            <input type="checkbox" name="send_mail" id="sendMail" checked>
            <div>
              <label for="sendMail">📧 Pošlji obvestilo stranki</label>
              <div class="sub">Stranka prejme uradni email z informacijo o plačilu</div>
            </div>
          </div>

          <button type="submit" class="primary-btn">
            💾 Shrani in pošlji obvestilo
          </button>
        </form>
      </div>

    </div>

  </div>

</div>

<script>
function syncRange(val) {
  var max = <?= $price ?>;
  var v = Math.min(parseFloat(val)||0, max);
  document.querySelector('input[type="range"]').value = v;
  document.getElementById('rangeDisplay').textContent = v.toFixed(2).replace('.',',') + ' €';
}
function setAmount(v) {
  document.getElementById('paidNum').value = v.toFixed(2);
  document.querySelector('input[type="range"]').value = v;
  document.getElementById('rangeDisplay').textContent = v.toFixed(2).replace('.',',') + ' €';
}
document.querySelector('input[type="range"]').addEventListener('input', function() {
  document.getElementById('rangeDisplay').textContent = parseFloat(this.value).toFixed(2).replace('.',',') + ' €';
});
</script>

</body>
</html>
