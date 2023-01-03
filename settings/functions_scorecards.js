<!--

/* ============================================================================================================= */
/* Preloading & displaying functions */
/* ============================================================================================================= */

//Populate the dropdown menu with items
function populateMenu(mode){
	if(mode == 'baseline'){
		var element = document.getElementById("baseline");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<baselines.length; i++){
			var option = document.createElement("option");
			option.text = baselines[i].displayName;
			option.value = baselines[i].name;
			element.add(option);
		}
	}
	else if(mode == 'fv3cam'){
		var element = document.getElementById("fv3cam");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<fv3cams.length; i++){
			var option = document.createElement("option");
			option.text = fv3cams[i].displayName;
			option.value = fv3cams[i].name;
			element.add(option);
		}
	}
	else if(mode == 'domain'){
		var element = document.getElementById("domain");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<domains.length; i++){
			var option = document.createElement("option");
			option.text = domains[i].displayName;
			option.value = domains[i].name;
			element.add(option);
		}
	}
        else if(mode == 'period'){
                var element = document.getElementById("period");
                for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}

                for(i=0; i<time_periods.length; i++){
                        var option = document.createElement("option");
                        option.text = time_periods[i].displayName;
                        option.value = time_periods[i].name;
                        element.add(option);
                }
        }
	else if(mode == 'verif'){
		var element = document.getElementById("verif");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<verifs.length; i++){
			var option = document.createElement("option");
			option.text = verifs[i].displayName;
			option.value = verifs[i].name;
			element.add(option);
		}
	}
}

//Format URL to the requested domain, variable, run & frame
function getURL(baseline,fv3cam,domain,verif,time_period){
	var newurl = url.replace("VVV",verif);
	newurl = newurl.replace("DDD",domain);
	newurl = newurl.replace("FFF",fv3cam);
	newurl = newurl.replace("BBB",baseline);
	newurl = newurl.replace("PERIOD",time_period);
	return newurl;
}

//Search for a name within an object
function searchByName(keyname, arr){
    for (var i=0; i < arr.length; i++){
        if (arr[i].name === keyname){
            return i;
        }
    }
	return -1;
}

//Display the current image object
function showImage(){
	
	//Variable index
	var idx_var = searchByName(imageObj.verif,verifs);

	//Update user on whether image is still loading
	if(verifs[idx_var].images[imageObj.frame].loaded == false){
		document.getElementById('loading').style.display = "block";
	}
	else{
		document.getElementById('loading').style.display = "none";
		document.map.src = verifs[idx_var].images[imageObj.frame].src;
	}
//	document.getElementById('loading').style.display = "none";
//	document.map.src = verifs[idx_var].images[0].src;
	
	//Update dropdown menus
//	document.getElementById("valid").selectedIndex = frames.indexOf(parseInt(imageObj.frame));//(parseInt(imageObj.frame) / incrementFrame);
//	document.getElementById("baseline").selectedIndex = searchByName(imageObj.baseline,baselines);
	document.getElementById("fv3cam").selectedIndex = searchByName(imageObj.fv3cam,fv3cams);
	document.getElementById("period").selectedIndex = searchByName(imageObj.time_period,time_periods);
	document.getElementById("domain").selectedIndex = searchByName(imageObj.domain,domains);
	document.getElementById("verif").selectedIndex = searchByName(imageObj.verif,verifs);
	
	//Update URL in address bar
	generate_url();
}

//Format integer as a string by number of characters
function formatString(i,val){
	if(val==3){
		if(i<10){return "00"+i;}
		if(i<100){return "0"+i;}
		return i;
	}
}

