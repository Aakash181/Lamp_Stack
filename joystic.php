<!DOCTYPE html>
<html>

<head>
	<title>Joystick Controls</title>
	<style>
	body
	{
		font-family: Courier, monospaced;
		font-size: 16px;
		font-weight: bold;
		background-repeat: no-repeat;	
		background-size: 1400px 800px;
	}
	</style>
</head>

<body>

Joystick Controls.
<hr>
<div id="status1" style="color: red;">Joystick 1</div>
<div id="status2" style="color: blue;">Joystick 2</div>

<hr>
<div style="border: 1px solid red; width: 128px; position: absolute; left:10px; top:150px;">
	<img src="images1/joystick-base.png"/>
	<div id="stick1" style="position: absolute; left:32px; top:32px;">
	<img src="images1/joystick-red.png"/>		
	</div>
</div>

<div style="border: 1px solid blue; width: 128px; position: absolute; left:210px; top:150px;">
	<img src="images1/joystick-base.png"/>
	<div id="stick2" style="position: absolute; left:32px; top:32px;">
	<img src="images1/joystick-blue.png"/>		
	</div>
</div>


<script>

class JoystickController
{
	// stickID: ID of HTML element (representing joystick) that will be dragged
	// maxDistance: maximum amount joystick can move in any direction
	// deadzone: joystick must move at least this amount from origin to register value change
	constructor( stickID, maxDistance, deadzone )
	{
		this.id = stickID;
		let stick = document.getElementById(stickID);

		// location from which drag begins, used to calculate offsets
		this.dragStart = null;

		// track touch identifier in case multiple joysticks present
		this.touchId = null;
		
		this.active = false;
		this.value = { x: 0, y: 0 }; 

		let self = this;

		function handleDown(event)
		{
		    self.active = true;

			// all drag movements are instantaneous
			stick.style.transition = '0s';

			// touch event fired before mouse event; prevent redundant mouse event from firing
			event.preventDefault();

		    if (event.changedTouches)
		    	self.dragStart = { x: event.changedTouches[0].clientX, y: event.changedTouches[0].clientY };
		    else
		    	self.dragStart = { x: event.clientX, y: event.clientY };

			// if this is a touch event, keep track of which one
		    if (event.changedTouches)
		    	self.touchId = event.changedTouches[0].identifier;
		}
		
		function handleMove(event) 
		{
		    if ( !self.active ) return;

		    // if this is a touch event, make sure it is the right one
		    // also handle multiple simultaneous touchmove events
		    let touchmoveId = null;
		    if (event.changedTouches)
		    {
		    	for (let i = 0; i < event.changedTouches.length; i++)
		    	{
		    		if (self.touchId == event.changedTouches[i].identifier)
		    		{
		    			touchmoveId = i;
		    			event.clientX = event.changedTouches[i].clientX;
		    			event.clientY = event.changedTouches[i].clientY;
		    		}
		    	}

		    	if (touchmoveId == null) return;
		    }

		    const xDiff = event.clientX - self.dragStart.x;
		    const yDiff = event.clientY - self.dragStart.y;
		    const angle = Math.atan2(yDiff, xDiff);
			const distance = Math.min(maxDistance, Math.hypot(xDiff, yDiff));
			const xPosition = distance * Math.cos(angle);
			const yPosition = distance * Math.sin(angle);

			// move stick image to new position
		    stick.style.transform = `translate3d(${xPosition}px, ${yPosition}px, 0px)`;

			// deadzone adjustment
			const distance2 = (distance < deadzone) ? 0 : maxDistance / (maxDistance - deadzone) * (distance - deadzone);
		    const xPosition2 = distance2 * Math.cos(angle);
			const yPosition2 = distance2 * Math.sin(angle);
		    const xPercent = parseFloat((xPosition2 / maxDistance).toFixed(4));
		    const yPercent = parseFloat((yPosition2 / maxDistance).toFixed(4));
		    
		    self.value = { x: xPercent, y: -1*yPercent };
		  }

		function handleUp(event) 
		{
		    if ( !self.active ) return;

		    // if this is a touch event, make sure it is the right one
		    if (event.changedTouches && self.touchId != event.changedTouches[0].identifier) return;

		    // transition the joystick position back to center
		    stick.style.transition = '.2s';
		    stick.style.transform = `translate3d(0px, 0px, 0px)`;

		    // reset everything
		    self.value = { x: 0, y: 0 };
		    self.touchId = null;
		    self.active = false;
		}

		stick.addEventListener('mousedown', handleDown);


		stick.addEventListener('touchstart', handleDown);
		document.addEventListener('mousemove', handleMove, {passive: false});
		document.addEventListener('touchmove', handleMove, {passive: false});
		document.addEventListener('mouseup', handleUp);
		document.addEventListener('touchend', handleUp);
	}
}

let joystick1 = new JoystickController("stick1", 64, 8);
let joystick2 = new JoystickController("stick2", 64, 8);

function update()
{
	document.getElementById("status1").innerText = "Joystick 1: " + JSON.stringify(joystick1.value);
	document.getElementById("status2").innerText = "Joystick 2: " + JSON.stringify(joystick2.value);
	//document.writeln(x_data)
}
//document.write(JSON.stringify(joystick1.value))

function loop()
{
	requestAnimationFrame(loop);
	update();
}

loop();

</script>

</body>
</html>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$db="company";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$db);

// Check connection
if(!$conn){
die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
$x=115;
$query11 = "INSERT INTO x_value values('$x')";
$data= mysqli_query($conn,$query11);
if($data)
{
    echo "data successfully recorded";
}
else
{
    echo "data can not be recorded due to some tecnical issue or wrong detais please fill correct information or try again ";
}


?>

