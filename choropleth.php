<!DOCTYPE html>
<meta charset="utf-8">
<title>AVAIL</title>
<style>

@import url(resources/css/chord_chart.css);

#data{
  position:absolute;
  top:650px;
}
#county_data{
  position:absolute;
  top:650px;
  left:300px;
}

#hover{
  position:absolute;
  top:150px;
}

.counties {
  fill: none;
}


.states {
  fill: none;
  stroke: #fff;
  stroke-linejoin: round;
}

.q0-9 { fill:rgb(247,251,255);stroke-linejoin: round; }
.q1-9 { fill:rgb(222,235,247);stroke-linejoin: round; }
.q2-9 { fill:rgb(198,219,239);stroke-linejoin: round; }
.q3-9 { fill:rgb(158,202,225);stroke-linejoin: round; }
.q4-9 { fill:rgb(107,174,214);stroke-linejoin: round; }
.q5-9 { fill:rgb(66,146,198); stroke-linejoin: round;}
.q6-9 { fill:rgb(33,113,181); stroke-linejoin: round;}
.q7-9 { fill:rgb(8,81,156); stroke-linejoin: round;}
.q8-9 { fill:rgb(8,48,107); }
.none { fill:#fff;stroke:#ccc;stroke-linejoin: round; }
.selected { fill:#f00;stroke:#000;stroke-linejoin: round; }

.county {
  stroke:#000;
}
.county:hover{
  stroke:#f00;
  stroke-width:3px;
}


</style>

<header>
  <a href="availabs.org" rel="author">AVAIL Labs Tredis Demo</a>
  <aside>May 16, 2013</aside>
</header>
<body>
<h1><span id="heading_county">MN County</span> Flows <span id='heading_commidity'>03</span></h1>

<aside style="margin-top:300px;"> 
<p>
Selected County
<select id='county_select'>
</select><br>
Commodity:
<select id='commodity_select'>
<option value="00" selected>All Commodities</option>
<option value="01">Live animals/fish</option>
<option value="02">Cereal grains</option>
<option value="03">Other ag prods.</option>
<option value="04">Animal feed</option>
<option value="05">Meat/seafood</option>
<option value="06">Milled grain prods.</option>
<option value="07">Other foodstuffs</option>
<option value="08">Alcoholic beverages</option>
<option value="09">Tobacco prods.</option>
<option value="10">Building stone</option>
<option value="11">Natural sands</option>
<option value="12">Gravel</option>
<option value="13">Nonmetallic minerals</option>
<option value="14">Metallic ores</option>
<option value="15">Coal</option>
<option value="16">Crude petroleum</option>
<option value="18">Gasoline</option>
<option value="19">Fuel oils</option>
<option value="20">Basic chemicals</option>
<option value="21">Pharmaceuticals</option>
<option value="22">Fertilizers</option>
<option value="23">Chemical prods.</option>
<option value="24">Plastics/rubber</option>
<option value="25">Logs</option>
<option value="26">Wood prods.</option>
<option value="27">Newsprint/paper</option>
<option value="28">Paper articles</option>
<option value="29">Printed prods.</option>
<option value="30">Textiles/leather</option>
<option value="31">Nonmetal min. prods.</option>
<option value="32">Base metals</option>
<option value="33">Articles-base metal</option>
<option value="34">Machinery</option>
<option value="35">Electronics</option>
<option value="36">Motorized vehicles</option>
<option value="37">Transport equip.</option>
<option value="38">Precision instruments</option>
<option value="39">Furniture</option>
<option value="40">Misc. mfg. prods.</option>
<option value="41">Waste/scrap</option>
<option value="43">Mixed freight</option>
<option value="99">Unknown</option></select>
<br>
Mode:
<select id='mode_select'>
  <option value='00'>All Modes</option>
  <option value="1">Truck</option>
  <option value="2">Rail</option>
  <option value="3">Water</option>
  <option value="5">Air</option>
  <option value="6">Pipeline</option>
  <option value="7">Other/Unkown</option>
</select>
<br>
Origin or Destination
<select id ='orig_or_dest'>
  <option value="orig_fips">Export Flows</option>
  <option value="dest_fips">Import Flows</option>
</select>




</aside>
<div id="container">
<div id="graph">
</div>
<div id="data">
</div>
<div id="county_data">
</div>
<div id="hover"
</div>
</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="resources/js/d3.v3.min.js"></script>
<script src="resources/js/queue.v1.min.js"></script>
<script src="resources/js/topojson.v1.min.js"></script>

<script>


function choropleth(data,fips){

 var width = 860,
    height = 500;

var rateById = d3.map();

var countyData = {};
var ton_domain = []
var max = 0;
data.forEach(function(d) { 
  rateById.set((d.orig)*1, +d.tons*1000);
  if(d.tons>max){
    max = d.tons;
  }
  ton_domain.push(d.tons);
})

//console.log(ton_domain);

var quantize = d3.scale.quantile()
    .domain([0,ton_domain[0]])
    .range(d3.range(9).map(function(i) { return "q" + i + "-9"; }));
console.log(quantize.quantiles());

var path = d3.geo.path();

d3.select("body").selectAll("svg").remove();

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);



queue()
    .defer(d3.json, "data/us-counties.json")
    .await(ready);



  function ready(error, us) {
    //console.log(error);
    svg.append("g")
      .attr("class", "counties")
    .selectAll("path")
      .data(topojson.feature(us, us.objects.counties).features)
    .enter().append("path")
      .attr("class", function(d) { if(d.id == fips){ return 'selected';}else{return quantize(rateById.get(d.id))+' county' || 'none';} })
      //.attr("tons",function(d){ return (rateById.get(d.id)*1000).toFixed(2); })
      //.attr("fips",function(d){ return d.id; })
      .attr("d", path)
      .on("mouseover", function(d) { d3.select("#hover").html('County: '+d.id+'<br>Tons: '+(rateById.get(d.id)/1000).toFixed(2)); });

    svg.append("path")
      .datum(topojson.mesh(us, us.objects.states, function(a, b) { return a !== b; }))
      .attr("class", "states")
      .attr("d", path);
  }
}

  // function createDB(fips){
  //   var url = 'data/create/createCountyTable.php';
  //   $.ajax({url:url, type:'POST',data: { fips:fips },dataType:'json',async:false})
  //   .done(function(data) { 
  //     console.log(data);
  //   })
  //   .fail(function(data) { console.log(data.responseText) });
  // }

  d3.json('MN_Counties.topojson', function(error, oh) {
    var counties = topojson.feature(oh, oh.objects.counties);
    counties.features.forEach(function(d){
      $('#county_select')
         .append($("<option></option>")
         .attr("value",d.id)
         .text(d.properties.name+"-"+d.id)); 
    });
    $('#county_select').val(27137);
  });

  function drawFlowTable(data){
    $('#county_data').html('<h3>'+$("#county_select").find(":selected").text()+' Totals<br>by Mode </h3>');
    $('#county_data').append('<strong>Export Flows</strong><br>');
    $('#county_data').append('All Modes : '+number_format((1*data['orig_fips']['total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Truck : '+number_format((1*data['orig_fips']['truck_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Rail : '+number_format((1*data['orig_fips']['rail_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Water : '+number_format((1*data['orig_fips']['water_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Pipeline : '+number_format((1*data['orig_fips']['pipeline_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Other/Unkown : '+number_format((1*data['orig_fips']['other_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('<br>');
    $('#county_data').append('<strong>Import Flows</strong><br>');
    $('#county_data').append('All Modes : '+number_format((1*data['dest_fips']['total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Truck : '+number_format((1*data['dest_fips']['truck_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Rail : '+number_format((1*data['dest_fips']['rail_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Water : '+number_format((1*data['dest_fips']['water_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Pipeline : '+number_format((1*data['dest_fips']['pipeline_total']).toFixed(2))+' tons<br>');
    $('#county_data').append('Other/Unkown : '+number_format((1*data['dest_fips']['other_total']).toFixed(2))+' tons<br>');
    console.log(data);
  }

  function drawTable(data){
    $('#data').html('<h3>Top Trade Destinations<br> by Mode &amp; Commodity</h3>');
          $('#data').append("<table><thead><tr><td>Rank</td><td>County</td><td>Tons</td></tr></thead>");
          $.each(data,function(d,v){
            if(d <20){
              $('#data').append("<tr><td>"+(d*1+1)+"&nbsp;&nbsp;</td><td>"+v.orig+"&nbsp;&nbsp;</td><td> "+(v.tons*1).toFixed(2)+"</td></tr>");
            }
          });
          $('#data').append("</table>");
  }

  var url = 'data/get/getCountyToNation.php';
  $.ajax({url:url, type:'POST',data: { sctg:'00',mode:"00",orig_or_dest:'orig_fips',fips:27137 },dataType:'json',async:true})
    .done(function(data) { 
      $('#heading_commidity').html($("#commodity_select").find(":selected").text());
      choropleth(data['map'],27137);
      drawTable(data['map']); 
      drawFlowTable(data['flow'])
    })
    .fail(function(data) { console.log(data.responseText) });
  
  $(function(){
    $('select').on('change',function(){
      var url = 'data/get/getCountyToNation.php';
      commodity = $("#commodity_select").val();
      mode = $("#mode_select").val();
      granularity = $("#granularity_select").val();
      orig_or_dest = $("#orig_or_dest").val();
      fips = $('#county_select').val();
      $('#heading_commidity').html($("#commodity_select").find(":selected").text());
      $.ajax({url:url, type:'POST',data: { sctg:commodity,mode:mode,orig_or_dest:orig_or_dest,fips:fips},dataType:'json',async:true})
        .done(function(data) { 
          choropleth(data['map'],fips);
          drawTable(data['map']);    
        })
        .fail(function(data) { console.log(data.responseText) });
    })
  })
  function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>




