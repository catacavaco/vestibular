<?php
	set_time_limit(0);

	require_once("system_config.php");
	GLOBAL $_SYS;
	
	require_once("system_top_head.php");
?>
	<link rel="stylesheet" type="text/css" href="tipsy/stylesheets/tipsy.css">
	<link rel="stylesheet" type="text/css" href="geo.css">
	
	<div id="geografico">
        <!--<input type="button" value="Meso-regiões" id="mesomg" />
		<input type="button" value="Municípios região metropolitana de Belo Horizonte" id="munmg" />
		<input type="button" value="Bairros de Belo Horizonte" id="bairrobh" />
		<br/>-->
		<h2>Mapa do vestibular</h2>
		<br/>
      </div>
	
	<script type='text/javascript' src="jquery/jquery-1.9.1.js"></script>
	<script type='text/javascript' src='tipsy/javascripts/jquery.tipsy.js'></script>
    <script>
    <?php
    	$regiao = "meso";
		$qr="SELECT id$regiao, count(*)
		FROM `candidato_relatorio`
		WHERE crel_processo = '20111_integrado'
		AND idmeso <> \"\"
		GROUP BY idmeso
		order by count(*) desc;";
		
		$RES = mysql_query($qr) or die(mysql_error()); 
		
		$i=0;
		while($row=mysql_fetch_array($RES)){
		    $mesos[$i] = $row['id'.$regiao.''];
		    $mesoat = $mesos[$i];
		    $quantidade[$i] = $row[1];
		   
		    $qr2="SELECT avg(crel_notafinal) FROM candidato_relatorio WHERE id$regiao='$mesoat' AND crel_processo = '20111_integrado'";
		    $RES2 = mysql_query($qr2) or die(mysql_error()); 
		    $row2=mysql_fetch_array($RES2);
		    $media[$i] = $row2[0];
			
			$qr3="SELECT count(*) FROM candidato_relatorio WHERE id$regiao='$mesoat' AND crel_processo = '20111_integrado' AND crel_sit_vestibular = 'C' ";
		    $RES3 = mysql_query($qr3) or die(mysql_error()); 
		    $row3=mysql_fetch_array($RES3);
		    $candidatos_aprovados[$i] = $row3[0];
		 
		 
		    $i++;
		}
		
		echo 'var '.$regiao.'Array = [';
		
		for($i=0;$i<count($mesos);$i++){
		   if($i!=0) echo ",\n\t\t\t\t";
		   echo '{"id'.$regiao.'": "'.$mesos[$i].'","candidatos": '.$quantidade[$i].', "candidatos_aprovados": '.$candidatos_aprovados[$i].', "media": '.$media[$i].'}';
		    
		}
		echo " ];\n";
		?>
		</script>
		<script>
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
			d3.json("minas.geojson", function(json) {
				
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
/* Parte que tem q entrar o PHP */
					.style("fill", function(d) { return color(d.properties.candidatosaprovados/d.properties.candidatos); })
					.attr("media_prova", function(d) { return d.properties.mediaprova; })
					.attr("indice_aprovamento", function(d) { return ((d.properties.candidatosaprovados/d.properties.candidatos) * 100).toFixed(2) + "%"; })
					.attr("candidatos", function(d) { return d.properties.candidatos; })
					.attr("candidatos_aprovados", function(d) { return d.properties.candidatosaprovados; })
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
			
			});
			
/* Pontos de candidatos OUTRA PARTE QUE TEM Q ENTRAR O PHP */
			var projectionPoints = d3.geo.mercator().scale(scale).center(center).translate(offset);
			var coords = projectionPoints([-43.95195, -19.91007]);
			
			points.append("circle")
			.attr('cx', coords[0])
			.attr('cy', coords[1])
			.attr('r', 1.5)
			.style('fill', 'red');
/* PHP */
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
