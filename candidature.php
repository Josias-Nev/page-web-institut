<?php
$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$nom        = clean_input($_POST['nom'] ?? '');
$prenom     = clean_input($_POST['prenom'] ?? '');
$email      = clean_input($_POST['email'] ?? '');
$telephone  = clean_input($_POST['telephone'] ?? '');
$naissance  = clean_input($_POST['naissance'] ?? '');
$programme  = clean_input($_POST['programme'] ?? '');
$niveau     = clean_input($_POST['niveau'] ?? '');
$motivation = clean_input($_POST['motivation'] ?? '');
$competences = clean_input($_POST['competences'] ?? '');

$errors = [];
if (empty($nom)) $errors[] = "Le nom est requis.";
if (empty($prenom)) $errors[] = "Le prénom est requis.";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
if (empty($telephone)) $errors[] = "Le téléphone est requis.";
if (empty($naissance)) $errors[] = "La date de naissance est requise.";
if (empty($programme)) $errors[] = "Le programme est requis.";
if (empty($niveau)) $errors[] = "Le niveau d'études est requis.";
if (empty($motivation)) $errors[] = "La lettre de motivation est requise.";

$fichiers = [];
$champs_fichiers = ['releves_notes', 'documents_academiques'];

foreach ($champs_fichiers as $champ) {
    if (isset($_FILES[$champ]) && $_FILES[$champ]['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES[$champ]['tmp_name'];
        $original_name = basename($_FILES[$champ]['name']);
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            $errors[] = "Le fichier $original_name n'est pas un PDF.";
            continue;
        }

        $unique_name = $champ . '_' . time() . '_' . uniqid() . '.pdf';
        $destination = $upload_dir . $unique_name;

        if (move_uploaded_file($tmp_name, $destination)) {
            $fichiers[$champ] = $destination;
        } else {
            $errors[] = "Erreur lors de l'upload du fichier $original_name.";
        }
    } elseif (isset($_FILES[$champ]) && $_FILES[$champ]['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "Erreur d'upload pour le champ $champ.";
    }
}

$json_response = ($_POST['ajax'] ?? '') === '1';

if (empty($errors)) {
    $data = [
        'date'       => date('Y-m-d H:i:s'),
        'nom'        => $nom,
        'prenom'     => $prenom,
        'email'      => $email,
        'telephone'  => $telephone,
        'naissance'  => $naissance,
        'programme'  => $programme,
        'niveau'     => $niveau,
        'motivation' => $motivation,
        'competences' => $competences,
        'fichiers'   => $fichiers,
    ];

    $json_file = 'candidatures.json';
    $candidatures = [];
    if (file_exists($json_file)) {
        $candidatures = json_decode(file_get_contents($json_file), true) ?? [];
    }
    $candidatures[] = $data;
    file_put_contents($json_file, json_encode($candidatures, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $success = true;
} else {
    $success = false;
}

if ($json_response) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'errors'  => $errors,
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature - Institut Nevil Prodige</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a1a;
            --bg-secondary: #12122a;
            --bg-card: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --text-primary: #f0f0f5;
            --text-secondary: #9898b8;
            --accent-1: #6c63ff;
            --accent-2: #ff6584;
            --gradient-accent: linear-gradient(135deg, #6c63ff, #ff6584);
            --gradient-success: linear-gradient(135deg, #43e97b, #38f9d7);
            --radius: 16px;
            --shadow-card: 0 8px 32px rgba(0,0,0,0.3);
            --transition: 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
            padding: 2rem;
            background-image: radial-gradient(circle at 20% 30%, rgba(108,99,255,0.08) 0%, transparent 50%),
                              radial-gradient(circle at 80% 70%, rgba(255,101,132,0.06) 0%, transparent 50%);
        }
        ::selection { background: var(--accent-1); color: #fff; }
        .message {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 3.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-card);
            animation: popIn 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.85) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .message .icon {
            font-size: 4.5rem;
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 90px; height: 90px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
            animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.2s both;
        }
        .icon-success { background: rgba(67,233,123,0.15); }
        .icon-error { background: rgba(255,101,132,0.15); }
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0); }
            50% { transform: scale(1.15); }
            100% { opacity: 1; transform: scale(1); }
        }
        .message h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .message p {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
        }
        .btn {
            display: inline-flex;
            align-items: center; gap: 0.5rem;
            margin-top: 1.5rem;
            padding: 0.85rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all var(--transition);
        }
        .btn-primary {
            background: var(--gradient-accent);
            color: #fff;
            box-shadow: 0 4px 20px rgba(108,99,255,0.3);
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(108,99,255,0.4); }
        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: var(--bg-card); border-color: var(--accent-1); }
        ul { list-style: none; margin-top: 1rem; }
        ul li {
            padding: 0.6rem 1rem;
            margin-bottom: 0.4rem;
            background: rgba(255,101,132,0.1);
            border: 1px solid rgba(255,101,132,0.2);
            border-radius: 8px;
            color: var(--accent-2);
            font-size: 0.9rem;
            text-align: left;
        }
        ul li::before { content: '! '; font-weight: 700; }
        @media (max-width: 480px) {
            .message { padding: 2rem; }
        }
    </style>
</head>
<body>
    <div class="message">
        <?php if ($success): ?>
            <div class="icon icon-success">&#10003;</div>
            <h1 style="background:var(--gradient-success);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Candidature envoyée !</h1>
            <p>Votre candidature a bien été enregistrée. Vous serez contacté pour la suite du processus une fois votre dossier validé.</p>
            <a href="institut.html" class="btn btn-primary">&#8592; Retour à l'accueil</a>
        <?php else: ?>
            <div class="icon icon-error">&#10007;</div>
            <h1 style="background:var(--gradient-accent);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Erreur dans le formulaire</h1>
            <p>Veuillez corriger les erreurs suivantes :</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="institut.html#candidature" class="btn btn-outline">&#8592; Retour au formulaire</a>
        <?php endif; ?>
    </div>
</body>
</html>
