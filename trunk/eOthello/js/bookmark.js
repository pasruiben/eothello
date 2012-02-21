var txt = "Bookmark Us!";
var url = "http://www.eothello.com";
var who = "eOthello: the Othello battleground";

var ver = navigator.appName;
var num = parseInt(navigator.appVersion);

if ((ver == "Microsoft Internet Explorer") && (num >= 4)) 
{			
	document.write('<a href="javascript:window.external.AddFavorite(url,who);">' + txt + '</a>');								
}		
else if (window.sidebar)
{
	document.write('<a href="javascript:window.sidebar.addPanel(who, url, \'\');">'+ txt + '</a>');												
}
else
{
	txt += "  (Ctrl+D)";
	document.write(txt);
}