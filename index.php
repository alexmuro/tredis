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

.county {
  fill: #eee;
}

.county:hover {
  fill: orange;
}

.county-border {
  fill: none;
  stroke: #777;
  pointer-events: none;
}

</style>
<body>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://d3js.org/topojson.v1.min.js"></script>
<script>

var width = 960,
    height = 1200;

var projection = d3.geo.conicConformal()
    .parallels([40 + 26 / 60, 41 + 42 / 60])
    .rotate([82 + 30 / 60, -39 - 40 / 60])
    .translate([width / 2, height / 2]);

var path = d3.geo.path()
    .projection(projection);

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

d3.json("MN_Counties.topojson", function(error, oh) {
var counties = topojson.feature(oh, oh.objects.counties);
console.log(counties)

  projection
      .scale(1)
      .translate([0, 0]);

  var b = path.bounds(counties),
      s = .95 / Math.max((b[1][0] - b[0][0]) / width, (b[1][1] - b[0][1]) / height),
      t = [(width - s * (b[1][0] + b[0][0])) / 2, (height - s * (b[1][1] + b[0][1])) / 2];
  console.log()

  projection
      .scale(s)
      .translate(t);

  svg.selectAll("path")
      .data(counties.features)
    .enter().append("path")
      .attr("class", "county")
      .attr("d", path)
    .append("title")
      .text(function(d) { 'Countyx' });

  svg.append("path")
      .datum(topojson.mesh(oh, oh.objects.counties, function(a, b) { return a !== b; }))
      .attr("class", "county-border")
      .attr("d", path);
});

d3.select(self.frameElement).style("height", height + "px");

</script>
