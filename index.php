<!-- mbostock’s block #5349951
Ohio State Plane (N)
April 10, 2013
Open in a new window.
The counties of Ohio, generated via the us-atlas and using the Ohio State Plane North projection, part of the State Plane Coordinate System.

index.html#
 -->
<!DOCTYPE html>

<meta charset="utf-8">

<style>
@import url(resources/css/chord_chart.css);

.county {
  fill: #eee;
  z-index:-1;
}

.county:hover {
  fill: orange;
}

.county-border {
  fill: none;
  stroke: #777;
  pointer-events: none;
}

path.cell {
  fill: none;
  pointer-events: all;
}

#cells.voronoi path.cell {
  stroke: brown;
  z-index:1000;
}

#cells g:hover path.arc {
  display: inherit;
}

</style>
<body>

</style>

<header>
  <aside>May 9, 2013</aside>
</header>

<h1>MN County Flows <span id='heading_commidity'>03</span></h1>

<aside>
<p>
  <input type="checkbox" id="voronoi"> <label for="voronoi">show Voronoi</label>
</p>
<p>
Commodity:
<select id='commodity_select'>
  <option value="01">01</option>
<option value="02">02</option>
<option value="03" selected>03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="43">43</option>
<option value="99">99</option>
</select>
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
 <h2>
      <span>Minnesota Counties</span>
    </h2>
</aside>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://d3js.org/topojson.v1.min.js"></script>
<script>
function getCentroid(selection) {
    // get the DOM element from a D3 selection
    // you could also use "this" inside .each()
    var element = selection.node(),
        // use the native SVG interface to get the bounding box
        bbox = element.getBBox();
    // return the center of the bounding box
    return [bbox.x + bbox.width/2, bbox.y + bbox.height/2];
}

function symbol_graph(map,flow_data)
{
var width = 700,
    height = 740,
    positions = [],
    hubs = [];

var projection = d3.geo.conicConformal()
    .parallels([39 + 26 / 60, 41 + 42 / 60])
    .rotate([93 + 45 / 60, -40 - 20 / 60])
    .translate([width / 2, height / 2]);

var path = d3.geo.path()
    .projection(projection);

var svg = d3.select("body").append("svg:svg")
    .attr("width", width)
    .attr("height", height);

var states = svg.append("svg:g")
    .attr("id", "states");

var circles = svg.append("svg:g")
    .attr("id", "circles");

var cells = svg.append("svg:g")
    .attr("id", "cells");



  d3.json(map, function(error, oh) {
  var counties = topojson.feature(oh, oh.objects.counties);


  projection
      .scale(1)
      .translate([0, 0]);

  var b = path.bounds(counties),
      s = .95 / Math.max((b[1][0] - b[0][0]) / width, (b[1][1] - b[0][1]) / height),
      t = [(width - s * (b[1][0] + b[0][0])) / 2, (height - s * (b[1][1] + b[0][1])) / 2];

  projection
      .scale(s)
      .translate(t);

  states.selectAll("path")
      .data(counties.features)
    .enter().append("svg:path")
      .attr("class", "county")
      .attr("d", path)
    .append("title")
      .text(function(d) { d.name });

  svg.append("path")
      .datum(topojson.mesh(oh, oh.objects.counties, function(a, b) { return a !== b; }))
      .attr("class", "county-border")
      .attr("d", path);

    svg.selectAll("path")
      .each(function(d){
        latlong = getCentroid(d3.select(this));
        positions.push(latlong);
        hub = {};
        hub['id'] = d.id;
        if(typeof d.properties != 'undefined'){
          hub['name'] = d.properties.name;
        }
        hub['latitude'] = latlong[0];
        hub['longitude'] = latlong[1];
        hubs.push(hub);
      });

     var linksByOrigin = {},
      countByOrig = {},
      countByDest = {},
      locationByCounty = {};

  var arc = d3.geo.greatArc()
      .source(function(d) { return locationByCounty[d.source]; })
      .target(function(d) { return locationByCounty[d.target]; });

  flow_data.forEach(function(flow) {
    var origin = flow.orig,
        destination = flow.dest,
        links = linksByOrigin[origin] || (linksByOrigin[origin] = []);
    links.push({source: origin, target: destination});
    countByOrig[origin] = (countByOrig[origin] || 0) + flow.tons*1;
    countByDest[destination] = (countByDest[destination] || 0) + flow.tons*1;
  });

  console.log(countByOrig);


  var polygons = d3.geom.voronoi(positions);

    var g = cells.selectAll("g")
      .data(hubs)
    .enter().append("svg:g");

    g.append("svg:path")
        .attr("class", "cell")
        .attr("d", function(d, i) { return "M" + polygons[i].join("L") + "Z"; })
        .on("mouseover", function(d, i) { d3.select("h2 span").text(d.name); });

    circles.selectAll("circle")
        .data(hubs)
      .enter().append("svg:circle")
        .attr("cx", function(d, i) { return positions[i][0]; })
        .attr("cy", function(d, i) { return positions[i][1]; })
        .attr("r", function(d, i) { return Math.sqrt(countByOrig[d.id+""]/7) || 1; });
        
     

    d3.select("input[type=checkbox]").on("change", function() {
     cells.classed("voronoi", this.checked);
    });

});

d3.select(self.frameElement).style("height", height + "px");
}

var url = 'data/get/getCountyOrigDestFlow.php';
  $.ajax({url:url, type:'POST',data: { sctg:'03',mode:"0",granularity:'3' },dataType:'json',async:true})
    .done(function(data) { 
      console.log(data);
      symbol_graph("MN_Counties.topojson",data);  
    })
    .fail(function(data) { console.log(data.responseText) });

</script>
