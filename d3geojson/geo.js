var width  = 1600;
var height = 900;
var scale   = 4000;

  var vis = d3.select("body").append("svg")
      .attr("width", width).attr("height", height)
         .call(d3.behavior.zoom()
    	.on("zoom", redraw))
    	.append("g");


function redraw() {
	console.log("redraw")
    vis.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
}
     
/*
 * Coloquei a area do estado inteiro porque a area dos municipios
 *  tava com poligonos bugados que o d3 nao reconhecia
 */ 
d3.json("estado.geojson", function(json) {
      var center = [-43.57, -19.55];
      var offset = [width/2, height/2];
      var projection = d3.geo.mercator().scale(scale).center(center)
          .translate(offset);

      // Limite
      var path = d3.geo.path().projection(projection);

      vis.selectAll("path").data(json.features).enter().append("path")
        .attr("d", path)
        .style("fill", function() { return "#FFFDC0" })
        .style("stroke-width", "0.1")
        .style("stroke", "black")
    });

  d3.json("bairro_popular.geojson", function(json) {
  	//lat -19° 55' lon +43° 57' 
      var center = [-43.57, -19.55];
      var offset = [width/2, height/2];
      var projection = d3.geo.mercator().scale(scale).center(center)
          .translate(offset);

      var path = d3.geo.path().projection(projection);

      vis.selectAll("path").data(json.features).enter().append("path")
        .attr("d", path)
        .style("stroke-width", "0.01")
        .style("stroke", "black")
        .style("fill", function() { return "#F7D683" })
        .on("mouseover", function(e){d3.select(this).style("fill", "#457D97")})
        .on("mouseout", function(e){d3.select(this).style("fill", "#F7D683")})
    });