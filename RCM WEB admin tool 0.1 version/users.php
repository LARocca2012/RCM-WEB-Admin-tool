<?php
function hx($sc)
{
    $sc = str_replace(array(
        "new.php"
    ), '', $sc);
    return $sc . "";
}
$x_ff = 0;
$cpath = hx(__FILE__);

error_reporting(E_ALL);
ini_set('display_errors','On');

// COD gameserver ip, port and rcon pass
$server_ip = '192.168.1.102';
$server_port = '27770';
$server_rconpass = 'xopge_)HG7###oRRTY';

$fxx = 0;

function colorx($string) {
$string = substr($string, 0, 777);
$string = str_replace("^^00", "</font><font color=\"gray\">", $string);
$string = str_replace("^^11", "</font><font color=\"red\">", $string);
$string = str_replace("^^22", "</font><font color=\"lime\">", $string);
$string = str_replace("^^33", "</font><font color=\"yellow\">", $string);
$string = str_replace("^^44", "</font><font color=\"blue\">", $string);
$string = str_replace("^^55", "</font><font color=\"aqua\">", $string);
$string = str_replace("^^66", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^^77", "</font><font color=\"white\">", $string);
$string = str_replace("^^88", "</font><font color=\"pink\">", $string);
$string = str_replace("^^99", "</font><font color=\"#ddd\">", $string);
$string = str_replace("^^00", "</font><font color=\"gray\">", $string);
$string = str_replace("^^1", "</font><font color=\"red\">", $string);
$string = str_replace("^^2", "</font><font color=\"lime\">", $string);
$string = str_replace("^^3", "</font><font color=\"yellow\">", $string);
$string = str_replace("^^4", "</font><font color=\"blue\">", $string);
$string = str_replace("^^5", "</font><font color=\"aqua\">", $string);
$string = str_replace("^^6", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^^7", "</font><font color=\"white\">", $string);
$string = str_replace("^^8", "</font><font color=\"pink\">", $string);
$string = str_replace("^^9", "</font><font color=\"#ddd\">", $string);
$string = str_replace("^11", "</font><font color=\"red\">", $string);
$string = str_replace("^22", "</font><font color=\"lime\">", $string);
$string = str_replace("^33", "</font><font color=\"yellow\">", $string);
$string = str_replace("^44", "</font><font color=\"blue\">", $string);
$string = str_replace("^55", "</font><font color=\"aqua\">", $string);
$string = str_replace("^66", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^77", "</font><font color=\"white\">", $string);
$string = str_replace("^88", "</font><font color=\"pink\">", $string);
$string = str_replace("^99", "</font><font color=\"#ddd\">", $string);
$string = str_replace("^0", "</font><font color=\"gray\">", $string);
$string = str_replace("^1", "</font><font color=\"red\">", $string);
$string = str_replace("^2", "</font><font color=\"lime\">", $string);
$string = str_replace("^3", "</font><font color=\"yellow\">", $string);
$string = str_replace("^4", "</font><font color=\"blue\">", $string);
$string = str_replace("^5", "</font><font color=\"aqua\">", $string);
$string = str_replace("^6", "</font><font color=\"fuchsia\">", $string);
$string = str_replace("^7", "</font><font color=\"white\">", $string);
$string = str_replace("^8", "</font><font color=\"pink\">", $string);
$string = str_replace("^9", "</font><font color=\"#ddd\">", $string);
//$string = str_replace("^", "", $string);
return $string . "</font>";
}
////////////////////////////////////////////////////////////////
// do not modify below here

usleep(998000);


/* - GEO - */
/* - SX GEO - */
include("SxGeo.php"); 
?>
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Lynx_Lynx" />

	<title>RCM ADMINTOOL</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" charset="utf-8"></script>
 
<style type="text/css">
h3 {
    margin-top: 50px;
}
.tooltip {
    position: relative;
    background: #eaeaea;
    cursor: help;
    display: inline-block;
    text-decoration: none;
    color: #222;
    outline: none;
    text-indent: 0;
    padding: 0 3px;
}
.tooltip:hover:before {
    content: attr(data-title);
    white-space: pre-line;
    position: absolute;
    bottom: 30px;
    left: 50%;
    z-index: 9999;
    width: 230px;
    margin-left: -127px;
    padding: 10px;
    border: 2px solid #ccc;
    opacity: .9;
    background-color: #ddd;
    background-image: -webkit-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -moz-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));
    -moz-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    box-shadow: 0 1px 2px rgba(0,0,0,.4), 0 1px 0 rgba(255,255,255,.5) inset;
    text-shadow: 0 1px 0 rgba(255,255,255,.4);
}
.tooltip:hover:after {
    content: "";
    position: absolute;
    z-index: 10000;
    bottom: 24px;
    left: 50%;
    margin-left: -8px;
    border-top: 8px solid #ddd;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 0;
}
/* Yellow */
.yellow-tooltip:hover:before {
    border-color: #e1ca82;
    background-color: #ffeaa6;
}
.yellow-tooltip:hover:after {
    border-top-color: #ffeaa6;
}
</style>



