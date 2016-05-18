<!DOCTYPE html>
<!-________________________________________
   | Olivia Zhang      Dot Canvas Lab     |
   | WebApp Development    Gabor Pd. 7    |
   |______________________________________| */ ->

<html>

<head><title>Dot Color Change Lab </title>
    <link rel="stylesheet" type="text/css" href="50statesstyle.css">
</head>
<body><br>
    
    <form id = "myForm" action="save.php" method="post" target = "myFrame">
         <input type="hidden" name="dotLoc" id = "dotLocInfo" value = ""/>
         <input type="hidden" name="selected" id = "selectedInfo" value = ""/>
         <button align = "center"  type = "button" accesskey="r"onClick = "goBack()"><u>R</u>evert Move</button>
    <button align = "center" type = "button" accesskey="c"onClick = "clearCanvas()"><u>C</u>lear Canvas</button> 
    <button align = "center" type = "button" accesskey="p"onClick = "replay()"><u>P</u>lay from beginning</button>
            <button onClick = saveFunc() accesskey="s" name="save"><u>S</u>ave</button>
    </form>
    
    <IFRAME name = myFrame id = "myFrame" width = 80% height = 20 style ="visibility:hidden"> 
    </IFRAME> 
    <form id = "myForm2" action="load.php" method="post" target = "myFrame">
         <input type="hidden" name="dotLoc" id = "dotLocInfo" value = ""/>
         <input type="hidden" name="selected" id = "selectedInfo" value = ""/>
        <button  onClick = reloadThis() accesskey="l" name="reload">Re<u>L</u>oad</button>   
    </form>
    
        
    <h1 id = "title">Dot Selection</h1><br>
    <h4 id = "numMoves"> Moves Made: </h4><br>
    <form align = "center"><br>
    <canvas id="myCanvas" width="800" height="400" style="border:10px solid white; color:white;" onmousedown = "start(event)" onmouseup =        "end(event)"></canvas><br><br>   
    
      
     
    </body>
</html>

</body>
    
