'use strict';

var stickyNote, sticky, pointY, pointX;
var stickyMaxZIndex = 100;

function init(){
    document.getElementById('newStickyNote').addEventListener('click', createNote);
    document.addEventListener('mousemove', dragSticky);
    document.addEventListener('mouseup', dragEnded);
    getAll();
}

function StickyNote(id, content, positionX, positionY){
    this.id = id;
    this.content = content;
    this.positionX = positionX;
    this.positionY = positionY;
}

function dragStickyStart(event){
    pointY =  this.getBoundingClientRect().top - event.clientY;
    pointX =  this.getBoundingClientRect().left - event.clientX;

    sticky = this;

    stickyMaxZIndex += 1;
    sticky.style.zIndex = stickyMaxZIndex;
}

function dragSticky(event){
    if(sticky == null){
        return;
    }
    var positionX = event.clientX + pointX;
    var positionY = event.clientY + pointY;
    sticky.style.left = positionX;
    sticky.style.top = positionY;
}

function getAll(){
    var http = new XMLHttpRequest();
    var url = 'getAllSticky.php';
    
    http.open('POST', url, true);
    
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    http.onreadystatechange = function() {//Call a function when the state changes.
    
        if(http.readyState == 4)
        {
            if(http.status == 200)    
            {
                var data = http.responseText;
                
                var allNotes = JSON.parse(data);
                
                for (var i=0; i<allNotes.length; i++){
                    
                    var id = allNotes[i]["id"];
                    var content = allNotes[i]["content"];
                    var posX = allNotes[i]["posX"];
                    var posY = allNotes[i]["posY"];
                   
                    createStickyNote(id, content, posX, posY);
                    
                }
            }
            else
            {
                alert("failed to load all sticky notes");
            }
        }
    }
    http.send();
}


//implements client part for note modifying
function dragEnded(event) {
    if(sticky == null){
        return;
    }
   
    moveSticky(event);
    
    sticky = null;
}

//implements server part for note modifying
function moveSticky(event){
    
    console.log("dragEnded=" + event.target);
    
    var http = new XMLHttpRequest();
    var url = 'modifySticky.php';
    
    var posX = sticky.style.left;
    posX = posX.substring(0, posX.length - 2);
    
    var posY = sticky.style.top;
    posY = posY.substring(0, posY.length - 2);
    
    //id of target event
    var id = "";
    if (event.target.id.includes("contentId_")) {
        //if paragraph is an event.target
        id = event.target.parentElement.id.substring(9, event.target.id.length);
    }
    else
    {
        //if section is an event.target
        id = event.target.id.substring(9, event.target.id.length);
    }
   
    var params = 'id=' + id + "&posX=" + posX + "&posY=" + posY;
   
   //for testing 
    //alert(params);
    
    http.open('POST', url, true);
    
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4)
        {
            if(http.status == 200) 
            {
                //do not need any response
            }
            else
            {
                alert("failed to modify sticky note");
            }
        }
    }
    http.send(params);
}


//implements client part for note creation
function createStickyNote(id, content, posX, posY){
    console.log("createStickyNote");
    //create html nodes
    var stickySection = document.createElement("section");
    stickySection.id = "stickyId_" + id;
    
    stickySection.style.zIndex = stickyMaxZIndex;
    stickyMaxZIndex += 1;
    
    stickySection.style.left = posX;
    stickySection.style.top = posY;

    var deleteButton = document.createElement("button");
    var stickyContent = document.createElement("p");
    stickyContent.id = "contentId_" + id;
    
    var contentText = document.createTextNode(content);
    
    //add attributes to html nodes
    stickySection.className = "sticky";
    deleteButton.textContent="delete";
    deleteButton.className = "delete";
    
    //add event listeners 
    deleteButton.addEventListener('click', deleteSticky);
    stickySection.addEventListener('mousedown', dragStickyStart);
    
    //appending children + add sticky to body
    stickyContent.appendChild(contentText);
    stickySection.appendChild(stickyContent);
    stickySection.appendChild(deleteButton)
    
    document.body.appendChild(stickySection);
}

//implements server part for note creation
function createNote(){
    console.log("createNote");
    var http = new XMLHttpRequest();
    var url = 'createSticky.php';
    var content = document.getElementById("stickyContent").value;
    document.getElementById("stickyContent").value = "";
    var posX = 100;
    var posY = 100;
    var params = 'content=' + content + '&posX=' + posX + '&posY=' + posY;
    http.open('POST', url, true);
    
    //Send the proper header information along with the request                                
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    http.onreadystatechange = function() {//Call a function when the state changes.
        console.log("onreadystatechange");
        if(http.readyState == 4)
        {
            if(http.status == 200) {
                var data = http.responseText;
                //for testing
                //alert(data);
                var jsonResponse = JSON.parse(data);
                
                var id = jsonResponse["id"];
                content = jsonResponse["content"];
                posX = jsonResponse["posX"];
                posY = jsonResponse["posY"];
               
                createStickyNote(id, content, posX, posY);
            }
            else
            {
                alert("failed to create sticky note");
            }
        }
    }
    http.send(params);
}

//implements client part for note removal
function deleteSticky(event){
    //event.target.parentElement.parentElement.removeChild(event.target.parentElement);
    deleteNote(event);
    document.body.removeChild(event.target.parentElement);
    
}

//implements server part for note removal
function deleteNote(event) {
    var http = new XMLHttpRequest();
    var url = 'deleteSticky.php';
    
    var id = event.target.parentElement.id;
    id = id.substring(9, event.target.parentElement.id.length);
    
    //for testing
    //alert(id);
    
    var params = 'id=' +  id;
    
    http.open('POST', url, true);
    
    //Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4) 
        {
             if(http.status == 200) 
             {
                 //do not need any response
            }
            else 
            {
                alert("failed to delete sticky note");
            }
        }
           
    }
    http.send(params);
}


document.addEventListener("DOMContentLoaded", init);