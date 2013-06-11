<?php
class Field{
	public $name;
	public $id;
	public $label;
	
	public $type;
	public $idcss="normalEdit";
	public $script;
	public $value;
	
	public $mask;
	public $maskCheck;
	
	public $validate=false;
	
	public $maxlength;
	public $size;
	public $width;
	
	public $options;
	
	public $mandatory=false;
	public $enabled=true;
	public $visible=true;
	
	
	public function printField(){
		if($this->visible==false) return;
		if($this->width!='') $wd="style='width: ".($this->width-26)."px'";
		echo '<div id="borderfield" '.$wd.'>';
		echo '<label id="labelfield" >'.$this->id.'</label><br /><label id="labelfieldSubject">'.$this->label.'</label><br>';
		if($this->type=="text") $this->printFieldText();
		if($this->type=="select") $this->printFieldSelect();
		if($this->type=="hiddenShow") $this->printFieldHiddenShow();
		echo '</div>';
	}
	public function printFieldText(){
		echo "<div style = 'padding-bottom:  3px'>";
		if($this->enabled){
			if($this->mask=="phone") $msk='onkeypress="mascara(this,telefone)"';
			if($this->mask=="numbers") $msk='onkeypress="mascara(this,soNumeros)"';
			if($this->mask=="date") $msk='onkeypress="mascara(this,data)"';
			
			
			echo "<input $msk name='$this->name' type='text' value='$this->value' size='$this->size' maxlength='$this->maxlength' id='$this->idcss' />";
		}else
			$this->printFieldHiddenShow();
		echo "</div>";
	}
	public function printFieldShow(){
		echo "<div style = 'padding-bottom:  4px'>".$this->value."</div>";

	
	}
	public function printFieldHidden(){

		echo "<input name='$this->name' type='hidden' value='$this->value' maxlength='$this->maxlength' id='$this->idcss' />";
	
	}
	public function printFieldHiddenShow(){
		$this->printFieldHidden();
		$this->printFieldShow();
	}
	public function printFieldSelect(){
		if($this->options!='') $chaves=array_keys($this->options);
			
		if($this->enabled){
			echo "<SELECT name='$this->name' id='$this->idcss' $this->script>";
		}
		
		
		for($i=0;$i<count($chaves);$i++){
			$SELECTED = '';
			if($chaves[$i]==$this->value){
				$SELECTED = 'SELECTED';
			}
			if($this->enabled)
				echo '<option '.$SELECTED.' value="'.$chaves[$i].'">'.$this->options[$chaves[$i]].'</option>';
			else if($SELECTED == 'SELECTED'){

				$vl = $this->value;
				
				$this->value=$this->options[$chaves[$i]];
				$this->printFieldShow();
				$this->value=$vl;
				$this->printFieldHidden();
			}
		}
		if($this->enabled)
		echo "</SELECT>";
	}
	
	public function checkMask(){
		if($this->mandatory && $this->value==''){
			$this->invalidate();
			return false;
		}
		if($this->maskCheck!=''&&$this->mandatory){
			if($this->maskCheck=='date'&&!ValidaData($this->value)){
				$this->invalidate();
				return false;
			}
			if($this->maskCheck=='cpf'&&!validaCPF($this->value)){
				$this->invalidate();
				return false;
			}
			
			if($this->maskCheck=='phone'&&!valida_tel($this->value)){
				$this->invalidate();
				return false;
			}
			if($this->maskCheck=='numbers'&&!soNumeros($this->value)){
				$this->invalidate();
				return false;
			}
			
		}
		$this->validate = true;
		return true;
	}
	public function invalidate(){
		$this->validate = false;
		$this->idcss="fieldError";
	}
	
}

class Form{
	public $title;
	public $label;
	public $image;

	
	public $fields;
	
	public function printForm(){
		
		echo'<div id="containerTable">';
		if($this->image!='') $img='<img src="'.$this->image.'" border="0" width="30" height="30">';
		echo '<div id="titleForm">
		
		<table id="table1" border="0" width="100%">
		<tbody>
			<tr>
				<td rowspan="2" width="1%">
					<p align="left">'.$img.'</p>
				</td>
				<td width="99%">
					<p align="left">
					'.$this->title.'
					
					</p>';
					if($this->label!='') echo '<label id="labelfieldSubject" align="justify">'.$this->label.'</label>';
				echo '</td>
			</tr>
			
		</tbody></table>
		</div>';
		
		for($i=0;$i<count($this->fields);$i++){
			$this->fields[$i]->printField();
		}
		echo '<div id="titleForm">';
		echo '<p align="center">
			<input value="Aplicar Filtros" name="B1" id="normalButton" type="submit">
		</p>';
		echo '</div>';
		echo '</div>';
	}
	
