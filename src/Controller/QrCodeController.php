<?php
// src/Controller/QrCodeController.php

namespace App\Controller;

use BaconQrCode\Renderer\ImageRendererInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\ImageRendererConfig;
use BaconQrCode\Writer\PngWriter;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
class QrCodeController extends AbstractController
{
    
    #[Route('/generate-qrcode', name: 'generate_qrcode')]
    public function generateQrCode(): Response
    {
        $data = 'Données à encoder dans le QR Code';
        $filename = 'qrcode.png';

        $writer = new Writer();
        $renderer = new ImageRenderer(
            new ImageRendererInterface\ImagickImageBackEnd(),
            400,
            300
        );

        $writer->writeFile($data, $renderer, $filename);

        // Vous pouvez également renvoyer l'image générée comme réponse
        $response = new Response(file_get_contents($filename));
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
}
