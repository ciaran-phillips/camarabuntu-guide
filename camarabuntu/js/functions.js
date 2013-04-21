/*
 * This is the path to the location of this folder
 *
 * It's used because the javascript library which opens local files seems to
 * need absolute paths, rather than relative paths.
 *
 * trailing slash
 */
var rootPath = "/camarabuntu/";

/*
 * Location of the share drive on the server
 * This location will be opened by nautilus (the file manager) if the share drive option is clicked on the
 * homepage
 * 
 */
var serverPath = "/camarabuntu/";



var tessaPath = "/camarabuntu/tessa/index.html";
var wikipediaPath = "localhost:50900";
var rachelPath = "http://rachel.worldpossible.org";


function getFile() {
    var test = $.twFile.load(rootPath + "xml/data2.json");
    var jsonObj;    
    eval("jsonObj = " + test + ";");
    buildTree(jsonObj);
}

function getArticle(title) {
    var test = $.twFile.load(rootPath + "xml/data2.json");
    var jsonObj;
    eval("jsonObj = " + test + ";");
    for (var art in jsonObj["articles"]) {
        if (jsonObj["articles"][art]["name"] == title) {
            var name = jsonObj["articles"][art]["name"];
            if (jsonObj["articles"][art]["full_desc"] != "")
                var description = jsonObj["articles"][art]["full_desc"];
            else
                var description = jsonObj["articles"][art]["desc"];
            var image = '<img id="screenshot" src="img/screenshots/'
                        + title.toLowerCase() + '.png"/>';
            var command = jsonObj["articles"][art]["command"];
            document.getElementById("content").innerHTML = image;
            document.getElementById("content").innerHTML += description;
            document.getElementById("appHeading").innerHTML = name;
            document.getElementById("runButton").href = "camara:" + command;
        }
    }
}
function buildTree(jsonObj) {
    
    tree = new Object();
    tree["edulevels"] = new Array();
    for (var edu in jsonObj["edulevels"]) {
        tree["edulevels"][edu] = new Array();
        tree["edulevels"][edu]["categories"] = new Array();
        
        tree["edulevels"][edu].push(jsonObj["edulevels"][edu]);
        for (var cat in jsonObj["categories"]) {
            
            
            var newObject = jQuery.extend(true, {}, jsonObj["categories"][cat]);
            tree["edulevels"][edu]["categories"].push(newObject);
            
            tree["edulevels"][edu]["categories"][cat]["articles"] = new Array();
            for (var article in jsonObj["articles"]) {
                var hasCat = checkCategories(jsonObj["articles"][article], jsonObj["categories"][cat]);
                
                var hasEdu = checkEdulevels(jsonObj["articles"][article], jsonObj["edulevels"][edu]);
                
                
                if (hasCat && hasEdu) {
                    tree["edulevels"][edu]["categories"][cat]["articles"].push(jsonObj["articles"][article]);
                }
            }
        }
    }
    showEdulevels();
}

function checkCategories(article, category) {
    var articleHasCategory = false;
    for (var cat in article["categories"]) {
        if (article["categories"][cat] == category["id"])
            articleHasCategory = true;
    }
    return articleHasCategory;
}


function checkEdulevels(article, edulevel) {
    var articleHasEdulevel = false;
    for (var edu in article["edulevels"]) {
        if (article["edulevels"][edu] == edulevel["id"]) {
            articleHasEdulevel = true;
        }
    }
    return articleHasEdulevel;
}


function showEdulevels() {
    hideAppDiv();
    hideAppDesc();
    hideSubjectDiv();
    
    var elem = document.getElementById("eduMenu");
    var contentString = "";
    for (var edu in tree["edulevels"]) {
        var title = tree["edulevels"][edu]["0"]["name"];
        contentString += "<a class='container small' href='javascript:showSubjects("+edu+")'>"
                        + "<div class='imgHolder'><img src='img/edulevel_logos/"
                        + tree["edulevels"][edu]["0"]["name"].toLowerCase()
                        + ".png' /></div>"
                        + "<span>"+title+"</span></a>";
    }
    elem.innerHTML = contentString;
}