	public function printFielsNames(){
		for($i=0;$i<count($this->fields);$i++){
			echo $this->fields[$i]->name.'<br>';
		}
	}
	
	public function addField($field){
		$this->fields[count($this->fields)] = $field;
	}
	
	public function setValues($values){
		$campos = array_keys($values);
		for($i=0;$i<count($this->fields);$i++){
			$this->fields[$i];
			for($j=0;$j<count($campos);$j++){
				if($campos[$j] == $this->fields[$i]->name){
					$this->fields[$i]->value=$values[$campos[$j]];

				}
			}
		}
	}
	public function changeType($flds,$type){
		
		for($i=0;$i<count($this->fields);$i++){
			$this->fields[$i];
			for($j=0;$j<count($flds);$j++){
				if($flds[$j] == $this->fields[$i]->name){
					$this->fields[$i]->type=$type;

				}
			}
		}
	}
	public function changeVisible($flds,$flag){
		
		for($i=0;$i<count($this->fields);$i++){
			$this->fields[$i];
			for($j=0;$j<count($flds);$j++){
				if($flds[$j] == $this->fields[$i]->name){
					$this->fields[$i]->visible=$flag;

				}
			}
		}
	}
	
	public function changeEnabled($flds,$flag){
		
		for($i=0;$i<count($this->fields);$i++){
			$this->fields[$i];
			for($j=0;$j<count($flds);$j++){
				if($flds[$j] == $this->fields[$i]->name){
					$this->fields[$i]->enabled=$flag;

				}
			}
		}
	}
	
	
	public function changeMandatory($flds,$flag){
		
		for($i=0;$i<count($this->fields);$i++){
			$this->fields[$i];
			for($j=0;$j<count($flds);$j++){
				if($flds[$j] == $this->fields[$i]->name){
					$this->fields[$i]->mandatory=$flag;

				}
			}
		}
	}
	
	
	
	public function checkAll(){
		$flag=true;
		for($i=0;$i<count($this->fields);$i++){
			$flagfield=$this->fields[$i]->checkMask();
			if($flagfield==false) $flag=false;
		}
		return $flag;
	}
}
class stackChart{
	public $id;
	public $data;
	
	function css(){
echo "<style>

#divStackChart$id{
   font: 7px sans-serif;
   border: 1px solid #000;
   width: 600px;
}

.axis$id path,
.axis$id line {
  fill: none;
  stroke: #CCCCCC;
  shape-rendering: crispEdges;
  color: #CCCCCC;

}

.bar {
  fill: steelblue;
}

.x.axis$id path {
  
  color: #CCCCCC;
}

</style>";
	
	}
	function script(){
echo '
<script>

var margin = {top: 20, right: 20, bottom: 30, left: 40},
    width = 600 - margin.left - margin.right,
    height = 130 - margin.top - margin.bottom;

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .rangeRound([height, 0]);

var color = d3.scale.ordinal()
    .range(["#82AAD8", "#D8E2EF", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(d3.format(".2s"));

var svg = d3.select("#divStackChart'.$id.'").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	
data = [';

for($i=1;$i<count($this->data);$i++){
	$dado = $this->data[$i];
	if($i!=1) echo ',';
    echo '{"State": "'.$dado[0].'","Pagos": '.$dado[1].', "Inscritos": '.$dado[2].'}';
}
 echo ' ];


  color.domain(d3.keys(data[0]).filter(function(key) { return key !== "State"; }));

  data.forEach(function(d) {
    var y0 = 0;
    d.ages = color.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });
    d.total = d.ages[d.ages.length - 1].y1;
  });

  //data.sort(function(a, b) { return b.total - a.total; });

  x.domain(data.map(function(d) { return d.State; }));
  y.domain([0, d3.max(data, function(d) { return d.total; })]);

  svg.append("g")
      .attr("class", "x axis'.$id.'")
	  
      .attr("transform", "translate(0," + height + ")")
	
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis'.$id.'")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
	  .style("color", "#CCCCCC")
      .text("");

  var state = svg.selectAll(".state")
      .data(data)
    .enter().append("g")
      .attr("class", "g")
	   
      .attr("transform", function(d) { return "translate(" + x(d.State) + ",0)"; });
	  

  state.selectAll("rect")
      .data(function(d) { return d.ages; })
    .enter().append("rect")
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d.y1); })
      .attr("height", function(d) { return y(d.y0) - y(d.y1); })
      .style("fill", function(d) { return color(d.name); });

  var legend = svg.selectAll(".legend")
       .data(color.domain().slice().reverse())
     .enter().append("g")
       .attr("class", "legend")
       .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });

   legend.append("rect")
       .attr("x", 43)
       .attr("width", 9)
       .attr("height", 9)
       .style("fill", color);

   legend.append("text")
       .attr("x", width - 40)
       .attr("y", 5)
       .attr("dy", ".35em")
       .style("text-anchor", "end")
       .text(function(d) { return d; });



