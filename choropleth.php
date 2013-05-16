
<!DOCTYPE html>
<meta charset="utf-8">
<title>AVAIL </title>
<style>

@import url(resources/css/chord_chart.css);


.counties {
  fill: none;
}

.states {
  fill: none;
  stroke: #fff;
  stroke-linejoin: round;
}

.q0-9 { fill:rgb(247,251,255);stroke:#000;stroke-linejoin: round; }
.q1-9 { fill:rgb(222,235,247);stroke:#000;stroke-linejoin: round; }
.q2-9 { fill:rgb(198,219,239);stroke:#000;stroke-linejoin: round; }
.q3-9 { fill:rgb(158,202,225);stroke:#000;stroke-linejoin: round; }
.q4-9 { fill:rgb(107,174,214);stroke:#000;stroke-linejoin: round; }
.q5-9 { fill:rgb(66,146,198); stroke:#000;stroke-linejoin: round;}
.q6-9 { fill:rgb(33,113,181); stroke:#000;stroke-linejoin: round;}
.q7-9 { fill:rgb(8,81,156); stroke:#000;stroke-linejoin: round;}
.q8-9 { fill:rgb(8,48,107);stroke:#000; }
.none { fill:#fff;stroke:#ccc;stroke-linejoin: round; }
.selected { fill:#f00;stroke:#000;stroke-linejoin: round; }


</style>

<header>
  <a href="availabs.org" rel="author">AVAIL Labs Tredis Demo</a>
  <aside>May 16, 2013</aside>
</header>

<h1>MN County Flows <span id='heading_commidity'>03</span></h1>

<aside style="margin-top:300px;"> 
<p>
Selected County
<select id='county_select'>
</select><br>
Commodity:
<select id='commodity_select'>
<option value="00" selected>All Commodities</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03" >03</option>
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
Origin or Destination
<select id ='orig_or_dest'>
  <option value="orig_fips">Outgoing Flows</option>
  <option value="dest_fips">Incoming Flows</option>
</select>




</aside>
<div id="graph"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="resources/js/d3.v3.min.js"></script>
<script src="resources/js/queue.v1.min.js"></script>
<script src="resources/js/topojson.v1.min.js"></script>

<script>


function choropleth(data,fips){

 var width = 860,
    height = 500;

var rateById = d3.map();

var quantize = d3.scale.quantize()
    .domain([0, 500])
    .range(d3.range(9).map(function(i) { return "q" + i + "-9"; }));

var path = d3.geo.path();

d3.select("body").selectAll("svg").remove();

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

data.forEach(function(d) { 
  rateById.set((d.orig)*1, +d.tons*1000);
})

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
      .attr("class", function(d) { if(d.id == fips){ return 'selected';}else{return quantize(rateById.get(d.id)) || 'none';} })
      .attr("d", path);

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


  var url = 'data/get/getCountyToNation.php';
  $.ajax({url:url, type:'POST',data: { sctg:'00',mode:"00",orig_or_dest:'orig_fips',fips:27137 },dataType:'json',async:true})
    .done(function(data) { 
      choropleth(data,27137);  
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
      $('#heading_commidity').html(commodity);
      $.ajax({url:url, type:'POST',data: { sctg:commodity,mode:mode,orig_or_dest:orig_or_dest,fips:fips},dataType:'json',async:true})
        .done(function(data) { 
          choropleth(data,fips);  
        })
        .fail(function(data) { console.log(data.responseText) });
    })
  })
</script>




