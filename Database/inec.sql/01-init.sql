-- INEC Election System Database Initialization
-- This file automatically initializes the database schema on container startup

USE inec;

-- Create Candidates table
CREATE TABLE IF NOT EXISTS candidates (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  image_path VARCHAR(255),
  party VARCHAR(100),
  position VARCHAR(100),
  biography TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Voters table
CREATE TABLE IF NOT EXISTS voters (
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) UNIQUE,
  phone VARCHAR(20),
  voter_id VARCHAR(50) UNIQUE NOT NULL,
  image_path VARCHAR(255),
  has_voted BOOLEAN DEFAULT FALSE,
  voted_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Staff table
CREATE TABLE IF NOT EXISTS staff (
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) UNIQUE,
  phone VARCHAR(20),
  role VARCHAR(100),
  image_path VARCHAR(255),
  password_hash VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Votes table
CREATE TABLE IF NOT EXISTS votes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  voter_id INT NOT NULL,
  candidate_id INT NOT NULL,
  voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (voter_id) REFERENCES voters(id) ON DELETE CASCADE,
  FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
  UNIQUE KEY unique_voter_vote (voter_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Polling Units table
CREATE TABLE IF NOT EXISTS polling_units (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  location VARCHAR(255),
  state VARCHAR(100),
  local_government VARCHAR(100),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Results table
CREATE TABLE IF NOT EXISTS results (
  id INT PRIMARY KEY AUTO_INCREMENT,
  candidate_id INT NOT NULL,
  polling_unit_id INT,
  total_votes INT DEFAULT 0,
  percentage DECIMAL(5, 2) DEFAULT 0,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
  FOREIGN KEY (polling_unit_id) REFERENCES polling_units(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create sample data for candidates
INSERT INTO candidates (name, party, position, biography) VALUES
('Chioma Adeleke', 'PDP', 'Presidential', 'Experienced politician with 20 years in public service'),
('Bola Tinubu', 'APC', 'Presidential', 'Successful businessman and former governor'),
('Peter Obi', 'LP', 'Presidential', 'Tech entrepreneur and development advocate'),
('Rabiu Kwankwaso', 'NNPP', 'Presidential', 'Former governor and military officer');

-- Create sample polling units
INSERT INTO polling_units (name, location, state, local_government) VALUES
('Polling Unit 001', 'Ward 1', 'Lagos', 'Lagos Island'),
('Polling Unit 002', 'Ward 2', 'Lagos', 'Lagos Mainland'),
('Polling Unit 003', 'Ward 1', 'Abuja', 'Gwagwalada'),
('Polling Unit 004', 'Ward 2', 'Kano', 'Kano Municipal');

-- Create indexes for better performance
CREATE INDEX idx_voters_voter_id ON voters(voter_id);
CREATE INDEX idx_voters_has_voted ON voters(has_voted);
CREATE INDEX idx_candidates_party ON candidates(party);
CREATE INDEX idx_votes_voter_id ON votes(voter_id);
CREATE INDEX idx_votes_candidate_id ON votes(candidate_id);
CREATE INDEX idx_results_candidate_id ON results(candidate_id);

-- Grant permissions to inec_user
GRANT ALL PRIVILEGES ON inec.* TO 'inec_user'@'%';
FLUSH PRIVILEGES;
