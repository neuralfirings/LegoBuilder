var index=0;
var exportstring =" ";
//export toxiMesh
function exportMesh(mesh, offsetx, offsety, offsetz, scale, parentNode) {
	var tri=[];
	tri=mesh.geometry.faces;
	var v=[];
	v=mesh.geometry.vertices;

	var i=0;
	var j=0;
		for (i=0;i<tri.length;i++){
			var A=tri[i].a;
			var B=tri[i].b;
			var C=tri[i].c;
			var D=tri[i].d;
			var N=tri[i].normal;
		
			var el = document.createElement('input');
	        el.setAttribute('type', 'hidden');
	        el.setAttribute('name','triangles' + '[' + index + ']');

	        var Avalue = (v[A].position.x + offsetx)*scale +','+ (v[A].position.y + offsety)*scale +','+ (v[A].position.z + offsetz)*scale;
	        var Bvalue = (v[B].position.x + offsetx)*scale +','+ (v[B].position.y + offsety)*scale +','+ (v[B].position.z + offsetz)*scale;
	        var Cvalue = (v[C].position.x + offsetx)*scale +','+ (v[C].position.y + offsety)*scale +','+ (v[C].position.z + offsetz)*scale;
	        var Dvalue = (v[D].position.x + offsetx)*scale +','+ (v[D].position.y + offsety)*scale +','+ (v[D].position.z + offsetz)*scale;
	        var Nvalue = N.x+','+N.y+','+N.z;
        
	        //el.value= Avalue+','+Bvalue+','+Cvalue+','+Nvalue;
	        //parentNode.appendChild(el);
	        index=j;
	        exportstring=exportstring+'&triangles[]='+Avalue+','+Bvalue+','+Cvalue+','+Nvalue;
			j++;

	        //el.value= Cvalue+','+Dvalue+','+Avalue+','+Nvalue;
	        //parentNode.appendChild(el);
	        index=j;
	        exportstring=exportstring+'&triangles[]='+Cvalue+','+Dvalue+','+Avalue+','+Nvalue;
			j++;

		}
}
////////////////////////////////////////////////////////////
function createXMLHttpRequest() {
   try { return new XMLHttpRequest(); } catch(e) {}
   try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) {}
   alert("XMLHttpRequest not supported");
   return null;
 }


/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;

	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";

	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];

			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