<style>
 /*demo page style*/
    body {background:url(data:image/png;base64,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);
}
    h1{
      font-family:"Myriad Pro","Helvetica Neue",Helvetica,Arial,Sans-Serif;
      text-shadow: rgba(0, 0, 0, 0.498039) 0px 1px 1px;
    color: #cc0033;
      /* The logo text */
      font-size:3.5em;
      padding:0.5em 0 0;
      text-transform:uppercase;
        margin:0; padding:0;
    text-align: left;
    }
     
    p {line-height:1.5em; padding-bottom:1em;}
     
    #wrapper {
        width:810px;
        margin:0 auto;
        padding: 0;
    }
     
    #header {height: 150px;}
     
    #logo {height: 100px;}
     
/*main menu*/
    #menu {
        text-transform: uppercase;
        text-align: center;
        line-height: 50px;
background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,0.9) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0.9)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,0.9) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
border: 1px solid #DDD;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
-webkit-box-shadow:1px 2px 8px #9A9A9A;
-moz-box-shadow:1px 2px 8px #9A9A9A;
 
    }
    #menu ul {padding:0; margin:0;}
    #menu li{
        display: inline;
        list-style:none;
        margin: 5px 10px;
    font-size: 1.5em;
    }
     
    #menu li a {
        padding:5px 10px;
        color:#bbb;
        text-decoration: none;
        text-shadow: rgba(0, 0, 0, 0.6) 0px 1px 1px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
 
    }
     
    #menu li a:hover{
        background: #eee;
        color: #C03;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-ms-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
-o-box-shadow: inset 0 1px 3px rgba(0,0,0,0.25), 0 1px 1px rgba(255,255,255,0.35);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.25), 0 1px 1px rgba(255, 255, 255, 0.35);
        -webkit-transition-property: color, background;
        -webkit-transition-duration: 0.5s, 0.5s;
    }
    .default {
        width:810px;
    }
    .fixed {
        position:fixed;
        top:-5px; left:0;
        width:100%;
        padding:10px 0;
         
        -moz-box-shadow: 5px 5px 20px #333;
        -webkit-box-shadow: 5px 5px 20px #333;
        box-shadow: 5px 5px 20px #333;
    }
    .transbg {
       background: rgb(255,255,255); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(238,238,238,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(238,238,238,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(238,238,238,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        
        var $menu = $("#menu");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });//scroll
        
        $menu.hover(
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).removeClass('transbg');
                }
            },
            function(){
                if( $(this).hasClass('fixed') ){
                    $(this).addClass('transbg');
                }
            });//hover
    });//jQuery
