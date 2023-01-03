<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title; ?></title>
<link rel="stylesheet" type="text/css" href="../../settings/style_python.css">
<script src="../../settings/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../../settings/functions_series_validhour_python.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php
//Read in passed url parameter(s)
$level = $_GET['level'];
$variable = $_GET['variable'];
$update_string = file_get_contents("update_timestamp.txt");
?>

<?php include "../../settings/fv3cam_verif_vars.php"; ?>

<!-- Head element -->
<div class="page-top">
        <span><?php echo $para_exp_header; ?></span>
</div>

<!-- Top menu -->
<div class="page-menu"><div class="table">
	
        <div class="element">
                <span class="bold">Plot Type:</span>
                <select id="indep" onchange="changeIndep(this.value);"></select>
        </div>
        <div class="element">
                <span class="bold">Time Period:</span>
                <select id="period" onchange="changePeriod(this.value);"></select>
        </div>
        <div class="element">
                <span class="bold">Variable:</span>
                <select id="variable" onchange="changeVariable(this.value)"></select>
        </div>
</div>

<div class="table">
        <div class="element">
                <span class="bold">Cycle:</span>
                <select id="cycle" onchange="changeCycle(this.value)"></select>
        </div>
	<div class="element">
		<span class="bold">Threshold:</span>
		<select id="level" onchange="changeLevel(this.value)"></select>
	</div>
        <div class="element">
                <span class="bold">Forecast Hour:</span>
                <select id="fhr" onchange="changeFhr(this.value)"></select>
        </div>
        <div class="element">
                <span class="bold">Statistic:</span>
                <select id="stat" onchange="changeStat(this.value)"></select>
        </div>
        <div class="element">
                <span class="bold">Verification Region:</span>
                <select id="domain" onchange="changeDomain(this.value)"></select>
        </div>

<!-- /Top menu -->
</div></div>

<!-- Middle menu -->
<div class="page-middle" id="page-middle">
Up/Down arrow keys = Change Threshold | Left/Right arrow keys = Change Statistic
<br>For details on these series plots, <button class="infobutton" id="myBtn">click here</button>
<div id="myModal" class="modal">
  <div class="modal-content" style="font-size:130%;">
    <span class="close">&times;</span>
    Reflectivity and Echo Top Series by Valid Hour Information
    <embed width=100% height=95% src="../info_series_validhour.php">
  </div>
</div>
<br>
<br>
<?php echo $update_string; ?>
<!-- /Middle menu -->
</div>

<div id="loading"><img style="width:100%" src="https://www.emc.ncep.noaa.gov/users/verification/style/images/loading.png"></div>

<!-- Image -->
<div id="page-map">
	<center><image name="map" style="width:180%"></center>
</div>

<!-- /Footer -->
<div class="page-footer">
        <span></div>

<script type="text/javascript">


// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


//====================================================================================================
//User-defined variables
//====================================================================================================

//Global variables
var minFrame = 0; //Minimum frame for every variable
var maxFrame = 32; //Maximum frame for every variable
var incrementFrame = 1; //Increment for every frame

var startFrame = 0; //Starting frame

/*
When constructing the URL below, DDD = valid time, VVV = level.
For X and Y, labeling one X or Y represents an integer (e.g. 0, 10, 20). Multiple of these represent a string
format (e.g. XX = 00, 06, 12 --- XXX = 000, 006, 012).
*/

   var url = "PERIOD/<?php echo $url_head; ?>_valid_hour_average_regional_DDD_init_CCC_VVV_SSS_FFF_TTT.png";

//====================================================================================================
//Add variables & domains
//====================================================================================================

var time_periods = [];
var indeps = [];
var levels = [];
var variables = [];
var stats = [];
var domains = [];
var fhrs = [];
var cycles = [];

/*
indeps.push({
        url: "reliability_radar.php",
        displayName: "Reliability Diagram",
        name: "reliability_radar",
});
*/
indeps.push({
        url: "performance_fcstthresh_radar.php",
        displayName: "Performance Diagram by Forecast Threshold",
        name: "performance_fcstthresh_radar",
});

indeps.push({
        url: "series_fcstthresh_radar.php",
        displayName: "Series by Forecast Threshold",
        name: "series_fcstthresh_radar",
});

indeps.push({
        url: "series_fcstlead_radar.php",
        displayName: "Series by Forecast Lead",
        name: "series_fcstlead_radar",
});

indeps.push({
        url: "series_validhour_radar.php",
        displayName: "Series by Valid Hour",
        name: "series_validhour_radar",
});


time_periods.push({
        displayName: "Recent (30 Days)",
        name: "past30days",
});
time_periods.push({
        displayName: "Recent (7 Days)",
        name: "past7days",
});
/*
time_periods.push({
        displayName: "Winter",
        name: "djf",
});
time_periods.push({
        displayName: "Spring",
        name: "mam",
});
time_periods.push({
        displayName: "Summer",
        name: "jja",
});
time_periods.push({
        displayName: "Fall",
        name: "son",
});
*/


