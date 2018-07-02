<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/excanvas.min.js"></script><![endif]-->
<script>
//style for the map		
var iconBase = '<?php echo base_url()?>img/mapicon/';
var styles = [
{
	stylers: [
	{ hue: "#88ff88" },
	{ saturation: 0}
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
	{ saturation: 50 },
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

function initializeUserLocations() 
{
	var map2 = new google.maps.Map(document.getElementById("map-user"), myOptions);
	map2.mapTypes.set('map_style', styledMap);
	map2.setMapTypeId('map_style');
	var bounds = new google.maps.LatLngBounds();
	var UserData =<?php echo $JsonUsers ?>;
	
	for (var i = 0; i < UserData.length; ++i) {
		
		var userMarkers= new google.maps.Marker({ position: new google.maps.LatLng(UserData[i].last_latitude,
						UserData[i].last_longitude),
					draggable:true, 
					title: contentString,
					icon: iconBase + UserData[i].vehicletype + '.png',
					clickable: true,
					map: map2
				});
				
		var contentString = '<div id="content">'+
				'<div id="siteNotice">'+
				'</div>'+
				'<h4 id="firstHeading" class="firstHeading"> User Name:'+ UserData[i].username+'</h4>'+
				'<div id="bodyContent">'+
				'<p>This User is  <b>  '+ UserData[i].user_roles+ '</b></p> '+
				'<p>Home Phone :'+ UserData[i].homephone +'</br>'+
				'Mobile Phone :' + UserData[i].mobilephone +'</br>'+
				'Email:' +   UserData[i].email +'</br>'+
				'Vehicle Type:' +  UserData[i].vehicletype + '</br>'+
				'Vehicle Number:' + UserData[i].vehicle_reg + '</br>'+
									'</div>'+
				'</div>';		
		
		infowindow= new google.maps.InfoWindow({
						content: contentString
				});
				
				google.maps.event.addListener(userMarkers, 'click', function() {
						infowindow.setContent(this.title);
						infowindow.open(map2,this);
				});
				
		
		var myLatLng2 = new google.maps.LatLng(UserData[i].last_latitude, UserData[i].last_longitude);
	bounds.extend(myLatLng2);
	map2.fitBounds(bounds);

	}
}
google.maps.event.addDomListener(window, 'load', initializeUserLocations);
</script>

<div class="container" style="margin-top:100px;">	
	<div class="row">	
		<div class="col-sm-12">
			<div id="map-user" style="width:100%;height:500px;"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b><?php echo get_phrase('all_users');?></b></div>
				<div class="panel-body">
					<div class="text-left" style="margin-bottom: 10px;">
						<input type="button" class="btn btn-primary" value="<?php echo get_phrase('add_user');?>" onclick="location.href='<?php echo base_url()?>index.php/supervisor/useradd'"/>
					</div>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo get_phrase('no');?></th>
								<th><?php echo get_phrase('email');?></th>
								<th><?php echo get_phrase('user_name');?></th>
								<th><?php echo get_phrase('user_role');?></th>
								<th><?php echo get_phrase('home_phone');?></th>
								<th><?php echo get_phrase('mobile_phone');?></th>
								<th><?php echo get_phrase('vehicle_type');?></th>
								<th><?php echo get_phrase('vehicle_reg');?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $idx=0; foreach ($UserList as $info): ?>
							<tr>
								<td><?php echo ++$idx;?></td>
								<td><?php echo $info->email?></td>
								<td><?php echo $info->username?></td>
								<td><?php echo $info->user_roles?></td>
								<td><?php echo $info->homephone?></td>
								<td><?php echo $info->mobilephone?></td>
								<td><?php echo $info->vehicletype?></td>
								<td><?php echo $info->vehicle_reg?></td>
								<td><a href='<?php echo base_url()?>index.php/supervisor/useredit/<?php echo $info->user_id?>'><?php echo get_phrase('edit');?></a></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
					
				</div>
			</div>
		</div>
	</div>
	
</div>
<script type="text/javascript">
            //<![CDATA[
            //var dataForm = new RegisternForm('form-validate', true);
            $(document).ready(function () {
                $('.table').dataTable();
            });
            //]]>
        </script>