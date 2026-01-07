<?php
require_once('bootstrap.php');

if (!isset($_POST['position'])) {
    echo '<p style="text-align:center; padding:20px;">Invalid request.</p>';
    exit;
}

$position = $_POST['position'];
$voterUsername = $_SESSION['MM_Username'] ?? null;

if (!$voterUsername) {
    echo '<p style="text-align:center; padding:20px; color:#f00;">Voter not authenticated.</p>';
    exit;
}

// Get voter details
try {
    $voterStmt = db_query('SELECT State, SenateZone, FedConstituency FROM voter WHERE UserName = :u LIMIT 1', [':u' => $voterUsername]);
    $voterData = db_fetch_assoc($voterStmt);
    if (!$voterData) {
        echo '<p style="text-align:center; padding:20px; color:#f00;">Voter not found.</p>';
        exit;
    }

    // Trim whitespace from voter data
    $voterData['State'] = trim($voterData['State'] ?? '');
    $voterData['SenateZone'] = trim($voterData['SenateZone'] ?? '');
    $voterData['FedConstituency'] = trim($voterData['FedConstituency'] ?? '');
} catch (Exception $e) {
    echo '<p style="text-align:center; padding:20px; color:#f00;">Error fetching voter data.</p>';
    exit;
}

// Build query based on position
$whereCondition = "ResultMode = 'Public' AND Position = :position";
$params = [':position' => $position];

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
    $stmt = db_query(
        "SELECT FirstName, OtherNames, PartyName, Image, Votes 
		 FROM contestant 
		 WHERE {$whereCondition}
		 ORDER BY Votes DESC",
        $params
    );

    $hasResults = false;
    while ($row = db_fetch_assoc($stmt)) {
        $hasResults = true;
        $img = htmlspecialchars($row['Image'] ?? '');
        $fname = htmlspecialchars($row['FirstName'] ?? '');
        $onames = htmlspecialchars($row['OtherNames'] ?? '');
        $party = htmlspecialchars($row['PartyName'] ?? '');
        $votes = htmlspecialchars($row['Votes'] ?? '0');

        echo '<div class="result-card">';
        echo '<img src="../Admin/ContestantsImages/' . $img . '" alt="' . $fname . '">';
        echo '<div class="result-body">';
        echo '<h3>' . $fname . '</h3>';
        echo '<p>' . $onames . '</p>';
        if ($party) echo '<small>' . $party . '</small>';
        echo '</div>';
        echo '<div class="result-votes">' . $votes . ' votes</div>';
        echo '</div>';
    }

    if (!$hasResults) {
        echo '<p style="text-align:center; padding:20px; background:#fff; display:inline-block; border-radius:8px;">No results available for this position in your location.</p>';
    }
} catch (Exception $e) {
    echo '<p style="text-align:center; padding:20px; color:#f00;">Error loading results.</p>';
}
