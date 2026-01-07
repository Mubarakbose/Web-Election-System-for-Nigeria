<?php
require_once('bootstrap.php');
require_once('access_control.php');
require_once('logout.php');

// Single, consistent staff lookup
$row_Staff = StaffContext::current();

// Initialize dropdown collections
$stateRows = [];
$fedConstRows = [];
$stateConstRows = [];
// Fetch distinct lists for dropdowns with state mapping
try {
  $stateRows = db_fetch_all(db_query("SELECT DISTINCT State FROM contestant ORDER BY State"));
  // Get Federal Constituencies grouped by state
  $fedConstQuery = db_query("SELECT DISTINCT State, FedConstituency FROM contestant WHERE FedConstituency IS NOT NULL AND FedConstituency != '' ORDER BY State, FedConstituency");
  $fedConstByState = [];
  while ($row = db_fetch_assoc($fedConstQuery)) {
    $state = trim($row['State']);
    $fedConst = trim($row['FedConstituency']);
    if (!isset($fedConstByState[$state])) {
      $fedConstByState[$state] = [];
    }
    if (!in_array($fedConst, $fedConstByState[$state])) {
      $fedConstByState[$state][] = $fedConst;
    }
  }

  // Get State Constituencies grouped by state
  $stateConstQuery = db_query("SELECT DISTINCT State, StateConstituency FROM contestant WHERE StateConstituency IS NOT NULL AND StateConstituency != '' ORDER BY State, StateConstituency");
  $stateConstByState = [];
  while ($row = db_fetch_assoc($stateConstQuery)) {
    $state = trim($row['State']);
    $stateConst = trim($row['StateConstituency']);
    if (!isset($stateConstByState[$state])) {
      $stateConstByState[$state] = [];
    }
    if (!in_array($stateConst, $stateConstByState[$state])) {
      $stateConstByState[$state][] = $stateConst;
    }
  }
} catch (Exception $e) {
  ErrorHandler::handle($e, 'AddVoters dropdowns');
  $fedConstByState = [];
  $stateConstByState = [];
}

// Date bounds for age validation (18+)
$maxBirthDate = date('Y-m-d', strtotime('-18 years'));
$minBirthDate = '1900-01-01';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Add Voters</title>
  <link rel="stylesheet" href="PStaff CSS/unified-responsive.css" type="text/css">
  <link rel="stylesheet" href="PStaff CSS/admin-form-override.css" type="text/css">
  <link rel="stylesheet" href="PStaff CSS/footer-modern.css" type="text/css">
</head>

