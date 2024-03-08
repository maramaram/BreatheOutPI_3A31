function generateQRCode(fileContent) {
    // Utilisez le lien généré pour générer le QR code
    var qr = new QRious({
        element: document.querySelector('.qrious'),
        size: 250,
        value: fileContent
    });

    // Affichage de la valeur du QR code
    var message_valeur = document.getElementById("qrValue");
    
}