//Preload images for the current run, variable & projection
function preload(obj){
	
	//Variable index
	var idx_var = searchByName(obj.verif,verifs);
	
	//alert(obj.variable);
	//alert(idx_var);
	
	verifs[idx_var].images[i] = [];
    verifs[idx_var].images[i] = [];
	verifs[idx_var].images[i] = [];
	
	//Arrange list of hour indices to loop through
	var frameidx = frames.indexOf(imageObj.frame);
	var hrs_loop = [frameidx];
	
	for(i=1; i<frames.length; i++){
		
		var idx_up = frameidx + i;
		var idx_down = frameidx - i;
		
		if(idx_up<=frames.indexOf(maxFrame)){hrs_loop.push(idx_up);}
		if(idx_down>=frames.indexOf(minFrame)){hrs_loop.push(idx_down);}
	}
	
	//Loop through all forecast hours & pre-load image
	for (i2=0; i2<hrs_loop.length; i2++){
//	for(i=0; i<verifs.length; i++){
		var i1 = hrs_loop[i2];
		var i = frames[i1];

		var urls = getURL(obj.baseline,obj.fv3cam,obj.domain,obj.verif,obj.time_period);
		
//		window.alert(levels[idx_var].loaded.length);
//		levels[idx_var].images[i] = new Image();
//               levels[idx_var].images[i].loaded = false;
//		levels[idx_var].images[i].id = i;
//		levels[idx_var].images[i].onload = function(){this.loaded = true; remove_loading(this.varid,this.id);};
//		levels[idx_var].images[i].onerror = function(){remove_loading(this.varid,this.id);};
//		levels[idx_var].images[i].src = urls;
//		levels[idx_var].images[i].level = obj.level;
//		levels[idx_var].images[i].varid = idx_var;

		verifs[idx_var].images[i] = new Image();
                verifs[idx_var].images[i].loaded = false;
		verifs[idx_var].images[i].id = i;
		verifs[idx_var].images[i].onload = function(){this.loaded = true; remove_loading(this.varid,this.id);};
//		verifs[idx_var].images[i].onerror = function(){remove_loading(this.varid,this.id);};
                verifs[idx_var].images[i].onerror = function(){remove_loading(this.varid,this.id); this.src='../../../../../style/images/noimage.png';};
		verifs[idx_var].images[i].src = urls;
		verifs[idx_var].images[i].verif = obj.verif;
		verifs[idx_var].images[i].varid = idx_var;
    }
}

//Remove sign of loading image
function remove_loading(idx_var,idx_frame){
	check1a = parseInt(idx_var);
	check1b = searchByName(imageObj.verif,verifs);
	check2a = frames.indexOf(parseInt(idx_frame));
	check2b = frames.indexOf(parseInt(imageObj.frame));
	
	//Remove if the image just loaded for the currently displayed image
	if((check1a == check1b) && (check2a == check2b)){
		document.getElementById('loading').style.display = "none";
		document.map.src = verifs[idx_var].images[imageObj.frame].src;
	}
}

/* ============================================================================================================= */
/* Dropdown menu functions */
/* ============================================================================================================= */

//Change the valid frame from dropdown menu
function changeValid(id){
	imageObj.frame = id;
	
	//Determine if need to preload (coming off of dprog/dt)
	//if(imageObj.images[0].run != imageObj.run){preload(imageObj);}
	
//	showImage();
//	document.getElementById("valid").blur();
}

//Change the map valid time from dropdown menu
function changeValidTime(id){
	imageObj.validtime = id;
	preload(imageObj);
	showImage();
	document.getElementById("validtime").blur();
}


//Change the baseline model
function changeBaseline(id){
	var newUrl = baselines[searchByName(id,baselines)].url;
	window.open(newUrl,"_self");
}

//Change the FV3-CAM from dropdown menu
function changeFV3(id){
	imageObj.fv3cam = id;
	preload(imageObj);
	showImage();
	document.getElementById("fv3cam").blur();
}

//Change the domain from dropdown menu
function changeDomain(id){
	imageObj.domain = id;
	preload(imageObj);
	showImage();
	document.getElementById("domain").blur();
}

//Change the verification from dropdown menu
function changeVerif(id){
	imageObj.verif = id;
	preload(imageObj);
	showImage();
	document.getElementById("verif").blur();
}

//Change the time period from dropdown menu
function changePeriod(id){
        imageObj.time_period = id;
        preload(imageObj);
        showImage();
        document.getElementById("period").blur();
}


/* ============================================================================================================= */
/* Keyboard controls */
/* ============================================================================================================= */

function keys(e){
	//Left
	if(e.keyCode == 37){
//		prevFrame();
		pressLeft();
		return !(e.keyCode);
	}
	//Up
	else if(e.keyCode == 38){
		pressUp();
		return !(e.keyCode);
	}
	//Right
	else if(e.keyCode == 39){
//		nextFrame();
		pressRight();
		return !(e.keyCode);
	}
	//Down
	else if(e.keyCode == 40){
		pressDown();
		return !(e.keyCode);
	}
}

function prevFrame(){
/*	var curFrame = parseInt(imageObj.frame);
	if(curFrame > minFrame){curFrame = curFrame - incrementFrame;}
	changeValid(curFrame); */

        var curFrame = parseInt(imageObj.frame);
	if(curFrame > minFrame){curFrame = curFrame - incrementFrame; changeValid(curFrame);} 
	if(curFrame == minFrame){curFrame = maxFrame; changeValid(curFrame);} 

	var curValid = searchByName(imageObj.validtime,validtimes);
	if(curValid > 0){curValid = curValid - 1; changeValidTime(validtimes[curValid].name);}
	if(curValid == 0){curValid = validtimes.length-1; changeValidTime(validtimes[curValid].name);}
}

