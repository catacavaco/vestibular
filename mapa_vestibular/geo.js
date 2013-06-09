var magnitude = "mesoregioes_mg";
var center = [-44.31301959952854, -18.95937600486544];

var width = $("#geografico").width(), height = width / 1.2, centered;
var scale = width * 3.5;
var offset = [width / 2, height / 2];
var projection = d3.geo.mercator().scale(scale).center(center).translate(offset);
var path = d3.geo.path().projection(projection);

var svg = d3.select("#geografico").append("svg")
    .attr("width", width)
    .attr("height", height);

svg.append("rect")
    .attr("class", "background")
    .attr("width", width)
    .attr("height", height)
    .on("click", click);

var g = svg.append("g")
    .attr("id", "states");
    
function draw(){
	d3.json("data.geojson", function(json) {
		
		json.features.forEach(function(d) {
			d.tipo = d.tipo;
		}); 
		
		g.selectAll("path").remove();
		
		g.selectAll("path").data(json.features).enter().append("path")
			.attr("id", function(d) {
				if (d.properties.nome == 'Belo Horizonte') {
					return "capital";
				} else if (d.properties.nome == 'Metropolitana de Belo Horizonte') {
					return "metropole";
				}
			})
			.attr("class", function(d) { return d.tipo; })
			.attr("nome", function(d) { return d.properties.nome; })
	        
	        .attr("uf", function(d) { return d.properties.uf; })
	        .attr("sigla", function(d) { return d.properties.sigla; })
			.attr("regiao", function(d) { return d.properties.regiao; })
			.attr("nome_meso", function(d) { return d.properties.nome_meso; })
			.attr("pop2010", function(d) { return d.properties.pop2010; })
			.attr("d", path)
			.on("click", function(d) {
				if (d.tipo != "bairro_popular") {
					// MESO
					if (d.tipo == "mesoregioes_mg") {
						if (d.properties.nome == 'Metropolitana de Belo Horizonte') {
							magnitude = "municipios_metropolitana_bh";
						} else {
							magnitude = "mesoregioes_mg";
						}
							click(d, false);
					// MUNICIPIOS
					} else if (d.tipo == "municipios_metropolitana_bh") {
						if (d.properties.nome == 'Belo Horizonte') {
							magnitude = "bairro_popular";
							click(d, true);
						} else {
							magnitude = "municipios_metropolitana_bh";
							click(d, false);
						}
					}
				}
			}); 
		
			$("path").tipsy({
				html : true,
				gravity : 's',//$.fn.tipsy.autoNS,
				offset: -1,
				trigger : 'hover',
				title : function() {
					var title = title = this.getAttribute("nome");
					var string = "<b>" + title + " </b><br/>";
					for (var i = 0; i < this.attributes.length; i++) {
						var attrib = this.attributes[i];
						if (attrib.specified && attrib.name != "d" && attrib.name != "original-title" && attrib.name != "class" && attrib.name != "nome") {
							string += "<b>" + attrib.name + "</b> <span> " + attrib.value + " </span>";
						}
					}
		
					return string;
				}
			}); 
		
			$("#mesoregioes_mg, #municipios_metropolitana_bh, #bairro_popular").click(function() {
				magnitude = this.id;
				sortElements();
			});
	
			sortElements(); 
	
	});
}

draw();

function click(d, isCapital) {
	var x, y, k;
	
	if (d && centered !== d) {
		var centroid = path.centroid(d);
		x = centroid[0];
		y = centroid[1];
		centered = d;
		if (isCapital) {
			k = 33;
		} else {
			k = 4;
		}
	} else {
		x = width / 2;
		y = height / 2;
		k = 1;
		centered = null;
		magnitude = "mesoregioes_mg";
	}
	sortElements();

	g.selectAll("path").classed("active", centered &&
	function(d) {
		return d === centered;
	});

	g.transition().duration(1000)
		.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
		.style("stroke-width", 0.5 / k + "px");

}

function getMV(d) {
	if ("mesoregioes_mg".indexOf(d) !== -1) {
		return 3;
	} else if ("municipios_metropolitana_bh".indexOf(d) !== -1) {
		return 2;
	} else {
		return 1;
	}
}

function sortElements() {
	
	svg.selectAll("path").sort(function(a, b) {
		// MESOREGIAO
		if (getMV(magnitude) == 3) {
			if (getMV(a.tipo) == 3) {
				return 1;
			} else {
				return -1;
			}
		// METROPOLITANA
		} else if (getMV(magnitude) == 2) {
			if (getMV(a.tipo) == 2) {
				return 1;
			} else {
				return -1;
			}
		// BAIRRO POPULAR
		} else {
			if (getMV(a.tipo) == getMV(b.tipo) && getMV(a.tipo) != 1) {
				return 0;
			} else if (getMV(a.tipo) > getMV(b.tipo)) {
				return -1;
			} else {
				if(getMV(a.tipo) == 1) {
					return 1;
				} else {
					return 0;
				} 
			}

		}
	});
}


$(window).resize(function() {
	width = $("#geografico").width();
	height = width / 1.2;
	scale = width * 3.5;
	offset = [width / 2, height / 2];
	projection = d3.geo.mercator()
				.scale(scale)
				.center(center)
				.translate(offset);
	path = d3.geo.path().projection(projection);

	svg = d3.select("svg").remove();
	svg = d3.select("#geografico").append("svg")
				.attr("width", width)
				.attr("height", height);

	svg.append("rect")
				.attr("class", "background")
				.attr("width", width)
				.attr("height", height)
				.on("click", click);

	g = svg.append("g").attr("id", "states");
	draw();
});
