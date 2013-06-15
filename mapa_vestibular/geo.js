var center = [-44.31301959952854, -18.95937600486544];
var color = d3.scale.linear()
    .domain([.00,.20,.40])
    .range(["#DEEBF7", "#9ECAE1", "#3182BD"]);

var magnitude = "mesomg";
var selectedMeso = "";

var width = $("#geografico").width(), height = width / 1.6, centered;
var scale = width * 3.3;
var offset = [width / 2, height / 2];
var projection = d3.geo.mercator().scale(scale).center(center).translate(offset);
var path = d3.geo.path().projection(projection);

var svg = d3.select("#geografico").append("svg")
    .attr("width", width)
    .attr("height", height);

var rect = svg.append("rect")
    .attr("class", "background")
    .attr("width", width)
    .attr("height", height)
    .on("click", click);

var g = svg.append("g")
    .attr("id", "mg");
    
var points = svg.append("g")
    .attr("id", "candidatos");
    
function draw(){
	d3.json("minas2.geojson", function(json) {
		
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
			.attr("gid", function(d) {
				if (d.tipo == "bairrobh") {
					return d.properties.bid;
				} else if (d.tipo == "munmg") {
					return d.properties.idmun;
				} else {
					return d.properties.idmeso;
				}
			})
			.attr("nome", function(d) { return d.properties.nome; })
	        
	        .attr("Sigla", function(d) { return d.properties.sigla; })
			.attr("Regiao", function(d) { return d.properties.regiao; })
			.attr("Mesoregiao", function(d) { return d.properties.nome_meso; })
			.attr("Populacao", function(d) { return d.properties.pop2010; })
			/* Parte que tem q entrar o PHP */
			.style("fill", function(d) { return color(d.properties.candidatosaprovados/d.properties.candidatos); })
			.attr("MediaProva", function(d) { return d.properties.mediaprova; })
			.attr("IndiceDeAprovamento", function(d) { return ((d.properties.candidatosaprovados/d.properties.candidatos) * 100).toFixed(2) + "%"; })
			.attr("Candidatos", function(d) { return d.properties.candidatos; })
			.attr("CandidatosAprovados", function(d) { return d.properties.candidatosaprovados; })
			/* Fim da parte */
			.attr("d", path)
			.on("click", function(d) {
					// MESO
					if (d.tipo == "mesomg") {
						magnitude = "munmg";
						selectedMeso = d.properties.nome;
						click(d, false);
					// MUNICIPIOS
					} else if (d.tipo == "munmg") {
						if (d.properties.nome == 'Belo Horizonte') {
							magnitude = "bairrobh";
							click(d, true);
						} else {
							magnitude = "munmg";
							click(d, false);
						}
					} else if (d.tipo == "bairrobh")  {
						magnitude = "bairrobh";
						click(d, true);
					}
			})
			.on("mouseover", function(d) {      
	            d3.select(this)
					.transition()        
	                .duration(200)      
	                .style("opacity", .7);      
	            })                  
	        .on("mouseout", function(d) {       
	            d3.select(this)
	            	.transition()        
	                .duration(500)      
	                .style("opacity", 1);   
	        }); 
		
			$("path").tipsy({
				html : true,
				gravity : $.fn.tipsy.autoNS,
				offset: -1,
				trigger : 'hover',
				title : function() {
					var title = title = this.getAttribute("nome");
					var string = "<b>" + title + " </b><br/>";
					for (var i = 0; i < this.attributes.length; i++) {
						var attrib = this.attributes[i];
						if (attrib.specified && attrib.name != "d" && attrib.name != "original-title" && attrib.name != "class" && attrib.name != "nome" && attrib.name != "id") {
							string += "<b>" + attrib.name + "</b> <span> " + attrib.value + " </span><br/>";
						}
					}
					return string;
				}
			}); 
		
			$("#mesomg, #munmg, #bairrobh").click(function() {
				magnitude = this.id;
				sortElements();
			});
			
			$(document).keydown(function(e) {
				var code = e.keyCode ? e.keyCode : e.which;
				if (code == 27) {
					click();
				}
			});

			sortElements(); 
	
	});
	
	/* Pontos de candidatos , ver se tem como associar com omysql php */
	var projectionPoints = d3.geo.mercator().scale(scale).center(center).translate(offset);
	var coords = projectionPoints([-43.95195, -19.91007]);
	
	points.append("circle")
	.attr('cx', coords[0])
	.attr('cy', coords[1])
	.attr('r', 1.5)
	.style('fill', 'red');
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
			k = 32;
		} else {
			k = 3;
		}
	} else {
		x = width / 2;
		y = height / 2;
		k = 1;
		centered = null;
		magnitude = "mesomg";
		selectedMeso = "";
	}
	sortElements();

	g.selectAll("path").classed("active", centered &&
	function(d) {
		return d === centered;
	});

	g.transition().duration(1000)
		.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
		.style("stroke-width", 0.5 / k + "px");
		
	points.transition().duration(1000)
		.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");
	points.selectAll("circle").transition().duration(1000)
		.attr('r', 1.5 / k);

}

function getMV(d) {
	if ("mesomg".indexOf(d) !== -1) {
		return 3;
	} else if ("munmg".indexOf(d) !== -1) {
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
				if(a.properties.nome_meso == selectedMeso) {
					return 1;
				} else {
					return -1;
				}				
			} else if((getMV(a.tipo) == 3 && a.properties.nome != selectedMeso) || (getMV(b.tipo) == 3 && b.properties.nome != selectedMeso) ) {
				return 1;
			} else if (getMV(b.tipo) == 2) {
				if(b.properties.nome_meso == selectedMeso) {
					return -1;
				} else {
					return 1;
				}	
			}
		// BAIRRO POPULAR
		} else {
			if((getMV(a.tipo) == 3 && a.properties.nome != selectedMeso) || (getMV(b.tipo) == 3 && b.properties.nome != selectedMeso) ) {
				return 1;
			}
			if(getMV(a.tipo) == 2 && a.properties.nome_meso != selectedMeso) {
				return -1;
			} else if (b.tipo == 2 && b.properties.nome_meso != selectedMeso) {
				return 1;
			}
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
	magnitude = "mesomg";
	selectedMeso = "";
	width = $("#geografico").width();
	height = width / 1.6;
	scale = width * 3.3;
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

	g = svg.append("g").attr("id", "mg");
	points = svg.append("g").attr("id", "candidatos");
	draw();
});
