
<!DOCTYPE html>
<meta charset="utf-8">
<title>AVAIL </title>
<style>

@import url(resources/css/chord_chart.css);

#circle circle {
  fill: none;
  pointer-events: all;
}

.group path {
  fill-opacity: .5;
}

path.chord {
  stroke: #000;
  stroke-width: .25px;
}

#circle:hover path.fade {
  display: none;
}

</style>

<header>
  <a href="/vis/tredis/" rel="author">AVAIL Labs Tredis Demo</a>
  <aside>May 9, 2013</aside>
</header>

<h1>MN County Flows <span id='heading_commidity'>03</span></h1>

<aside>
<p>
Commodity:
<select id='commodity_select'>
<option value="00" selected>All Commodities</option>
<option value="01">01 - Live animals/fish</option>
<option value="02">02 - Cereal grains</option>
<option value="03">03 - Other ag prods.</option>
<option value="04">04 - Animal feed</option>
<option value="05">05 - Meat/seafood</option>
<option value="06">06 - Milled grain prods.</option>
<option value="07">07 - Other foodstuffs</option>
<option value="08">08 - Alcoholic beverages</option>
<option value="09">09 - Tobacco prods.</option>
<option value="10">10 - Building stone</option>
<option value="11">11 - Natural sands</option>
<option value="12">12 - Gravel</option>
<option value="13">13 - Nonmetallic minerals</option>
<option value="14">14 - Metallic ores</option>
<option value="15">15 - Coal</option>
<option value="16">16 - Crude petroleum</option>
<option value="18">18 - Gasoline</option>
<option value="19">19 - Fuel oils</option>
<option value="20">20 - Basic chemicals</option>
<option value="21">21 - Pharmaceuticals</option>
<option value="22">22 - Fertilizers</option>
<option value="23">23 - Chemical prods.</option>
<option value="24">24 - Plastics/rubber</option>
<option value="25">25 - Logs</option>
<option value="26">26 - Wood prods.</option>
<option value="27">27 - Newsprint/paper</option>
<option value="28">28 - Paper articles</option>
<option value="29">29 - Printed prods.</option>
<option value="30">30 - Textiles/leather</option>
<option value="31">31 - Nonmetal min. prods.</option>
<option value="32">32 - Base metals</option>
<option value="33">33 - Articles-base metal</option>
<option value="34">34 - Machinery</option>
<option value="35">35 - Electronics</option>
<option value="36">36 - Motorized vehicles</option>
<option value="37">37 - Transport equip.</option>
<option value="38">38 - Precision instruments</option>
<option value="39">39 - Furniture</option>
<option value="40">40 - Misc. mfg. prods.</option>
<option value="41">41 - Waste/scrap</option>
<option value="43">43 - Mixed freight</option>
<option value="99">99 - Unknown</option></select>
<br>
<br>
Mode:
<select id='mode_select'>
  <option value='00'>All Modes</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
</select>
<br>
Granularity
<select id='granularity_select'>>
  <option value='0'>0</option>
  <option value='1'>1</option>
  <option value='2'>2</option>
  <option value='3' selected>3</option>
  <option value='4'>4</option>
  <option value =" 5"> 5</option>
  <option value ="10">10</option>
  <option value ="15" >15</option>
  <option value ="20">20</option>
  <option value ="25">25</option>
  <option value ="30">30</option>
  <option value ="35">35</option>
  <option value ="40">40</option>
  <option value ="45">45</option>
</select>
<br>
Origin or Destination
<select id ='orig_or_dest'>
  <option value="orig_fips">Outgoing Flows</option>
  <option value="dest_fips">Incoming Flows</option>
</select>

<br>
<p>The thickness of links be&shy;tween counties encodes the relative quantity of transport between two neighborhoods: thicker links represent more tons.

<p>Links are directed. Links are colored by the greater tonnage origin.
</aside>
<div id="graph"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="resources/js/d3.v3.min.js"></script>

<script>


