<?php

namespace App\Services;

require __DIR__ . '/../../vendor/autoload.php';

use \Mailjet\Client;
use \Mailjet\Resources;

/**
 * Envoie un email de candidature à l'entreprise avec les détails de l'étudiant et la lettre de motivation, ainsi que le CV en pièce jointe. (utilise l'API Mailjet)
 *
 * @param $emailDestination
 * @param $titreOffre
 * @param $nom
 * @param $prenom
 * @param $emailEtudiant
 * @param $cv
 * @param $lettreMotivation
 * @param $linkedin
 * @return bool
 */
function mail(
    $emailDestination,
    $titreOffre,
    $nom,
    $prenom,
    $emailEtudiant,
    $cv,
    $lettreMotivation,
    $linkedin) : bool
{
    $env = parse_ini_file(__DIR__ .'/../../Config/.env');

    $apiKey = $env['API'];
    $secretKey = $env['SECRET'];

    if ($linkedin == null && !filter_var($linkedin, FILTER_VALIDATE_URL)) {
        $linkedin = "Non renseigné";
    }

    $cv = __DIR__ . "/../../Uploads/$cv";

    $mj = new Client($apiKey, $secretKey, true, [
        'version' => 'v3.1',
        'timeout' => 30]);

    $html = '
    <div style="font-family:Arial,sans-serif;padding:20px;background:#f5f5f5;">
      <div style="max-width:600px;margin:auto;background:#B8CBD0FF;padding:20px;border-radius:10px;">
        <img src="https://marco200609.github.io/Projet-dev-web/Images/LogoHead.png" alt="Logo Juniors" style="max-width:120px;border-radius:8px;margin-bottom: 20px;text-align: center">
        <h2 style="color:#7B9DD2;">Nouvelle candidature - '.htmlspecialchars($titreOffre).'</h2>
        <hr style="border:none;height:1px;background:#eee;margin:20px 0;">
        <h3 style="color:#222;">Coordonnées de l’étudiant :</h3>
        <table style="width:100%;color:#444;font-size:16px">
          <tr><td><strong>Nom</strong></td><td>'.htmlspecialchars($nom).'</td></tr>
          <tr><td><strong>Prénom</strong></td><td>'.htmlspecialchars($prenom).'</td></tr>
          <tr><td><strong>Email</strong></td><td>'.htmlspecialchars($emailEtudiant).'</td></tr>
          <tr><td><strong>LinkedIn</strong></td><td><a href="'.htmlspecialchars($linkedin).'" target="_blank">'.$linkedin. '</a></td></tr>
        </table>
        <h3 style="color:#222;margin-top:30px;">Lettre de motivation</h3>
        <div style="padding:15px;border-left:4px solid #7b9dd2;background:#f6f8fb;">' .nl2br(htmlspecialchars($lettreMotivation)).'</div>
        <p style="margin-top:25px;">Le CV de cet étudiant est joint à cet email en pièce jointe.</p>
      </div>
      <p style="text-align:center;color:#aaa;font-size:13px;">Mail envoyé par Juniors</p>
    </div>
    ';

    $message = [
        'From' => [
            'Email' => "juniors-avenir@outlook.fr",
            'Name' => "Juniors"
        ],
        'To' => [
            [
                'Email' => $emailDestination,
                'Name' => "Recruteur"
            ]
        ],
        'Subject' => "Candidature : ".$titreOffre,
        'TextPart' => "Nouvelle candidature : $prenom $nom - Email : $emailEtudiant - Offre : $titreOffre ",
        'HTMLPart' => $html
    ];

    if (file_exists($cv)) {
        $message['Attachments'] = [
            [
                'ContentType' => "application/pdf",
                'Filename' => "CV-{$prenom}-{$nom}.pdf",
                'Base64Content' => base64_encode(file_get_contents($cv))
            ]
        ];
    }

    $body = ['Messages' => [$message]];

    try {
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if ($response->success()) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $e) {
        return false;
    }
}