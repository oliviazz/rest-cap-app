<!--------| FlashCard App Project   
----------| Jack Boyle & Olivia Zhang; Gabor Pd. 7  
----------| index.html: home page for web project with toolbar
-->

<html>
<head><title>Flashcards 2.0</title>
   <link rel="stylesheet" type="text/css" href="mypage.css">
    
</head>
<body>
    <div class = "wrapper">
        <div class = "header">
              <table>
            <tr><td></td>
            <td><button type = "button" class = "tbbutton" accesskey="c"  onclick="location.href='newacct.html'">Create New</button></td>
            <td><button type = "button" class = "tbbutton" accesskey="c" onclick="location.href='search.html'">Search</button></td>
            <td><button type = "button" class = "tbbutton" accesskey="c" onclick="location.href='review.html'">Review</button></td>
            <td><button type = "button" class = "tbbutton" accesskey="c" id = "thisPage">My Account</font></button></td>
            <td><button type = "button" class = "tbbutton" accesskey="c" >     </button></td>
            <td><button type = "button" class = "tbbutton" accesskey="c"  onclick="location.href='login.html'">Login</font></button></td>
            <td></td>
            </tr>
            </table>
        </div>
        <div class = "content">
           
                <br><br>
                <div class= mySets id = "mySetsId">
                    <div class = "setLabel">My Account Sets:</div>
                    <button id = "newSetBtn" class = "button" onClick = "newSetBtn()" style = "font-size:60px; background-color: #009092; border:none; float:right; height:100px; width: 80px">+</button>
                  
                  <div id = "spanish" class = "flashcardSet"  onClick="switchNewSet()">Spanish</div>
                  <div id = "physics" class = "flashcardSet"  onClick="switchNewSet()">Physics</div>
                    <div id = "apush" class = "flashcardSet"  onClick="switchNewSet()">Biology</div>
                  
                </div>
            <div id = setTitle></div>
             <div class = "myFlashcardFrame" >
                 <button type = "button" class = "cyclebuttonL" onClick = "prevCard()" style = "color:#009092; font-size:60px;">&lt;                          </button>
                 <div class = "myFlashcards" id = "container"  onClick = "turnCard()"><i>Select a flashcard set</i> </div>
                <button type = "button" class = "cyclebuttonR" onClick = "nextCard()" style = "color:#009092; font-size:60px;">&gt;                            </button>
             </div>
            <button type = "button" onClick = editCard()>Edit Card </button>
            
                <br><br><br><br><br><br><br>
        </div>
 
        <div class = "footer">
            <div  class = "footer_contents">
                <button type = "footer_button" class = "footer_button"href = "about.html">About</button> &nbsp;&nbsp;&nbsp;
                <button type = "footer_button" class = "footer_button"  href = "contact.html">Contact</button>
            </div>
        </div>
    </div>

</body>

