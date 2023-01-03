<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>EMC FV3-CAM Verification</title>
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
<link href="../../settings/main.css" rel="stylesheet" type="text/css" media="all" />
<link href="https://www.emc.ncep.noaa.gov/users/verification/style/fonts.css" rel="stylesheet" type="text/css" media="all" />
<script src="https://d3js.org/d3.v4.min.js"></script>
</head>

<?php
$randomtoken = base64_encode( openssl_random_pseudo_bytes(32));
$_SESSION['csrfToken']=$randomtoken;
?>

<body>
<br>
All plots were generated using Python.
<br>
<br>
<br>
<b><u>Surface and Upper Air Statistics:</u></b>
<br>
Continuous statistics are generated using <b>grid-to-obs verification</b>. Model fields are interpolated to <a href="https://www.nco.ncep.noaa.gov/pmb/docs/on388/grids/grid104.gif" title="" target="_blank">NCEP Grid 104</a> and verified against point data from surface and upper air observations.
<br> 
<br> 
Surface variables are verified on a 3-hourly basis, and upper air variables are only verified at 00 UTC and 12 UTC valid times.
<br>
<br>
<br>
<b><u>Ceiling and Visibility Statistics:</u></b>
<br>
Categorical statistics are generated using <b>grid-to-obs verification</b>. Model fields are interpolated to <a href="https://www.nco.ncep.noaa.gov/pmb/docs/on388/grids/grid104.gif" title="" target="_blank">NCEP Grid 104</a> and verified against point data from surface observations. Variables are verified on a 3-hourly basis.
<br>
<br>
<br>
<b><u>CAPE Statistics:</u></b>
<br>
Categorical statistics are generated using <b>grid-to-obs verification</b>. Surface-based CAPE fields are interpolated to <a href="https://www.nco.ncep.noaa.gov/pmb/docs/on388/grids/grid104.gif" title="" target="_blank">NCEP Grid 104</a> and verified against point data from upper air observations. Verification is conducted at 00 UTC and 12 UTC valid times. 
<br>
<br>
<br>
<b><u>24-h Precipitation Statistics:</u></b>
<br>
Categorical statistics are generated using <b>grid-to-grid verification</b>. 24-h QPFs are verified on <a href="https://www.nco.ncep.noaa.gov/pmb/docs/on388/grids/grid218.gif" title="" target="_blank">NCEP Grid 218</a> (dx = ~12 km) in 12Z-12Z periods.
<br><b>Included 00Z cycles are verified at F36 and F60.
<br>Included 12Z cycles are verified at F24 and F48.</b> 
<br>
<br>
<br>
<b><u>6-h Precipitation Statistics:</u></b>
<br>
Neighborhood statistics are generated using <b>grid-to-grid verification</b>. 6-h QPFs are verified on <a href="https://www.nco.ncep.noaa.gov/pmb/docs/on388/tableb.html#GRID240" title="" target="_blank">NCEP Grid 240</a> (dx = ~5 km).
<br>
<br>
<br>
<b><u>Reflectivity Statistics:</u></b>
<br>
Categorical and neighborhood statistics are generated using <b>grid-to-grid verification</b>. Model fields are interpolated to <a href="https://www.nco.ncep.noaa.gov/pmb/docs/on388/tableb.html#GRID227" title="" target="_blank">NCEP Grid 227</a> (dx = ~5 km) and verified against MRMS reflectivity observations. 
<br>
<br>
<br>
</body>
</html>
