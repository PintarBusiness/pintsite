<?php
// Baza računov — shranjevanje v JSON datoteko
define("INVOICES_FILE", __DIR__ . "/invoices_data.json");

function loadInvoices(): array {
    if (!file_exists(INVOICES_FILE)) return [];
    $data = file_get_contents(INVOICES_FILE);
    return json_decode($data, true) ?: [];
}

function saveInvoices(array $invoices): void {
    file_put_contents(INVOICES_FILE, json_encode($invoices, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function addInvoice(array $invoice): void {
    $invoices = loadInvoices();
    $invoices[] = $invoice;
    saveInvoices($invoices);
}

function getInvoiceByNumber(string $number): ?array {
    foreach (loadInvoices() as $inv) {
        if ($inv['invoice'] === $number) return $inv;
    }
    return null;
}

function updateInvoice(string $number, array $updates): bool {
    $invoices = loadInvoices();
    foreach ($invoices as &$inv) {
        if ($inv['invoice'] === $number) {
            foreach ($updates as $k => $v) $inv[$k] = $v;
            saveInvoices($invoices);
            return true;
        }
    }
    return false;
}
?>