<script>
    //document.getElementById('myFrame').style.visibility = "hidden";
    var clicked;
    var count = 0;
    var controlPressed = false;
    var altPressed = false;
    var dotLoc = [];
    var selected = [];
    var pastMoves = [];
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");
    var startx,starty,endx,endy,curx,cury;
    var tempdiff, maxdiff;
    var chosenIndex = -1;
    var rectangle = false;
    var inS = false;
    var inD = false;
    var prevCoord = [0,0];
    var coord;
    var canvas = document.getElementById("myCanvas");
    var prevDot,prevSel;
    var myVar;
    var dotRadius;
    var defdotRadius = 10;
    var shiftPress = false;
    
    var dotLocstring = "";
   
    function saveFunc(){
        document.getElementById('dotLocInfo').value =  dotLoc;
        document.getElementById('selectedInfo').value =  selected;
        document.getElementById('myForm').submit();
     
    }
    
    function reloadThis(){
         document.getElementById('dotLocInfo').value =  dotLoc;
        document.getElementById('selectedInfo').value =  selected;
        document.getElementById('myForm').submit();
        var myIFrame = document.getElementById('myFrame');
        var content = myIFrame.contentWindow.document.body.innerHTML; //read from php submitted
        var arrays = content.split('-');
        var dla = arrays[0];
        var sla = arrays[1];
        var dotTemp = [];
        var selTemp = [];
        var ta = [];
        var na = [];
        
        dla = arrays[0].split(',');
        sla = arrays[1].split(',');
        count = 0;
        //Constructing needs work
        for(index in dla){
        
            if(count == 2){
                ta.push(dla[index]);
                dotTemp.push(ta);
                ta = [];
                count = 0;
            }
            else{
                ta.push(dla[index]);
                count++;
                }
        }
        count = 0;
    
        for(index in sla){
          
           if(count == 2){
               
                na.push(sla[index]);
                selTemp.push(na);
                na = [];
                count = 0;
            }
            else{
                na.push(sla[index]);
                count++;
            }
        }
        
        dotLoc = dotTemp.slice();
        selected = selTemp.slice();
        
        ctx.clearRect(0,0,canvas.width, canvas.height);
        redrawDots();
        
        
    }
        
    function replay(){
        var decoy = pastMoves.slice(0);
        var cInd = 1;
        var myVar = setInterval(function(){
            if(decoy.length < 1 ){
                clearTimeout(myVar);
                alert('Finished');
            }
            
            //window.setTimeout(partB,1000);
           //use repeated setTime outs and for loop? 
            pastMove = decoy[cInd];
            selected = pastMove[1];
            dotLoc = pastMove[0];
            decoy.splice(0,1);
            cInd++;
            
            ctx.clearRect(0,0,canvas.width, canvas.height);
            redrawDots();
              
            }, 500);
    }  
    
    document.addEventListener("mousemove", function(e){
        prevSel = selected.slice(0);
        prevDot = dotLoc.slice(0);
        var ctx2 = canvas.getContext("2d");
        coord = getXY(e);
        curx = coord[0];
        cury = coord[1];
        
        var dx = curx - prevCoord[0];
        var dy = cury - prevCoord[1];
    
       if(clicked && chosenIndex == -1){
            ctx2.clearRect(0,0,canvas.width, canvas.height);
            redrawDots();
            tempdiff = Math.sqrt(Math.pow(Math.abs(startx - curx),2) + Math.pow(Math.abs(starty - cury),2));
            if (tempdiff > maxdiff)
                maxdiff = tempdiff;
            if(tempdiff >= 20){
                rectangle = true;
            }
            if(rectangle && chosenIndex == -1){
                ctx2.beginPath();
                ctx2.setLineDash([6]);
                ctx2.strokeRect(startx ,starty, curx - startx, cury - starty);
            }
        }  
        else if (chosenIndex != -1){
            for (dIndex in selected){
                var curXY = selected[dIndex];
                newx = curXY[0] + dx;
                newy = curXY[1] + dy;
                
                selected[dIndex] = [newx,newy, curXY[2]];
            }
            
            ctx2.clearRect(0,0,canvas.width, canvas.height);
            redrawDots();
            prevCoord = [curx,cury];
        }
        redrawDots();
        if(clicked){
           var currentStatus = [dotLoc.slice(0), selected.slice(0), count];
            //alert(" Dotloc " + currentStatus[0] + "selected " + currentStatus[1] + " #" + count );
           pastMoves.push(currentStatus);
        }
        
    });
    
    function moveSelectedDots(e){
        var up = (e.keyCode == 38);
        var down =(e.keyCode == 40);
        var right = (e.keyCode == 39);
        var left = (e.keyCode == 37);
        
        for(dotIndex in selected){
            var tempInfo = selected[dotIndex];
            var tempx = tempInfo[0];
            var tempy = tempInfo[1];
            
            if(down && tempy < canvas.height - 5){
                tempy +=5;
            }
            else if(up && tempy > 5){
                tempy -= 5;
            }
            else if(left && tempx > 5){
                tempx -= 5;
            }
            else if(right && tempx < canvas.width - 5){
                tempx += 5;
            }
            
            selected[dotIndex] = [tempx, tempy, selected[dotIndex][2]];
        }
        ctx.clearRect(0,0,canvas.width, canvas.height);
        redrawDots();
    }
    
    function redrawDots(){
        for (thing in dotLoc){
            dot =  dotLoc[thing];
            var dotx = dot[0];
            var doty = dot[1];
            var dotsize = dot[2];
            ctx.beginPath();
            ctx.fillStyle =  "black";
            ctx.arc(dotx,doty,dotsize,0,2*Math.PI);
            ctx.fill();
            ctx.fillStyle = "";
        }
        for (thing in selected){
            sdot =  selected[thing];
            var selectedx = sdot[0];
            var selectedy = sdot[1];
            var selectedsize = sdot[2];
            ctx.beginPath();
            ctx.fillStyle = "red";
            ctx.arc(selectedx,selectedy,selectedsize,0,2*Math.PI);
            ctx.fill();
            ctx.fillStyle = "";
        }
    }
    
    function start(e){
        clicked = true;
        var currentStatus = [dotLoc.slice(0), selected.slice(0)];
        pastMoves.push(currentStatus);
        
        var startcoordinates = getXY(e);
        startx = startcoordinates[0];
        starty = startcoordinates[1];
        prevCoord = [startx, starty];
        tempdiff = 0;
        maxdiff = 0;
        clicked = true;  

        for (index in dotLoc){
            tempDot = dotLoc[index];
            tempx = tempDot[0];
            tempy = tempDot[1];
            var distance = Math.sqrt((Math.pow(tempx-startx, 2)) + Math.pow(tempy - starty, 2));
            if(distance < 1.5*defdotRadius && chosenIndex == -1){
                chosenIndex = index;  
                inD = true;
                inS = false;
            } 
        }
        if(chosenIndex == -1){
            for (index in selected){
                tempDot = selected[index];
                tempx = tempDot[0];
                tempy = tempDot[1];
                var distance = Math.sqrt((Math.pow(tempx-startx, 2)) + Math.pow(tempy - starty, 2));
                if(distance < 1.5*defdotRadius && chosenIndex == -1){
                    chosenIndex = index;  
                    inS = true;
                    inD = false;
                } 
            }
        }
        if(chosenIndex != -1){
            if(inS){
                var temp = selected[chosenIndex];
                selected.splice(chosenIndex,1);
               
            }
            else if(inD){
                temp = dotLoc[chosenIndex];
                dotLoc.splice(chosenIndex,1);
                if(!controlPressed ){
                    while(selected.length > 0){
                        dotLoc.push(selected.pop());
                    }
                } 
            }
            
            selected.push(temp);
            inS = true;  
            inD = false;
            chosenIndex = selected.indexOf(temp);
        }
        redrawDots(); 
    }
    
    function end(e){
        if(maxdiff < 35 && chosenIndex == -1){
            drawDot(e);
            redrawDots();
        }
        else{
            var endcoordinates = getXY(e);
            endx = endcoordinates[0];
            endy = endcoordinates[1];
            ctx.clearRect(0,0,canvas.width, canvas.height);
            if(chosenIndex == -1){
                changeDotColors(startx, starty,endx, endy);
            }
        }
        
        redrawDots(); 
        clicked = false;
        chosenIndex = -1;
        inD = false;
        inS = false;
        count++;
        document.getElementById('numMoves').innerHTML = "Moves Made: " + count;
    }
    
    document.addEventListener("keydown", function(e){
        if(e.keyCode == 17){
            controlPressed = true;
        }
        if(e.keyCode == 37 |e.keyCode == 38|e.keyCode ==39|e.keyCode ==40){
            moveSelectedDots(e);
        }
        if(e.keyCode == 187 && shiftPress){ //+
            if(altPressed){
                defdotRadius++;
            }
            else{
            increaseSelected();
            }
            pastMoves.push([dotLoc, selected]);
        }
        if(e.keyCode == 189){ //-
            if(altPressed){
                if(defdotRadius > 1){
                    defdotRadius--;
                } 
            }
            else{
             shrinkSelected();
            }
            pastMoves.push([dotLoc, selected]);
        }
        if(e.keyCode == 18)
            altPressed = true;
        if(e.keyCode == 16)
            shiftPress = true;
    });
    
    document.addEventListener("keyup", function(e){
        if(e.keyCode == 17){
            controlPressed = false;
        }
        if(e.keyCode == 18){
            altPressed = false;
        }
        if(e.keyCode == 16)
            shiftPress = false;
        
    });
    
    function increaseSelected(){
        for(index in selected){
            dot = selected[index];
            var dotSize = dot[2];
            dotSize++;
            selected[index] = [dot[0], dot[1], dotSize];
            ctx.clearRect(0,0,canvas.width, canvas.height);
            redrawDots();
        }
        
    }
    
    function shrinkSelected(){
       for(index in selected){
            sdot = selected[index];
            var sSize = sdot[2];
           if(sSize > 1){
                sSize = sSize -1;
           }
            selected[index] = [sdot[0], sdot[1], sSize];
           ctx.clearRect(0,0,canvas.width, canvas.height);
            redrawDots();
        }   
    }
    
    function goBack(){
        ctx.clearRect(0,0,canvas.width, canvas.height);
        if (pastMoves.length > 0){
            var revert = pastMoves.pop();
            dotLoc = revert[0];
            selected = revert[1];
            count--;
            document.getElementById('numMoves').innerHTML = "Moves Made: " + count;
        }
        else{
            ctx.clearRect(0,0,canvas.width, canvas.height);
        }
        
        dotLoc = revert[0];
        selected = revert[1];
        redrawDots();     
    }
    
    function changeDotColors(startx, starty,endx, endy){
        if(!controlPressed && chosenIndex == -1){
             while(selected.length > 0){
                    dotLoc.push(selected.pop());
                }
        }
        var removedDots = [];
        for (thing in dotLoc){
           dotinfo = dotLoc[thing];
           dotx = dotinfo[0];
           doty = dotinfo[1];
           dotsize = dotinfo[2];
           if((dotx < startx && dotx > endx && doty > starty && doty < endy)|
              (dotx > startx && dotx < endx && doty > starty && doty < endy)|
              (dotx > startx && dotx < endx && doty < starty && doty > endy)|
              (dotx <  startx && dotx > endx && doty < starty && doty > endy)){
                    selected.push(dotinfo);
                    removedDots.push(thing);
           }
        }
        tempDotLoc = [];
        for(index in dotLoc){
            if(removedDots.indexOf(index) == -1)
                tempDotLoc.push(dotLoc[index]);
        }
        dotLoc = tempDotLoc;    
        redrawDots();
    }
    
    function drawDot(e){
        var dotx;
        var doty;
        if (e.pageX || e.pageY){ 
            dotx = e.pageX;
            doty = e.pageY;
        }
        else { 
            dotx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft; 
            doty = e.clientY + document.body.scrollTop + document.documentElement.scrollTop; 
        } 
        dotx -= canvas.offsetLeft + 13;
        doty -=canvas.offsetTop + 13;
        dotinfo = [dotx, doty, defdotRadius];
        
        ctx.beginPath();
        ctx.fillStyle = "red";
        ctx.arc(dotx,doty,defdotRadius,0,2*Math.PI);
        if(!(dotx == curx && doty == cury) | maxdiff < 35){
        ctx.fill();
        if(!controlPressed && chosenIndex == -1){
            while(selected.length > 0){
                dotLoc.push(selected.pop());
            }
            selected = [];
        }
        selected.push(dotinfo);  
        redrawDots();
     }
    }
    
    function clearCanvas(){
      
        var ctx3 = canvas.getContext("2d");
        ctx.beginPath();
        ctx3.clearRect(0,0,800,400);
        dotLoc = [];
        selected = [];
        pastMoves = [];
        count = 0;
        document.getElementById('numMoves').innerHTML = "Moves Made: " + count;
    }
    
    function getXY(e){
            var x;
            var y;
         
            if (e.pageX || e.pageY) { 
                x = e.pageX;
                y = e.pageY;
            }
            else { 
                x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft; 
                y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop; 
            } 
            x -= canvas.offsetLeft + 13;
            y -=canvas.offsetTop + 13;
            return [x,y];
        }
        
</script>
</html>