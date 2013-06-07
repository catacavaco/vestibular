var width  = 1600;
var height = 900;
var scale   = 4000;
var centered;
var k = 1;

var center = [-43.57, -19.55];
var offset = [width / 2, height / 2];
var projection = d3.geo.mercator().scale(scale).center(center).translate(offset); 
var path = d3.geo.path().projection(projection);

var vis = d3.select("body").append("svg")
      .attr("width", width).attr("height", height)
         .call(d3.behavior.zoom()
         .scaleExtent([1, 40])
    	.on("zoom", redraw))
    	.append("g");
    	

function redraw() {
    vis.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")")
    .style("stroke-width", 1.5 / d3.event.scale + "px");
}
     
    
d3.json("municipios.geojson", function(json) {
	
	json.features.forEach(function(d) {
		d.properties.mun = +d.properties.mun;
	}); 
	
	vis.selectAll("path").data(json.features).enter().append("path")
		.attr("d", path)
        .attr("class", "municipio")
        
        .attr("uf", function(d) { return d.properties.uf; })
        .attr("sigla", function(d) { return d.properties.sigla; })
		.attr("nome_munic", function(d) { return d.properties.nome_munic; })
		.attr("regiao", function(d) { return d.properties.regiao; })
		.attr("meso", function(d) { return d.properties.meso; })
		.attr("nome_meso", function(d) { return d.properties.nome_meso; })
		.attr("micro", function(d) { return d.properties.micro; })
		.attr("nome_micro", function(d) { return d.properties.nome_micro; })
		.attr("nome_mun", function(d) { return d.properties.nome_mun; })
		.attr("pop2000", function(d) { return d.properties.pop2000; })
		.attr("homens2010", function(d) { return d.properties.homens2010; })
		.attr("mulheres2010", function(d) { return d.properties.mulheres2010; })
		.attr("popurb2010", function(d) { return d.properties.popurb2010; })
		.attr("poprural2010", function(d) { return d.properties.poprural2010; })
		.attr("pop2010", function(d) { return d.properties.pop2010; })

        .style("stroke-width", "0.15")
        .style("stroke", "black")
        .style("fill", function(d) { 
	        	if(d.properties.nome_mun == 'Belo Horizonte'){
	        		return "#7eaec4";
	        	} else {
	        		return "#a1c4d4";	
	        	}
        	})
        .on("mouseover", function(d){d3.select(this).style("fill", "#4d8ca8")})
        .on("mouseout", function(d){
        		if(d.properties.nome_mun == 'Belo Horizonte'){
	        		d3.select(this).style("fill", "#7eaec4")
	        	} else {
	        		d3.select(this).style("fill", "#a1c4d4");	
	        	}
			})
        .on("click",function(d) {
        		click(d);
        		}
        	);
        
        
        $(".municipio").tipsy({
		fade : false,
		html : true,
		gravity : $.fn.tipsy.autoNS,
		trigger : 'hover',
		title: function() {
	          return "<b>" + this.getAttribute("nome_munic") + "</b>" +
	          "<br><span>uf " + this.getAttribute("uf")  + "</span></br>" +
	          "<br><span>sigla " + this.getAttribute("sigla")  + "</span></br>" +
	          "<br><span>regiao " + this.getAttribute("regiao")  + "</span></br>" +
	          "<br><span>meso " + this.getAttribute("meso")  + "</span></br>" +
	          "<br><span>nome_meso " + this.getAttribute("nome_meso")  + "</span></br>" +
	          "<br><span>micro " + this.getAttribute("micro")  + "</span></br>" +
	          "<br><span>nome_micro " + this.getAttribute("nome_micro")  + "</span></br>" +
	          "<br><span>nome_mun " + this.getAttribute("nome_mun")  + "</span></br>" +
	          "<br><span>pop2000 " + this.getAttribute("pop2000")  + "</span></br>" +
	          "<br><span>homens2010 " + this.getAttribute("homens2010")  + "</span></br>" +
	          "<br><span>mulheres2010 " + this.getAttribute("mulheres2010")  + "</span></br>" +
	          "<br><span>pop2010 " + this.getAttribute("pop2010")  + "</span></br>"
	          
	        }
		});
    });

function click(d) {
  var x, y;

  if (d && centered !== d) {
    var centroid = path.centroid(d);
    x = centroid[0];
    y = centroid[1];
    k = 25;
    centered = d;
  } else {
    x = width / 2;
    y = height / 2;
    k = 1;
    centered = null;
  }
    
  vis.selectAll("path")
      .classed("active", centered && function(d) { return d === centered; });

  vis.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
      .style("stroke-width", 1.5 / k + "px");
}


/* criar listener pra quando clicar em bh, criar mapa separado
 * 
 d3.json("bairros.geojson", function(json) {
      // create a first guess for the projection
      var center = d3.geo.centroid(json)
      var scale  = 150;
      var offset = [width/2, height/2];
      var projection = d3.geo.mercator().scale(scale).center(center)
          .translate(offset);

      // create the path
      var path = d3.geo.path().projection(projection);

      // using the path determine the bounds of the current map and use 
      // these to determine better values for the scale and translation
      var bounds  = path.bounds(json);
      var hscale  = scale*width  / (bounds[1][0] - bounds[0][0]);
      var vscale  = scale*height / 2 * (bounds[1][1] - bounds[0][1]);
      var scale   = (hscale < vscale) ? hscale : vscale;
      var offset  = [width - (bounds[0][0] + bounds[1][0])/2,
                        height - (bounds[0][1] + bounds[1][1])/2];

      // new projection
      projection = d3.geo.mercator().center(center)
        .scale(scale).translate(offset);
      path = path.projection(projection);

      vis.selectAll("path").data(json.features).enter().append("path")
        .attr("d", path)
        .style("stroke-width", "0.01")
        .style("stroke", "black")
        .style("fill", function() { return "#F7D683" })
        .on("mouseover", function(e){d3.select(this).style("fill", "#457D97")})
        .on("mouseout", function(e){d3.select(this).style("fill", "#F7D683")})
    });
 */