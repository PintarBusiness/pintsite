<?php
require_once "config.php";
require_once "invoices_db.php";

if(!isset($_SESSION["admin_logged"])){
    header("Location: admin.php");
    exit;
}

$invoices = array_reverse(loadInvoices()); // Najnovejši prvi

$statusLabels = [
    'unpaid'  => 'Čaka na plačilo',
    'partial' => 'Delno plačano',
    'paid'    => 'Plačano',
];
$statusColors = [
    'unpaid'  => '#dc2626',
    'partial' => '#d97706',
    'paid'    => '#16a34a',
];
$statusBg = [
    'unpaid'  => '#fee2e2',
    'partial' => '#fef3c7',
    'paid'    => '#dcfce7',
];
?>
<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Poslani računi – PintSite</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="admin.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
.invoices-page { max-width: 1100px; margin: 0 auto; padding: 40px 28px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
.page-header h1 { font-size: 28px; font-weight: 800; }
.back-btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 20px; border-radius: 999px;
  background: rgba(37,99,235,.1); color: #2563eb;
  text-decoration: none; font-weight: 600; font-size: 14px;
  transition: background .2s;
}
.back-btn:hover { background: rgba(37,99,235,.18); }

.filters { display: flex; gap: 10px; margin-bottom: 22px; flex-wrap: wrap; }
.filter-btn {
  padding: 8px 18px; border-radius: 999px; border: 1.5px solid #e2e8f0;
  background: #fff; font-family: inherit; font-size: 13px; font-weight: 600;
  cursor: pointer; color: #64748b; transition: all .15s;
}
.filter-btn.active, .filter-btn:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }

.invoices-table {
  background: #fff; border-radius: 24px;
  box-shadow: 0 4px 32px rgba(37,99,235,.08);
  overflow: hidden;
}
.inv-table { width: 100%; border-collapse: collapse; }
.inv-table th {
  padding: 14px 20px; text-align: left;
  font-size: 11px; text-transform: uppercase; letter-spacing: 1px;
  color: #64748b; font-weight: 700; border-bottom: 1px solid #f1f5f9;
  background: #f8fbff;
}
.inv-table td {
  padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
  font-size: 14px; color: #0f172a; vertical-align: middle;
}
.inv-table tr:last-child td { border-bottom: none; }
.inv-table tr:hover td { background: #f8fbff; }

.status-badge {
  display: inline-block; padding: 4px 12px; border-radius: 999px;
  font-size: 11px; font-weight: 700; letter-spacing: .5px;
}
.company-name { font-weight: 700; }
.email-small { font-size: 12px; color: #64748b; margin-top: 2px; }
.price-col { font-weight: 800; color: #2563eb; font-size: 15px; }
.action-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 7px 14px; border-radius: 999px; font-family: inherit;
  font-size: 12px; font-weight: 700; cursor: pointer;
  border: none; text-decoration: none; transition: all .15s;
}
.btn-detail { background: #eff6ff; color: #2563eb; }
.btn-detail:hover { background: #dbeafe; }

.empty-state {
  text-align: center; padding: 80px 40px;
  color: #64748b;
}
.empty-state .icon { font-size: 48px; margin-bottom: 16px; }

.success-banner {
  background: #dcfce7; border: 1px solid #86efac;
  border-radius: 16px; padding: 16px 22px;
  margin-bottom: 22px; color: #166534; font-weight: 600;
  display: flex; align-items: center; gap: 10px;
}

.summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
.sum-card {
  background: #fff; border-radius: 18px;
  padding: 20px 22px;
  box-shadow: 0 2px 16px rgba(37,99,235,.07);
}
.sum-card .sum-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700; margin-bottom: 6px; }
.sum-card .sum-value { font-size: 22px; font-weight: 800; color: #0f172a; }
.sum-card .sum-sub { font-size: 12px; color: #64748b; margin-top: 2px; }

.paid-info { font-size: 12px; color: #64748b; margin-top: 3px; }
</style>
</head>
<body class="dashboard-body">

<div class="invoices-page">

  <div class="page-header">
    <div>
      <h1>📄 Poslani računi</h1>
      <p style="color:#64748b;margin-top:4px;font-size:14px;">Upravljanje in sledenje plačilom</p>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="dashboard.php" class="back-btn">← Nova račun</a>
      <a href="logout.php" class="back-btn" style="background:rgba(100,116,139,.1);color:#64748b;">Odjava</a>
    </div>
  </div>

  <?php if(isset($_GET['sent'])): ?>
  <div class="success-banner">✅ Račun je bil uspešno poslan s PDF prilogo!</div>
  <?php endif; ?>
  <?php if(isset($_GET['updated'])): ?>
  <div class="success-banner">✅ Status računa je bil posodobljen<?= isset($_GET['mail']) ? ' in obvestilo poslano stranki' : '' ?>.</div>
  <?php endif; ?>

  <?php
  $total = array_sum(array_column($invoices, 'price'));
  $paid = array_sum(array_column(array_filter($invoices, fn($i)=>$i['status']==='paid'), 'price'));
  $totalPaid = array_sum(array_column($invoices, 'price_paid'));
  $unpaidCount = count(array_filter($invoices, fn($i)=>$i['status']==='unpaid'));
  ?>

  <div class="summary-cards">
    <div class="sum-card">
      <div class="sum-label">Skupaj poslano</div>
      <div class="sum-value"><?= count($invoices) ?></div>
      <div class="sum-sub">računov</div>
    </div>
    <div class="sum-card">
      <div class="sum-label">Skupna vrednost</div>
      <div class="sum-value"><?= number_format($total, 2, ',', '.') ?> €</div>
      <div class="sum-sub">vsi računi</div>
    </div>
    <div class="sum-card">
      <div class="sum-label">Prejeto</div>
      <div class="sum-value" style="color:#16a34a;"><?= number_format($totalPaid, 2, ',', '.') ?> €</div>
      <div class="sum-sub">plačila skupaj</div>
    </div>
    <div class="sum-card">
      <div class="sum-label">Čaka na plačilo</div>
      <div class="sum-value" style="color:#dc2626;"><?= $unpaidCount ?></div>
      <div class="sum-sub">odprtih računov</div>
    </div>
  </div>

  <div class="filters">
    <button class="filter-btn active" onclick="filterTable('all', this)">Vsi (<?= count($invoices) ?>)</button>
    <button class="filter-btn" onclick="filterTable('unpaid', this)">Čaka na plačilo</button>
    <button class="filter-btn" onclick="filterTable('partial', this)">Delno plačano</button>
    <button class="filter-btn" onclick="filterTable('paid', this)">Plačano</button>
  </div>

  <div class="invoices-table">
    <?php if(empty($invoices)): ?>
    <div class="empty-state">
      <div class="icon">📭</div>
      <div style="font-size:18px;font-weight:700;margin-bottom:8px;">Še ni poslanih računov</div>
      <div>Ko pošlješ prvi račun, se bo tukaj pojavil.</div>
    </div>
    <?php else: ?>
    <table class="inv-table">
      <thead>
        <tr>
          <th>Račun</th>
          <th>Stranka</th>
          <th>Datum</th>
          <th>Znesek</th>
          <th>Plačano</th>
          <th>Status</th>
          <th>Akcija</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($invoices as $inv):
          $status = $inv['status'] ?? 'unpaid';
          $paid_amount = floatval($inv['price_paid'] ?? 0);
          $remaining = floatval($inv['price']) - $paid_amount;
        ?>
        <tr data-status="<?= $status ?>">
          <td>
            <strong style="color:#2563eb;"><?= htmlspecialchars($inv['invoice']) ?></strong>
            <div class="email-small">Rok: <?= htmlspecialchars($inv['due']) ?></div>
          </td>
          <td>
            <div class="company-name"><?= htmlspecialchars($inv['company']) ?></div>
            <div class="email-small"><?= htmlspecialchars($inv['email']) ?></div>
          </td>
          <td style="font-size:13px;"><?= htmlspecialchars($inv['date']) ?></td>
          <td>
            <div class="price-col"><?= number_format(floatval($inv['price']), 2, ',', '.') ?> €</div>
          </td>
          <td>
            <div style="font-weight:700;color:#16a34a;"><?= number_format($paid_amount, 2, ',', '.') ?> €</div>
            <?php if($status === 'partial'): ?>
            <div class="paid-info">Ostane: <?= number_format($remaining, 2, ',', '.') ?> €</div>
            <?php endif; ?>
          </td>
          <td>
            <span class="status-badge" style="background:<?= $statusBg[$status] ?>;color:<?= $statusColors[$status] ?>;">
              <?= $statusLabels[$status] ?>
            </span>
          </td>
          <td>
            <a href="invoice_detail.php?id=<?= urlencode($inv['invoice']) ?>" class="action-btn btn-detail">
              ✏️ Uredi
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

</div>

<script>
function filterTable(status, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('tbody tr').forEach(row => {
    if (status === 'all' || row.dataset.status === status) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}
</script>

</body>
</html>