function nextFrame(){
/*	var curFrame = parseInt(imageObj.frame);
	if(curFrame < maxFrame){curFrame = curFrame + incrementFrame;}
	changeValid(curFrame); */

        var curFrame = parseInt(imageObj.frame);
	if(curFrame < maxFrame){curFrame = curFrame + incrementFrame; changeValid(curFrame);} 
	if(curFrame == maxFrame){curFrame = minFrame; changeValid(curFrame);} 
         
	var curValid = searchByName(imageObj.validtime,validtimes);
	if(curValid < validtimes.length-1){curValid += 1; changeValidTime(validtimes[curValid].name);}
	if(curValid == validtimes.length-1){curValid = 0; changeValidTime(validtimes[curValid].name);}

}

function pressDown(){
	var curVar = searchByName(imageObj.verif,verifs);
	if(curVar < verifs.length-1){curVar += 1; changeVerif(verifs[curVar].name);}
}

function pressUp(){
	var curVar = searchByName(imageObj.verif,verifs);
	if(curVar > 0){curVar = curVar - 1; changeVerif(verifs[curVar].name);}
}

function pressRight(){
	var curFV3 = searchByName(imageObj.fv3cam,fv3cams);
	if(curFV3 < fv3cams.length-1){curFV3 += 1; changeFV3(fv3cams[curFV3].name);}
}

function pressLeft(){
	var curFV3 = searchByName(imageObj.fv3cam,fv3cams);
	if(curFV3 > 0){curFV3 = curFV3 - 1; changeFV3(fv3cams[curFV3].name);}
}


/* ============================================================================================================= */
/* SPC functions */
/* ============================================================================================================= */
var mode_animate = 0; // Animation off by default
var id_animate = 0; // Animation off by default
var animation_speed = 500; // animation speed

function animationToggle(){
   mode_animate = (mode_animate*-1)+1;
   if (mode_animate == 1){animate();}
   if (mode_animate == 0){clearInterval(id_animate);}
}


function animate(){
	if(mode_animate == 1){
		var delay = animation_speed;
		id_animate = setInterval(nextFrame, delay);
	}
}



/* ============================================================================================================= */
/* Additional functions */
/* ============================================================================================================= */

//Update the URL in the address bar by the current domain and variable
function generate_url(){
	
	var url = window.location.href.split('?')[0] + "?";
	var append = "";
	
	//Add domain
	append += "fv3cam=" + imageObj.fv3cam;
	
	//Add domain
	append += "&domain=" + imageObj.domain;
	
	//Add verif
	append += "&verif=" + imageObj.verif;
	
	//Get new URL
	var total = url + append;
	
	//Update in address bar without reloading page
	var pagename = window.location.href.split('/');
	pagename = pagename[pagename.length-1];
	pagename = pagename.split(".php")[0];
	var stateObj = { foo: "bar" };
	history.replaceState(stateObj, "", pagename+".php?"+append);
	
	//Update selected menu item based on this
	document.getElementById('baseline').selectedIndex = searchByName(pagename,baselines);

	return total;
}

function updateMobile(){
	if( navigator.userAgent.match(/Android/i)
	|| navigator.userAgent.match(/webOS/i)
	|| navigator.userAgent.match(/iPhone/i)
	|| navigator.userAgent.match(/iPod/i)
	//|| navigator.userAgent.match(/iPad/i)
	|| navigator.userAgent.match(/BlackBerry/i)
	|| navigator.userAgent.match(/Windows Phone/i)
	){
		document.getElementById('page-middle').innerHTML = "Swipe Up/Down = Change level | Swipe Left/Right = Change valid time";
	}


	//Swipe for mobile devices only when focused on image
	var element = document.getElementsByName("map")[0];
	element.addEventListener("touchstart", touchStart, false);
	element.addEventListener("touchend", touchEnd, false);
	element.addEventListener("touchmove", touchMove, false);

}

function touchStart(e){
    xInit = e.touches[0].clientX;
    yInit = e.touches[0].clientY;
};

function touchMove(e){
	e.preventDefault();
    xPos = e.touches[0].clientX;
    yPos = e.touches[0].clientY;
};

function touchEnd() {
    if ( ! xPos || ! yPos ) {
        return;
    }
	
    //Get difference in x & y positions
    var xDiff = xInit - xPos;
    var yDiff = yInit - yPos;
	
	//Determine whether swipe was vertical or horizontal
    if ( Math.abs(xDiff) > Math.abs(yDiff) ){
        if( xDiff > 0 ){
            //Left swipe
			nextFrame();
        }
		else{
            //Right swipe
			prevFrame();
        }                       
    }
	else{
        if ( yDiff > 0 ){
            //Up swipe
			pressDown();
        }
		else{ 
            //Down swipe
			pressUp();
        }                                                                 
    }
	
    //reset values
    xInit = null;
    yInit = null;  
	xPos = null;
	yPos = null;
};

-->