<script>
    document.getElementById("thisPage").style.textShadow = "-1px 0 #008080, 0 1px #008080, 1px 0 #008080, 0 -1px #008080";
    var flashcardSets = {"apush":{0:["The process of transport of materials through a membrane is ______", "diffusion" ],1:["The organelle that produces energy in the cell, known as  a cell's powerhouse, is the ____ ", "mitochondria"], 2:["The lack of cell ____ is one of the major differences between plant and animal cells", "walls"]}, 
                         "math":{0:["Local maximums must yield a ____ value for the second derivative test","negative"],1:["1 + 1 = ", "2"], 2:["3/5 = ", ".6"]},
                         "spanish":{0:["Espanol Problems","Facts para ayudar con espanol"],1:["La definicion de la palabra 'entonces' in ingles", "Therefore"], 2:["Una palabra que significa 'socks' en ingles", "Los calcetones"], 3:["Una fiesta para celebrar el compleano de una chica que tiene quince anos","La quinceaneara"]},
                         "physics":{0:["Physics Formulas","Facts to help with physics"],1:["E = _____ ", "mc^2"], 2:["Impulse is equal to _____", "Change in momentum, or average force * duration of time force is applied"], 3:["At the point of equilibrium in a harmonic system, the external forces are equal to _____"," 0 "]  }
                         //Each card represented by an array: first is question, second element is answer
                        }; 
    var curIndex = 0;
    var sets = document.getElementsByClassName("flashcardSet");
    var setCount = sets.length;
    var defaultSetKey = (Object.keys(flashcardSets)[0]);
    var curSet = flashcardSets[''+defaultSetKey];
    switchNewSet(curSet);
    var question = true;
    
    document.addEventListener("keydown", function(event){
           
            if(event.keyCode == 37){
                prevCard();
            }
            if(event.keyCode == 39){
                nextCard();
             }
            if(event.keyCode == 32){
                turnCard();  
            }
    });
    calibrateSets();
    
    function calibrateSets(){
        var sets = document.getElementsByClassName("flashcardSet");
        var setCount = sets.length;
        var defaultSetKey = (Object.keys(flashcardSets)[0]);
        var curSet = flashcardSets[''+defaultSetKey];
        
    for (var i = 0; i < setCount; i += 1) {
        sets[i].onclick = function(e) {
            switchNewSet(flashcardSets[this.id]);
        };
        }
    }
    
    function switchNewSet(newSet){  //Puts a new set in the flashcard viewer
        question = false;
        curSet = newSet;
        curIndex = 0; //resets to beginning of deck
        setCards();
    }
    
   function setCards(){
      curCard = curSet[curIndex];
       
      document.getElementById('container').innerHTML = curCard[0]; //First Element in Array is question;     
   }
    
    function nextCard(){
    
        if(curIndex < Object.keys(curSet).length && curIndex!= -1){
            curIndex += 1;
            setCards();
        } 
    }
    
    function prevCard(){
        if(curIndex >0  && curIndex!= -1){
            curIndex -= 1;
            setCards();
        }
    }
    
    function turnCard(){
        if(question  && curIndex != -1){ //If not the title card
            document.getElementById('container').innerHTML = curCard[1]; 
            question = false;
        }
        
        else if(!question  && curIndex!= -1){
            document.getElementById('container').innerHTML = curCard[0];
            question = true;
        }
    }
    
    function newSetBtn(){
        curIndex = -1;
        //document.getElementById('setTitle').innerHTML += '<input type = text placeholder="Flashcard Set Name"  id = newTitle>'; 
        document.getElementById('container').innerHTML = "<input type = text placeholder=\"Enter Flashcard Set Title\"  id = newTitle><br><textarea type  = text class = enter id = enteredText placeholder= Enter outline or study guide to auto create your own flashcard set > </textarea> <br><button type = button class = button id = makeSetBtn onClick = makeSet() ><u>C</u>reate Set </button>";
    }
    
    function editCard(){
        
        document.getElementById('container').innerHTML = "<textarea type  = text class = enter id = curText></textarea><button type = button onclick = saveCard()> <u>S</u>ave</button>";
        if(question)
            document.getElementById('curText').value = curSet[curIndex][0];
        else
             document.getElementById('curText').value = curSet[curIndex][1];
        document.getElementById('container').onclick = "";
    }
    
    function saveCard(){
        if(question)
       curSet[curIndex][0] =  document.getElementById('curText').value;
        else
         curSet[curIndex][1] =  document.getElementById('curText').value;   
       document.getElementById('container').innerHTML = "";
        setCards();
        
         document.getElementById('container').onclick = turnCard();
    }
    function makeSet(){
        var setIndex = 0;
        var setDict = {};
        var entered = document.getElementById('enteredText').value;
        var newTitle = document.getElementById('newTitle').value;
        
        var newTitleId = newTitle.replace(/\s/g, '');
        if(entered.length > 10 && newTitle.length > 0 ){
            
            
            var initArray = entered.split(/[.|?|!|*]/);
            for(index in initArray){
                var text = initArray[index];
                if(text.length > 10){
                    var textCard = removeWord(text); //Chooses key word to remove for user to guess 
                    setDict[setIndex] = textCard;
                    setIndex ++;
                }
            }
           
            document.getElementById('mySetsId').innerHTML += ("<div id = " + newTitleId + " class = flashcardSet onClick=switchNewSet()>" + newTitle + "</div>");
        
            parent.innerHTML = ""; //Clears creating tools
            
            flashcardSets[''+newTitleId] = setDict;
            switchNewSet(flashcardSets[newTitleId]);
//         
             var numflash = 0; 
             var myVar= setInterval(
                function(){
                    if(numflash %2 !== 0 ){
                        document.getElementById(''+newTitleId).style.border = "4px dashed #009092";
                    }
                    else{
                        document.getElementById(''+newTitleId).style.border = "4px dashed #ffff00";
                    }
                    numflash += 1;
                    if(numflash > 7){
                        clearInterval(myVar);
                    }},
            330);
//        alert(Object.keys(setDict).length);
            calibrateSets();
        }
    }
   
    function removeWord(text){
        var allWords = text.split(' '); //Splits a sentence/paragraph into individual words
        var answer; //answer located at removeIndex
        var removeIndex;
        var genericWords = ['the','and', 'so', 'but', 'for', 'it', 'why', 'or', 'not', 'because', 'as',
                            'a', 'an', 'cause', 'with', 'they','then', 'did', 'also', "to", "however", "therefore", "after", "before"];
      
        do{
            removeIndex = Math.floor(Math.random()*allWords.length);
            answer = allWords[removeIndex];
        }
        while (genericWords.indexOf(answer.toLowerCase()) != -1 || answer.length < 6);
        allWords[removeIndex] = "___________";
        return [allWords.join(" "), answer];
        }
                            
        
</script>
</html>


<?php

$servername = "localhost";
$user= "Olivia";
$password = "sk8gr898";
getDBHandle("flashcardInfo.db");

function getDBHandle($dbFileName){
    $user= "Olivia";
$password = "sk8gr898";
    $sqliteName = "sqlite$dbFileName";
    try{ $dbh = new PDO($sqliteName, $user, $password); }
    catch (PDOException $e){
        die("Connection to $sqliteName;". $e->getMessage()); }
    return $dbh;  
}
// Create connection

?>