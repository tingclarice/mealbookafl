function showQr(displayId, midtransId) {
    // Set ID text
    document.getElementById('qrOrderId').innerText = displayId;

    // Clear previous QR
    const qrContainer = document.getElementById('qrcode');
    qrContainer.innerHTML = '';

    // Generate new QR
    new QRCode(qrContainer, {
        text: midtransId,
        width: 200,
        height: 200,
        colorDark: "#2D114B",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Show Modal
    const modal = new bootstrap.Modal(document.getElementById('qrDisplayModal'));
    modal.show();
}