<!-- HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- styles -->
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">

<!-- default image for facebook status updates -->
<meta property="og:image" content="/images/apple-touch-icon-72x72.png">
<link rel="image_src" href="/images/apple-touch-icon-72x72.png">
<!-- favorite and touch icons -->
<link rel="icon" href="/images/favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png">
<style> ul li a{ color:#005DAB !important}</style>
<?php 
//grab the current browser URL
  $page_name=pathinfo($_SERVER['PHP_SELF'],PATHINFO_DIRNAME);

$uriPage = basename($_SERVER['SCRIPT_NAME']);

$topbar = '<div class="topbar">
 <div class="fill">
  <div class="container">
   <a class="brand" href="/">RadElement.org</a>
   <div align="right" style="float:right; margin:10px">
    <a href="/about/">About this site</a>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
    <a href="/doc/">API Documentation</a>
   </div>
  </div>
 </div>
</div>';


$footer = '<footer>
<!-- <p class="copyright">Copyright (c) Radiological Society of North America.  ALL RIGHTS RESERVED.</p> -->
<div align="left" style="float:left " >
<a href="http://www.rsna.org/" target="_blank">
 <img src="/images/rsna_informatics.png" border="0"/></a>
</div>
</footer>';

?>