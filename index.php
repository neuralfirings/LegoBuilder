<!DOCTYPE HTML>
<html lang="en">
	<head>
		
		<title>Lego my Eggo</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
		<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<style type="text/css">
	#demo-frame > div.demo { padding: 10px !important; };
			</style>
					<style type="text/css">
			body {
				font-family: Monospace;
				background-color: #f0f0f0;
				margin: 0px;
				overflow: hidden;
			}
		</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/Three.js"></script>
		<script type="text/javascript" src="js/RequestAnimationFrame.js"></script>
		<script type="text/javascript" src="js/Stats.js"></script>
		<script src="exporter.js"></script>
	</head>
	<body>
		<table>
		<tr>
			<td>#Cols:</td>
			<td>
				<div class="demo" style="width:400px;">
				<div id="xslider"></div>
				</div>
			</td>
			<td><input type="text" id="xamount" style="border:0; color:#f6931f; font-weight:bold;" /></td>
		</tr>
		<tr>
			<td>#Rows:</td>
			<td>
				<div class="demo" style="width:400px;">
				<div id="yslider"></div>
				</div>
			</td>
			<td><input type="text" id="yamount" style="border:0; color:#f6931f; font-weight:bold;" /></td>
		</tr>
		<tr>
			<td>Spacing:</td>
			<td>
				<div class="demo" style="width:400px;">
				<div id="spacingSlider"></div>
				</div>
			</td>
			<td><input type="text" id="spacingAmount" style="border:0; color:#f6931f; font-weight:bold;" /></td>
		</tr>
		<tr>
			<td>Thickness:</td>
			<td>
				<div class="demo" style="width:400px;">
				<div id="thicknessSlider"></div>
				</div>
			</td>
			<td><input type="text" id="thicknessAmount" style="border:0; color:#f6931f; font-weight:bold;" /></td>
		</tr>
		<tr>
			<td>Zoom:</td>
			<td>
				<div class="demo" style="width:400px;">
				<div id="camZSlider"></div>
				</div>
			</td>
			<td><input type="text" id="camZAmount" style="border:0; color:#f6931f; font-weight:bold;" /></td>
		</tr>
		</table>
		
		<form>
		<!--<div id="console">console output</div>-->
		Drag mouse to move camera.
		</form>

		<form action="" type="post" id="exportform" name="exportform"> 
		<label>Title</label> 
		<input type="text" id="modeltitle" name="modeltitle" size="18"></input> <br /> 
		<label>username</label> 
		<input type="text" id="username" name="username" size="18"></input> <br /> 
		<label>password</label> 
		<input type="password" id="password" name="password" size="18"></input> <br /> 
		<input type="button" value="submit" onClick="submitform();">
		<br> 
		<div id="message"></div>

	<script>
	$(function() {
		$( "#xslider" ).slider({
			value:10,
			min: 1,
			max: 30,
			step: 1,
			slide: function( event, ui ) {
				$( "#xamount" ).val(ui.value );
				draw();
			}
		});
		$( "#xamount" ).val( $( "#xslider" ).slider( "value" ) );
		
		$( "#yslider" ).slider({
			value:10,
			min: 1,
			max: 30,
			step: 1,
			slide: function( event, ui ) {
				$( "#yamount" ).val(ui.value );
				draw();
			}
		});
		$( "#yamount" ).val( $( "#yslider" ).slider( "value" ) );
		
		$( "#spacingSlider" ).slider({
			value:30,
			min: 1,
			max: 100,
			step: .1,
			slide: function( event, ui ) {
				$( "#spacingAmount" ).val(ui.value );
				draw();
			}
		});
		$( "#spacingAmount" ).val( $( "#spacingSlider" ).slider( "value" ) );
		
		$( "#thicknessSlider" ).slider({
			value:10,
			min: 10,
			max: 50,
			step: .1,
			slide: function( event, ui ) {
				$( "#thicknessAmount" ).val(ui.value );
				draw();
			}
		});
		$( "#thicknessAmount" ).val( $( "#thicknessSlider" ).slider( "value" ) );
		
		$( "#camZSlider" ).slider({
			value:500,
			min: -1000,
			max: 1000,
			step: .1,
			slide: function( event, ui ) {
				$( "#camZAmount" ).val(ui.value );
				camera.position.z = ui.value;
			}
		});
		$( "#camZAmount" ).val( $( "#camZSlider" ).slider( "value" ) );
		
		draw();
	});
	</script>
		<script type="text/javascript">
			var container, stats;
			var camera, scene, renderer;
			
			var rotation = 0;
			var plane;
			var cubes = [];

			var targetX = 0;
			var targetXOnMouseDown = 0;
			var targetY = 0;
			var targetYOnMouseDown = 0;
			
			var mouseX = 0;
			var mouseY = 0;
			var mouseXOnMouseDown = 0;
			var mouseYOnMouseDown = 0;

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;

			init();
			animate();

			function createCube(x, y, z, size) {
			
				var shape = new THREE.Mesh( new THREE.CubeGeometry(size, size, size), new THREE.MeshLambertMaterial({color: 0xffffff}) );

				shape.position.x = x;
				shape.position.y = y;
				shape.position.z = z;
				shape.overdraw = true;
				scene.addObject( shape );
				cubes.push(shape);
			}
			
			function createCylinder(x, y, z, facets,size) {		
				var cylinder = new THREE.Mesh( new THREE.CylinderGeometry(facets,.5 * size, .5 * size, size), new THREE.MeshLambertMaterial({color: 0xffffff}) );
				cylinder.position.x = x;
				cylinder.position.y = y;
				cylinder.position.z = z;
				cylinder.overdraw = true;
				scene.addObject( cylinder );
				cubes.push(cylinder);
			}

			function draw() {
				
				var slValue = $('#slider').slider("value");
				
				for (var i  = 0; i < cubes.length; i++) {
					scene.removeObject(cubes[i]);
				}
				
				cubes = [];
				
				var numHorizontal = $('#xamount').val();//document.getElementById('xs').value;
				var numVertical = $('#yamount').val(); // document.getElementById('ys').value;
				var spacing = $('#spacingAmount').val();// document.getElementById('spacing').value;
				var size = 20;
				
				var thickness = $('#thicknessAmount').val();

				var width = spacing * (numHorizontal - 1) + 2 * size;
				var height = spacing * (numVertical - 1) + 2 * size;
				var cube = new THREE.Mesh( new THREE.CubeGeometry(width, height, thickness), new THREE.MeshLambertMaterial({color: 0xffffff}) );
				cube.position.x = .5 * spacing * (numHorizontal - 1);// - (size * .5 );
				cube.position.y = .5 * spacing * (numVertical - 1);//   - (size * .5 );
				cube.position.z = -1 * (thickness * .5);
				
				camera.target.position.x = cube.position.x;
				camera.target.position.y = cube.position.y;

				cube.overdraw = true;
				cubes.push(cube);
				scene.addObject(cube);
				
				for (var i = 0; i < numHorizontal; i++){
					for (var j = 0; j < numVertical; j++){
						createCylinder( (i * spacing), (j * spacing), 0, 20, size);
					}
				}			
			}

			function init() {

				container = document.createElement( 'div' );
				container.setAttribute('id', 'scene');
				document.body.appendChild( container );

				var info = document.createElement( 'div' );
				info.style.position = 'absolute';
				info.style.top = '10px';
				info.style.width = '100%';
				info.style.textAlign = 'center';
				container.appendChild( info );

				camera = new THREE.Camera( 60, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.x = 0;
				camera.position.y = 150;
				camera.position.z = 500;
				camera.target.position.x = 100;
				camera.target.position.y = 100;

				scene = new THREE.Scene();

				var color = Math.random() * 0x222222;
				
				scene.addLight( new THREE.AmbientLight(Math.random() * 0xffffff));
				var light = new THREE.PointLight( Math.random() * 0xffffff, 1, 1000 );
				light.position.x = 200;
				light.position.y = 200;
				light.position.z = 500;
				
				var light2 = new THREE.PointLight( Math.random() * 0xffffff, 1, 1000 );
				light2.position.x = -500;
				light2.position.y = -500;
				light2.position.z = 500;
				
				scene.addLight( light);
				scene.addLight( light2);

				renderer = new THREE.WebGLRenderer();
				renderer.setSize( window.innerWidth, window.innerHeight);

				container.appendChild( renderer.domElement );

				/*
				stats = new Stats();
				stats.domElement.style.position = 'absolute';
				stats.domElement.style.top = '0px';
				container.appendChild( stats.domElement );
				*/
				container.addEventListener( 'mousedown', onDocumentMouseDown, false );
				container.addEventListener( 'touchstart', onDocumentTouchStart, false );
				container.addEventListener( 'touchmove', onDocumentTouchMove, false );
			}

			function onDocumentMouseDown( event ) {

				event.preventDefault();

				document.addEventListener( 'mousemove', onDocumentMouseMove, false );
				document.addEventListener( 'mouseup', onDocumentMouseUp, false );
				document.addEventListener( 'mouseout', onDocumentMouseOut, false );

				mouseXOnMouseDown = event.clientX - windowHalfX;
				mouseYOnMouseDown = event.clientY - windowHalfY;
				targetXOnMouseDown = targetX;
				targetYOnMouseDown = targetY;
			}

			function onDocumentMouseMove( event ) {

				mouseX = event.clientX - windowHalfX;
				mouseY = event.clientY - windowHalfY;

				targetX = targetXOnMouseDown + ( mouseX - mouseXOnMouseDown );
				targetY = targetYOnMouseDown + ( mouseY - mouseYOnMouseDown );
			}

			function onDocumentMouseUp( event ) {

				document.removeEventListener( 'mousemove', onDocumentMouseMove, false );
				document.removeEventListener( 'mouseup', onDocumentMouseUp, false );
				document.removeEventListener( 'mouseout', onDocumentMouseOut, false );
			}

			function onDocumentMouseOut( event ) {

				document.removeEventListener( 'mousemove', onDocumentMouseMove, false );
				document.removeEventListener( 'mouseup', onDocumentMouseUp, false );
				document.removeEventListener( 'mouseout', onDocumentMouseOut, false );
			}

			function onDocumentTouchStart( event ) {
				if ( event.touches.length == 1 ) {
					event.preventDefault();

					mouseXOnMouseDown = event.touches[ 0 ].pageX - windowHalfX;
					mouseYOnMouseDown = event.touches[ 0 ].pageY - windowHalfY;
					targetXOnMouseDown = targetX;
					targetYOnMouseDown = targetY;
				}
			}

			function onDocumentTouchMove( event ) {
				if ( event.touches.length == 1 ) {
					event.preventDefault();

					mouseX = event.touches[ 0 ].pageX - windowHalfX;
					mouseY = event.touches[ 0 ].pageY - windowHalfY;
					targetX = targetXOnMouseDown + ( mouseX - mouseXOnMouseDown );
					targetY = targetYOnMouseDown + ( mouseY - mouseYOnMouseDown );
				}
			}

			function animate() {
				requestAnimationFrame( animate );

				render();
				stats.update();
			}

			function render() {
			/*
				var d = 2000.0;
				var r = d/2.0;
				
				if (targetX < 0){
					targetX +=  d;
				}
				var x = (targetX % d)
				if (x > r) {
					x = r - (x - r);
				}
				
				if (targetY < 0){
					targetY +=  d;
				}
				var y = (targetY % d)
				if (y > r) {
					y = r - (y - r);
				}
				var z = r;
				*/
				
				
				var x = targetX + 50;
				var y = -1 * targetY - 200;
				var z = $( "#camZAmount" ).val();
				
				var text = 'X:' + x + ', Y:' + y + ', Z:' + z;
				
				camera.position.x = x;
				camera.position.y = y;
				camera.position.z = z;
				
				//$('#console').html(text);
				renderer.render( scene, camera );

			}

			function submitform()
			{
				$("#message").html("Thinking...");
				var log = document.getElementById('exportform');	
				//var sizedMesh=toxiMesh.getScaled(.5);
				var triangles = " ";
				for (var i = 0; i < cubes.length; i++)
				{
					exportstring =" ";
					exportMesh(cubes[i], cubes[i].position.x, cubes[i].position.y, cubes[i].position.z, .25, log);	
	 				triangles = triangles + exportstring;
				}

				var rand=Math.floor(Math.random()*10000);
				var modeltitle = document.getElementById('modeltitle').value;
				var username = document.getElementById('username').value;
				var password = document.getElementById('password').value;
 
				// EXPORTS
				
				var xhreq = createXMLHttpRequest();
			   	xhreq.open("post", "magic.php", true);
			   	xhreq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
			    xhreq.onreadystatechange = function statechanged() {
			  		if ( xhreq.readyState == 4 || xhreq.readyState == "complete" ) {
			    		$("#message").html("<a target='_blank' href='http://www.shapeways.com/model/" + 
							xhreq.responseText + "/'>Link to your model, " + modeltitle + "</a>!");	
			    	}
			  	};
 
			    xhreq.send("username=" + username + 
								"&password=" + password +
								"&modeltitle=" + modeltitle +
								  triangles);	
				
			}

		</script>
		

	</body>
</html>