levels.push({
        displayName: ">= 20",
        name: "ge20",
});

levels.push({
        displayName: ">= 30",
        name: "ge30",
});

levels.push({
        displayName: ">= 40",
        name: "ge40",
});

levels.push({
        displayName: ">= 50",
        name: "ge50",
});


variables.push({
        displayName: "Composite Reflectivity (dBZ)",
        name: "refc",
});

variables.push({
        displayName: "1-km AGL Reflectivity (dBZ)",
        name: "1000m_refd",
});

/*
variables.push({
        displayName: "Echo Top Height (kft)",
        name: "retop",
});
*/
/*
stats.push({
        displayName: "Brier Skill Score",
        name: "PSTD_BSS_SMPL",
});

stats.push({
        displayName: "ROC Area",
        name: "PSTD_ROC_AUC",
});
*/

stats.push({
        displayName: "Critical Success Index",
        name: "csi",
});

stats.push({
        displayName: "Frequency Bias",
        name: "fbias",
});


domains.push({
        displayName: "CONUS",
        name: "conus",
});

domains.push({
        displayName: "Appalachians",
        name: "apl",
});

domains.push({
        displayName: "Gulf of Mexico Coast",
        name: "gmc",
});

domains.push({
        displayName: "Great Basin",
        name: "grb",
});
domains.push({
        displayName: "Lower Mississippi Valley",
        name: "lmv",
});
domains.push({
        displayName: "Midwest",
        name: "mdw",
});
domains.push({
        displayName: "Northeast Coast",
        name: "nec",
});
domains.push({
        displayName: "Northern Mountain Region",
        name: "nmt",
});
domains.push({
        displayName: "Northern Plains",
        name: "npl",
});
domains.push({
        displayName: "Northwest Coast",
        name: "nwc",
});
domains.push({
        displayName: "Southeast Coast",
        name: "sec",
});
domains.push({
        displayName: "Southern Mountain Region",
        name: "smt",
});
domains.push({
        displayName: "Southern Plains",
        name: "spl",
});
domains.push({
        displayName: "Southwest Coast",
        name: "swc",
});
domains.push({
        displayName: "Southwest Desert",
        name: "swd",
});

domains.push({
        displayName: "SPC Outlooks",
        name: "spc",
});
/*
for(i=0;i<=5;i=i+1){
	fhrs.push({
        	displayName: "F"+formatString(i*12,2),
        	name: "f"+(i*12),
	});
}
*/

cycles.push({
        displayName: "00Z",
        name: "00z",
});
cycles.push({
        displayName: "12Z",
        name: "12z",
});

fhrs.push({
       	displayName: "F00 to F60",
       	name: "f0_to_f60",
});


//====================================================================================================
//Initialize the page
//====================================================================================================

//function for keyboard controls
document.onkeydown = keys;

//Decare object containing data about the currently displayed map
imageObj = {};

//Initialize the page
initialize();


//Initialize the page
function initialize(){

	//Set image object based on default variables
	imageObj = {
		indep: "series_validhour_radar",
		level: "ge30",
                cycle: "00z",
		variable: "refc",
		stat: "csi",
		domain: "conus",
		fhr: "f0_to_f60",
                time_period: "past30days",
        };
    
        //Change verification group based on passed argument, if any
        var passed_level = "<?echo $level;?>";
        if(passed_level!=""){
                if(searchByName(passed_level,levels)>=0){
                        imageObj.level = passed_level;
                }
        }

        //Change verification group based on passed argument, if any
        var passed_variable = "<?echo $variable;?>";
        if(passed_variable!=""){
                if(searchByName(passed_variable,variables)>=0){
                        imageObj.variable = passed_variable;
                }
        }


	//Populate forecast hour and dprog/dt arrays for this run and frame
	populateMenu('indep');
	populateMenu('variable');
	populateMenu('level');
        populateMenu('cycle');
	populateMenu('stat');
	populateMenu('fhr');
	populateMenu('domain');
        populateMenu('period');
	
	//Populate the frames arrays
	frames = [];
	for(i=minFrame;i<=maxFrame;i=i+incrementFrame){frames.push(i);}

	//Predefine empty array for preloading images
	for(i=0; i<levels.length; i++){
		levels[i].images = [];
		levels[i].loaded = [];
		levels[i].dprog = [];
	}
	
	//Preload images and display map
	preload(imageObj);
	showImage();
	

	//Update mobile display for swiping
	updateMobile();

}

var xInit = null;                                                        
var yInit = null;                  
var xPos = null;
var yPos = null;


</script>

<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100786126); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100786126ns.gif" /></p></noscript>


</body></html>
