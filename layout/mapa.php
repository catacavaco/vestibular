<?php
	set_time_limit(0);

	require_once("system_config.php");
	GLOBAL $_SYS;
	
	require_once("system_top_head.php");
?>
	<link rel="stylesheet" type="text/css" href="tipsy/stylesheets/tipsy.css">
	<link rel="stylesheet" type="text/css" href="mapa.css">
	<div id="geografico">
		<h2>Mapa do vestibular</h2>
		<div id="tiposCor">
			<input type="radio" name="tipo" value="Candidatos Total" id="candidatos" checked/><div>Candidatos Total</div>
			<input type="radio" name="tipo" value="Candidatos Aprovados" id="aprovados"/><div>Candidatos Aprovados</div>
			<input type="radio" name="tipo" value="Índice de Aprovação" id="aprovacao"/><div>Índice de Aprovação</div>
		</div>
		<br/>
      </div>
	
	<script type='text/javascript' src="jquery/jquery-1.9.1.js"></script>
	<script type='text/javascript' src='tipsy/javascripts/jquery.tipsy.js'></script>
    <?php require_once("mapa_functions.php"); 
    	getDataArray("meso"); 
		getDataArray("mun");
		getDataArray("bairro");
    	?>
	<script>
	function getValues(value,array) {
		var result;
		$.each(array, function(i, v) {
	    if (v.id == value) {
	        result =  v;
	        return false;
	    }
		});
		return result;
	}
	
	</script>
	<script>
    	var center = [-44.31301959952854, -18.95937600486544];
		var magnitude = "mesomg";
		var selectedMeso = "";
		var tipo = "candidatos";
		
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
			d3.json("mapa_minas.geojson", function(json) {
				
				/* Cor */
		    	var domain;
		    	var legend_labels;
				    
				if(tipo == "aprovacao") {
					domain  = [.00,.01,.50,1.0];
		    		legend_labels = ["0%", "1%", "50%", "100%"];
				} else if (tipo == "candidatos") {
					domain  = [0,10,5000,10000];
		    		legend_labels = ["Nenhum candidato", "10+ candidatos", "5000+ candidatos", "10000 candidatos"];
				} else {
					domain  = [0,1,200,600];
		    		legend_labels = ["Nenhum candidato aprovado", "1+ candidato aprovado", "200+ candidatos aprovados", "600 candidatos aprovados"];
				}
				
				var color = d3.scale.linear()
				    .domain(domain)
				    .range(["#EEF6FF", "#A0C3DE", "#497495", "#1d4e71"]);
				/* End cor */
				
				json.features.forEach(function(d) {
					var values;
					if(d.tipo == "bairrobh") {
						values = getValues(d.properties.bid,bairroArray);
					} else if (d.tipo == "munmg") {
						values = getValues(d.properties.idmun,munArray);
					} else {
						values = getValues(d.properties.idmeso,mesoArray);
					}
					if(values) {
						d.properties["media_prova"] = values.media_prova;
						d.properties["candidatos"] = values.candidatos;
					    d.properties["candidatos_aprovados"] = values.candidatos_aprovados;
					}
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
			        
			        .attr("sigla", function(d) { return d.properties.sigla; })
					.attr("regiao", function(d) { return d.properties.regiao; })
					.attr("mesoregiao", function(d) { return d.properties.nome_meso; })
					.attr("populacao", function(d) { return d.properties.pop2010; })
					.style("fill", function(d) { 
							if(tipo == "aprovacao") {
								return color(d.properties.candidatos_aprovados/d.properties.candidatos);
							} else if (tipo == "candidatos") {
								return color(d.properties.candidatos);
							} else {
								return color(d.properties.candidatos_aprovados);
							}
						})
					.attr("candidatos", function(d) { return d.properties.candidatos; })
					.attr("candidatos_aprovados", function(d) { return d.properties.candidatos_aprovados; })
					.attr("indice_aprovacao", function(d) { return ((d.properties.candidatos_aprovados/d.properties.candidatos) * 100).toFixed(2) + "%"; })
					.attr("media_prova", function(d) { return d.properties.media_prova; })
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
								if (attrib.specified && 
									attrib.name != "d" && 
									attrib.name != "original-title" && 
									attrib.name != "class" && 
									attrib.name != "nome" && 
									attrib.name != "id" &&
									attrib.name != "style") {
									string += "<b>" + ucFirstAllWords(attrib.name.replace("_"," ")) + "</b> <span> " + attrib.value + " </span><br/>";
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
			
			//Legendas

		  var legend = svg.selectAll("g.legend")
		  .data(domain)
		  .enter().append("g")
		  .attr("class", "legend");
		
		  var ls_w = 20, ls_h = 20;
		
		  legend.append("rect")
		  .attr("x", 20)
		  .attr("y", function(d, i){ return height - (i*ls_h) - 2*ls_h;})
		  .attr("width", ls_w)
		  .attr("height", ls_h)
		  .style("fill", function(d, i) { return color(d); })
		  .style("opacity", 0.8);
		
		  legend.append("text")
		  .attr("x", 50)
		  .attr("y", function(d, i){ return height - (i*ls_h) - ls_h - 4;})
		  .text(function(d, i){ return legend_labels[i]; });
  
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
			recreate();
		});
		
		$("#aprovacao, #candidatos, #aprovados").click(function() {
		   tipo = this.id;
		   console.log(tipo);
		   recreate();
		});
		
		function recreate() {
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
		}
		
		function ucFirstAllWords( str )	{
		    var pieces = str.split(" ");
		    for ( var i = 0; i < pieces.length; i++ )
		    {
		        var j = pieces[i].charAt(0).toUpperCase();
		        pieces[i] = j + pieces[i].substr(1);
		    }
		    return pieces.join(" ");
		}

    </script>
<?php require_once("system_footer.php");
?>
