<?php
require_once('bootstrap.php');

if (!isset($_POST['position'])) {
    die('No position specified');
}

$position = $_POST['position'];
$voterUsername = $_SESSION['MM_Username'] ?? null;

if (!$voterUsername) {
    die('Voter not authenticated');
}

// Get voter details
try {
    $voterStmt = db_query('SELECT State, SenateZone, FedConstituency FROM voter WHERE UserName = :u LIMIT 1', [':u' => $voterUsername]);
    $voterData = db_fetch_assoc($voterStmt);
    if (!$voterData) {
        die('Voter not found');
    }

    // Trim whitespace from voter data
    $voterData['State'] = trim($voterData['State'] ?? '');
    $voterData['SenateZone'] = trim($voterData['SenateZone'] ?? '');
    $voterData['FedConstituency'] = trim($voterData['FedConstituency'] ?? '');
} catch (Exception $e) {
    die('Error fetching voter: ' . htmlspecialchars($e->getMessage()));
}

// Build query based on position
$whereCondition = "Position = :pos";
$params = [':pos' => $position];

switch ($position) {
    case 'President':
        // All presidential candidates (national level)
        break;
    case 'Governor':
        // Filter by voter's state
        $whereCondition .= " AND TRIM(State) = :state";
        $params[':state'] = $voterData['State'];
        break;
    case 'Senator':
        // Filter by voter's state AND senate zone
        $whereCondition .= " AND TRIM(State) = :state AND TRIM(SenateZone) = :zone";
        $params[':state'] = $voterData['State'];
        $params[':zone'] = $voterData['SenateZone'];
        break;
    case 'Member':
        // Filter by voter's state AND federal constituency
        $whereCondition .= " AND TRIM(State) = :state AND TRIM(FedConstituency) = :fedconst";
        $params[':state'] = $voterData['State'];
        $params[':fedconst'] = $voterData['FedConstituency'];
        break;
}

try {
    $stmt = db_query("SELECT * FROM contestant WHERE {$whereCondition} ORDER BY FirstName", $params);

    $count = 0;
    while ($r = db_fetch_assoc($stmt)) {
        $count++;
        $image = $r['Image'];
        $id = $r['ContestantID'];
        $fullName = htmlspecialchars($r['FirstName'] . ' ' . $r['OtherNames']);
        $party = isset($r['PartyName']) ? htmlspecialchars($r['PartyName']) : '';

        echo '<div class="tile candidate-card" data-candidate-id="' . htmlspecialchars($id) . '" data-state="normal">';
        echo '<img src="../Admin/ContestantsImages/' . htmlspecialchars($image) . '" alt="' . $fullName . '">';
        echo '<div class="cand-name">' . $fullName . '</div>';
        if ($party) {
            echo '<div class="cand-party">' . $party . '</div>';
        }
        echo '<div class="card-actions" style="display:none; margin-top:12px; padding-top:12px; border-top:1px solid #ddd;">';
        echo '<button class="btn-vote" style="width:100%; padding:10px; background:#4CAF50; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Vote</button>';
        echo '</div>';
        echo '<div class="card-confirm" style="display:none; margin-top:12px; padding:12px; background:#fff3cd; border-radius:6px; text-align:center;">';
        echo '<p style="margin:0 0 10px 0; font-weight:600; color:#856404;">Are you sure this is your final choice?</p>';
        echo '<button class="btn-yes" style="padding:8px 20px; background:#28a745; color:#fff; border:none; border-radius:4px; margin-right:8px; cursor:pointer; font-weight:600;">Yes</button>';
        echo '<button class="btn-no" style="padding:8px 20px; background:#dc3545; color:#fff; border:none; border-radius:4px; cursor:pointer; font-weight:600;">No</button>';
        echo '</div>';
        echo '<div class="card-result" style="display:none; margin-top:12px; padding:12px; border-radius:6px; text-align:center;"></div>';
        echo '</div>';
    }

    // Debug info when no candidates found
    if ($count === 0) {
        echo '<div style="padding:20px; color:#666;">';
        echo '<strong>No candidates found.</strong><br>';
        echo 'Position: ' . htmlspecialchars($position) . '<br>';
        if ($position === 'Senator') {
            echo 'Voter State: ' . htmlspecialchars($voterData['State']) . '<br>';
            echo 'Voter SenateZone: ' . htmlspecialchars($voterData['SenateZone']) . '<br>';
        } elseif ($position === 'Member') {
            echo 'Voter State: ' . htmlspecialchars($voterData['State']) . '<br>';
            echo 'Voter FedConstituency: ' . htmlspecialchars($voterData['FedConstituency']) . '<br>';
        }
        echo '</div>';
    }
} catch (Exception $e) {
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
