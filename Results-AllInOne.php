<?php require_once('Connections/db.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INEC | Election Results</title>
    <link rel="stylesheet" href="bootstrap.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            margin-bottom: 0;
        }

        .jumbotron {
            margin-bottom: 30px;
        }

        /* Tab Navigation */
        .results-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #ddd;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .results-tab-btn {
            padding: 12px 24px;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            color: #333;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .results-tab-btn:hover {
            background: #e8e8e8;
        }

        .results-tab-btn.active {
            background: white;
            color: #f60;
            border-bottom-color: #f60;
        }

        /* Tab Content */
        .results-tab-content {
            display: none;
        }

        .results-tab-content.active {
            display: block;
        }

        /* Presidential Results (No collapsible) */
        .pres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .pres-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .pres-card.winner {
            border-color: #22c55e;
            border-width: 3px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        .pres-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .pres-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .pres-body {
            padding: 16px;
        }

        .pres-body h4 {
            margin: 0 0 8px 0;
            color: #f60;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pres-body .party {
            color: #666;
            font-size: 0.95em;
            margin-bottom: 10px;
        }

        .pres-body .votes {
            font-weight: bold;
            font-size: 1.3em;
            color: #333;
        }

        .winner-badge {
            background: #22c55e;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7em;
            font-weight: bold;
        }

        /* State/Zone/Constituency Collapsible Sections */
        .geo-section {
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .geo-header {
            background: linear-gradient(135deg, #333 0%, #555 100%);
            color: white;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .geo-header:hover {
            background: linear-gradient(135deg, #444 0%, #666 100%);
        }

        .geo-header.active {
            background: linear-gradient(135deg, #1a5490 0%, #2d6ca8 100%);
        }

        .geo-header h3 {
            margin: 0;
            font-size: 1.2em;
            font-weight: 600;
        }

        .toggle-icon {
            transition: transform 0.3s;
        }

        .geo-header.active .toggle-icon {
            transform: rotate(180deg);
        }

        .geo-content {
            display: none;
            background: #f9f9fa;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .result-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .result-card.winner {
            border-color: #22c55e;
            border-width: 3px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .result-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .result-body {
            padding: 12px;
        }

        .result-body h4 {
            margin: 0 0 6px 0;
            font-size: 1em;
            color: #333;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .result-body .party {
            color: #666;
            font-size: 0.85em;
            margin-bottom: 8px;
        }

        .result-body .votes {
            font-weight: bold;
            font-size: 1.1em;
            color: #f60;
        }

        .result-body .location {
            color: #1a5490;
            font-size: 0.8em;
            font-weight: 600;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        .error-msg {
            padding: 40px 20px;
            text-align: center;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-default navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="Index.php">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-navbar">
                <ul class="nav navbar-nav">
                    <li><a href="HowToVote.html">How To Vote</a></li>
                    <li class="active"><a href="Results.php">Election Results</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="jumbotron">
                    <h1 class="text-center">Election Results</h1>
                    <p class="text-center">View all results organized by position</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="results-tabs">
            <button class="results-tab-btn active" onclick="switchTab(event, 'pres-tab')">Presidential</button>
            <button class="results-tab-btn" onclick="switchTab(event, 'gov-tab')">Governors</button>
            <button class="results-tab-btn" onclick="switchTab(event, 'sen-tab')">Senators</button>
            <button class="results-tab-btn" onclick="switchTab(event, 'mem-tab')">Representatives</button>
        </div>

        <!-- Presidential Results Tab -->
        <div id="pres-tab" class="results-tab-content active">
            <h2>Presidential Election Results</h2>
            <p style="margin-bottom: 30px; color: #666;">All candidates ordered by highest votes</p>
            <div class="pres-grid">
                <?php
                $query_Pres = "SELECT FirstName, OtherNames, PartyName, Image, Votes FROM contestant WHERE ResultMode='Public' AND Position='President' ORDER BY Votes DESC";
                try {
                    $Pres = db_query($query_Pres);
                    $pres_candidates = db_fetch_all($Pres);

                    if (count($pres_candidates) > 0) {
                        $totalPresVotes = array_sum(array_column($pres_candidates, 'Votes'));
                        $isFirst = true;
                        foreach ($pres_candidates as $candidate) {
                            $isWinner = $isFirst && $totalPresVotes > 0;
                ?>
                            <div class="pres-card <?php echo $isWinner ? 'winner' : ''; ?>">
                                <img src="Admin/ContestantsImages/<?php echo htmlspecialchars($candidate['Image']); ?>" alt="<?php echo htmlspecialchars($candidate['FirstName'] . ' ' . $candidate['OtherNames']); ?>">
                                <div class="pres-body">
                                    <h4>
                                        <?php echo htmlspecialchars($candidate['FirstName'] . ' ' . $candidate['OtherNames']); ?>
                                        <?php if ($isWinner): ?>
                                            <span class="winner-badge">WINNER</span>
                                        <?php endif; ?>
                                    </h4>
                                    <div class="party"><?php echo htmlspecialchars($candidate['PartyName']); ?></div>
                                    <div class="votes"><?php echo number_format($candidate['Votes']); ?> votes</div>
                                </div>
                            </div>
                <?php
                            $isFirst = false;
                        }
                    } else {
                        echo "<div class='error-msg'><p>No presidential results available</p></div>";
                    }
                } catch (Exception $e) {
                    echo "<div class='error-msg'><p>Error loading results: " . htmlspecialchars($e->getMessage()) . "</p></div>";
                }
                ?>
            </div>
        </div>

        <!-- Governors Results Tab -->
        <div id="gov-tab" class="results-tab-content">
            <h2>Governors Election Results</h2>
            <p style="margin-bottom: 30px; color: #666;">Results by state - click to expand</p>
            <div>
                <?php
                $query_States = "SELECT DISTINCT State FROM contestant WHERE ResultMode='Public' AND Position='Governor' ORDER BY State";
                try {
                    $States = db_query($query_States);
                    $stateId = 0;

                    while ($state_row = db_fetch_assoc($States)) {
                        $stateId++;
                        $stateName = $state_row['State'];

                        $query_Contestants = "SELECT FirstName, OtherNames, PartyName, State, Image, Votes FROM contestant WHERE ResultMode='Public' AND Position='Governor' AND State = :state ORDER BY Votes DESC";
                        $Contestants = db_query($query_Contestants, [':state' => $stateName]);
                        $contestants = db_fetch_all($Contestants);

                        if (count($contestants) > 0):
                ?>
                            <div class="geo-section">
                                <div class="geo-header" onclick="toggleGeo(this)">
                                    <h3><?php echo htmlspecialchars($stateName); ?> State</h3>
                                    <span class="toggle-icon">▼</span>
                                </div>
                                <div class="geo-content">
                                    <div class="results-grid">
                                        <?php
                                        $stateVotes = array_sum(array_column($contestants, 'Votes'));
                                        $isFirst = true;
                                        foreach ($contestants as $contestant):
                                            $isWinner = $isFirst && $stateVotes > 0;
                                        ?>
                                            <div class="result-card <?php echo $isWinner ? 'winner' : ''; ?>">
                                                <img src="Admin/ContestantsImages/<?php echo htmlspecialchars($contestant['Image']); ?>" alt="<?php echo htmlspecialchars($contestant['FirstName'] . ' ' . $contestant['OtherNames']); ?>">
                                                <div class="result-body">
                                                    <h4>
                                                        <?php echo htmlspecialchars($contestant['FirstName'] . ' ' . $contestant['OtherNames']); ?>
                                                        <?php if ($isWinner): ?>
                                                            <span class="winner-badge">WINNER</span>
                                                        <?php endif; ?>
                                                    </h4>
                                                    <div class="party"><?php echo htmlspecialchars($contestant['PartyName']); ?></div>
                                                    <div class="votes"><?php echo number_format($contestant['Votes']); ?> votes</div>
                                                </div>
                                            </div>
                                        <?php
                                            $isFirst = false;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            </div>
                <?php
                        endif;
                    }
                } catch (Exception $e) {
                    echo "<div class='error-msg'><p>Error loading results: " . htmlspecialchars($e->getMessage()) . "</p></div>";
                }
                ?>
            </div>
        </div>

        <!-- Senators Results Tab -->
        <div id="sen-tab" class="results-tab-content">
            <h2>Senatorial Election Results</h2>
            <p style="margin-bottom: 30px; color: #666;">Results by State - click to expand</p>
            <style>
                .zone-section {
                    margin-bottom: 24px;
                }

                .zone-label {
                    color: #1a5490;
                    font-weight: 600;
                    font-size: 1.1em;
                    padding: 12px 0;
                    border-bottom: 2px solid #1a5490;
                    margin-bottom: 16px;
                }
            </style>
            <div>
                <?php
                $query_States = "SELECT DISTINCT State FROM contestant WHERE ResultMode='Public' AND Position='Senator' ORDER BY State";
                try {
                    $States = db_query($query_States);
                    $stateId = 0;

                    while ($state_row = db_fetch_assoc($States)) {
                        $stateId++;
                        $stateName = $state_row['State'];

                        // Get all zones for this state
                        $query_Zones = "SELECT DISTINCT SenateZone FROM contestant WHERE ResultMode='Public' AND Position='Senator' AND State = :state ORDER BY SenateZone";
                        $Zones = db_query($query_Zones, [':state' => $stateName]);
                        $zones = db_fetch_all($Zones);

                        if (count($zones) > 0):
                ?>
                            <div class="geo-section">
                                <div class="geo-header" onclick="toggleGeo(this)">
                                    <h3><?php echo htmlspecialchars($stateName); ?> State</h3>
                                    <span class="toggle-icon">▼</span>
                                </div>
                                <div class="geo-content">
                                    <?php foreach ($zones as $zone): ?>
                                        <div class="zone-section">
                                            <div class="zone-label"><?php echo htmlspecialchars($zone['SenateZone']); ?></div>
                                            <div class="results-grid">
                                                <?php
                                                $query_Contestants = "SELECT FirstName, OtherNames, PartyName, State, SenateZone, Image, Votes FROM contestant WHERE ResultMode='Public' AND Position='Senator' AND State = :state AND SenateZone = :zone ORDER BY Votes DESC";
                                                $Contestants = db_query($query_Contestants, [':state' => $stateName, ':zone' => $zone['SenateZone']]);
                                                $contestants = db_fetch_all($Contestants);
                                                $zoneVotes = array_sum(array_column($contestants, 'Votes'));

                                                $isFirst = true;
                                                foreach ($contestants as $contestant):
                                                    $isWinner = $isFirst && $zoneVotes > 0;
                                                ?>
                                                    <div class="result-card <?php echo $isWinner ? 'winner' : ''; ?>">
                                                        <img src="Admin/ContestantsImages/<?php echo htmlspecialchars($contestant['Image']); ?>" alt="<?php echo htmlspecialchars($contestant['FirstName'] . ' ' . $contestant['OtherNames']); ?>">
                                                        <div class="result-body">
                                                            <h4>
                                                                <?php echo htmlspecialchars($contestant['FirstName'] . ' ' . $contestant['OtherNames']); ?>
                                                                <?php if ($isWinner): ?>
                                                                    <span class="winner-badge">WINNER</span>
                                                                <?php endif; ?>
                                                            </h4>
                                                            <div class="party"><?php echo htmlspecialchars($contestant['PartyName']); ?></div>
                                                            <div class="votes"><?php echo number_format($contestant['Votes']); ?> votes</div>
                                                            <div class="location">📍 <?php echo htmlspecialchars($contestant['SenateZone']); ?></div>
                                                        </div>
                                                    </div>
                                                <?php
                                                    $isFirst = false;
                                                endforeach;
                                                ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                <?php
                        endif;
                    }
                } catch (Exception $e) {
                    echo "<div class='error-msg'><p>Error loading results: " . htmlspecialchars($e->getMessage()) . "</p></div>";
                }
                ?>
            </div>
        </div>

        <!-- Representatives Results Tab -->
        <div id="mem-tab" class="results-tab-content">
            <h2>Representatives Election Results</h2>
            <p style="margin-bottom: 30px; color: #666;">Results by State - click to expand</p>
            <div>
                <?php
                $query_States = "SELECT DISTINCT State FROM contestant WHERE ResultMode='Public' AND Position='Member' ORDER BY State";
                try {
                    $States = db_query($query_States);
                    $stateId = 0;

                    while ($state_row = db_fetch_assoc($States)) {
                        $stateId++;
                        $stateName = $state_row['State'];

                        // Get all constituencies for this state
                        $query_Constituencies = "SELECT DISTINCT FedConstituency FROM contestant WHERE ResultMode='Public' AND Position='Member' AND State = :state ORDER BY FedConstituency";
                        $Constituencies = db_query($query_Constituencies, [':state' => $stateName]);
                        $constituencies = db_fetch_all($Constituencies);

                        if (count($constituencies) > 0):
                ?>
                            <div class="geo-section">
                                <div class="geo-header" onclick="toggleGeo(this)">
                                    <h3><?php echo htmlspecialchars($stateName); ?> State</h3>
                                    <span class="toggle-icon">▼</span>
                                </div>
                                <div class="geo-content">
                                    <?php foreach ($constituencies as $constituency): ?>
                                        <div class="zone-section">
                                            <div class="zone-label"><?php echo htmlspecialchars($constituency['FedConstituency']); ?></div>
                                            <div class="results-grid">
                                                <?php
                                                $query_Contestants = "SELECT FirstName, OtherNames, PartyName, State, FedConstituency, Image, Votes FROM contestant WHERE ResultMode='Public' AND Position='Member' AND State = :state AND FedConstituency = :const ORDER BY Votes DESC";
                                                $Contestants = db_query($query_Contestants, [':state' => $stateName, ':const' => $constituency['FedConstituency']]);
                                                $contestants = db_fetch_all($Contestants);
                                                $constVotes = array_sum(array_column($contestants, 'Votes'));

                                                $isFirst = true;
                                                foreach ($contestants as $contestant):
                                                    $isWinner = $isFirst && $constVotes > 0;
                                                ?>
                                                    <div class="result-card <?php echo $isWinner ? 'winner' : ''; ?>">
                                                        <img src="Admin/ContestantsImages/<?php echo htmlspecialchars($contestant['Image']); ?>" alt="<?php echo htmlspecialchars($contestant['FirstName'] . ' ' . $contestant['OtherNames']); ?>">
                                                        <div class="result-body">
                                                            <h4>
                                                                <?php echo htmlspecialchars($contestant['FirstName'] . ' ' . $contestant['OtherNames']); ?>
                                                                <?php if ($isWinner): ?>
                                                                    <span class="winner-badge">WINNER</span>
                                                                <?php endif; ?>
                                                            </h4>
                                                            <div class="party"><?php echo htmlspecialchars($contestant['PartyName']); ?></div>
                                                            <div class="votes"><?php echo number_format($contestant['Votes']); ?> votes</div>
                                                            <div class="location">📍 <?php echo htmlspecialchars($contestant['FedConstituency']); ?></div>
                                                        </div>
                                                    </div>
                                                <?php
                                                    $isFirst = false;
                                                endforeach;
                                                ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                <?php
                        endif;
                    }
                } catch (Exception $e) {
                    echo "<div class='error-msg'><p>Error loading results: " . htmlspecialchars($e->getMessage()) . "</p></div>";
                }
                ?>
            </div>
        </div>

        <div style="height: 60px;"></div>
    </div>

    <script src="jquery-1.11.3.min.js"></script>
    <script src="bootstrap.js"></script>
    <script>
        function switchTab(e, tabId) {
            // Hide all tabs
            const allTabs = document.querySelectorAll('.results-tab-content');
            allTabs.forEach(tab => tab.classList.remove('active'));

            // Remove active from all buttons
            const allBtns = document.querySelectorAll('.results-tab-btn');
            allBtns.forEach(btn => btn.classList.remove('active'));

            // Show selected tab
            document.getElementById(tabId).classList.add('active');
            e.target.classList.add('active');

            // Scroll to top of content
            window.scrollTo({
                top: 200,
                behavior: 'smooth'
            });
        }

        function toggleGeo(header) {
            const content = header.nextElementSibling;
            const isVisible = content.style.display === 'block';

            // Close all sections
            document.querySelectorAll('.geo-content').forEach(c => c.style.display = 'none');
            document.querySelectorAll('.geo-header').forEach(h => h.classList.remove('active'));

            // Toggle current
            if (!isVisible) {
                content.style.display = 'block';
                header.classList.add('active');
                content.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }
    </script>

</body>

</html>