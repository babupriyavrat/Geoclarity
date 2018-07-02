
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/markerwithlabel.js"></script>
<style>
.MarkerLabels {
     color: white;
     background-color: red;
     font-family: "Lucida Grande", "Arial", sans-serif;
     font-size: 10px;
     text-align: center;
     width: 20px;     
     white-space: nowrap;
   }
</style>
<script>
//CHANGE 1 - Adding the path to the current task
//CHANGE 2 - displaying the distance to the current task

//MarkerClusterer compiled
(function(){var d=null;function e(a){return function(b){this[a]=b}}function h(a){return function(){return this[a]}}var j;
function k(a,b,c){this.extend(k,google.maps.OverlayView);this.c=a;this.a=[];this.f=[];this.ca=[53,56,66,78,90];this.j=[];this.A=!1;c=c||{};this.g=c.gridSize||60;this.l=c.minimumClusterSize||2;this.J=c.maxZoom||d;this.j=c.styles||[];this.X=c.imagePath||this.Q;this.W=c.imageExtension||this.P;this.O=!0;if(c.zoomOnClick!=void 0)this.O=c.zoomOnClick;this.r=!1;if(c.averageCenter!=void 0)this.r=c.averageCenter;l(this);this.setMap(a);this.K=this.c.getZoom();var f=this;google.maps.event.addListener(this.c,
"zoom_changed",function(){var a=f.c.getZoom();if(f.K!=a)f.K=a,f.m()});google.maps.event.addListener(this.c,"idle",function(){f.i()});b&&b.length&&this.C(b,!1)}j=k.prototype;j.Q="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m";j.P="png";j.extend=function(a,b){return function(a){for(var b in a.prototype)this.prototype[b]=a.prototype[b];return this}.apply(a,[b])};j.onAdd=function(){if(!this.A)this.A=!0,n(this)};j.draw=function(){};
function l(a){if(!a.j.length)for(var b=0,c;c=a.ca[b];b++)a.j.push({url:a.X+(b+1)+"."+a.W,height:c,width:c})}j.S=function(){for(var a=this.o(),b=new google.maps.LatLngBounds,c=0,f;f=a[c];c++)b.extend(f.getPosition());this.c.fitBounds(b)};j.z=h("j");j.o=h("a");j.V=function(){return this.a.length};j.ba=e("J");j.I=h("J");j.G=function(a,b){for(var c=0,f=a.length,g=f;g!==0;)g=parseInt(g/10,10),c++;c=Math.min(c,b);return{text:f,index:c}};j.$=e("G");j.H=h("G");
j.C=function(a,b){for(var c=0,f;f=a[c];c++)q(this,f);b||this.i()};function q(a,b){b.s=!1;b.draggable&&google.maps.event.addListener(b,"dragend",function(){b.s=!1;a.L()});a.a.push(b)}j.q=function(a,b){q(this,a);b||this.i()};function r(a,b){var c=-1;if(a.a.indexOf)c=a.a.indexOf(b);else for(var f=0,g;g=a.a[f];f++)if(g==b){c=f;break}if(c==-1)return!1;b.setMap(d);a.a.splice(c,1);return!0}j.Y=function(a,b){var c=r(this,a);return!b&&c?(this.m(),this.i(),!0):!1};
j.Z=function(a,b){for(var c=!1,f=0,g;g=a[f];f++)g=r(this,g),c=c||g;if(!b&&c)return this.m(),this.i(),!0};j.U=function(){return this.f.length};j.getMap=h("c");j.setMap=e("c");j.w=h("g");j.aa=e("g");
j.v=function(a){var b=this.getProjection(),c=new google.maps.LatLng(a.getNorthEast().lat(),a.getNorthEast().lng()),f=new google.maps.LatLng(a.getSouthWest().lat(),a.getSouthWest().lng()),c=b.fromLatLngToDivPixel(c);c.x+=this.g;c.y-=this.g;f=b.fromLatLngToDivPixel(f);f.x-=this.g;f.y+=this.g;c=b.fromDivPixelToLatLng(c);b=b.fromDivPixelToLatLng(f);a.extend(c);a.extend(b);return a};j.R=function(){this.m(!0);this.a=[]};
j.m=function(a){for(var b=0,c;c=this.f[b];b++)c.remove();for(b=0;c=this.a[b];b++)c.s=!1,a&&c.setMap(d);this.f=[]};j.L=function(){var a=this.f.slice();this.f.length=0;this.m();this.i();window.setTimeout(function(){for(var b=0,c;c=a[b];b++)c.remove()},0)};j.i=function(){n(this)};
function n(a){if(a.A)for(var b=a.v(new google.maps.LatLngBounds(a.c.getBounds().getSouthWest(),a.c.getBounds().getNorthEast())),c=0,f;f=a.a[c];c++)if(!f.s&&b.contains(f.getPosition())){for(var g=a,u=4E4,o=d,v=0,m=void 0;m=g.f[v];v++){var i=m.getCenter();if(i){var p=f.getPosition();if(!i||!p)i=0;else var w=(p.lat()-i.lat())*Math.PI/180,x=(p.lng()-i.lng())*Math.PI/180,i=Math.sin(w/2)*Math.sin(w/2)+Math.cos(i.lat()*Math.PI/180)*Math.cos(p.lat()*Math.PI/180)*Math.sin(x/2)*Math.sin(x/2),i=6371*2*Math.atan2(Math.sqrt(i),
Math.sqrt(1-i));i<u&&(u=i,o=m)}}o&&o.F.contains(f.getPosition())?o.q(f):(m=new s(g),m.q(f),g.f.push(m))}}function s(a){this.k=a;this.c=a.getMap();this.g=a.w();this.l=a.l;this.r=a.r;this.d=d;this.a=[];this.F=d;this.n=new t(this,a.z(),a.w())}j=s.prototype;
j.q=function(a){var b;a:if(this.a.indexOf)b=this.a.indexOf(a)!=-1;else{b=0;for(var c;c=this.a[b];b++)if(c==a){b=!0;break a}b=!1}if(b)return!1;if(this.d){if(this.r)c=this.a.length+1,b=(this.d.lat()*(c-1)+a.getPosition().lat())/c,c=(this.d.lng()*(c-1)+a.getPosition().lng())/c,this.d=new google.maps.LatLng(b,c),y(this)}else this.d=a.getPosition(),y(this);a.s=!0;this.a.push(a);b=this.a.length;b<this.l&&a.getMap()!=this.c&&a.setMap(this.c);if(b==this.l)for(c=0;c<b;c++)this.a[c].setMap(d);b>=this.l&&a.setMap(d);
a=this.c.getZoom();if((b=this.k.I())&&a>b)for(a=0;b=this.a[a];a++)b.setMap(this.c);else if(this.a.length<this.l)z(this.n);else{b=this.k.H()(this.a,this.k.z().length);this.n.setCenter(this.d);a=this.n;a.B=b;a.ga=b.text;a.ea=b.index;if(a.b)a.b.innerHTML=b.text;b=Math.max(0,a.B.index-1);b=Math.min(a.j.length-1,b);b=a.j[b];a.da=b.url;a.h=b.height;a.p=b.width;a.M=b.textColor;a.e=b.anchor;a.N=b.textSize;a.D=b.backgroundPosition;this.n.show()}return!0};
j.getBounds=function(){for(var a=new google.maps.LatLngBounds(this.d,this.d),b=this.o(),c=0,f;f=b[c];c++)a.extend(f.getPosition());return a};j.remove=function(){this.n.remove();this.a.length=0;delete this.a};j.T=function(){return this.a.length};j.o=h("a");j.getCenter=h("d");function y(a){a.F=a.k.v(new google.maps.LatLngBounds(a.d,a.d))}j.getMap=h("c");
function t(a,b,c){a.k.extend(t,google.maps.OverlayView);this.j=b;this.fa=c||0;this.u=a;this.d=d;this.c=a.getMap();this.B=this.b=d;this.t=!1;this.setMap(this.c)}j=t.prototype;
j.onAdd=function(){this.b=document.createElement("DIV");if(this.t)this.b.style.cssText=A(this,B(this,this.d)),this.b.innerHTML=this.B.text;this.getPanes().overlayMouseTarget.appendChild(this.b);var a=this;google.maps.event.addDomListener(this.b,"click",function(){var b=a.u.k;google.maps.event.trigger(b,"clusterclick",a.u);b.O&&a.c.fitBounds(a.u.getBounds())})};function B(a,b){var c=a.getProjection().fromLatLngToDivPixel(b);c.x-=parseInt(a.p/2,10);c.y-=parseInt(a.h/2,10);return c}
j.draw=function(){if(this.t){var a=B(this,this.d);this.b.style.top=a.y+"px";this.b.style.left=a.x+"px"}};function z(a){if(a.b)a.b.style.display="none";a.t=!1}j.show=function(){if(this.b)this.b.style.cssText=A(this,B(this,this.d)),this.b.style.display="";this.t=!0};j.remove=function(){this.setMap(d)};j.onRemove=function(){if(this.b&&this.b.parentNode)z(this),this.b.parentNode.removeChild(this.b),this.b=d};j.setCenter=e("d");
function A(a,b){var c=[];c.push("background-image:url("+a.da+");");c.push("background-position:"+(a.D?a.D:"0 0")+";");typeof a.e==="object"?(typeof a.e[0]==="number"&&a.e[0]>0&&a.e[0]<a.h?c.push("height:"+(a.h-a.e[0])+"px; padding-top:"+a.e[0]+"px;"):c.push("height:"+a.h+"px; line-height:"+a.h+"px;"),typeof a.e[1]==="number"&&a.e[1]>0&&a.e[1]<a.p?c.push("width:"+(a.p-a.e[1])+"px; padding-left:"+a.e[1]+"px;"):c.push("width:"+a.p+"px; text-align:center;")):c.push("height:"+a.h+"px; line-height:"+a.h+
"px; width:"+a.p+"px; text-align:center;");c.push("cursor:pointer; top:"+b.y+"px; left:"+b.x+"px; color:"+(a.M?a.M:"black")+"; position:absolute; font-size:"+(a.N?a.N:11)+"px; font-family:Arial,sans-serif; font-weight:bold");return c.join("")}window.MarkerClusterer=k;k.prototype.addMarker=k.prototype.q;k.prototype.addMarkers=k.prototype.C;k.prototype.clearMarkers=k.prototype.R;k.prototype.fitMapToMarkers=k.prototype.S;k.prototype.getCalculator=k.prototype.H;k.prototype.getGridSize=k.prototype.w;
k.prototype.getExtendedBounds=k.prototype.v;k.prototype.getMap=k.prototype.getMap;k.prototype.getMarkers=k.prototype.o;k.prototype.getMaxZoom=k.prototype.I;k.prototype.getStyles=k.prototype.z;k.prototype.getTotalClusters=k.prototype.U;k.prototype.getTotalMarkers=k.prototype.V;k.prototype.redraw=k.prototype.i;k.prototype.removeMarker=k.prototype.Y;k.prototype.removeMarkers=k.prototype.Z;k.prototype.resetViewport=k.prototype.m;k.prototype.repaint=k.prototype.L;k.prototype.setCalculator=k.prototype.$;
k.prototype.setGridSize=k.prototype.aa;k.prototype.setMaxZoom=k.prototype.ba;k.prototype.onAdd=k.prototype.onAdd;k.prototype.draw=k.prototype.draw;s.prototype.getCenter=s.prototype.getCenter;s.prototype.getSize=s.prototype.T;s.prototype.getMarkers=s.prototype.o;t.prototype.onAdd=t.prototype.onAdd;t.prototype.draw=t.prototype.draw;t.prototype.onRemove=t.prototype.onRemove;
})();
//style for the map		
var iconBase = '<?php echo base_url()?>img/mapicon/';
var styles = [
{
	stylers: [
	{ hue: "#00ffe6" },
	{ saturation: -20 }
	]
},{
	featureType: "road",
	elementType: "geometry",
	stylers: [
	{ lightness: 100 },
	{ visibility: "simplified" }
	]
},
{
	featureType: "water",
	elementType: "geometry",
	stylers: [
	{ saturation: 69 },
	{ gamma: 1.22 },
	{ color: "#807dff" },
	{ hue: "#003bff" }
	]
}
];