</script>
';	
	}
	
	function div(){
		echo "<div id='divStackChart$id'></div>";
		
	}
	
	function printStackChart(){
		$this->css();
		$this->div();
		$this->script();
		
	}
}

class radar1{
	public $id;
	public $data;
	
	function css(){
	
	}
	function script(){
echo '
<script>

var RadarChart = {
  g: null,
  draw: function(id, d, options){
    var self = this;
    var cfg = {
     radius: 5,
     w :105, 
     h: 105, 
     factor: 1, 
     factorLegend:.85,
     total: 4,
     levels: 3,
     maxValue: 1000,
     radians: 2 * Math.PI, 
     minDistance : 50,
     opacityArea: 0.5
   }
    if(options != undefined){
      for(var i in options){
        cfg[i] = options[i];
      }
    }
    cfg.maxValue = d3.max(d, function(i){return Math.max.apply(Math,i.map(function(o){return o.value;}))});
    var allAxis = (d[0].map(function(i, j){return i.axis}));
    total = allAxis.length;
    var radius = cfg.factor*Math.min(cfg.w/2, cfg.h/2);
    d3.select(id).select("svg").remove();
    var g = d3.select(id).append("svg").attr("width", cfg.w).attr("height", cfg.h).append("g");

    for(var j=0; j<cfg.levels; j++){
      var levelFactor = cfg.factor*radius*((j+1)/cfg.levels);
      g.selectAll(".levels").data(allAxis).enter().append("svg:line")
       .attr("x1", function(d, i){return levelFactor*(1-cfg.factor*Math.sin(i*cfg.radians/total));})
       .attr("y1", function(d, i){return levelFactor*(1-cfg.factor*Math.cos(i*cfg.radians/total));})
       .attr("x2", function(d, i){return levelFactor*(1-cfg.factor*Math.sin((i+1)*cfg.radians/total));})
       .attr("y2", function(d, i){return levelFactor*(1-cfg.factor*Math.cos((i+1)*cfg.radians/total));})
       .attr("class", "line").style("stroke", "grey").style("stroke-width", "0.5px").attr("transform", "translate(" + (cfg.w/2-levelFactor) + ", " + (cfg.h/2-levelFactor) + ")");;

    }

    var color = d3.scale.category10();

    series = 0;

    var axis = g.selectAll(".axis").data(allAxis).enter().append("g").attr("class", "axis");

    axis.append("line")
        .attr("x1", cfg.w/2)
        .attr("y1", cfg.h/2)
        .attr("x2", function(j, i){return cfg.w/2*(1-cfg.factor*Math.sin(i*cfg.radians/total));})
        .attr("y2", function(j, i){return cfg.h/2*(1-cfg.factor*Math.cos(i*cfg.radians/total));})
        .attr("class", "line").style("stroke", "grey").style("stroke-width", "1px");

    axis.append("text").attr("class", "legend")
        .text(function(d){if(d==0||d==6||d==12||d==18) return d; else return "";}).style("font-family", "sans-serif").style("font-size", "8px").attr("transform", function(d, i){ if(d==0) return "translate(1, 5)";  if(d==6) return "translate(-35, +7)"; if(d==12) return "translate(-10, 0)";  if(d==18) return "translate(30, -1)"; return "translate(0, 0)";})
        .attr("x", function(d, i){return cfg.w/2*(1-cfg.factorLegend*Math.sin(i*cfg.radians/total))-20*Math.sin(i*cfg.radians/total);})
        .attr("y", function(d, i){return cfg.h/2*(1-Math.cos(i*cfg.radians/total))+20*Math.cos(i*cfg.radians/total);});

 
    for(x in d){
      dataValues = [];
      y = d[x];
      d3.select(id+" g").selectAll(".nodes")
        .data(y, function(j, i){
          dataValues.push([
            cfg.w/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total)), 
            cfg.h/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total))
          ]);
        });
      dataValues.push(dataValues[0]);
      g.select(id+" g").selectAll(".area")
                     .data([dataValues])
                     .enter()
                     .append("polygon")
                     .attr("class", "serie"+series)
                     .style("stroke-width", "2px")
                     .style("stroke", "#82AAD8")
                     .attr("points",function(d) {
                         var str="";
                         for(var pti=0;pti<d.length;pti++){
                             str=str+d[pti][0]+","+d[pti][1]+" ";
                         }
                         return str;
                      })
                     .style("fill", function(j, i){return color(series)})
                     .style("fill-opacity", cfg.opacityArea)
                     .on(\'mouseover\', function (d){
                                        z = "polygon."+d3.select(this).attr("class");
                                        g.selectAll("polygon").transition(200).style("fill-opacity", 0.1); 
                                        g.selectAll(z).transition(200).style("fill-opacity", .7);
                                      })
                     .on(\'mouseout\', function(){
                                        g.selectAll("polygon").transition(200).style("fill-opacity", cfg.opacityArea);
                     });
      series++;
    }
    series=0;


    for(x in d){
      y = d[x];
      d3.select(id+" g").selectAll(".nodes")
        .data(y).enter()
        .append("svg:circle").attr("class", "serie"+series)
        .attr(\'r\', cfg.radius)
        .attr("alt", function(j){return Math.max(j.value, 0)})
        .attr("cx", function(j, i){
          dataValues.push([
            cfg.w/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total)), 
            cfg.h/2*(1-(parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total))
        ]);
        return cfg.w/2*(1-(Math.max(j.value, 0)/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total));
        })
        .attr("cy", function(j, i){
          return cfg.h/2*(1-(Math.max(j.value, 0)/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total));
        })
        .attr("data-id", function(j){return j.axis})
        .style("fill", color(series)).style("fill-opacity", .9)
        .on(\'mouseover\', function (d){
                    newX =  parseFloat(d3.select(this).attr(\'cx\')) - 10;
                    newY =  parseFloat(d3.select(this).attr(\'cy\')) - 5;
                    tooltip.attr(\'x\', newX).attr(\'y\', newY).text(d.value).transition(200).style(\'opacity\', 1);
                    z = "polygon."+d3.select(this).attr("class");
                    g.selectAll("polygon").transition(200).style("fill-opacity", 0.1); 
                    g.selectAll(z).transition(200).style("fill-opacity", .7);
                  })
        .on(\'mouseout\', function(){
                    tooltip.transition(200).style(\'opacity\', 0);
                    g.selectAll("polygon").transition(200).style("fill-opacity", cfg.opacityArea);
                  })
        .append("svg:title")
        .text(function(j){return Math.max(j.value, 0)});

      series++;
    }
    //Tooltip
    //tooltip = g.append(\'text\').style(\'opacity\', 0).style(\'font-family\', \'sans-serif\').style(\'font-size\', 13);
  }
}



var d = [
          [
          
           ';
		  $i=0;
echo '{"axis": "'.$i.'",value: '.$this->data[$i].'}';		   
for($i=count($this->data)-1;$i>=1;$i--){
	
	 echo ',';
    echo '{"axis": "'.$i.'",value: '.$this->data[$i].'}';
}
          echo ']
        ];
		RadarChart.draw("#chart", d);

</script>
';	
	}
	
	function div(){
		echo "<div id='chart'></div>";
		
	}
	
	function printStackChart(){
		$this->css();
		$this->div();
		$this->script();
		
	}
}
?>