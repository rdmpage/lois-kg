/*

Shared code


*/
//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/25715455
function isObject(item) {
  return (typeof item === "object" && !Array.isArray(item) && item !== null);
}

//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/21445415
function uniques(arr) {
  var a = [];
  for (var i = 0, l = arr.length; i < l; i++)
    if (a.indexOf(arr[i]) === -1 && arr[i] !== '')
      a.push(arr[i]);
  return a;
}


//----------------------------------------------------------------------------------------
// Store a triple with optional language code
function triple(subject, predicate, object, language) {
  var triple = [];
  triple[0] = subject;
  triple[1] = predicate;
  triple[2] = object;

  if (typeof language === 'undefined') {} else {
    triple[3] = language;
  }

  return triple;
}

//----------------------------------------------------------------------------------------
// Enclose triple in suitable wrapping for HTML display or triplet output
function wrap(s, html) {
  if (s) {

    if (s.match(/^(http|urn|_:)/)) {
      s = s.replace(/\\_/g, '_');

      // handle < > in URIs such as SICI-based DOIs
      s = s.replace(/</g, '%3C');
      s = s.replace(/>/g, '%3E');

      if (html) {
        s = '&lt;' + s + '&gt;';
      } else {
        s = '<' + s + '>';
      }
    } else {
      s = '"' + s.replace(/"/g, '\\"') + '"';
    }
  }
  return s;
}

//----------------------------------------------------------------------------------------
// https://css-tricks.com/snippets/javascript/htmlentities-for-javascript/
function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

//----------------------------------------------------------------------------------------
function detect_language(s) {
  var language = null;
  var matched = 0;
  var parts = [];

  var regexp = [];

  // https://gist.github.com/ryanmcgrath/982242
  regexp['ja'] = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B/g;
  // http://hjzhao.blogspot.co.uk/2015/09/javascript-detect-chinese-character.html
  regexp['zh'] = /[\u4E00-\uFA29]/g;
  // http://stackoverflow.com/questions/32709687/js-check-if-string-contains-only-cyrillic-symbols-and-spaces
  regexp['ru'] = /[\u0400-\u04FF]/g;

  for (var i in regexp) {
    parts = s.match(regexp[i]);

    if (parts != null) {
      if (parts.length > matched) {
        language = i;
        matched = parts.length;
      }
    }
  }

  // require a minimum matching
  if (matched < 2) {
    language = null;
  }

  return language;

}

//----------------------------------------------------------------------------------------
function finger_print(s) {
	s = s.toLowerCase();

	// stop words
	// en
	s = s.replace(/\bfor\b/g, '');
	s = s.replace(/\band\b/g, '')
	s = s.replace(/\bof\b/g, '');
	s = s.replace(/\bthe\b/g, '');
	s = s.replace(/\bin\b/g, '');

	// fr
	s = s.replace(/\bde\b/g, '');
	s = s.replace(/\bla\b/g, '');
	s = s.replace(/\bet\b/g, '');

	// de
	s = s.replace(/\bdas\b/g, '');
	s = s.replace(/\bder\b/g, '');
	s = s.replace(/\bfur\b/g, '');

	// numbers
	s = s.replace(/\d+/g, '');


	// white space
	s = s.replace(/\s\s*/g, '');

	// punctuation
	s = s.replace(/[\.|,|\'|\(|\)|"|:|-|&|-]+/g, '');

	return s;
}

//----------------------------------------------------------------------------------------
function output(doc, triples) {
  // CouchDB
  for (var i in triples) {
    var s = 0;
    var p = 1;
    var o = 2;

    var lang = 3;

    var nquads = wrap(triples[i][s], false) +
      ' ' + wrap(triples[i][p], false) +
      ' ' + wrap(triples[i][o], false);
    if (triples[i][lang]) {
      nquads += '@' + triples[i][lang];
    }

    nquads += ' .' + "\n";

    // use cluster_id as the key so triples from different versions are linked together
    emit(doc._id, nquads);
    //console.log(nquads);
  }
}

var issn_lookup = {
    "actaamazonica": [
        "0044-5967"
    ],
    "actabiolparan": [
        "0301-2123"
    ],
    "actabotacadscihung": [
        "0001-5350"
    ],
    "actabotanicabrasilica": [
        "0102-3306"
    ],
    "actabotanicaneerlandica": [
        "0044-5983"
    ],
    "actabotborealoccidsin": [
        "1000-4025"
    ],
    "actabotbrasil": [
        "0102-3306"
    ],
    "actabotcroat": [
        "0365-0588"
    ],
    "actabotgallica": [
        "1253-8078"
    ],
    "actabotgallicabotlett": [
        "1253-8078"
    ],
    "actabothung": [
        "0236-6495"
    ],
    "actabotmalac": [
        "0210-9506"
    ],
    "actabotmex": [
        "0187-7151"
    ],
    "actabotneerl": [
        "0044-5983"
    ],
    "actabotvenez": [
        "0084-5906"
    ],
    "actabotyunnan": [
        "0253-2700"
    ],
    "actahortiberg": [
        "0373-4269"
    ],
    "actamanilana": [
        "0065-1370"
    ],
    "actaphytotaxgeobot": [
        "0001-6799",
        "1346-7565"
    ],
    "actaphytotaxsin": [
        "0529-1526"
    ],
    "actasocbotpoloniae": [
        "0001-6977"
    ],
    "actualbiol": [
        "0304-3584"
    ],
    "adansonia": [
        "1280-8571",
        "0001-804X",
        "1954-6475"
    ],
    "adansoniaser": [
        "0001-804X"
    ],
    "adansoniasér": [
        "0001-804X"
    ],
    "africanjbiotech": [
        "1684-5315"
    ],
    "aliso": [
        "2327-2929"
    ],
    "allertonia": [
        "0735-8032"
    ],
    "alpinebot": [
        "1664-2201"
    ],
    "amerfernj": [
        "0002-8444"
    ],
    "americanfernjournal": [
        "0002-8444"
    ],
    "americanjournalbotany": [
        "0002-9122"
    ],
    "amerjbot": [
        "0002-9122"
    ],
    "amermidlnaturalist": [
        "0003-0031"
    ],
    "amerorchidsocbull": [
        "0003-0252"
    ],
    "analesdeljardínbotánicomadrid": [
        "0211-1322"
    ],
    "analesinstbiolunivnacautónméxicobot": [
        "0185-254X"
    ],
    "analesjardbotmadrid": [
        "0211-1322"
    ],
    "annalesbotanicifennici": [
        "0003-3847"
    ],
    "annbotfenn": [
        "0003-3847"
    ],
    "annbotoxford": [
        "0305-7364"
    ],
    "annbotrome": [
        "0365-0812"
    ],
    "anncarnegiemus": [
        "0097-4463"
    ],
    "annjardbotbuitenzorg": [
        "0169-5754"
    ],
    "annmagnathist": [
        "0374-5481"
    ],
    "annmissouribotgard": [
        "0026-6493"
    ],
    "annmuscolmarseille": [
        "1256-4060"
    ],
    "annnathist": [
        "0374-5481"
    ],
    "annnaturhistmuswien": [
        "0083-6133",
        "0255-0105"
    ],
    "annnaturhistmuswienb": [
        "0255-0105"
    ],
    "annnewyorkacadsci": [
        "0077-8923"
    ],
    "annscinatbot": [
        "0150-9314"
    ],
    "anntransvaalmus": [
        "0041-1752"
    ],
    "anntsukubabotgard": [
        "0289-3568"
    ],
    "annuaireconservjardbotgenève": [
        "0255-9676"
    ],
    "aquaticbot": [
        "0304-3770"
    ],
    "archivmusnacriojaneiro": [
        "0365-4508"
    ],
    "archjardbotriojaneiro": [
        "0103-2550"
    ],
    "archmusnacriojaneiro": [
        "0365-4508"
    ],
    "arcula": [
        "0946-9575"
    ],
    "arkbot": [
        "0376-1649"
    ],
    "arkivbotstockh": [
        "0376-1649"
    ],
    "arnaldoa": [
        "1815-8242"
    ],
    "aroideana": [
        "0197-4033"
    ],
    "arqmusnacriojaneiro": [
        "0365-4508"
    ],
    "asklepios": [
        "0260-9533"
    ],
    "australianjournalbotany": [
        "0067-1924"
    ],
    "australiansystematicbotany": [
        "1030-1887"
    ],
    "australjbot": [
        "0067-1924"
    ],
    "australjbotsupplser": [
        "0810-6908"
    ],
    "australorchidrev": [
        "0045-0782"
    ],
    "australsystbot": [
        "1030-1887",
        "0067-1924"
    ],
    "austrobaileya": [
        "0155-4131"
    ],
    "balduinia": [
        "1808-2688"
    ],
    "bangladeshjpltaxon": [
        "1028-2092"
    ],
    "bauhinia": [
        "0067-4605"
    ],
    "beitrbiolpflanzen": [
        "0005-8041"
    ],
    "belgjbot": [
        "0778-4031"
    ],
    "berdeutschbotges": [
        "0011-9970"
    ],
    "berschweizbotges": [
        "0366-3094"
    ],
    "biodiversdataj": [
        "1314-2828"
    ],
    "biodiversresconservation": [
        "1897-2810"
    ],
    "bioldiversityconservation": [
        "1308-8084"
    ],
    "bishopmusbullbot": [
        "0893-3138"
    ],
    "blumea": [
        "0006-5196"
    ],
    "blumeabiodiversityevolutionbiogeographyplants": [
        "0006-5196"
    ],
    "blumeasuppl": [
        "0373-4293"
    ],
    "boissiera": [
        "0373-2975"
    ],
    "bolbotunivsaopaulo": [
        "0302-2439"
    ],
    "boletimbotânica": [
        "0302-2439"
    ],
    "bolinstbotunivguadalajara": [
        "0187-7054"
    ],
    "bolmusgoeldihistnatethnogr": [
        "0100-4123"
    ],
    "bolmushistnattucuman": [
        "1669-4309"
    ],
    "bolmusparaenseemiliogoeldinsbot": [
        "0077-2216"
    ],
    "bolsocbotméx": [
        "0366-2128"
    ],
    "bolsocbotméxico": [
        "0366-2128"
    ],
    "bonplandiacorrientes": [
        "0524-0476"
    ],
    "bostonjnathist": [
        "0271-5716"
    ],
    "botanicaljournallinneansociety": [
        "0024-4074"
    ],
    "botanicalmagazinetokyo": [
        "0006-808X"
    ],
    "botbullacadsin": [
        "0006-8063"
    ],
    "botbullacadsintaipei": [
        "0006-8063"
    ],
    "botgaz": [
        "0006-8071"
    ],
    "bothalia": [
        "0006-8241"
    ],
    "bothelv": [
        "0253-1453"
    ],
    "botjahrbsyst": [
        "0006-8152"
    ],
    "botjlinnsoc": [
        "0024-4074"
    ],
    "botmag": [
        "1355-4905"
    ],
    "botmagkewmag": [
        "1355-4905"
    ],
    "botmagtokyo": [
        "0006-808X"
    ],
    "botmusleafl": [
        "0006-8098"
    ],
    "botnot": [
        "0006-8195"
    ],
    "botrevlancaster": [
        "0006-8101"
    ],
    "botsci": [
        "2007-4298"
    ],
    "botstudtaipei": [
        "1817-406X",
        "1999-3110"
    ],
    "bottidsskr": [
        "0006-8187"
    ],
    "botzhurnmoscowleningrad": [
        "0006-8136"
    ],
    "bouteloua": [
        "1988-4257"
    ],
    "bradea": [
        "0084-800X"
    ],
    "bradleya": [
        "0265-086X"
    ],
    "britferngaz": [
        "0524-5826"
    ],
    "brittonia": [
        "0007-196X"
    ],
    "brunonia": [
        "0313-4245"
    ],
    "bullbimenssoclinnlyon": [
        "0366-1326"
    ],
    "bullbotresharbin": [
        "1673-5102"
    ],
    "bullbotsurvindia": [
        "0006-8128"
    ],
    "bullbritmusnathistbot": [
        "0068-2292"
    ],
    "bulletinmiscellaneousinformationkewadditionalseriesnewgeneraspeciescyperaceae": [
        "0366-4457"
    ],
    "bulletinmiscellaneousinformationroyalgardenskew": [
        "0366-4457"
    ],
    "bulletinsoci?t?botaniquefrance": [
        "0037-8941"
    ],
    "bullherbboissier": [
        "0256-5617"
    ],
    "bulljardbotbuitenzorg": [
        "0852-8756"
    ],
    "bulljardbotnatlbelg": [
        "0303-9153",
        "0374-6313",
        "0037-9557"
    ],
    "bulljardbotétatbruxelles": [
        "0374-6313"
    ],
    "bullmenssoclinnlyon": [
        "0366-1326"
    ],
    "bullmenssoclinnparis": [
        "1954-6483"
    ],
    "bullmiscinformkew": [
        "0366-4457"
    ],
    "bullmiscinformkewadditser": [
        "0366-4457"
    ],
    "bullmushistnatparis": [
        "1148-8425",
        "0240-8937"
    ],
    "bullmusnationhistnatparis": [
        "0376-4443"
    ],
    "bullmusnatlhistnat": [
        "1148-8425",
        "0240-8937"
    ],
    "bullmusnatlhistnatbadansonia": [
        "0240-8937"
    ],
    "bullnathistmuslondonbot": [
        "0968-0446"
    ],
    "bullnatlmusnatscitokyob": [
        "1881-9060"
    ],
    "bullnewyorkbotgard": [
        "8755-4801"
    ],
    "bullsocbotfrance": [
        "0037-8941"
    ],
    "bullsocbotfrancelettbot": [
        "0181-1797",
        "0037-8941"
    ],
    "bullsoclinnparis": [
        "1954-6483"
    ],
    "bullsocneuchâteloisescinat": [
        "0366-3469"
    ],
    "bullsocroybotbelgique": [
        "0037-9557"
    ],
    "bulltorreybotclub": [
        "0040-9618"
    ],
    "bunrui": [
        "1346-6852"
    ],
    "butinstcatalanahistnat": [
        "0210-6205",
        "1133-6889"
    ],
    "cactsuccjlosangeles": [
        "0007-9367"
    ],
    "cactsucmex": [
        "0526-717X"
    ],
    "caldasia": [
        "0366-5232"
    ],
    "canadjbot": [
        "0008-4026"
    ],
    "candollea": [
        "0373-2967"
    ],
    "carnifloraaustralis": [
        "1448-9570"
    ],
    "carnivplnewslett": [
        "0190-9215"
    ],
    "castanea": [
        "0008-7475"
    ],
    "ceiba": [
        "0008-8692"
    ],
    "cienciamexico": [
        "1405-6550"
    ],
    "cladistics": [
        "0748-3007"
    ],
    "collectaneabotanica": [
        "0010-0730"
    ],
    "collectbotbarcelona": [
        "0010-0730"
    ],
    "contrbiollabkyotouniv": [
        "0452-9987"
    ],
    "contrgrayherb": [
        "0195-6094"
    ],
    "contribherbaustral": [
        "1030-1887"
    ],
    "contribqueenslherb": [
        "2202-0802"
    ],
    "contributionsfromherbariumaustraliense": [
        "1030-1887"
    ],
    "contributionsfromqueenslandherbarium": [
        "2202-0802"
    ],
    "contrunivmichiganherb": [
        "0091-1860"
    ],
    "contrusnatlherb": [
        "0097-1618"
    ],
    "curtissbotanicalmagazine": [
        "1355-4905"
    ],
    "curtissbotanicalmagazineappendixcompaniontobotanicalmagazine": [
        "1355-4905"
    ],
    "curtissbotanicalmagazinenewseries": [
        "1355-4905"
    ],
    "curtissbotanicalmagazinens": [
        "1355-4905"
    ],
    "curtissbotmag": [
        "1355-4905"
    ],
    "danskbotark": [
        "0011-6211"
    ],
    "darwiniana": [
        "0011-6793"
    ],
    "edinburghjbot": [
        "0960-4286"
    ],
    "edinburghjournalbotany": [
        "0960-4286"
    ],
    "eurjtaxon": [
        "2118-9773"
    ],
    "feddesrepert": [
        "0014-8962"
    ],
    "femsyeastresearch": [
        "1567-1356"
    ],
    "ferngaz": [
        "0308-0838"
    ],
    "ferngazuk": [
        "0308-0838"
    ],
    "fieldianabot": [
        "0015-0746"
    ],
    "flcongobelgeruandaurundi": [
        "0374-6313"
    ],
    "flmalesserspermat": [
        "0071-5778"
    ],
    "flneotropmonogr": [
        "0071-5794"
    ],
    "floramontiber": [
        "1138-5952"
    ],
    "foliageobotphytotax": [
        "0015-5551"
    ],
    "foliamalaysiana": [
        "1511-8916"
    ],
    "fontqueria": [
        "0212-0623"
    ],
    "forumgeobot": [
        "1867-9315"
    ],
    "fritschiana": [
        "1024-0306"
    ],
    "fungaldiversity": [
        "1560-2745"
    ],
    "gardbullsingapore": [
        "0374-7859",
        "217697"
    ],
    "gardensbulletinsingapore": [
        "0374-7859"
    ],
    "gardensbulletinsingaporemaywlchewflaustralia": [
        "0374-7859"
    ],
    "gartenflora": [
        "0177-0349"
    ],
    "gayana": [
        "0016-5301"
    ],
    "gayanabot": [
        "0016-5301"
    ],
    "gayanabotánica": [
        "0717-6643"
    ],
    "genetica": [
        "0016-6707"
    ],
    "genschefflerasabah": [
        "2398-6336"
    ],
    "globalfl": [
        "2398-6336"
    ],
    "grana": [
        "0017-3134"
    ],
    "greatbasinnaturalist": [
        "0017-3614"
    ],
    "guihaia": [
        "1000-3142"
    ],
    "harvardpapbot": [
        "1043-4534"
    ],
    "harvardpapersbotany": [
        "1043-4534"
    ],
    "haseltonia": [
        "1070-0048"
    ],
    "hoehnea": [
        "0073-2877"
    ],
    "ibugana": [
        "0187-7054"
    ],
    "iheringiabot": [
        "0073-4705"
    ],
    "intjplsci": [
        "1058-5893"
    ],
    "jadelaidebotgard": [
        "0313-4083"
    ],
    "jagrictropbotappl": [
        "0021-7662"
    ],
    "jarnoldarbor": [
        "0004-2625"
    ],
    "jassocstraits": [
        "2304-7534"
    ],
    "jbamboores": [
        "1000-6567"
    ],
    "jbiogeogr": [
        "0305-0270"
    ],
    "jbombaynathistsoc": [
        "0006-6982"
    ],
    "jbotresinsttexas": [
        "1934-5259"
    ],
    "jbotsocbotfrance": [
        "1280-8202"
    ],
    "jeafrnathist": [
        "1026-1613"
    ],
    "jgeobot": [
        "0374-8081"
    ],
    "jgeobothokuriku": [
        "0374-8081"
    ],
    "jintconiferpreservsoc": [
        "1075-3524"
    ],
    "jjapbot": [
        "0022-2062"
    ],
    "jlinnsocbot": [
        "0368-2927"
    ],
    "jnanjingforestunivnatscied": [
        "1000-2006"
    ],
    "journagrictropbotappliq": [
        "0021-7662"
    ],
    "journaladelaidebotanicgardens": [
        "0313-4083"
    ],
    "journallinneansocietybotany": [
        "0368-2927"
    ],
    "journalroyalsocietywesternaustralia": [
        "0035-922X"
    ],
    "journalwashingtonacademysciences": [
        "0043-0439"
    ],
    "journjapbot": [
        "0022-2062"
    ],
    "jproclinnsocbot": [
        "1945-9483"
    ],
    "jroysocwesternaustralia": [
        "0035-922X"
    ],
    "jsafricanbot": [
        "0022-4618"
    ],
    "jstraitsbranchroyasiatsoc": [
        "2304-7534"
    ],
    "jsystevol": [
        "1674-4918"
    ],
    "jthreattaxa": [
        "0974-7893"
    ],
    "jtropsubtropbot": [
        "1005-3395"
    ],
    "jwashacadsci": [
        "0043-0439"
    ],
    "jwuhanbotres": [
        "1000-470X"
    ],
    "kalikasan": [
        "0115-0553"
    ],
    "kalmia": [
        "0080-4274"
    ],
    "kanunnah": [
        "1832-536X"
    ],
    "kewbull": [
        "0075-5974"
    ],
    "kewbulletin": [
        "0075-5974"
    ],
    "kirkia": [
        "0451-9930"
    ],
    "komarovia": [
        "1819-0154"
    ],
    "koreanjpltaxon": [
        "1225-8318"
    ],
    "lankesteriana": [
        "1409-3871"
    ],
    "lichenologist": [
        "0024-2829"
    ],
    "lindleyana": [
        "0889-258X"
    ],
    "lundellia": [
        "1097-993X"
    ],
    "madroño": [
        "0024-9637"
    ],
    "malayannatj": [
        "0025-1291"
    ],
    "mededbotmusherbrijksunivutrecht": [
        "2352-5754"
    ],
    "memamacadartssc": [
        "0096-6134"
    ],
    "memameracad": [
        "0096-6134"
    ],
    "memameracadarts": [
        "0096-6134"
    ],
    "memameracadartsser": [
        "0096-6134"
    ],
    "memjuntainvestultramarser": [
        "0870-0915"
    ],
    "memmushistnatparisserbot": [
        "0078-9755"
    ],
    "memnamacadartssc": [
        "0096-6134"
    ],
    "memnewyorkbotgard": [
        "0077-8931"
    ],
    "memoirsamericanacademyartssciences": [
        "0096-6134"
    ],
    "memoirsamericanacademyartssciencesser": [
        "0096-6134"
    ],
    "memoirsamericanacademysciences": [
        "0096-6134"
    ],
    "memoirstorreybotanicalclub": [
        "0097-3807"
    ],
    "memtorreybotclub": [
        "0097-3807"
    ],
    "micronesica": [
        "0026-279X"
    ],
    "mittbotstaatssammlmünchen": [
        "0006-8179"
    ],
    "molecphylogenevol": [
        "1055-7903",
        "0102-3306"
    ],
    "monogrsystbotmissouribotgard": [
        "0161-1542"
    ],
    "moscosoa": [
        "0254-6442"
    ],
    "muelleria": [
        "0077-1813"
    ],
    "métecolsist": [
        "1659-3049"
    ],
    "nathistbullsiamsoc": [
        "0080-9462"
    ],
    "nelumbo": [
        "0976-5069"
    ],
    "neodiversity": [
        "1809-5348"
    ],
    "newjbot": [
        "2042-3489"
    ],
    "newzealandjbot": [
        "0028-825X"
    ],
    "newzealandjournalbotany": [
        "0028-825X"
    ],
    "nordicjbot": [
        "0107-055X"
    ],
    "nordicjournalbotany": [
        "0107-055X"
    ],
    "norwegjbot": [
        "0300-1156"
    ],
    "notesroybotgardedinburgh": [
        "0080-4274"
    ],
    "notizblbotgartberlindahlem": [
        "0258-1485"
    ],
    "notizblköniglbotgartberlin": [
        "0258-1485"
    ],
    "notulsystparis": [
        "0374-9223"
    ],
    "nouvarchmushistnat": [
        "0766-7248"
    ],
    "novon": [
        "1055-3177"
    ],
    "novostisistvysshrast": [
        "0568-5435"
    ],
    "nuytsia": [
        "0085-4417"
    ],
    "occaspapcalifacadsci": [
        "0068-5461"
    ],
    "oesterrbotwochenbl": [
        "1029-0729"
    ],
    "oesterrbotz": [
        "0029-8948"
    ],
    "operabot": [
        "0078-5237"
    ],
    "organismsdiversityevol": [
        "1439-6092"
    ],
    "orquideologia": [
        "0120-1433"
    ],
    "pacificsci": [
        "0030-8870"
    ],
    "parazitologiya": [
        "0031-1847"
    ],
    "philippjsci": [
        "0031-7683"
    ],
    "philippjscic": [
        "0370-0208"
    ],
    "philippscientist": [
        "0079-1466"
    ],
    "philtransroysoclondserb": [
        "0962-8436"
    ],
    "phytokeys": [
        "1314-2011"
    ],
    "phytologia": [
        "0031-9430"
    ],
    "phytoneuron": [
        "2153-733X"
    ],
    "phytonhorn": [
        "0079-2047"
    ],
    "phytotaxa": [
        "1179-3155"
    ],
    "plants[basel]": [
        "2223-7747"
    ],
    "plbiosystems": [
        "1126-3504"
    ],
    "pldiversevol": [
        "1869-6155"
    ],
    "pldiversityresources": [
        "2095-0845"
    ],
    "plecolevol": [
        "2032-3913"
    ],
    "plosone": [
        "1932-6203"
    ],
    "plosonee[oct][epublished]": [
        "1932-6203"
    ],
    "plscij": [
        "2095-0837"
    ],
    "plsystevol": [
        "0378-2697"
    ],
    "polishbotj": [
        "1641-8190"
    ],
    "procacadnatsciphiladelphia": [
        "0097-3157"
    ],
    "procameracadarts": [
        "0199-9818"
    ],
    "procamerphilossoc": [
        "0003-049X"
    ],
    "procbiolsocwashington": [
        "0006-324X"
    ],
    "proccalifacadsci": [
        "0068-547X"
    ],
    "proceedingslinneansocietynewsouthwales": [
        "0370-047X"
    ],
    "proclinnsocnewsouthwales": [
        "0370-047X"
    ],
    "procroysocedinb": [
        "0370-1646"
    ],
    "procroysocqueensland": [
        "0080-469X"
    ],
    "publfieldcolumbianmusbotser": [
        "0096-2759"
    ],
    "publfieldcolumbmusbotser": [
        "0096-2759"
    ],
    "publfieldmusnathistbotser": [
        "0096-2759"
    ],
    "pyrexjbiodiversconservation": [
        "2985-8844"
    ],
    "recaucklandinstmus": [
        "0067-0464"
    ],
    "recaucklinstmus": [
        "0067-0464"
    ],
    "recueiltravbotnéerl": [
        "0370-7504"
    ],
    "reinwardtia": [
        "0034-365X"
    ],
    "repertoriumnovarumspecierumregnivegetabilis": [
        "0375-121X"
    ],
    "repertspecnovregniveg": [
        "0375-121X"
    ],
    "repertspecnovregnivegbeih": [
        "0233-187X"
    ],
    "revbotapplagrictrop": [
        "0370-3681"
    ],
    "revfacagronmaracay": [
        "0041-8285"
    ],
    "revintbotapplagrictrop": [
        "0370-5412"
    ],
    "revistaacadcolombciexact": [
        "0370-3908"
    ],
    "revistabrasilbot": [
        "0100-8404"
    ],
    "revistafacagronmaracay": [
        "0041-8285"
    ],
    "revistaguatemal": [
        "1562-7217"
    ],
    "revistaguatemalensis": [
        "1562-7217"
    ],
    "revistajardbotnacunivhabana": [
        "0253-5696"
    ],
    "revistamexbiodivers": [
        "1870-3453"
    ],
    "revistamusplata": [
        "0375-1147"
    ],
    "revistamusplataseccbot": [
        "0372-4611",
        "0375-1147"
    ],
    "revistaperubiol": [
        "1561-0837"
    ],
    "revistasocbolivbot": [
        "2076-3190"
    ],
    "revjardbotnacionunivhabana": [
        "0253-5696"
    ],
    "rheedea": [
        "0971-2313"
    ],
    "rhodora": [
        "0035-4902"
    ],
    "richardiana": [
        "1626-3596"
    ],
    "richardianans": [
        "2262-9017"
    ],
    "rodriguésia": [
        "0370-6583"
    ],
    "safricanjbot": [
        "0254-6299"
    ],
    "sarawakmusj": [
        "0581-7897"
    ],
    "sarawakmusjourn": [
        "0581-7897"
    ],
    "schlechtendalia": [
        "1436-2317"
    ],
    "schütziana": [
        "2191-3099"
    ],
    "selbyana": [
        "0361-185X"
    ],
    "sendtnera": [
        "0944-0178"
    ],
    "sida": [
        "0036-1488"
    ],
    "sidabotmisc": [
        "0883-1475"
    ],
    "smithsoniancontrbot": [
        "0081-024X"
    ],
    "smithsoniancontributionstobotany": [
        "0081-024X"
    ],
    "smithsonianmisccollect": [
        "0096-8749"
    ],
    "southwnaturalist": [
        "0038-4909"
    ],
    "stapfia": [
        "0252-192X"
    ],
    "svenskbottidskr": [
        "0039-646X"
    ],
    "systbot": [
        "0363-6445"
    ],
    "systbotmonogr": [
        "0737-8211"
    ],
    "systematicbotany": [
        "0363-6445"
    ],
    "systgeogrpl": [
        "1374-7886"
    ],
    "taiwania": [
        "0372-333X"
    ],
    "tapchísinhhọc": [
        "0866-7160"
    ],
    "taprobanica": [
        "1800-427X"
    ],
    "taxon": [
        "0040-0262"
    ],
    "taxonomania": [
        "1783-0362"
    ],
    "telopea": [
        "0312-9764"
    ],
    "thaiforestbullbot": [
        "0495-3843"
    ],
    "transactionsproceedingsroyalsocietysouthaustralia": [
        "0372-1426"
    ],
    "transbotsocedinburgh": [
        "0374-6607"
    ],
    "translinnsoclondon": [
        "1945-9432"
    ],
    "translinnsoclondonbot": [
        "1945-9459"
    ],
    "transprocbotsocedin": [
        "0374-6607"
    ],
    "transprocbotsocedinb": [
        "0374-6607"
    ],
    "transprocbotsocedinburgh": [
        "0374-6607"
    ],
    "transprocroysocsaustral": [
        "0372-1426"
    ],
    "transprocroysocsouthaustralia": [
        "0372-1426"
    ],
    "transroysocsouthafrica": [
        "0035-919X"
    ],
    "transsafricanphilossoc": [
        "2156-0382"
    ],
    "transsandiegosocnathist": [
        "0080-5947"
    ],
    "turczaninowia": [
        "1560-7259"
    ],
    "turkishjbot": [
        "1300-008X"
    ],
    "ukrayinskbotzhurn": [
        "0372-4123"
    ],
    "utafiti": [
        "1015-8707"
    ],
    "verhandlungenkaiserlichk?niglichenzoologischbotanischengesellschaftwien": [
        "0084-5647"
    ],
    "verhandlungenzoologischbotanischengesellschaftwien": [
        "0084-5647"
    ],
    "verhbotvereinsprovbrandenburg": [
        "0724-312X"
    ],
    "verhkkzoolbotgeswien": [
        "0084-5647"
    ],
    "verhzoolbotges": [
        "0084-5647"
    ],
    "verhzoolbotgeswien": [
        "0084-5647"
    ],
    "verhzoolbotgesösterr": [
        "0252-1911"
    ],
    "verhzoolbotgesösterreich": [
        "0252-1911"
    ],
    "victoriannaturalist": [
        "0042-5184"
    ],
    "vierteljahrsschrnaturfgeszürich": [
        "0042-5672"
    ],
    "watsonia": [
        "0043-1532"
    ],
    "webbia": [
        "0083-7792"
    ],
    "weberbauerella": [
        "2414-8814"
    ],
    "wentia": [
        "0511-4780"
    ],
    "willdenowia": [
        "0511-9618"
    ],
    "wrightia": [
        "0084-2648"
    ],
    "wulfenia": [
        "1561-882X"
    ],
    "yearbookheathersoc": [
        "0440-5757"
    ],
    "znaturfc": [
        "0939-5075"
    ],
    "菌物学报": [
        "1672-6472"
    ]
};

//----------------------------------------------------------------------------------------
function journal_to_issn (journal) {
  var issn = '';
  var key = finger_print(journal);
  
  if (issn_lookup[key]) {
    issn = issn_lookup[key][0];
  }
  return issn;
}

//----------------------------------------------------------------------------------------
// START COUCHDB VIEW
function message(doc) {
  if (doc.message) {

    var subject_id = '';
    
    if (subject_id == '')
    {
		if (doc.message.DOI) {    	
			subject_id = 'https://doi.org/' + doc.message.DOI.toLowerCase();
		}
	}
	
    if (subject_id == '')
    {
		if (doc.message.HANDLE) {    	
			subject_id = 'https://hdl.handle.net/' + doc.message.HANDLE.toLowerCase();
		}
	}	

    if (subject_id == '')
    {
		if (doc.message.URL) {    	
			subject_id = doc.message.URL;
		}
	}
	
	if (subject_id == '')
  {
		if (doc.message.PMID) {    	
			subject_id = 'https://www.ncbi.nlm.nih.gov/pubmed/' + doc.message.PMID;
		}
	}
	
	// HTTPS
	if (subject_id.match(/^http:\/\/(www\.)?jstor/)) {
	  subject_id = subject_id.replace(/^http:/, 'https:');
	}
    
    var triples = [];
    var type = '';
    
    // handle multilingual keys first so we don't duplicate them
    
    // title -----------------------------------------------------------------------------
    var have_title = false;
    if (doc.message.multi) {
    	if (doc.message.multi._key['title']) {
    		have_title = true;
    		for (var k in doc.message.multi._key['title']) {
    			triples.push(triple(subject_id,
						'http://schema.org/name',
						doc.message.multi._key['title'][k], k));
    		}    	
    	}
    }
    
    if (!have_title) {
    	if (doc.message.title) {
    	  have_title = true;
          if (Array.isArray(doc.message.title)) {
            for (var j in doc.message.title) {

              var lang = detect_language(doc.message.title[j]);

              triples.push(triple(subject_id,
                'http://schema.org/name',
                doc.message.title[j], lang));
            }
          } else {
            triples.push(triple(subject_id,
              'http://schema.org/name',
              doc.message.title));
          }
        }
    }
 
    // abstract --------------------------------------------------------------------------
    var have_abstract = false;
    if (doc.message.multi) {
    	if (doc.message.multi._key['abstract']) {
    		have_abstract = true;
    		for (var k in doc.message.multi._key['abstract']) {
    			triples.push(triple(subject_id,
						'http://schema.org/description',
						doc.message.multi._key['abstract'][k], k));
    		}    	
    	}
    }
    
    if (!have_abstract) {
    	if (doc.message['abstract']) {
    		    have_abstract = true;
    		    
    		    var abstract_text = doc.message['abstract'];
    		    
            // fix JATS tags
            abstract_text = abstract_text.replace(/<jats:title>/g, ' <i>');
            abstract_text = abstract_text.replace(/<\/jats:title>/g, '</i>');
            abstract_text = abstract_text.replace(/<jats:p>/g, '<p>');
            abstract_text = abstract_text.replace(/<\/jats:p>/g, '</p>');
            abstract_text = abstract_text.replace(/<jats:italic>/g, ' <i>');
            abstract_text = abstract_text.replace(/<\/jats:italic>/g, '</i>');
    		    
            triples.push(triple(subject_id,
              'http://schema.org/description',
              abstract_text));
        }
    }
 	
	
    // container -------------------------------------------------------------------------
    var container_id = '';    
       
    if (doc.message.ISSN) {
    	container_id = 'http://worldcat.org/issn/' + doc.message.ISSN[0];
    	
	  triples.push(triple(container_id,
		'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
		'http://schema.org/Periodical'));
    	
    } else {
    	container_id = subject_id + '#container';
    }
    
    
    if (container_id != '') {
    	triples.push(triple(subject_id,
              'http://schema.org/isPartOf',
              container_id));
              
        var have_container_title = false;      
           
          
		if (doc.message.multi) {
			if (doc.message.multi._key['container-title']) {
				have_container_title = true;
				for (var k in doc.message.multi._key['container-title']) {
					triples.push(triple(container_id,
							'http://schema.org/name',
							doc.message.multi._key['container-title'][k], k));
				}    	
			}
		}
	    
		if (!have_container_title) {
			if (doc.message['container-title']) {
				have_container_title = true;
				
				if (Array.isArray(doc.message['container-title'])) {
           			for (var j in doc.message['container-title']) {
 	          			triples.push(triple(container_id,
						  'http://schema.org/name',
						  doc.message['container-title'][j]));  
           			}
           		} else {
					triples.push(triple(container_id,
					  'http://schema.org/name',
					  doc.message['container-title']));           		
           		}
			}
		} 
		            
    }

    for (var i in doc.message) {
      switch (i) {
      
      	// identifiers
        case 'DOI':
      		var identifier_id = subject_id + '#doi';
      		
      		triples.push(triple(subject_id,
              'http://schema.org/identifier',
              identifier_id));

      		triples.push(triple(identifier_id,
              'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
              'http://schema.org/PropertyValue'));

      		triples.push(triple(identifier_id,
              'http://schema.org/propertyID',
              'doi'));
 
      		triples.push(triple(identifier_id,
              'http://schema.org/value',
              doc.message[i].toLowerCase()));
			break;   
			
		// Handle, JSTOR, etc.
     	// identifiers
        case 'HANDLE':
      		var identifier_id = subject_id + '#handle';
      		
      		triples.push(triple(subject_id,
              'http://schema.org/identifier',
              identifier_id));

      		triples.push(triple(identifier_id,
              'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
              'http://schema.org/PropertyValue'));

      		triples.push(triple(identifier_id,
              'http://schema.org/propertyID',
              'handle'));
 
      		triples.push(triple(identifier_id,
              'http://schema.org/value',
              doc.message[i].toLowerCase()));
			break;  
			
       case 'JSTOR':
      		var identifier_id = subject_id + '#jstor';
      		
      		triples.push(triple(subject_id,
              'http://schema.org/identifier',
              identifier_id));

      		triples.push(triple(identifier_id,
              'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
              'http://schema.org/PropertyValue'));

      		triples.push(triple(identifier_id,
              'http://schema.org/propertyID',
              'jstor'));
 
      		triples.push(triple(identifier_id,
              'http://schema.org/value',
              doc.message[i].toLowerCase()));
			break;  
			
			
       case 'PMID':
      		var identifier_id = subject_id + '#pmid';
      		
      		triples.push(triple(subject_id,
              'http://schema.org/identifier',
              identifier_id));

      		triples.push(triple(identifier_id,
              'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
              'http://schema.org/PropertyValue'));

      		triples.push(triple(identifier_id,
              'http://schema.org/propertyID',
              'pmid'));
 
      		triples.push(triple(identifier_id,
              'http://schema.org/value',
              doc.message[i].toString()));
			break;  			
		

/*
        case 'DOI':
          triples.push(triple(subject_id,
            'http://schema.org/identifier',
            'http://identifiers.org/doi/' + doc.message[i].toLowerCase()));
          break;

        case 'URL':
          triples.push(triple(subject_id,
            'http://schema.org/url',
            doc.message[i]));
          break;
          
        case 'alternative-id':
			for (var j in doc.message[i]) {
			  triples.push(triple(subject_id,
				'http://schema.org/identifier',
				doc.message[i][j]));
			}
			break;  
*/			    
			
		case 'type':
			switch (doc.message[i]) {
			  case 'article-journal':
			  case 'journal-article':
			    type = 'http://schema.org/ScholarlyArticle';
				break;
			  default:
				break;
			}
			break;

			// article metadata
		  case 'issue':
			triples.push(triple(subject_id,
			  'http://schema.org/issueNumber',
			  doc.message[i]));
			break;

		  case 'pages':
			triples.push(triple(subject_id,
			  'http://schema.org/pagination',
			  doc.message[i]));
			break;

		  case 'volume':
			triples.push(triple(subject_id,
			  'http://schema.org/volumeNumber',
			  doc.message[i]));
			break;
		
		  case 'page':
			var parts = doc.message[i].match(/^(.*)\-(.*)$/);
			if (parts) {
			  triples.push(triple(subject_id,
				'http://schema.org/pageStart',
				parts[1]));
			  triples.push(triple(subject_id,
				'http://schema.org/pageEnd',
				parts[2]));
			} else {
			  triples.push(triple(subject_id,
				'http://schema.org/pagination',
				doc.message[i]));
			}
			break;
		
	  case 'issued':
	     var date = '';
	     var dateparts = doc.message[i]['date-parts'][0];
	     
	     switch (dateparts.length) {
	        case 1:
	           date = dateparts[0];
	           break;
	        case 2:
	           date = dateparts[0] + '-' + ("00" + dateparts[1]).slice(-2) + '-00';
	           break;
	        case 3:
	           date = dateparts[0] + '-' + ("00" + dateparts[1]).slice(-2) + '-' + ("00" + dateparts[2]).slice(-2);	           
	           break;
	        default:
	        	break;
	    }
	    triples.push(triple(subject_id,
          'http://schema.org/datePublished',
          date.toString()));	    
	    break;
                   
			// tags
		  case 'subject':
			for (var j in doc.message[i]) {
			  triples.push(triple(subject_id,
				'http://schema.org/keywords',
				doc.message[i][j]));
			}
			break;
			
			// license
		  case 'license':
			for (var j in doc.message[i]) {
			  if (doc.message[i][j].URL) {
				triples.push(triple(subject_id,
				  'http://schema.org/license',
				  doc.message[i][j].URL));
			  }
			}
			break;
			
			// publisher
			// should this be linked to journal rather than article?
			// should this be an object rather than a string?
		  case 'publisher':
			triples.push(triple(subject_id,
			  'http://schema.org/publisher',
			  doc.message[i]));
			break;
			
		  case 'ISSN':
			  // ISSNs
			for (var j in doc.message[i]) {
			  // issn
			  triples.push(triple(container_id,
				'http://schema.org/issn',
				doc.message[i][j]));
			}
			break;
						
			// authors (may include ORCIDs or possibly other identifiers)
			// create an identifier to make this addressable
		  case 'author':
			var n = doc.message[i].length;
						
			for (var j = 0; j < n; j++) {
				var index = parseInt(j) + 1;
            	var role_id    = subject_id + '#role-' + index;            	
            	var creator_id = subject_id + '#creator-' + index;
            	
            	// role
				triples.push(triple(
					subject_id,
					'http://schema.org/creator',
					role_id)
					);

				triples.push(triple(
					role_id,
					'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
					'http://schema.org/Role')
					);

				triples.push(triple(
					role_id,
					'http://schema.org/roleName',
					String(index)
					));

				triples.push(triple(
					role_id,
					'http://schema.org/creator',
					creator_id
					));            	
				
				 // creator 
				
				  // type, need to handle organisations as authors
				  triples.push(triple(
					creator_id,
					'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
					'http://schema.org/Person'));
								
				  // name, which may be multilingual                
					if (doc.message[i][j]['multi']) {
					  if (doc.message[i][j]['multi']._key['literal']) {
						for (var k in doc.message[i][j]['multi']._key['literal']) {				  
						  triples.push(triple(creator_id,
							'http://schema.org/name',
							doc.message[i][j]['multi']._key['literal'][k], k));  
						}
					  }
					} else {
						// one or more strings
						
					   var person_name;
						if (doc.message[i][j].literal) {
						  person_name = doc.message[i][j].literal;
						} else {
						  var parts = [];
						  if (doc.message[i][j].given) {
							parts.push(doc.message[i][j].given); 
							
							triples.push(triple(creator_id,
				  				'http://schema.org/givenName', // ?
				  				doc.message[i][j].given));
														
						  }
						  if (doc.message[i][j].family) {
							parts.push(doc.message[i][j].family); 
							
							triples.push(triple(creator_id,
				  				'http://schema.org/familyName', // ?
				  				doc.message[i][j].family));
							
						  }
						  person_name = parts.join(' ');
						}

					  triples.push(triple(
						creator_id,
						'http://schema.org/name',
						person_name));						
					
					} 
					
					// identifier(s)
					if (doc.message[i][j].ORCID) {
						var identifier_id = creator_id + '-orcid';
	           			var orcid = doc.message[i][j].ORCID;
            			orcid = orcid.replace(/https?:\/\/orcid.org\//, '');
            			
						triples.push(triple(
						  creator_id,
						  'http://schema.org/identifier',
						  identifier_id));
						
						triples.push(triple(
						  identifier_id,
						  'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
						  'http://schema.org/PropertyValue'));

						triples.push(triple(
						  identifier_id,
						  'http://schema.org/propertyID',
						  'orcid'));

						triples.push(triple(
						  identifier_id,
						  'http://schema.org/value',
						  orcid
						));                          			            			
            		}
	
			}
			break;	
				
			
          /*
			// funding
		  case 'funder':
			var n = doc.message[i].length;
			for (var j = 0; j < n; j++) {
			  var funder_id = '';

			  // create identifier for funder
			  if (doc.message[i][j].DOI) {
				funder_id = 'http://identifiers.org/doi/' + doc.message[i][j].DOI;
			  } else {
				// #hash identifier 
				funder_id = subject_id + '#funder_' + (j + 1);
			  }

			  // create funder 
			  triples.push(triple(funder_id,
				'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
				'http://schema.org/Organisation'));

			  triples.push(triple(funder_id,
				'http://schema.org/name',
				doc.message[i][j].name));

			  // role
			  var m = doc.message[i][j].award.length;

			  if (m == 0) {
				// funder but award not known
				var award_id = funder_id + '/award';

				triples.push(triple(award_id,
				  'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
				  'http://schema.org/Role'));

				// link to funder 
				triples.push(triple(award_id,
				  'http://schema.org/funder',
				  funder_id));

				// link role to work         
				triples.push(triple(subject_id,
				  'http://schema.org/funder',
				  award_id));
			  } else {
				// we have one or more awards
				for (var a = 0; a < m; a++) {
				  var award_id = funder_id + '/award_' + (a+1);

				  triples.push(triple(award_id,
					'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
					'http://schema.org/Role'));

				  triples.push(triple(award_id,
					'http://schema.org/roleName',
					doc.message[i][j].award[a]
				  ));

				  // link to funder 
				  triples.push(triple(award_id,
					'http://schema.org/funder',
					funder_id));

				  // link role to work         
				  triples.push(triple(subject_id,
					'http://schema.org/funder',
					award_id));
				}
			  }
			}
			break;
			*/
			
			/*

			// links to representions such as PDFs, XML, HTML, etc.
			// may be links to text mining sources
		  case 'link':
			var n = doc.message[i].length;
			for (var j = 0; j < n; j++) {
			  var link_id = subject_id + '#link_' + (j + 1);

			  // type
			  triples.push(triple(link_id,
				'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
				'http://schema.org/CreativeWork'));

			  if (doc.message[i][j].URL) {
				triples.push(triple(link_id,
				  'http://schema.org/url',
				  doc.message[i][j].URL));

				if (doc.message[i][j]['content-type']) {
				  triples.push(triple(link_id,
					'http://schema.org/fileFormat',
					doc.message[i][j]['content-type']));
				}

				// link to work
				triples.push(triple(subject_id,
				  'http://schema.org/workExample',
				  link_id));
			  }
			}
			break;
			
		*/


/*
      "reference": [
           {
               "key": "11192_B1",
               "first-page": "119",
               "article-title": "Pseudocrangonyx, a new genus of subterranean amphipods from Japan.",
               "volume": "10",
               "author": "Akatsuka",
               "year": "1922",
               "journal-title": "Annotationes Zoologicae Japonenses"
           },
           
		{
		key: "ref32",
		first-page: "1",
		article-title: "Exkursionsflora für die Kanarischen Inseln. Verlag Eugen Ulmer, Stuttgart. Hughes, C. 1998. Monograph of Leucaena (Leguminosae- Mimosoideae).",
		volume: "55",
		author: "Hohenester",
		year: "1993",
		journal-title: "Syst. Bot. Monogr.",
		ISSN: "http://id.crossref.org/issn/0737-8211",
		issn-type: "print"
		}
           
 */
 
 
 		  case 'reference':
			var n = doc.message[i].length;
			for (var j = 0; j < n; j++) {
			  
			  // by default use key as identifier, but if we have a DOI use that
			  
			  // use key as identifier, but clean first
			  var key = doc.message[i][j].key;
			  
        key = key.replace(/\t/g, '');
        key = key.replace(/\n/g, '');

        // replace characters not allowed
        key = key.replace(/\|/g, '-');
        key = key.replace(/\//g, '-');
        key = key.replace(/\./g, '-');
        key = key.replace(/\s/g, '-');
	 
	      var reference_id = subject_id + '#' + key;
			  
			  // 
			  if (1) {
			    // if we use the DOI citatoon linking becomes more
			    // obvious, and we get to talk about the cited references
			    // using their DOIs, but we also gnerate duplicate values for 
			    // many metadata items, such as titles and dates, so we need to
			    // handle this. May want to do something such as 
			    // store titles as alternateNames to avoid problems.
			    
          // DOI?
          if (doc.message[i][j].DOI) {
 				    // DOI cleaning (sigh)
				    /* Sometimes publishes make a mess of this, e.g. 
				    http://dx.doi.org/10.1111/j.1096-0031.2007.00176.x
				    {
					  key: "b129_103",
					  DOI: "10.1636/H05-14 SC.1",
					  doi-asserted-by: "publisher"
				    }
				   */
				    var doi = doc.message[i][j].DOI;
				    doi = doi.replace(/sc.1?/g, '');
				    doi = doi.replace(/\s/g, ''); 
				    doi = doi.toLowerCase(); 
				    
				    reference_id = 'https://doi.org/' + doi;
         } 
			  }

 				// link to work
				triples.push(triple(subject_id,
				  'http://schema.org/citation',
				  reference_id));
			  
			  // type
			  triples.push(triple(reference_id,
				'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
				'http://schema.org/CreativeWork'));


               // DOI?
               if (doc.message[i][j].DOI) {
 				  // DOI cleaning (sigh)
				  /* Sometimes publishes make a mess of this, e.g. 
				  http://dx.doi.org/10.1111/j.1096-0031.2007.00176.x
				  {
					key: "b129_103",
					DOI: "10.1636/H05-14 SC.1",
					doi-asserted-by: "publisher"
				  }
				  */
				  var doi = doc.message[i][j].DOI;
				  doi = doi.replace(/sc.1?/g, '');
				  doi = doi.replace(/\s/g, ''); 
				  doi = doi.toLowerCase(); 
				  
                 var identifier_id = reference_id + '#doi';
                              
				  triples.push(triple(reference_id,
					'http://schema.org/identifier',
					identifier_id));
					
				 triples.push(triple(identifier_id,
					'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
					'http://schema.org/PropertyValue'));

				  triples.push(triple(identifier_id,
					'http://schema.org/propertyID',
					'doi'));

				  triples.push(triple(identifier_id,
					'http://schema.org/value',
					doc.message[i][j].DOI.toLowerCase()
				  ));
				              
				  
					
				 			  						
				}  
				
				// unstructured?
                if (doc.message[i][j].unstructured) {
                	var text = doc.message[i][j].unstructured;
                	text = text.replace(/\n/g, '');
				  triples.push(triple(reference_id,
					'http://schema.org/description',
					text
					));
				}               
					
				// metadata
               if (doc.message[i][j].author) {
				  triples.push(triple(reference_id,
					'http://schema.org/creator',
					doc.message[i][j].author));
				}               
				
				
               if (doc.message[i][j]['article-title']) {
				  triples.push(triple(reference_id,
					'http://schema.org/name',
					doc.message[i][j]['article-title']));
				}               
				
				// container
                if (doc.message[i][j]['journal-title']) {
 
                	// Default container id
               		var container_id = reference_id + '-container';
                 
 					// A better container_id is the ISSN               
                
                	// 1. Do we have ISSN?
                	var reference_issn = '';

                	if (doc.message[i][j].ISSN) {
                		reference_issn = doc.message[i][j].ISSN;
                		reference_issn = reference_issn.replace('http://id.crossref.org/issn/', '');                		
                	}
                	
                	// 2. To do, can we lookup ISSN?
                	if (reference_issn == '') {
                	  reference_issn = journal_to_issn(doc.message[i][j]['journal-title']);
                	}
                	              	
                	// 3. Use ISSN if we have it	
               		if (reference_issn != '') {
               			container_id = 'http://worldcat.org/issn/' + reference_issn;
               		}
               		
  				  triples.push(triple(reference_id,
					'http://schema.org/isPartOf',
					container_id));

 				  triples.push(triple(container_id,
					'http://schema.org/name',
					doc.message[i][j]['journal-title']
					));
					
				  triples.push(triple(container_id,
					'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
					'http://schema.org/Periodical'));
					
				} 
               if (doc.message[i][j].volume) {
				  triples.push(triple(reference_id,
					'http://schema.org/volumeNumber',
					doc.message[i][j].volume));
				}               				             
               if (doc.message[i][j]['first-page']) {
				  triples.push(triple(reference_id,
					'http://schema.org/pageStart',
					doc.message[i][j]['first-page']));
				}               
               if (doc.message[i][j].year) {
               		var year = doc.message[i][j].year;
                	year = year.replace(/[a-z]$/, '');
				  triples.push(triple(reference_id,
					'http://schema.org/datePublished',
					year));
				}               
				             
			  }
			
			break;
  
			

        default:
          break;
      }
    }

    if (type == '') {
      type = 'http://schema.org/CreativeWork';
    }
    

    // defaults
    triples.push(triple(subject_id,
      'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
      type));

    output(doc, triples);
  }
}

function (doc) {
  if (doc['message-format']) {
    if (doc['message-format'] == 'application/vnd.crossref-api-message+json') {
      message(doc);
    }
  }
}
// END COUCHDB VIEW