<body>
  <?php
  $staffFirstName = $row_Staff['FirstName'];
  $currentPage = 'AddVoters.php';
  include('header.php');
  ?>
  <div class="body">
    <div class="register">
      <h2>register voters here!</h2>
      <?php echo FlashRenderer::renderAll(); ?>
      <form action="Scripts/AddVoterScript.php" method="POST" enctype="multipart/form-data" name="Add Voter" id="Add Voter" class="form-container">
        <div id="formMessages"></div>
        <fieldset>
          <legend>Add New Voter Here</legend>

          <div class="form-row">
            <label for="FirstName" class="form-label">First Name <span class="required">*</span></label>
            <div class="form-control">
              <input type="text" name="FirstName" id="FirstName" required minlength="3" maxlength="20" aria-required="true">
            </div>
          </div>

          <div class="form-row">
            <label for="OtherName" class="form-label">Other Name(s) <span class="required">*</span></label>
            <div class="form-control">
              <input type="text" name="OtherName" id="OtherName" required minlength="3" maxlength="40" aria-required="true">
            </div>
          </div>

          <div class="form-row">
            <label for="BirthDate" class="form-label">Birth Date <span class="required">*</span></label>
            <div class="form-control">
              <input type="date" name="BirthDate" id="BirthDate" required form="Add Voter" max="<?php echo $maxBirthDate; ?>" min="<?php echo $minBirthDate; ?>" aria-required="true">
            </div>
          </div>

          <div class="form-row">
            <label for="Phone" class="form-label">Phone Number <span class="required">*</span></label>
            <div class="form-control">
              <input type="tel" name="Phone" id="Phone" placeholder="e.g. +2348112345678" required minlength="11" maxlength="16" pattern="[0-9+]{11,16}" inputmode="tel" aria-required="true">
            </div>
          </div>

          <div class="form-row">
            <label for="Email" class="form-label">Email <span class="required">*</span></label>
            <div class="form-control">
              <input type="email" name="Email" id="Email" placeholder="e.g. example@abc.com" required aria-required="true">
            </div>
          </div>

          <div class="form-row">
            <label for="Gender" class="form-label">Gender <span class="required">*</span></label>
            <div class="form-control">
              <select name="Gender" id="Gender" required aria-required="true">
                <option value="">Please Select One</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <label for="State" class="form-label">State <span class="required">*</span></label>
            <div class="form-control">
              <select name="State" id="State" required aria-required="true">
                <option value="">Please Select One</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <label for="LGA" class="form-label">Local Government Area <span class="required">*</span></label>
            <div class="form-control">
              <select name="LGA" id="LGA" required aria-required="true" disabled>
                <option value="">Select a state first</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <label for="PostCode" class="form-label">Post Code <span class="required">*</span></label>
            <div class="form-control">
              <input type="text" name="PostCode" id="PostCode" required minlength="6" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" aria-required="true" title="6-digit postal code" placeholder="123456">
            </div>
          </div>

          <div class="form-row">
            <label for="HomeAddress" class="form-label">Home Address <span class="required">*</span></label>
            <div class="form-control">
              <textarea name="HomeAddress" id="HomeAddress" required minlength="5" maxlength="255" aria-required="true" rows="4"></textarea>
            </div>
          </div>

          <div class="form-row">
            <label for="SenateZone" class="form-label">Senatorial Zone <span class="required">*</span></label>
            <div class="form-control">
              <select name="SenateZone" id="SenateZone" required aria-required="true">
                <option value="">Please Select One</option>
                <option value="Central">Central</option>
                <option value="East">East</option>
                <option value="North">North</option>
                <option value="South">South</option>
                <option value="West">West</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <label for="FedConstituency" class="form-label">Federal Constituency <span class="required">*</span></label>
            <div class="form-control">
              <select name="FedConstituency" id="FedConstituency" required aria-required="true" disabled>
                <option value="">Select a state first</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <label for="StateConstituency" class="form-label">State Constituency <span class="required">*</span></label>
            <div class="form-control">
              <select name="StateConstituency" id="StateConstituency" required aria-required="true" disabled>
                <option value="">Select a state first</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <label for="UnitID" class="form-label">Registration Unit ID</label>
            <div class="form-control">
              <input type="text" name="UnitID" id="UnitID" value="<?php echo $row_Staff['UnitID']; ?>" readonly title="Read Only">
              <small class="help-text">Read Only</small>
            </div>
          </div>

          <div class="form-row">
            <label for="Image" class="form-label">Voter Image</label>
            <div class="form-control">
              <input type="file" name="Image" id="Image" accept="image/*">
              <small class="help-text">Accepted: PNG, JPG, GIF</small>
            </div>
          </div>

          <div class="form-actions">
            <label class="form-label"></label>
            <div class="form-control">
              <button type="submit" name="submit" id="register" class="btn btn-primary">Add Voter</button>
              <button type="reset" name="reset" id="reset" class="btn btn-secondary">Clear Form</button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
  <?php include('footer.php'); ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Constituency data from PHP
    const fedConstByState = <?php echo json_encode($fedConstByState); ?>;
    const stateConstByState = <?php echo json_encode($stateConstByState); ?>;

    // Nigeria states and LGAs (static for reliability; no external API dependency)
    const nigeriaStates = [
      "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno",
      "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "Gombe", "Imo",
      "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos",
      "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers",
      "Sokoto", "Taraba", "Yobe", "Zamfara", "FCT"
    ];

    const lgaByState = {
      "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikawuno", "Ikwuano", "Isiala-Ngwa North", "Isiala-Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East", "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"],
      "Adamawa": ["Demsa", "Fufore", "Ganye", "Gayuk", "Gombi", "Grie", "Hong", "Jada", "Lamurde", "Madagali", "Maiha", "Mayo Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"],
      "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno", "Ibesikpo Asutan", "Ibiono Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo", "Mkpat Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubium", "Obot Akara", "Okobo", "Onna", "Oron", "Oruk Anam", "Udung Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"],
      "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum", "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"],
      "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade", "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Warji", "Zaki"],
      "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"],
      "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala", "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Oturkpo", "Tarka", "Ukum", "Ushongo", "Vandeikya"],
      "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala", "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"],
      "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakuur", "Yala"],
      "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West", "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South", "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"],
      "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo", "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"],
      "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba Okha", "Orhionmwon", "Oredo", "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"],
      "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin", "Ido Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"],
      "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti", "Igbo Eze North", "Igbo Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River", "Udenu", "Udi", "Uzo Uwani"],
      "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada", "Shongom", "Yamaltu/Deba"],
      "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South", "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre", "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Onuimo", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"],
      "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel", "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kaugama", "Kazaure", "Kiri Kasama", "Kiyawa", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura", "Yankwashi"],
      "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North", "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"],
      "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu", "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garun Mallam", "Gaya", "Gezawa", "Gwale", "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura", "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"],
      "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja", "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita", "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi", "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"],
      "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai", "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu/Danko", "Yauri", "Zuru"],
      "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela Odolu", "Ijumu", "Kabba/Bunu", "Kogi", "Lokoja", "Mopa Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East", "Yagba West"],
      "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West", "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"],
      "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa", "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"],
      "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Egon", "Obi", "Toto", "Wamba"],
      "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha", "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Muya", "Paikoro", "Rafi", "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"],
      "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon", "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"],
      "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-West", "Akoko South-East", "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo", "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"],
      "Osun": ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North", "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun", "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"],
      "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Ifedayo", "Ifelodun", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu", "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope", "Ori Ire", "Oyo", "Oyo East", "Saki East", "Saki West", "Surulere"],
      "Plateau": ["Bokkos", "Barkin Ladi", "Bassa", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke", "Langtang South", "Langtang North", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"],
      "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny", "Degema", "Eleme", "Emohua", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor", "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"],
      "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa", "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"],
      "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kumi", "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"],
      "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa", "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"],
      "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau", "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Chafe", "Zurmi"],
      "FCT": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"]
    };

    function populateStates() {
      const stateSelect = document.getElementById('State');
      nigeriaStates.forEach(state => {
        const opt = document.createElement('option');
        opt.value = state;
        opt.textContent = state;
        stateSelect.appendChild(opt);
      });
    }

    function populateLGAs(state) {
      const lgaSelect = document.getElementById('LGA');
      lgaSelect.innerHTML = '';
      const placeholder = document.createElement('option');
      placeholder.value = '';
      placeholder.textContent = state ? 'Select an LGA' : 'Select a state first';
      lgaSelect.appendChild(placeholder);

      if (!state || !lgaByState[state]) {
        lgaSelect.disabled = true;
        return;
      }

      lgaByState[state].forEach(lga => {
        const opt = document.createElement('option');
        opt.value = lga;
        opt.textContent = lga;
        lgaSelect.appendChild(opt);
      });
      lgaSelect.disabled = false;
    }

    function populateFedConstituencies(state) {
      const fedSelect = document.getElementById('FedConstituency');
      fedSelect.innerHTML = '';
      const placeholder = document.createElement('option');
      placeholder.value = '';
      placeholder.textContent = state ? 'Select federal constituency' : 'Select a state first';
      fedSelect.appendChild(placeholder);

      if (!state || !fedConstByState[state]) {
        fedSelect.disabled = true;
        return;
      }

      fedConstByState[state].forEach(constituency => {
        const opt = document.createElement('option');
        opt.value = constituency;
        opt.textContent = constituency;
        fedSelect.appendChild(opt);
      });
      fedSelect.disabled = false;
    }

    function populateStateConstituencies(state) {
      const stateSelect = document.getElementById('StateConstituency');
      stateSelect.innerHTML = '';
      const placeholder = document.createElement('option');
      placeholder.value = '';
      placeholder.textContent = state ? 'Select state constituency' : 'Select a state first';
      stateSelect.appendChild(placeholder);

      if (!state || !stateConstByState[state]) {
        stateSelect.disabled = true;
        return;
      }

      stateConstByState[state].forEach(constituency => {
        const opt = document.createElement('option');
        opt.value = constituency;
        opt.textContent = constituency;
        stateSelect.appendChild(opt);
      });
      stateSelect.disabled = false;
    }

    document.addEventListener('DOMContentLoaded', function() {
      populateStates();
      populateLGAs('');
      populateFedConstituencies('');
      populateStateConstituencies('');

      document.getElementById('State').addEventListener('change', function() {
        const selectedState = this.value;
        populateLGAs(selectedState);
        populateFedConstituencies(selectedState);
        populateStateConstituencies(selectedState);
      });
    });
  </script>
</body>

</html>