function showSubjects(level) {
    hideAppDiv();
    hideAppDesc();
    
    displaySubjectDiv();
    var elem = document.getElementById("subjectMenu");
    var contentString = "<a class='backlink backlink2' href='#header'>Back to Top</a>";
    for (var cat in tree["edulevels"][level]["categories"]) {
        if (tree["edulevels"][level]["categories"][cat]["articles"].length > 0){
            contentString += "<a class='container small' href='javascript:showApps(" + level + "," + cat + ")'>"
                        + "<div class='imgHolder'><img src='img/category_logos/"
                        + decode(tree["edulevels"][level]["categories"][cat]["name"].toLowerCase())
                        + ".jpg' />"
                        + "</div><span>"
                        + tree["edulevels"][level]["categories"][cat]["name"]
                        + "</span></a>";
        }
    }
    if (contentString == "")
        contentString = "<p>No resources currently available for this teaching level</p>";
    elem.innerHTML = contentString;
    goToByScroll("subjectMenu");
}

function decode(str) {
    return str.replace("&amp;", "&");
   
}


function showApps(level,cat) {
    hideAppDesc();
    displayAppDiv();
    var elem = document.getElementById("appMenu");
    var contentString = "<a class='backlink backlink2' href='#header'>Back to Top</a>";
    for (var app in tree["edulevels"][level]["categories"][cat]["articles"]) {
        contentString += "<a class='container small' "
                    + "href='javascript:showAppDesc(" + level + "," + cat + "," + app +")'>"
                    + "<div class='imgHolder'><img src='img/app_logos/"
                    + tree["edulevels"][level]["categories"][cat]["articles"][app]["name"].toLowerCase().replace("/","")
                    + ".png' />"
                    + "</div><span>"
                    + tree["edulevels"][level]["categories"][cat]["articles"][app]["name"]
                    + "</span></a>";
    }
    elem.innerHTML = contentString;
    goToByScroll("appMenu");
}


function showAppDesc(level, cat, app) {
    var elem = document.getElementById("appDescDiv");
    var name = tree["edulevels"][level]["categories"][cat]["articles"][app]["name"];
    var desc = tree["edulevels"][level]["categories"][cat]["articles"][app]["desc"];
    var icon = tree["edulevels"][level]["categories"][cat]["articles"][app]["icon"];
    var command = tree["edulevels"][level]["categories"][cat]["articles"][app]["command"];
    var contentString = "";
    contentString += "<h2>" + name + "</h2><a class='backlink backlink2' href='#header'>Back to Top</a>";
    //contentString += "<img src ='file://" + icon + "' />";
    contentString += "<p>" + desc + "</p>";
    contentString += "<p><a class='button' href='"
                    + "application.html?title=" + name
                    + "'>Full Description</a>";
    
    contentString += "<span id='loading'></span><a class='button' id='runButton' href='"
                    + "camara:" + command
                    + "'>Run Program</a></p>";
    
    elem.innerHTML = contentString;
    displayAppDesc();
    goToByScroll("appMenu");
	addRunScript();
}

function goToByScroll(id){
      // Scroll
    $('html,body').animate({
        scrollTop: $("#"+id).offset().top},
        'slow');
}
function addRunScript() {     
	$('#runButton').click(function() {
			var timer = setInterval(function() {
					elem.innerHTML = "";
					clearInterval(timer);
				}, 10000);
			var elem = document.getElementById("loading");
			
			elem.innerHTML = "<img src='img/loading.gif' alrt='loading' />";
		
		});
}
function hideAppDiv() {
    var elem = document.getElementById("appDiv");
    elem.style.display = "none";
}
function displayAppDiv() {
    var elem = document.getElementById("appDiv");
    elem.style.display = "block";
}
function hideSubjectDiv() {
    var elem = document.getElementById("subjectDiv");
    elem.style.display = "none";
}
function displaySubjectDiv() {
    var elem = document.getElementById("subjectDiv");
    elem.style.display = "block";
}
function hideAppDesc() {
    var elem = document.getElementById("appDescDiv");
    elem.style.display = "none";
}
function displayAppDesc() {
    var elem = document.getElementById("appDescDiv");
    elem.style.display = "block";
}
