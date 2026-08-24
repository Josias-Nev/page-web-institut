<?php
$json_file = 'candidatures.json';
$candidatures = [];
if (file_exists($json_file)) {
    $candidatures = json_decode(file_get_contents($json_file), true) ?? [];
}
$programme_labels = [
    'sciences' => 'Sciences',
    'economiques' => 'Sciences Économiques',
    'lettres' => 'Lettres',
    'droit' => 'Droit',
    'ingenierie' => 'Ingénierie',
    'arts' => 'Arts',
];
$niveau_labels = [
    'licence1' => 'Licence 1', 'licence2' => 'Licence 2', 'licence3' => 'Licence 3',
    'master1' => 'Master 1', 'master2' => 'Master 2', 'doctorat' => 'Doctorat',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Candidatures Institut Nevil Prodige</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a1a;
            --bg-secondary: #12122a;
            --bg-card: rgba(255,255,255,0.04);
            --bg-card-hover: rgba(255,255,255,0.07);
            --border: rgba(255,255,255,0.08);
            --text-primary: #f0f0f5;
            --text-secondary: #9898b8;
            --text-muted: #6a6a8a;
            --accent-1: #6c63ff;
            --accent-2: #ff6584;
            --accent-3: #43e97b;
            --gradient-accent: linear-gradient(135deg, #6c63ff, #ff6584);
            --gradient-header: linear-gradient(135deg, #0a0a1a, #1a1a4e);
            --radius: 16px;
            --radius-sm: 10px;
            --shadow-card: 0 8px 32px rgba(0,0,0,0.3);
            --transition: 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            padding: 2rem;
            min-height: 100vh;
        }
        ::selection { background: var(--accent-1); color: #fff; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--accent-1); border-radius: 10px; }

        /* Header */
        .admin-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            backdrop-filter: blur(20px);
        }
        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .admin-header .count {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .admin-header .count strong {
            color: var(--accent-1);
            -webkit-text-fill-color: var(--accent-1);
            font-size: 1.2rem;
        }
        .admin-actions { display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap; }

        /* Search */
        .search-box {
            position: relative;
        }
        .search-box input {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 0.6rem 1.2rem 0.6rem 2.5rem;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.85rem;
            width: 220px;
            transition: all var(--transition);
        }
        .search-box input:focus {
            outline: none;
            border-color: var(--accent-1);
            box-shadow: 0 0 0 3px rgba(108,99,255,0.15);
            width: 280px;
        }
        .search-box input::placeholder { color: var(--text-muted); }
        .search-box::before {
            content: '\01F50D';
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .btn {
            display: inline-flex;
            align-items: center; gap: 0.4rem;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all var(--transition);
        }
        .btn-primary {
            background: var(--gradient-accent);
            color: #fff;
            box-shadow: 0 4px 15px rgba(108,99,255,0.25);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(108,99,255,0.35); }
        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: var(--bg-card); border-color: var(--accent-1); }

        /* Table */
        .table-wrapper {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-card);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: var(--gradient-header);
        }
        th {
            padding: 1rem 1.2rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 1rem 1.2rem;
            font-size: 0.9rem;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
        }
        tr:last-child td { border-bottom: none; }
        tbody tr {
            transition: all var(--transition);
            animation: rowIn 0.4s ease both;
        }
        tbody tr:hover { background: var(--bg-card-hover); }
        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        tbody tr:nth-child(1) { animation-delay: 0.02s; }
        tbody tr:nth-child(2) { animation-delay: 0.04s; }
        tbody tr:nth-child(3) { animation-delay: 0.06s; }
        tbody tr:nth-child(4) { animation-delay: 0.08s; }
        tbody tr:nth-child(5) { animation-delay: 0.1s; }
        tbody tr:nth-child(6) { animation-delay: 0.12s; }
        tbody tr:nth-child(7) { animation-delay: 0.14s; }
        tbody tr:nth-child(8) { animation-delay: 0.16s; }
        tbody tr:nth-child(9) { animation-delay: 0.18s; }
        tbody tr:nth-child(10) { animation-delay: 0.2s; }

        .badge-programme {
            display: inline-block;
            padding: 0.25rem 0.8rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 500;
            background: rgba(108,99,255,0.15);
            color: var(--accent-1);
            border: 1px solid rgba(108,99,255,0.2);
        }
        .badge-niveau {
            display: inline-block;
            padding: 0.25rem 0.8rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 500;
            background: rgba(67,233,123,0.12);
            color: var(--accent-3);
            border: 1px solid rgba(67,233,123,0.2);
        }
        .fichiers a {
            display: inline-block;
            padding: 0.25rem 0.7rem;
            border-radius: 6px;
            font-size: 0.75rem;
            text-decoration: none;
            background: rgba(108,99,255,0.1);
            color: var(--accent-1);
            border: 1px solid rgba(108,99,255,0.15);
            margin: 0.1rem;
            transition: all var(--transition);
        }
        .fichiers a:hover { background: rgba(108,99,255,0.2); }

        .details-btn {
            background: transparent;
            color: var(--accent-1);
            border: 1px solid rgba(108,99,255,0.3);
            padding: 0.35rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-family: inherit;
            font-weight: 500;
            transition: all var(--transition);
        }
        .details-btn:hover {
            background: var(--accent-1);
            color: #fff;
        }

        .empty {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
        .empty .icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
        .empty p { font-size: 1rem; }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .modal.show { display: flex; }
        .modal-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: var(--shadow-card);
            animation: modalIn 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-content .close {
            position: absolute;
            top: 1.2rem;
            right: 1.5rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
            transition: color var(--transition);
            background: none;
            border: none;
        }
        .modal-content .close:hover { color: var(--text-primary); }
        .modal-content h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .modal-content p {
            margin-bottom: 0.7rem;
            line-height: 1.7;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        .modal-content p strong {
            color: var(--text-primary);
            font-weight: 600;
        }
        .modal-content .field-section {
            margin-top: 1.2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
        .modal-content .field-section:first-of-type { border-top: none; margin-top: 0; padding-top: 0; }

        @media (max-width: 768px) {
            body { padding: 1rem; }
            .admin-header { flex-direction: column; align-items: stretch; text-align: center; padding: 1.5rem; }
            .admin-actions { justify-content: center; }
            .search-box input { width: 100%; }
            .search-box input:focus { width: 100%; }
            .table-wrapper { overflow-x: auto; }
            table { font-size: 0.8rem; min-width: 700px; }
            th, td { padding: 0.7rem 0.8rem; }
            .modal-content { padding: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="admin-header">
        <div>
            <h1>&#128221; Tableau de bord</h1>
            <p class="count"><strong><?= count($candidatures) ?></strong> candidature(s) reçue(s)</p>
        </div>
        <div class="admin-actions">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Rechercher..." oninput="filterTable()">
            </div>
            <a href="institut.html" class="btn btn-outline">&#8592; Site</a>
            <a href="admin.php" class="btn btn-primary">&#8635; Actualiser</a>
        </div>
    </div>

    <?php if (empty($candidatures)): ?>
        <div class="table-wrapper">
            <div class="empty">
                <span class="icon">&#128196;</span>
                <p>Aucune candidature pour le moment.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Programme</th>
                        <th>Niveau</th>
                        <th>Fichiers</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($candidatures as $i => $c): ?>
                    <tr data-index="<?= $i ?>">
                        <td style="color:var(--text-muted);font-size:0.82rem;"><?= htmlspecialchars($c['date'] ?? '') ?></td>
                        <td><strong><?= htmlspecialchars($c['nom'] ?? '') ?></strong></td>
                        <td><?= htmlspecialchars($c['prenom'] ?? '') ?></td>
                        <td style="color:var(--accent-1);"><?= htmlspecialchars($c['email'] ?? '') ?></td>
                        <td style="color:var(--text-secondary);"><?= htmlspecialchars($c['telephone'] ?? '') ?></td>
                        <td><span class="badge-programme"><?= htmlspecialchars($programme_labels[$c['programme'] ?? ''] ?? $c['programme'] ?? '') ?></span></td>
                        <td><span class="badge-niveau"><?= htmlspecialchars($niveau_labels[$c['niveau'] ?? ''] ?? $c['niveau'] ?? '') ?></span></td>
                        <td class="fichiers">
                            <?php if (!empty($c['fichiers'])): ?>
                                <?php foreach ($c['fichiers'] as $key => $path): ?>
                                    <a href="<?= htmlspecialchars($path) ?>" target="_blank"><?= $key === 'releves_notes' ? 'Notes' : 'Docs' ?></a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:var(--text-muted);font-size:0.8rem;">-</span>
                            <?php endif; ?>
                        </td>
                        <td><button class="details-btn" onclick="showDetails(<?= $i ?>)">Détails</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div id="modal" class="modal">
        <div class="modal-content">
            <button class="close" onclick="document.getElementById('modal').classList.remove('show')">&times;</button>
            <h2>Détails de la candidature</h2>
            <div id="modal-body"></div>
        </div>
    </div>

    <script>
        const candidatures = <?= json_encode($candidatures, JSON_UNESCAPED_UNICODE) ?>;
        const progLabels = <?= json_encode($programme_labels, JSON_UNESCAPED_UNICODE) ?>;
        const nivLabels = <?= json_encode($niveau_labels, JSON_UNESCAPED_UNICODE) ?>;

        function showDetails(index) {
            const c = candidatures[index];
            if (!c) return;
            const fichiers = c.fichiers ? Object.entries(c.fichiers).map(([k, v]) =>
                `<a href="${v}" target="_blank" style="color:var(--accent-1);text-decoration:none;font-weight:500;">${k === 'releves_notes' ? 'Relevés de notes' : 'Documents académiques'}</a>`
            ).join(' &nbsp;|&nbsp; ') : '<span style="color:var(--text-muted)">Aucun</span>';

            const html = `
                <div class="field-section">
                    <p><strong>Date :</strong> ${c.date || '—'}</p>
                    <p><strong>Nom :</strong> ${c.nom || '—'}</p>
                    <p><strong>Prénom :</strong> ${c.prenom || '—'}</p>
                    <p><strong>Email :</strong> ${c.email || '—'}</p>
                    <p><strong>Téléphone :</strong> ${c.telephone || '—'}</p>
                    <p><strong>Date de naissance :</strong> ${c.naissance || '—'}</p>
                </div>
                <div class="field-section">
                    <p><strong>Programme :</strong> ${progLabels[c.programme] || c.programme || '—'}</p>
                    <p><strong>Niveau :</strong> ${nivLabels[c.niveau] || c.niveau || '—'}</p>
                </div>
                <div class="field-section">
                    <p><strong>Motivation :</strong><br>${c.motivation || '—'}</p>
                </div>
                <div class="field-section">
                    <p><strong>Compétences :</strong><br>${c.competences || '—'}</p>
                </div>
                <div class="field-section">
                    <p><strong>Fichiers :</strong> ${fichiers}</p>
                </div>
            `;
            document.getElementById('modal-body').innerHTML = html;
            document.getElementById('modal').classList.add('show');
        }

        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });

        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#tableBody tr').forEach(row => {
                const txt = row.textContent.toLowerCase();
                row.style.display = txt.includes(q) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
