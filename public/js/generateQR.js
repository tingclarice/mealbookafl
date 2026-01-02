function showQr(displayId, midtransId) {
    // 1. Set ID text
    document.getElementById('qrOrderId').innerText = displayId;

    // 2. Clear previous QR
    const qrContainer = document.getElementById('qrcode');
    qrContainer.innerHTML = '';

    // 3. Generate new QR
    // We encode the sensitive midtrans_order_id because that's what the backend expects
    new QRCode(qrContainer, {
        text: midtransId,
        width: 200,
        height: 200,
        colorDark: "#2D114B",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // 4. Show Modal
    const modal = new bootstrap.Modal(document.getElementById('qrDisplayModal'));
    modal.show();
}