function chord_chart(data){

  var width = 720,
      height = 720,
      outerRadius = Math.min(width, height) / 2 - 10,
      innerRadius = outerRadius - 24;

  var formatPercent = d3.format(".1%");

  var arc = d3.svg.arc()
      .innerRadius(innerRadius)
      .outerRadius(outerRadius);

  var layout = d3.layout.chord()
      .padding(.04)
      .sortSubgroups(d3.descending)
      .sortChords(d3.ascending);

  var path = d3.svg.chord()
      .radius(innerRadius);

  d3.select("#graph").selectAll("svg").remove();

  var svg = d3.select("#graph").append("svg")
      .attr("width", width)
      .attr("height", height)
    .append("g")
      .attr("id", "circle")
      .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

  svg.append("circle")
      .attr("r", outerRadius);


  cities = data['csv'];
  matrix = data['matrix'];
  // Compute the chord layout.

  layout.matrix(matrix);

  // Add a group per neighborhood.
  var group = svg.selectAll(".group")
      .data(layout.groups)
    .enter().append("g")
      .attr("class", "group")
      .on("mouseover", mouseover);

  // Add a mouseover title.
  group.append("title").text(function(d, i) {
 if (cities[i].name == "27069"){
  cities[i].name = "Kittson County";
 }
 else if (cities[i].name == "27035")
 {
  cities[i].name = "Roseau County";
 }
 else if (cities[i].name == "27071")
 {
  cities[i].name = "Koochiching County";
 }
 else if (cities[i].name == "27137")
 {
  cities[i].name  = "Saint Louis County";
 }
 else if (cities[i].name == "27013"){
  cities[i].name  = "Blue Earth County";
 }
 else if (cities[i].name == "27089"){
  cities[i].name  = "Marshall County";
 }
 else if (cities[i].name == "27007"){
  cities[i].name  = "Beltrami County";
 }
 else if (cities[i].name == "27075"){
  cities[i].name  = "Lake County";
 }
 else if (cities[i].name == "27119"){
  cities[i].name  = "Polk County";
 }
 else if (cities[i].name == "27113"){
  cities[i].name  = "Pennington County";
 }
 else if (cities[i].name == "27029"){
  cities[i].name  = "Clearwater County";
 }
 else if (cities[i].name == "27125"){
  cities[i].name  = "Red Lake County";
 }
 else if (cities[i].name == "27061"){
  cities[i].name  = "Itasca County";
 }
 else if (cities[i].name == "27087"){
  cities[i].name  = "Mahnomen County";
 }
 else if (cities[i].name == "27107"){
  cities[i].name  = "Norman County";
 }
 else if (cities[i].name == "27021"){
  cities[i].name  = "Cass County";
 }
 else if (cities[i].name == "27057"){
  cities[i].name  = "Hubbard County";
 }
 else if (cities[i].name == "27005"){
  cities[i].name  = "Becker County";
 }
 else if (cities[i].name == "27027"){
  cities[i].name  = "Clay County";
 }
 else if (cities[i].name == "27001"){
  cities[i].name  = "Aitkin County";
 }
 else if (cities[i].name == "27159"){
  cities[i].name  = "Wadena County";
 }
 else if (cities[i].name == "27035"){
  cities[i].name  = "Crow Wing County";
 }
 else if (cities[i].name == "27017"){
  cities[i].name  = "Carlton County";
 }
 else if (cities[i].name == "27111"){
  cities[i].name  = "Otter Tail County";
 }
 else if (cities[i].name == "27167"){
  cities[i].name  = "Wilkin County";
 }
 else if (cities[i].name == "27115"){
  cities[i].name  = "Pine County";
 }
 else if (cities[i].name == "27153"){
  cities[i].name  = "Todd County";
 }
 else if (cities[i].name == "27097"){
  cities[i].name  = "Morrison County";
 }
 else if (cities[i].name == "27095"){
  cities[i].name  = "Mille Lacs County";
 }
 else if (cities[i].name == "27065"){
  cities[i].name  = "Kanabec County";
 }
 else if (cities[i].name == "27051"){
  cities[i].name  = "Grant County";
 }
 else if (cities[i].name == "27041"){
  cities[i].name  = "Douglas County";
 }
 else if (cities[i].name == "27155"){
  cities[i].name  = "Traverse County";
 }
 else if (cities[i].name == "27009"){
  cities[i].name  = "Benton County";
 }
 else if (cities[i].name == "27145"){
  cities[i].name  = "Stearns County";
 }
 else if (cities[i].name == "27149"){
  cities[i].name  = "Stevens County";
 }
 else if (cities[i].name == "27121"){
  cities[i].name  = "Pope County";
 }
 else if (cities[i].name == "27059"){
  cities[i].name  = "Isanti County";
 }
 else if (cities[i].name == "27025"){
  cities[i].name  = "Chisago County";
 }
 else if (cities[i].name == "27011"){
  cities[i].name  = "Big Stone County";
 }
 else if (cities[i].name == "27141"){
  cities[i].name  = "Sherburne County";
 }
 else if (cities[i].name == "27171"){
  cities[i].name  = "Wright County";
 }
 else if (cities[i].name == "27013"){
  cities[i].name  = "Blue Earth County";
 }
 else if (cities[i].name == "27003"){
  cities[i].name  = "Anoka County";
 }
 else if (cities[i].name == "27067"){
  cities[i].name  = "Kandiyohi County";
 }
 else if (cities[i].name == "27151"){
  cities[i].name  = "Swift County";
 }
 else if (cities[i].name == "27093"){
  cities[i].name  = "Meeker County";
 }
 else if (cities[i].name == "27163"){
  cities[i].name  = "Washington County";
 }
 else if (cities[i].name == "27073"){
  cities[i].name  = "Lac qui Parle County";
 }
 else if (cities[i].name == "27053"){
  cities[i].name  = "Hennepin County";
 }
 else if (cities[i].name == "27023"){
  cities[i].name  = "Chippewa County";
 }
 else if (cities[i].name == "27123"){
  cities[i].name  = "Ramsey County";
 }
 else if (cities[i].name == "27085"){
  cities[i].name  = "McLeod County";
 }
 else if (cities[i].name == "27019"){
  cities[i].name  = "Carver County";
 }
 else if (cities[i].name == "27173"){
  cities[i].name  = "Yellow Medicine County";
 }
 else if (cities[i].name == "27037"){
  cities[i].name  = "Dakota County";
 }
 
 else if (cities[i].name == "27129"){
  cities[i].name  = "Renville County";
 }
 else if (cities[i].name == "27139"){
  cities[i].name  = "Scott County";
 }
 else if (cities[i].name == "27143"){
  cities[i].name  = "Sibley County";
 }
 else if (cities[i].name == "27049"){
  cities[i].name  = "Goodhue County";
 }
 else if (cities[i].name == "27127"){
  cities[i].name  = "Redwood County";
 }
 else if (cities[i].name == "27081"){
  cities[i].name  = "Lincoln County";
 }
 else if (cities[i].name == "27083"){
  cities[i].name  = "Lyon County";
 }
 else if (cities[i].name == "27019"){
  cities[i].name  = "Carver County";
 }
 else if (cities[i].name == "27079"){
  cities[i].name  = "Le Seuer County";
 }
 else if (cities[i].name == "27131"){
  cities[i].name  = "Rice County";
 }
 else if (cities[i].name == "27015"){
  cities[i].name  = "Brown County";
 }
 else if (cities[i].name == "27103"){
  cities[i].name  = "Nicollet County";
 }
 else if (cities[i].name == "27157"){
  cities[i].name  = "Wabasha County";
 }
 else if (cities[i].name == "27117"){
  cities[i].name  = "Pipestone County";
 }
 else if (cities[i].name == "27101"){
  cities[i].name  = "Murray County";
 }
 else if (cities[i].name == "27147"){
  cities[i].name  = "Steele County";
 }
 else if (cities[i].name == "27039"){
  cities[i].name  = "Dodge County";
 }
 else if (cities[i].name == "27161"){
  cities[i].name  = "Waseca County";
 }
 else if (cities[i].name == "27109"){
  cities[i].name  = "Olmsted County";
 }
 else if (cities[i].name == "27033"){
  cities[i].name  = "Cottonwood County";
 }
 else if (cities[i].name == "27169"){
  cities[i].name  = "Winona County";
 }
 else if (cities[i].name == "27165"){
  cities[i].name  = "Watonwan County";
 }
 else if (cities[i].name == "27133"){
  cities[i].name  = "Rock County";
 }
 else if (cities[i].name == "27105"){
  cities[i].name  = "Nobles County";
 }
 else if (cities[i].name == "27047"){
  cities[i].name  = "Freeborn County";
 }
 else if (cities[i].name == "27099"){
  cities[i].name  = "Mower County";
 }
 else if (cities[i].name == "27055"){
  cities[i].name  = "Houston County";
 }
 else if (cities[i].name == "27045"){
  cities[i].name  = "Fillmore County";
 }
 else if (cities[i].name == "27063"){
  cities[i].name  = "Jackson County";
 }
 else if (cities[i].name == "27043"){
  cities[i].name  = "Faribault County";
 }
 else if (cities[i].name == "27091"){
  cities[i].name  = "Martin County";
 }
 else if (cities[i].name == "27077"){
  cities[i].name  = "Lake of the Woods County";
 }
else if (cities[i].name == "27031"){
  cities[i].name  = "Cook County";
 }
 else if (cities[i].name == "27019"){
  cities[i].name  = "Carver County";
 }
  //console.log(cities[i].name);
    return cities[i].name + ": " + parseFloat(cities[i].sum).toFixed(2) + " tons.";

  });


  // Add the group arc.
  var groupPath = group.append("path")
      .attr("id", function(d, i) { return "group" + i; })
      .attr("d", arc)
      .style("fill", function(d, i) { return cities[i].color; });

  // Add a text label.
  var groupText = group.append("text")
      .attr("x", 6)
      .attr("dy", 15);

  groupText.append("textPath")
      .attr("xlink:href", function(d, i) { return "#group" + i; })
      .text(function(d, i) { return cities[i].name; });

  // Remove the labels that don't fit. :(
  groupText.filter(function(d, i) { return groupPath[0][i].getTotalLength() / 2 - 16 < this.getComputedTextLength(); })
      .remove();

  // Add the chords.
  var chord = svg.selectAll(".chord")
      .data(layout.chords)
    .enter().append("path")
      .attr("class", "chord")
      .style("fill", function(d) { return cities[d.source.index].color; })
      .attr("d", path);

  // Add an elaborate mouseover title for each chord.
  chord.append("title").text(function(d) {
    return cities[d.source.index].name
        + " → " + cities[d.target.index].name
        + ": " + d.source.value.toFixed(2)+"tns"
        + "\n" + cities[d.target.index].name
        + " → " + cities[d.source.index].name
        + ": " + d.target.value.toFixed(2)+'tns';
  });

  function mouseover(d, i) {
    chord.classed("fade", function(p) {
      return p.source.index != i
          && p.target.index != i;
    });
  }
}

  var url = 'data/get/getCountyFlow.php';
  $.ajax({url:url, type:'POST',data: { sctg:'03',mode:"0",granularity:'3',orig_or_dest:'orig_fips' },dataType:'json',async:true})
    .done(function(data) { 
      chord_chart(data);  
    })
    .fail(function(data) { console.log(data.responseText) });
  
  $(function(){
    $('select').on('change',function(){
      var url = 'data/get/getCountyFlow.php';
      commodity = $("#commodity_select").val();
      mode = $("#mode_select").val();
      granularity = $("#granularity_select").val();
      orig_or_dest = $("#orig_or_dest").val();
      $('#heading_commidity').html(commodity);
      $.ajax({url:url, type:'POST',data: { sctg:commodity,mode:mode,granularity:granularity,orig_or_dest:orig_or_dest},dataType:'json',async:true})
        .done(function(data) { 
          
          chord_chart(data);  
        })
        .fail(function(data) { console.log(data.responseText) });
    })
  })
</script>




