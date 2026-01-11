<?php
require_once('bootstrap.php');
require_once('access_control.php');

$uname = isset($_SESSION['MM_Username']) ? $_SESSION['MM_Username'] : null;
if (!$uname) {
  die('Not authenticated');
}

if (!isset($_POST['id'])) {
  die('No candidate selected');
}

$contestantId = (int) $_POST['id'];

try {
  $pdo = db();
  $pdo->beginTransaction();

  // Fetch contestant
  $stmt = db_query('SELECT ContestantID, FirstName, OtherNames, Position, PartyName, Votes FROM contestant WHERE ContestantID = :cid', [':cid' => $contestantId]);
  $contestant = db_fetch_assoc($stmt);
  if (!$contestant) {
    throw new RuntimeException('Contestant not found');
  }

  $Name = $contestant['FirstName'] . ' ' . $contestant['OtherNames'];
  $Position = $contestant['Position'];
  $ContestantID = $contestant['ContestantID'];
  $PartyName = $contestant['PartyName'];

  // Fetch or create aggregate votes row
  $stmt = db_query('SELECT Votes FROM votesresults WHERE ContestantID = :cid', [':cid' => $ContestantID]);
  $votesRow = db_fetch_assoc($stmt);
  $votes = $votesRow ? intval($votesRow['Votes']) : 0;

  // Fetch voter id
  $stmt = db_query('SELECT VoterID FROM voter WHERE UserName = :uname', [':uname' => $uname]);
  $voterRow = db_fetch_assoc($stmt);
  if (!$voterRow) {
    throw new RuntimeException('Voter not found');
  }
  $voterId = $voterRow['VoterID'];

  // Check if voter already voted for this position
  $stmt = db_query('SELECT 1 FROM votes WHERE VoterID = :vid AND Position = :pos', [':vid' => $voterId, ':pos' => $Position]);
  $already = db_fetch_assoc($stmt);
  if ($already) {
    $pdo->rollBack();
    // Set flash message for already voted with different type
    if (!isset($_SESSION['vote_flash'])) {
      $_SESSION['vote_flash'] = [];
    }
    $_SESSION['vote_flash'][] = ['message' => "You have already voted for {$Position}", 'type' => 'warning'];
    echo "Oops.. Sorry, You have already voted for " . $Position;
    exit;
  }

  // Increment aggregate votes and contestant votes
  $votes += 1;
  if ($votesRow) {
    db_query('UPDATE votesresults SET Votes = :votes, ContestantName = :name, PartyName = :party WHERE ContestantID = :cid', [':votes' => $votes, ':name' => $Name, ':party' => $PartyName, ':cid' => $ContestantID]);
  } else {
    db_query('INSERT INTO votesresults (ContestantID, ContestantName, Votes, PartyName) VALUES (:cid, :name, :votes, :party)', [':cid' => $ContestantID, ':name' => $Name, ':votes' => $votes, ':party' => $PartyName]);
  }

  db_query('INSERT INTO votes (VoterID, Position, ContestantID) VALUES (:vid, :pos, :cid)', [':vid' => $voterId, ':pos' => $Position, ':cid' => $ContestantID]);

  db_query('UPDATE contestant SET Votes = Votes + 1 WHERE ContestantID = :cid', [':cid' => $ContestantID]);

  $pdo->commit();

  // Track voted position in session
  if (!isset($_SESSION['voted_positions'])) {
    $_SESSION['voted_positions'] = [];
  }
  $_SESSION['voted_positions'][] = $Position;

  // Set flash message
  if (!isset($_SESSION['vote_flash'])) {
    $_SESSION['vote_flash'] = [];
  }
  $_SESSION['vote_flash'][] = ['message' => "Successfully voted for {$Position}: {$Name}", 'type' => 'success'];

  echo "Your vote has been cast. Thank You!";
} catch (Exception $e) {
  if (isset($pdo) && $pdo->inTransaction()) {
    $pdo->rollBack();
  }
  error_log('castVote error: ' . $e->getMessage());
  die('An error occurred while casting your vote.');
}
