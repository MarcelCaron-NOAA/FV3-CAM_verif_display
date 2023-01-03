<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>EMC FV3-CAM Verification</title>
<link rel="stylesheet" type="text/css" href="../../settings/style.css">
<script src="../../settings/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../../settings/functions_scorecards.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php
//Read in passed url parameter(s)
$fv3cam = $_GET['fv3cam'];
$domain = $_GET['domain'];
$verif = $_GET['verif'];
?>

<?php include "../../settings/fv3cam_verif_vars.php"; ?>

<!-- Head element -->
<div class="page-top">
        <span><?php echo $da_exp_header; ?></span>
</div>

<!-- Top menu -->
<div class="page-menu"><div class="table">
	
	<div class="element">
		<span class="bold">FV3-CAM Configuration:</span>
		<select id="fv3cam" onchange="changeFV3(this.value)"></select>
	</div>
        <div class="element">
                <span class="bold">Baseline Model:</span>
                <select id="baseline" onchange="changeBaseline(this.value);"></select>
        </div>
</div>

<div class="table">
        <div class="element">
                <span class="bold">Time Period:</span>
                <select id="period" onchange="changePeriod(this.value);"></select>
        </div>
	<div class="element">
		<span class="bold">Domain:</span>
		<select id="domain" onchange="changeDomain(this.value)"></select>
	</div>
	<div class="element">
		<span class="bold">Verification Grouping:</span>
		<select id="verif" onchange="changeVerif(this.value)"></select>
	</div>

<!-- /Top menu -->
</div></div>

<!-- Middle menu -->
<div class="page-middle" id="page-middle">
Left/Right arrow keys = Change FV3-CAM Configuration | Up/Down arrow keys = Change Verification Grouping
<br>For details on these scorecards, <button class="infobutton" id="myBtn">click here</button>
<div id="myModal" class="modal">
  <div class="modal-content" style="font-size:130%;">
    <span class="close">&times;</span>
    Scorecard Information
    <embed width=100% height=95% src="info.php">
  </div>
</div>
<br>
<br>
Plots updated at 2156 UTC 10 August 2021
</div>

<div id="loading"><img style="width:100%" src="../../images/loading.png"></div>

<!-- Image -->
<div id="page-map">
	<center><image name="map" style="width:75%"></center>
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

   var url = "PERIOD/FFF_vs_BBB_DDD_VVV_scorecard.png";

//====================================================================================================
//Add variables & domains
//====================================================================================================

var time_periods = [];
var baselines = [];
var fv3cams = [];
var domains = [];
var verifs = [];


baselines.push({
        url: "scorecard_vs_fv3lam.php",
        displayName: "FV3LAM",
        name: "scorecard_vs_fv3lam",
});

baselines.push({
        url: "scorecard_vs_fv3lam-da.php",
        displayName: "FV3LAM-DA",
        name: "scorecard_vs_fv3lam-da",
});


fv3cams.push({
        displayName: "FV3LAM-DA-X",
        name: "fv3lam-da-x",
});


time_periods.push({
        displayName: "LAM-DA-X Exp.",
        name: "past30days",
});


domains.push({
        displayName: "CONUS",
        name: "conus",
});


verifs.push({
        displayName: "Upper Air",
        name: "upper",
});

verifs.push({
        displayName: "Surface",
        name: "sfc",
});

verifs.push({
        displayName: "Ceiling & Visibility",
        name: "cv",
});

verifs.push({
        displayName: "CAPE",
        name: "cape",
});

verifs.push({
        displayName: "24-h Precipitation",
        name: "24hpcp",
});

verifs.push({
        displayName: "6-h Precipitation",
        name: "6hpcp",
});

verifs.push({
        displayName: "Composite Reflectivity",
        name: "refc",
});

verifs.push({
        displayName: "Echo Top Height",
        name: "retop",
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
		baseline: "fv3lam-da",
		fv3cam: "fv3lam-da-x",
                time_period: "exp_period",
		domain: "conus",
		verif: "sfc",
	};
	
        //Change FV3-CAM based on passed argument, if any
        var passed_fv3cam = "<?echo $fv3cam;?>";
        if(passed_fv3cam!=""){
                if(searchByName(passed_fv3cam,fv3cams)>=0){
                        imageObj.fv3cam = passed_fv3cam;
                }
        }

        //Change domain based on passed argument, if any
        var passed_domain = "<?echo $domain;?>";
        if(passed_domain!=""){
                if(searchByName(passed_domain,domains)>=0){
                        imageObj.domain = passed_domain;
                }
        }

        //Change verification group based on passed argument, if any
        var passed_verif = "<?echo $verif;?>";
        if(passed_verif!=""){
                if(searchByName(passed_verif,verifs)>=0){
                        imageObj.verif = passed_verif;
                }
        }

	
	//Populate forecast hour and dprog/dt arrays for this run and frame
	populateMenu('baseline');
	populateMenu('fv3cam');
        populateMenu('period');
	populateMenu('domain');
	populateMenu('verif');
	
	//Populate the frames arrays
	frames = [];
	for(i=minFrame;i<=maxFrame;i=i+incrementFrame){frames.push(i);}

	//Predefine empty array for preloading images
	for(i=0; i<verifs.length; i++){
		verifs[i].images = [];
		verifs[i].loaded = [];
		verifs[i].dprog = [];
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