var center = new google.maps.LatLng(39, -99);
var myOptions = {
	zoom: 4,
	center:center,
	mapTypeControlOptions: {
		mapTypeIds: [google.maps.MapTypeId.TERRAIN, 'map_style']
	}
};

var styledMap = new google.maps.StyledMapType(styles,
	{name: "Styled Map"});

	
//CHANGE 1 -starts
function calculateAndDisplayRoute(directionsDisplay, startpoint, endpoint, directionsService, map) {
  // First, remove any existing markers from the map.

  directionsDisplay.setOptions( { suppressMarkers: true } );
  // Retrieve the start and end locations and create a DirectionsRequest using
  // WALKING directions.
  directionsService.route({
    origin: startpoint,
    destination: endpoint,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    // Route the directions and pass the response to a function to create
    // markers for each step.
    if (status === google.maps.DirectionsStatus.OK) {
           directionsDisplay.setDirections(response);
     // showSteps(response, markerArray, stepDisplay, map);
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}	
	
//Change 1- ends

//get mark
function getMark(data){
	var result = "";
}

<?php
    $focusTask = $curTask;
    if ($action == 'next') {
        $curTask = $prev;
        $prev = $focusTask;
    }
?>
markers=[];

function initialize() {
	
	var TaskRoute=[];
    //getting the JSON Data created from Database based on checkbox filters	
        
    var map = new google.maps.Map(document.getElementById("map-company"), myOptions);

	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

	var bounds = new google.maps.LatLngBounds();

	var directionsService = new google.maps.DirectionsService();
	var directionsDisplay = new google.maps.DirectionsRenderer({map: map});

	var Distanceservice = new google.maps.DistanceMatrixService;

	//console.log(<?php echo json_encode($prev);?>);
	//console.log(<?php echo json_encode($curTask);?>);

	var index = 0;
	//prev config
	<?php if ($prev != null):?>
	
	   <?php if ($action == 'init'){?>
	       TaskRoute[index] = new google.maps.LatLng('<?php echo $prev['latitude']?>', '<?php echo $prev['longitude']?>');
	       bounds.extend(TaskRoute[index]);
	   <?php } else {?>
	       TaskRoute[index] = new google.maps.LatLng('<?php echo $prev['task_lat']?>', '<?php echo $prev['task_long']?>');
	       bounds.extend(TaskRoute[index]);
		
	       <?php if($prev['SOS_STATUS'] == 'Yes'){?>

    	       var contentString = '<div id="content">' + '<div id="siteNotice">' + '</div>'+
    	   		'<h4 id="firstHeading" class="firstHeading"><?php echo get_phrase('contact_name');?> :'
    	   		+ '<?php echo $prev['contact_name'];?>' + '</h4>'+
    	   		'<div id="bodyContent">'+
    	   		'<p><?php echo get_phrase('this_task_is_in');?> <b>SOS STATUS</b></p> '+
    	   		'<p><?php echo get_phrase('customer_address');?> :'+ '<?php echo $prev['contact_address'];?>' +'</br>'+
    	   		'<?php echo get_phrase('appoint_start_time');?>:' +   '<?php echo $prev['scheduled_Start'];?>' +'</br>'+
    	   		'<?php echo get_phrase('appoint_finish_time');?>:' +   '<?php echo $prev['scheduled_End'];?>' +'</br>'+
    	   		'<font color="red"><?php echo get_phrase('user');?>:' + '<?php echo $prev['user_email'];?>' +'</br></font>'+
    	   		'<?php echo get_phrase('actual_start_time');?>:'+ '<?php echo $prev['actual_Start'];?>' + '</br>'+
    	   		'<?php echo get_phrase('actual_end_time');?>:'+ '<?php echo $prev['actual_end'];?>' + '</br>'+
    	   
    	   		'<?php echo get_phrase('postcode');?>:'+ '<?php echo $prev['postcode'];?>' + '</br>' + 
    	   		'<?php echo get_phrase('current_status');?>:' +  '<?php echo $prev['task_Status'];?>' + '</br>'+
    	   		'<?php echo get_phrase('contact_phone_number');?>:' +  '<?php echo $prev['contact_phone'];?>' + '</br>'+
    	   		'<?php echo get_phrase('campaign_code');?>:' +  '<?php echo $prev['Region'];?>' + '</p>'+
    	   		'</div>'+'</div>';
	   		
	            var iconName = '<?php echo $prev['TasktypeCategory']?>' + "-sos.png";
	            
	       <?php } elseif (($prev['SOS_STATUS'] == 'No')) {?>

    	       var contentString = '<div id="content">'+ '<div id="siteNotice">'+ '</div>'+
    	   		'<h4 id="firstHeading" class="firstHeading"><?php echo get_phrase('contact_name');?> :'
    	   		+ '<?php echo $prev['contact_name'];?>' 
    	   		+ '</h4>' +  '<div id="bodyContent">'
    	   		+ '<p><?php echo get_phrase('this_task_is');?>  <b>  '+ '<?php echo $prev['TasktypeCategory'];?>' + '</b></p> '+
    	   		'<p><?php echo get_phrase('customer_address');?> :'+ '<?php echo $prev['contact_address'];?>' +'</br>'+
    	   		'<?php echo get_phrase('appoint_start_time');?>:' +   '<?php echo $prev['scheduled_Start'];?>' +'</br>'+
    	   		'<?php echo get_phrase('appoint_finish_time');?>:' +  '<?php echo $prev['scheduled_End'];?>' +'</br>'+
    	   		'<font color="red"><?php echo get_phrase('user');?>:' + '<?php echo $prev['user_email'];?>' +'</br></font>'+
    	   		'<?php echo get_phrase('actual_start_time');?>:'+ '<?php echo $prev['actual_Start'];?>' + '</br>'+
    	   		'<?php echo get_phrase('actual_end_time');?>:'+ '<?php echo $prev['actual_end'];?>' + '</br>'+
    	   		'<?php echo get_phrase('postcode');?>:'+ '<?php echo $prev['postcode'];?>' + '</br>'+
    	   		'<?php echo get_phrase('current_status');?>:' +  '<?php echo $prev['task_Status'];?>' + '</br>'+
    	   		'<?php echo get_phrase('contact_phone_number');?>:' +  '<?php echo $prev['contact_phone'];?>' + '</br>'+
    	   		'<?php echo get_phrase('campain_code');?>:' +  '<?php echo $prev['Region'];?>' + '</p>'+
    	   		'</div>'+ '</div>';
	   		
	            var iconName = '<?php echo $prev['TasktypeCategory']?>' + "-" + '<?php echo $prev['task_Status']?>' + ".png";
	            <?php if ($prev['task_Status']=='CANCELLED') {?>
			        var iconName = '<?php echo $prev['TasktypeCategory']?>' + "-sos.png";
			    <?php }?>
	       <?php }?>
	       
	       var taskMarkers= new MarkerWithLabel({
	    	    position : new google.maps.LatLng('<?php echo $prev['task_lat']?>', '<?php echo $prev['task_long']?>'),
				draggable : true, 
				title: contentString,
				icon: iconBase + iconName.toLowerCase(),
				clickable: true,
				map: map,
				labelClass:"MarkerLabels",
				labelContent: '<?php echo $prev['task_id']?>',								
			});
			
			infowindow= new google.maps.InfoWindow({
					content: contentString
			});
			
			google.maps.event.addListener(taskMarkers, 'click', function() {
					infowindow.setContent(this.title);
					infowindow.open(map,this);
			});
				
			markers.push(taskMarkers);
	   <?php }?>

	   index++;
            
	<?php endif;?>

	<?php if ($curTask != null):?>
	   TaskRoute[index] = new google.maps.LatLng('<?php echo $curTask['task_lat']?>', '<?php echo $curTask['task_long']?>');
	   bounds.extend(TaskRoute[index]);
	   
	   <?php if($curTask['SOS_STATUS'] == 'Yes'){?>
	   
    	   var contentString = '<div id="content">' + '<div id="siteNotice">' + '</div>'+
    		'<h4 id="firstHeading" class="firstHeading"><?php echo get_phrase('contact_name');?> :'
    		+ '<?php echo $curTask['contact_name'];?>' + '</h4>'+
    		'<div id="bodyContent">'+
    		'<p><?php echo get_phrase('this_task_is_in');?> <b>SOS STATUS</b></p> '+
    		'<p><?php echo get_phrase('customer_address');?> :'+ '<?php echo $curTask['contact_address'];?>' +'</br>'+
    		'<?php echo get_phrase('appoint_start_time');?>:' +   '<?php echo $curTask['scheduled_Start'];?>' +'</br>'+
    		'<?php echo get_phrase('appoint_finish_time');?>:' +   '<?php echo $curTask['scheduled_End'];?>' +'</br>'+
    		'<font color="red"><?php echo get_phrase('user');?>:' + '<?php echo $curTask['user_email'];?>' +'</br></font>'+
    		'<?php echo get_phrase('actual_start_time');?>:'+ '<?php echo $curTask['actual_Start'];?>' + '</br>'+
    		'<?php echo get_phrase('actual_end_time');?>:'+ '<?php echo $curTask['actual_end'];?>' + '</br>'+
    
    		'<?php echo get_phrase('postcode');?>:'+ '<?php echo $curTask['postcode'];?>' + '</br>' + 
    		'<?php echo get_phrase('current_status');?>:' +  '<?php echo $curTask['task_Status'];?>' + '</br>'+
    		'<?php echo get_phrase('contact_phone_number');?>:' +  '<?php echo $curTask['contact_phone'];?>' + '</br>'+
    		'<?php echo get_phrase('campaign_code');?>:' +  '<?php echo $curTask['Region'];?>' + '</p>'+
    		'</div>'+'</div>';
	   
           var iconName = '<?php echo $curTask['TasktypeCategory']?>' + "-sos.png";
           
      <?php } elseif (($curTask['SOS_STATUS'] == 'No')) {?>

          var contentString = '<div id="content">'+ '<div id="siteNotice">'+ '</div>'+
    		'<h4 id="firstHeading" class="firstHeading"><?php echo get_phrase('contact_name');?> :'
    		+ '<?php echo $curTask['contact_name'];?>' 
    		+ '</h4>' +  '<div id="bodyContent">'
    		+ '<p><?php echo get_phrase('this_task_is');?>  <b>  '+ '<?php echo $curTask['TasktypeCategory'];?>' + '</b></p> '+
    		'<p><?php echo get_phrase('customer_address');?> :'+ '<?php echo $curTask['contact_address'];?>' +'</br>'+
    		'<?php echo get_phrase('appoint_start_time');?>:' +   '<?php echo $curTask['scheduled_Start'];?>' +'</br>'+
    		'<?php echo get_phrase('appoint_finish_time');?>:' +  '<?php echo $curTask['scheduled_End'];?>' +'</br>'+
    		'<font color="red"><?php echo get_phrase('user');?>:' + '<?php echo $curTask['user_email'];?>' +'</br></font>'+
    		'<?php echo get_phrase('actual_start_time');?>:'+ '<?php echo $curTask['actual_Start'];?>' + '</br>'+
    		'<?php echo get_phrase('actual_end_time');?>:'+ '<?php echo $curTask['actual_end'];?>' + '</br>'+
    		'<?php echo get_phrase('postcode');?>:'+ '<?php echo $curTask['postcode'];?>' + '</br>'+
    		'<?php echo get_phrase('current_status');?>:' +  '<?php echo $curTask['task_Status'];?>' + '</br>'+
    		'<?php echo get_phrase('contact_phone_number');?>:' +  '<?php echo $curTask['contact_phone'];?>' + '</br>'+
    		'<?php echo get_phrase('campain_code');?>:' +  '<?php echo $curTask['Region'];?>' + '</p>'+
    		'</div>'+ '</div>';
		
          var iconName = '<?php echo $curTask['TasktypeCategory']?>' + "-" + '<?php echo $curTask['task_Status']?>' + ".png";
            
          <?php if ($curTask['task_Status']=='CANCELLED') {?>
    	      var iconName = '<?php echo $curTask['TasktypeCategory']?>' + "-sos.png";
    	  <?php }?>
    	    
      <?php }?>
      
      var taskMarkers= new MarkerWithLabel({
    	    position: new google.maps.LatLng('<?php echo $curTask['task_lat']?>', '<?php echo $curTask['task_long']?>'),
    		draggable: false, 
    		title: contentString,
    		icon: iconBase + iconName.toLowerCase(),
    		clickable: true,
    		map: map,
    		labelClass:"MarkerLabels",
    		labelContent: '<?php echo $curTask['task_id']?>'		
    	});
    	
    	infowindow= new google.maps.InfoWindow({
    			content: contentString
    	});
    	
    	google.maps.event.addListener(taskMarkers, 'click', function() {
    			infowindow.setContent(this.title);
    			infowindow.open(map,this);
    	});
    		
    	markers.push(taskMarkers);
	<?php endif;?>

	//drag line between two task location
	/*var TaskIconsetngs = { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW };
	
	var TaskPath = new google.maps.Polyline(
                    	{
                    		path: TaskRoute,
                    		strokeOpacity: 1,
                    		strokeColor: "blue",
                    		strokeWeight: 2,
                    		icons: [{ repeat: '800px', icon: TaskIconsetngs, offset: '100%'}],
                    		geodesic: true
                    	});
	
	TaskPath.setMap(map); */

	// marker for user current location 
	<?php if ($curLocation['latitude'] != ""){?>
    	var userMaker= new MarkerWithLabel({
    	    position: new google.maps.LatLng('<?php echo $curLocation['latitude']?>', '<?php echo $curLocation['longitude']?>'),
    		draggable: true, 
    		title: '<?php echo $curLocation['curAddr']?>',
    		icon: iconBase + "userloc.png",
    		clickable: true,
    		map: map		
    	});
    	
    	google.maps.event.addListener(userMaker, 'click', function() {
			infowindow.setContent(this.title);
			infowindow.open(map,this);
	    });
	    
    	markers.push(userMaker);
	<?php }?>

	//drag google path between two task location
	<?php if ($curTask != null && $prev != null) {
	    if ($action=='init'){
	        $start_lat = $curLocation['latitude'];
	        $start_long = $curLocation['longitude'];
	    } else {
	        $start_lat = $prev['task_lat'];
	        $start_long = $prev['task_long'];
	    }
	?>
    	var startpoint= new google.maps.LatLng(<?php echo $start_lat;?>, <?php echo $start_long;?>);
    	var endpoint=new google.maps.LatLng(<?php echo $curTask['task_lat']?>, <?php echo $curTask['task_long']?>);
    	calculateAndDisplayRoute(directionsDisplay, startpoint, endpoint, directionsService,map);
    
    	//CHANGE 2 -starts
    	Distanceservice.getDistanceMatrix(
    	    	{
                  origins: [startpoint],
                  destinations: [endpoint],
                  travelMode: google.maps.TravelMode.DRIVING,
                  unitSystem: google.maps.UnitSystem.METRIC,
                  avoidHighways: false,
                  avoidTolls: false
                },
                
                function(response, status) {
             	   if (status !== google.maps.DistanceMatrixStatus.OK) {
            		    alert('Error was: ' + status);
            	   } else {
                        var originList = response.originAddresses;
                        var destinationList = response.destinationAddresses;
                        var outputDiv = document.getElementById('output');
                        outputDiv.innerHTML = '';
    		
                		for (var i = 0; i < originList.length; i++) {
                			var results = response.rows[i].elements;
                			
                			for (var j = 0; j < results.length; j++) {
             				   outputDiv.innerHTML += '<?php echo get_phrase('eta_to_destination')?>' +
                                ': ' + results[j].distance.text + ' in ' +
                                results[j].duration.text + '<br>';
                            }
                        }
                   }
               }
        );
      //CHANGE 2 -ends
    <?php } ?>

    //drag polyline for user movement history
    var flightPlanCoordinates = [];
	<?php
	   $pointer= 0;
	   foreach ($movLocations as $locInfo) {
	       if ($pointer%3==0) {
    ?>
               var contentString = '<div id="content">'+
                                         '<div id="siteNotice">' + '</div>'+
                                         '<h4 id="firstHeading" class="firstHeading"><?php echo get_phrase('timer_information');?></h4>'+
                        			     '<div id="bodyContent">' + 
                                             '<?php echo get_phrase('timestamp');?>:' +   '<?php echo $locInfo['start_date_time'];?>' +'</br>'+
                        			         '<?php echo get_phrase('battery');?>:'+ <?php echo $locInfo['battery'];?> +'</br></font>'+
                        				 '</div>'+
                        			'</div>';
    			
    		   var pointMarkers= new MarkerWithLabel(
			                    { position: new google.maps.LatLng('<?php echo $locInfo['latitude'];?>', '<?php echo $locInfo['longitude'];?>'),
                    			  draggable : false, 
                    			  title: contentString,
                    			  icon: iconBase + "bluedot.png",
                    			  clickable: true, 
                    			  map: map
                    			});
    			
    			google.maps.event.addListener(pointMarkers, 'click', function() {
    					infowindow.setContent(this.title);
    					infowindow.open(map,this);
    			});
    				
    			markers.push(pointMarkers);
			
	   <?php }?>

			flightPlanCoordinates[<?php echo $pointer;?>] = new google.maps.LatLng('<?php echo $locInfo['latitude']?>', '<?php echo $locInfo['longitude']?>');	
    <?php
            $pointer++;
        }
    ?>
    console.log(flightPlanCoordinates);
	
	// show user tracking history
    var UserIconsetngs = { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW};
    
    var flightPath = new google.maps.Polyline({
                        path: flightPlanCoordinates,
                        strokeOpacity: 1,
                        strokeColor: "red",
                        strokeWeight: 2,
                        icons: [{
                    		icon: UserIconsetngs,
                    		offset: '100%'}],
                    	geodesic: true    //The polylines need to be declared as geodesic. Google Maps API takes care of the maths behind this.
                    });
    flightPath.setMap(map);

    //end user movement history

	map.fitBounds(bounds);
}  

google.maps.event.addDomListener(window, 'load', initialize);

</script>  
<div class="container" style="margin-top:100px;">
	
	<h3 style="margin-top:0px;text-align:left"><?php echo get_phrase('track_user');?>: <?php echo $userInfo->email?></h3>	
	<div class="row">
		
		<div class="col-sm-12">
			<div id="map-company" style="width:100%;height:400px;">
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:10px;">
		<div class="col-md-4">
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<b><?php echo get_phrase($userInfo->user_roles)?> <?php echo get_phrase('contact');?></b>
				</div>
				<div class="panel-body text-left">
					<?php echo get_phrase('name');?>: <?php echo $userInfo->username?> <br/>
					<?php echo get_phrase('email');?>: <?php echo $userInfo->email?> <br/>
					<?php echo get_phrase('home_phone');?>: <?php echo $userInfo->homephone?> <br/>
					<?php echo get_phrase('mobile_phone');?>: <?php echo $userInfo->mobilephone?> <br/>
					<?php echo get_phrase('vehicle_type');?>: <?php echo $userInfo->vehicletype?> <br/>
					<?php echo get_phrase('vehicle_reg');?>: <?php echo $userInfo->vehicle_reg?> <br/>
				</div>
			</div>
		
		</div>
		<div class="col-md-4">
			
			<div class="panel panel-default">
				<div class="panel-heading">
				    <?php if ($prev != null && $focusTask != null) {
				        $link = '/' . $focusTask['task_id'] . '/prev';
				    ?>
				    <a href="<?php echo site_url('supervisor/trackuser/'.$userID.$link)?>">
				        <i class="fa fa-arrow-left" style="float: left; font-size: 22px;"></i>
				    </a>
				    <?php }?>
				    <a href="<?php echo site_url('supervisor/trackuser/'.$userID)?>">
    					<b>
    					   <?php 
    					       if ($action == 'init')
    					           echo get_phrase('current_task');
    					       else 
    					           echo get_phrase('focus_task');
    					       ?>
    					</b>
					</a>
					<?php if ($curTask != null) {
				        $link = '/' . $focusTask['task_id'] . '/next';
				    ?>
					<a href="<?php echo site_url('supervisor/trackuser/'.$userID.$link)?>">
					   <i class="fa fa-arrow-right" style="float: right; font-size: 22px;"></i>
					</a>
					<?php }?>
				</div>
				<div class="panel-body text-left">
					<?php if ($focusTask == null) {?>
					<?php echo get_phrase('no_current_task');?>
					<?php } else {?>
					<?php echo get_phrase('customer_name');?>: <?php echo $focusTask['contact_name'];?> <br/> 
					<?php echo get_phrase('customer_address');?>: <?php echo $focusTask['contact_address']?> <br/>
					<?php echo get_phrase('customer_phone');?>: <?php echo $focusTask['contact_phone']?> <br/>
					<?php echo get_phrase('postcode');?>: <?php echo $focusTask['postcode']?> <br/>
					<?php echo get_phrase('task_category');?>: <?php echo $focusTask['TasktypeCategory']?> <br/>
					<?php echo get_phrase('sos_status');?>: <?php echo $focusTask['SOS_STATUS']?> <br/>
					<?php echo get_phrase('task_status');?>: <?php echo $focusTask['task_Status']?> <br/>
					 <div id="output"><?php echo get_phrase('ETA');?>: </div>			
					<?php }?>
				</div>
			</div>
		
		</div>
		
		<div class="col-md-4">
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<b><?php echo get_phrase('current_location');?></b>
				</div>
				<div class="panel-body text-left">
					<?php echo $curLocation['curAddr'];?>
				</div>
			</div>
		
		</div>
	</div>
	
</div>