</script>
<style>
	.modalDialog {
		position: fixed;
		font-family: Arial, Helvetica, sans-serif;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0,0,0,0.8);
		z-index: 99999;
		-webkit-transition: opacity 400ms ease-in;
		-moz-transition: opacity 400ms ease-in;
		transition: opacity 400ms ease-in;
		display: none;
		pointer-events: none;
	}

	.modalDialog:target {
		display: block;
		pointer-events: auto;
	}

	.modalDialog > div {
		width: 400px;
		position: relative;
		margin: 10% auto;
		padding: 5px 20px 13px 20px;
		border-radius: 10px;
		background: #fff;
		background: -moz-linear-gradient(#fff, #999);
		background: -webkit-linear-gradient(#fff, #999);
		background: -o-linear-gradient(#fff, #999);
	}

	.close {
		background: #606061;
		color: #FFFFFF;
		line-height: 25px;
		position: absolute;
		right: -12px;
		text-align: center;
		top: -10px;
		width: 24px;
		text-decoration: none;
		font-weight: bold;
		-webkit-border-radius: 12px;
		-moz-border-radius: 12px;
		border-radius: 12px;
		-moz-box-shadow: 1px 1px 3px #000;
		-webkit-box-shadow: 1px 1px 3px #000;
		box-shadow: 1px 1px 3px #000;
	}

	.close:hover { background: #00d9ff; }


/*Всплывающий блок CSS*/
.thumbnail_my{
position: relative;
z-index: 0;
}
.thumbnail_my:hover{
background-color: transparent;
z-index: 70;
}
.thumbnail_my span{ /*CSS for enlarged image*/
position: absolute;
background-color: #3d3d3d;
padding: 15px;
left: 1200px;
border: 1px solid white;
visibility: hidden;
color: Yellow;
text-decoration: none;
border-radius: 4px 4px 4px 4px;
-moz-border-radius: 4px 4px 4px 4px;
-webkit-border-radius: 4px 4px 4px 4px;
}
.thumbnail_my span img{ /*CSS for enlarged image*/
border-width: 0;
padding: 20px;
}
.thumbnail_my:hover span{ /*CSS for enlarged image on hover*/
visibility: visible;
top: 0;
left: -240px; /*position where enlarged image should offset horizontally */
}
/*---------------------*/
</style>		
</head>
<body>

<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h1>RCM Web Admintool</h1>
        </div>
        <div id="menu" class="default">
            <ul>
<?php
include('menux.php');
?>
            </ul>
        </div>
    </div>
    <div id="content">
 

<?php
echo <<<MEMBERS
			<br/>
			 
			<br/>
			<div align="right">{$pages}<br><br>
				<form method="post" action="tools.php?action=members">
					<div style="float:left;">{$fm->LANG['PrintBy']}
						<input name="pg" type="text" value="{$per_page}" size="2">{$fm->LANG['UsersByList']}
						{$fm->LANG['SortBy']}
					</div>&nbsp;
					<select name="s" class="dats">
						<option value="d"{$d_selected}>{$fm->LANG['SortByJoin']}</option>
						<option value="p"{$p_selected}>{$fm->LANG['SortByPost']}</option>
						<option value="n"{$n_selected}>{$fm->LANG['SortByName']}</option>
					</select>&nbsp;&nbsp;
					<select name="order" class="dats">
						<option value="ASC"{$ASC_selcted}>{$fm->LANG['SortASC']}</option>
						<option value="DESC"{$DESC_selcted}>{$fm->LANG['SortDESC']}</option>
					</select>&nbsp;&nbsp;
					<input type="submit" name="submit" value="{$fm->LANG['Sorting']}">
				</form>
			</div>
			<br/>
			<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
				<tr>
					<td class="maintitle" colspan="8"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['Memberlist']}</td>
				</tr>
				<tr class="postlinksbar" align="center">
					<td width="20%" height="29">{$fm->LANG['Name']}</td>
					<td width="15%">{$fm->LANG['Status']}</td>
					<td width="15%">{$fm->LANG['PostsTotal']}</td>
					<td width="15%">{$fm->LANG['RegedDate']}</td>
					<td width="15%">{$fm->LANG['From']}</td>
					<td width="8%">E-mail</td>
					<td width="8%">WWW</td>
					<td width="4%">ICQ</td>
				</tr>
				{$members_data}
				<tr>
					<td class="activeuserstrip" align="center" colspan="8">&nbsp;</td>
				</tr>
			</table>
			<br>
			<div align="right">
				<form method="post" action="tools.php?action=members">
					<div style="float:left;">{$fm->LANG['PrintBy']}
						<input name="pg" type="text" value="{$per_page}" size="2">{$fm->LANG['UsersByList']}
						{$fm->LANG['SortBy']}
					</div>&nbsp;
					<select name="s" class="dats">
						<option value="d"{$d_selected}>{$fm->LANG['SortByJoin']}</option>
						<option value="p"{$p_selected}>{$fm->LANG['SortByPost']}</option>
						<option value="n"{$n_selected}>{$fm->LANG['SortByName']}</option>
					</select>&nbsp;&nbsp;
					<select name="order" class="dats">
						<option value="ASC"{$ASC_selcted}>{$fm->LANG['SortASC']}</option>
						<option value="DESC"{$DESC_selcted}>{$fm->LANG['SortDESC']}</option>
					</select>&nbsp;&nbsp;
					<input type="submit" name="submit" value="{$fm->LANG['Sorting']}">
				</form>
				<br><br>{$pages}
			</div>
MEMBERS;
?>


    </div>
</div>
</body>
</html>
