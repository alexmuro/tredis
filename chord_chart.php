
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
  <a href="availabs.org" rel="author">AVAIL Labs Tredis Demo</a>
  <aside>May 9, 2013</aside>
</header>

<h1>MN County Flows <span id='heading_commidity'>03</span></h1>

<aside>
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
  <option value='3'>3</option>
  <option value='4'>4</option>
  <option value =" 5"> 5</option>
  <option value ="10">10</option>
  <option value ="15" selected>15</option>
  <option value ="20">20</option>
  <option value ="25">25</option>
  <option value ="30">30</option>
  <option value ="35">35</option>
  <option value ="40">40</option>
  <option value ="45">45</option>
</select>
<br>
<p>The thickness of links be&shy;tween counties encodes the relative quantity of transport between two neighborhoods: thicker links represent more tons.

<p>Links are directed. Links are colored by the greater tonnage origin.
</aside>
<div id="graph"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="http://d3js.org/d3.v2.min.js?2.8.1"></script>
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
  console.log(cities);
  console.log(matrix)
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
    return cities[i].name + ": " + formatPercent(d.value) + " of origins";
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
        + ": " + formatPercent(d.source.value)
        + "\n" + cities[d.target.index].name
        + " → " + cities[d.source.index].name
        + ": " + formatPercent(d.target.value);
  });

  function mouseover(d, i) {
    chord.classed("fade", function(p) {
      return p.source.index != i
          && p.target.index != i;
    });
  }
}

  var url = 'data/get/getCountyFlow.php';
  $.ajax({url:url, type:'POST',data: { sctg:'03',mode:"0",granularity:'15' },dataType:'json',async:true})
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
      $('#heading_commidity').html(commodity);
      $.ajax({url:url, type:'POST',data: { sctg:commodity,mode:mode,granularity:granularity},dataType:'json',async:true})
        .done(function(data) { 

          chord_chart(data);  
        })
        .fail(function(data) { console.log(data.responseText) });
    })
  })
</script>




