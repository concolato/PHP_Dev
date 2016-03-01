L.Control.FullScreen=L.Control.extend({options:{position:"topleft",title:"Full Screen",forceSeparateButton:false},onAdd:function(e){var t="leaflet-control-zoom-fullscreen",n;if(e.zoomControl&&!this.options.forceSeparateButton){n=e.zoomControl._container}else{n=L.DomUtil.create("div","leaflet-bar")}this._createButton(this.options.title,t,n,this.toogleFullScreen,e);return n},_createButton:function(e,t,n,r,i){var s=L.DomUtil.create("a",t,n);s.href="#";s.title=e;L.DomEvent.addListener(s,"click",L.DomEvent.stopPropagation).addListener(s,"click",L.DomEvent.preventDefault).addListener(s,"click",r,i);L.DomEvent.addListener(n,fullScreenApi.fullScreenEventName,L.DomEvent.stopPropagation).addListener(n,fullScreenApi.fullScreenEventName,L.DomEvent.preventDefault).addListener(n,fullScreenApi.fullScreenEventName,this._handleEscKey,i);L.DomEvent.addListener(document,fullScreenApi.fullScreenEventName,L.DomEvent.stopPropagation).addListener(document,fullScreenApi.fullScreenEventName,L.DomEvent.preventDefault).addListener(document,fullScreenApi.fullScreenEventName,this._handleEscKey,i);return s},toogleFullScreen:function(){this._exitFired=false;var e=this._container;if(this._isFullscreen){if(fullScreenApi.supportsFullScreen){fullScreenApi.cancelFullScreen(e)}else{L.DomUtil.removeClass(e,"leaflet-pseudo-fullscreen")}this.invalidateSize();this.fire("exitFullscreen");this._exitFired=true;this._isFullscreen=false}else{if(fullScreenApi.supportsFullScreen){fullScreenApi.requestFullScreen(e)}else{L.DomUtil.addClass(e,"leaflet-pseudo-fullscreen")}this.invalidateSize();this.fire("enterFullscreen");this._isFullscreen=true}},_handleEscKey:function(){if(!fullScreenApi.isFullScreen(this)&&!this._exitFired){this.fire("exitFullscreen");this._exitFired=true;this._isFullscreen=false}}});L.Map.addInitHook(function(){if(this.options.fullscreenControl){this.fullscreenControl=L.control.fullscreen(this.options.fullscreenControlOptions);this.addControl(this.fullscreenControl)}});L.control.fullscreen=function(e){return new L.Control.FullScreen(e)};(function(){var e={supportsFullScreen:false,isFullScreen:function(){return false},requestFullScreen:function(){},cancelFullScreen:function(){},fullScreenEventName:"",prefix:""},t="webkit moz o ms khtml".split(" ");if(typeof document.exitFullscreen!="undefined"){e.supportsFullScreen=true}else{for(var n=0,r=t.length;n<r;n++){e.prefix=t[n];if(typeof document[e.prefix+"CancelFullScreen"]!="undefined"){e.supportsFullScreen=true;break}}}if(e.supportsFullScreen){e.fullScreenEventName=e.prefix+"fullscreenchange";e.isFullScreen=function(){switch(this.prefix){case"":return document.fullScreen;case"webkit":return document.webkitIsFullScreen;default:return document[this.prefix+"FullScreen"]}};e.requestFullScreen=function(e){return this.prefix===""?e.requestFullscreen():e[this.prefix+"RequestFullScreen"](Element.ALLOW_KEYBOARD_INPUT)};e.cancelFullScreen=function(e){return this.prefix===""?document.exitFullscreen():document[this.prefix+"CancelFullScreen"]()}}if(typeof jQuery!="undefined"){jQuery.fn.requestFullScreen=function(){return this.each(function(){var t=jQuery(this);if(e.supportsFullScreen){e.requestFullScreen(t)}})}}window.fullScreenApi=e})()

  //Class for customizing icons on zoom
  var LeafIcon = L.Icon.extend({
      options: {
          iconSize:     [15, 23],
          popupAnchor:  [0, -15]
      }
  });
  L.icon = function (options) {
      return new L.Icon(options);
  };
  function detectBoundaries(tipBounds, elem){
    tipBounds.left = (tipBounds.left + $(elem).width()) > window.innerWidth ? tipBounds.left - $(elem).width() - 30 : tipBounds.left;
    tipBounds.left = (tipBounds.left - $(elem).width()) < 0 ? $(elem).width() : tipBounds.left;

    tipBounds.top = (tipBounds.top + $(elem).height()) > window.innerHeight ? tipBounds.top - $(elem).height() + 10 : tipBounds.top;
    tipBounds.top = tipBounds.top < 0 ? $(elem).height() : tipBounds.top;

    return tipBounds;
  }
  function numberWithCommas(x) {
    if(x){
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }else{
      return "No Data";
    }     
  }
  function capitaliseFirstLetter(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  //initialize map
  function initMap(GeoData){
    // Added for stamen tiles
    var layer = new L.StamenTileLayer("toner-lite");
    var map = new L.Map('mapdiv', {
        //layers: [layer],
        center: new L.LatLng(10, -10),
        zoom: 3,
        minZoom: 3,
        fullscreenControl: true,
        fullscreenControlOptions: { // optional
          title:"Fullscreen"
        }
    });
    
    //map.locate({setView: true, maxZoom: 2}); //zoom into user location
    map.addLayer(layer);

    return map;
  }

  //Produces color range
  function getColorPercent(d) {
    return d > 100 ? '#003300' :
     d > 100  ? '#006600' :
     d > 80  ? '#1f7a1f' :
     d > 75  ? '#00b300' :
     d > 50   ? '#33cc33' :
     d > 25   ? '#66ffcc' : //
     d > 10   ? '#99ffdd' :
     d > 1    ? '#ccffee':
     isNaN(d) == true  ? '#FFF':
     d == 0 ?    '#FFF':
     '#FFF';
  }
  function getColorBoth(d){
    //10, 250, 500, 1000, 5000, 10000, 200000, 500000
    return d > 500000 ? '#800026' :
     d > 200000  ? '#BD0026' :
     d > 10000  ? '#E31A1C' :
     d > 5000  ? '#FC4E2A' :
     d > 1000   ? '#FD8D3C' :
     d > 500   ? '#FEB24C' :
     d > 250   ? '#FED976' :
     d > 1    ? '#FFEDA0':
     isNaN(d) == true  ? '#ccc':
     d == 0 ?    '#ccc':
     '#ccc';
  }

  //Class for placing data on the map and tree map
  function buildMapData(GeoData){
    
    function xAxisTooltip(e){
      // Grab the height of the generated tooltip
      var tmPopHeight = $(".leaflet-top.leaflet-right").height();
      var tmPopWidth = $(".leaflet-top.leaflet-right").width() / 2; 
      var tipBounds = {};
          tipBounds = e.pageX -205;
      //console.log(tipBounds);
      tipBounds = detectBoundaries(tipBounds, "#mapdiv");

      //There is a more elegent way to do this, but will work for now.
      if(tipBounds > 40 || tipBounds < 1000){
        $(".leaflet-top.leaflet-right").css({
          "left":tipBounds-180, "width":"300px",
          "top":0, "opacity":0.9, "padding-bottom":"15px"
        });
      }  
      if(tipBounds > 1100){       
        $(".leaflet-top.leaflet-right").css({
          "right":40, "width":"300px",
          "top":0, "opacity":0.9, "padding-bottom":"15px"
        });
      }
      if(tipBounds < 240){      
        $(".leaflet-top.leaflet-right").css({
          "left":40, "width":"300px",
          "top":0, "opacity":0.9, "padding-bottom":"15px"
        });
      }
      
    }

    function treemapToolTip(popCount, sets, group, jobCount){
      d3.selectAll("#TMLegend rect").on("mousemove",function () {
          var title = d3.select(this).text();
          $("#TMLegendPopUp").show().html("<h4>"+title+"</h4>");

          // Style the tooltip
          $("#TMLegendPopUp h4").css({"background": colorArr[title], "margin":0, "text-align":"center"});

          //Popoup position
          $(document).mousemove(function(e){
              $("#TMLegendPopUp").css({"left":e.pageX - 102 + "px","top":e.pageY - 30 + "px"});
         });    
        });

        d3.selectAll("#TMLegend rect").on("mouseover",function () {
          var title = d3.select(this).text().replace(/[\W\s]/g,"");

          d3.selectAll("rect.node")
            .style("background", function(d) {
              return d.parent ? colorArr[d.parent.name] : colorArr[d.name]; 
            })
            .transition()
              .duration(500)
              .style("opacity", .4);

          d3.selectAll("rect#"+title)
            .transition()
              .duration(500)
              .style("opacity", 1)
              .style("background", function(d){        
                  return colorArr[d.parent.name];
              })
              .text(function(d){
                if(d.value >= 5){ 
                   return d.name; 
                }
              });
        });

        d3.selectAll("#TMLegend rect").on("mouseout",function () {
          var title = d3.select(this).text().replace(/[\W\s]/g,"");      
          
          d3.selectAll("rect#"+title)
            .transition()
              .duration(500)
              .style("opacity", 0.4)
              .style("background", function(d) { 
                return d.children ? colorArr[d.name] : colorArr[d.parent.name]; 
              })
              .text(function(){
                return '';
              });
        });
    } 
    
    //Not in use
    function barChartToolTip(data){
      var tooltip = d3.select("#treemap").append("div")
        .attr("id","tooltip")
          .style("width", "200px")
          .style("position", "absolute")
          .style("z-index", 999)
          .style("background", "#fff"); 

      return 0;
    }

    //Begin Choropleth code
    function choropleth(GeoData, group){      
      function style(feature) {
        var ITServiceGroup;
        //return dynamic legend
        switch(group){
          case 'regworkpercentcompleted':
            ITServiceGroup = feature.properties.regworkpercentcompleted;

            return {
              fillColor: getColorPercent(ITServiceGroup),
              weight: 1,
              opacity: .5,
              color: getColorPercent(ITServiceGroup),
              //dashArray: '3',
              fillOpacity: 0.7,
              //className: feature.properties.metro_area.replace(/[\W\s]/g,"")
            };
          break;
          case 'vcentryworkstations':
            ITServiceGroup = feature.properties.vcentryworkstations;

            return {
              fillColor: getColorAsian(ethnicGroup),
              weight: 1,
              opacity: .5,
              color: getColorAsian(ethnicGroup),
              fillOpacity: 0.7,
              //className: feature.properties.metro_area.replace(/[\W\s]/g,"")
            };
          break;
          case '':
            console.log("Group is not set.");
            return false;
          break;
          default:
            ITServiceGroup = feature.properties.vcentryworkstations + feature.properties.regworkstations;

            return {
              fillColor: getColorBoth(ITServiceGroup),
              weight: 1,
              opacity: .5,
              color: getColorBoth(ITServiceGroup),
              fillOpacity: 0.7,
              //className: feature.properties.metro_area.replace(/[\W\s]/g,"")
            };
        }//end switch
      }
      
      function highlightFeature(e) {
        var layer = e.target;

        layer.setStyle({
            weight: 5,
            //color: '#B7D2D2',
            color: '#000',
            dashArray: '1',
            fillOpacity: 2
        });

        if (!L.Browser.ie && !L.Browser.opera) {
            layer.bringToFront();
        }

        //Hover text update
        info.update(e.target.feature.properties);
      }

      function resetHighlight(e) {
        var layer = e.target;
        geojson.resetStyle(layer);
        info.update();
      }

      function zoomToFeature(e) {
          map.fitBounds(e.target.getBounds());
      }

      function mapToTreeIds(feature){
        return feature.properties.metro_area;
      }

      function onEachFeature(feature, layer) {
        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: zoomToFeature, 
        });    
      }
      //End Choropleth code

      var info = L.control();
      info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
        this.update();

        return this._div;
      };

      //Method that we will use to update the control based on feature properties passed
      info.update = function (dosRegion) {       
        var Data ='', vsentryDeploymentData, jobCenterName;

        // Position the tooltip based on mouse position            
        if(dosRegion){
          regionName = dosRegion.dos_region;
          vsentryDeploymentData = "<br />"+ //<div id='dolOffices' style='background:"+getColorJob(dosRegion.regworkstations)+" '>"
            "vSentry Workstations Deployments:" +numberWithCommas(dosRegion.vsenpercentcompleted) +"% <br /> Bandwidth Capacity: "+numberWithCommas(dosRegion.capacity)+" Bits/Second</div>";

          switch(group){
            case 'regworkpercentcompleted':
              Data ='<br /> <strong>Bromium Workstations Deployments: '+ numberWithCommas(dosRegion.regworkpercentcompleted)+'%</strong>'
              +
              vsentryDeploymentData;
            break;
            case 'vsenpercentcompleted':
              Data ='<br /> vSentry Workstations Deployments: '+ numberWithCommas(dosRegion.vsenpercentcompleted)+
              vsentryDeploymentData;
            break;
            case 'regworkstations':
              Data ='<br /> Bromium Deployments: '+ numberWithCommas(dosRegion.regworkstations)+
              vsentryDeploymentData;
            break;
          }  
        }
        
        this._div.innerHTML = '<div id="populationBox">'+
          '<h4>Global IRM IT Services Deployments</h4>' +  
          (dosRegion ?
              '<b> Region: ' + regionName+ '</b>'+ Data
          : 'Hover over regions.') +
        '</div>';
      };
      info.addTo(map);

      geojson = L.geoJson(GeoData, {
        style: style,
        onEachFeature: onEachFeature,
      }).addTo(map);
    } //end choropleth() class
    
    /* Build Legend Color Scale */
    function legendMap(group){
      var div = "<div class='info legend'>",
      labels = [], grades = [], i;

      if(group == "regworkpercentcompleted"){
        grades = [1, 10, 25, 50, 75, 80, 90, 100];
        //grades = [10, 250, 500, 1000, 5000, 10000, 200000, 500000];
        
        // loop through our density intervals and generate a label 
        //with a colored square for each interval for legend
        div +='<i style="background:#fff; border: 1px solid;"></i>0 or No Data<br>';
        for (i = 0; i < grades.length; i++) {
            div +=
                '<i style="background:' + getColorPercent(grades[i] + 1) + '; clear:both"></i> ' +
                numberWithCommas(grades[i]) + (grades[i + 1] ? '&ndash;' + numberWithCommas(grades[i + 1]) + '%<br>' : '%+');
        }
        div += "</div>";

        return div;
      }else if(group == "vcentryworkstations"){ //TODO: These will need to be addressed beyond prototype stage.

        grades = [10, 250, 500, 1000, 5000, 10000, 100000, 250000];

        for (i = 0; i < grades.length; i++) {
            div +=
                '<i style="background:' + getColorAsian(grades[i] + 1) + '; clear:both"></i> ' +
                numberWithCommas(grades[i]) + (grades[i + 1] ? '&ndash;' + numberWithCommas(grades[i + 1]) + '%<br>' : '%+');
        }
        div+="</div>";

        return div;
      }else{
        grades = [10, 250, 500, 1000, 5000, 10000, 200000, 500000];

        for (i = 0; i < grades.length; i++) {
            div +=
                '<i style="background:' + getColorBoth(grades[i] + 1) + '; clear:both"></i> ' +
                numberWithCommas(grades[i]) + (grades[i + 1] ? '&ndash;' + numberWithCommas(grades[i + 1]) + '<br>' : '+');
        }
        div+="</div>";

        return div;
      }        
    }

    //Main
    function main(GeoData){
      $(".leaflet-top.leaflet-right").css({
        "right":0
      });

      $("#mapdiv").mousemove(function(e){
          xAxisTooltip(e);   
      });

      var group = 'regworkpercentcompleted';

      choropleth(GeoData, group);  
      $(".legendDiv").append(legendMap(group));    
    }
    main(GeoData);
    //End Main
  }

  function barChart(domesticData){
    var maxWidth = 1140, rightPadding = 80;
    var xScale, yScale, xAxisComponent, yAxisComponent;
    var data = [], desc, units, maxDate, minDate, tip;
    
    var container = d3.select('svg g.chart-wrapper'),
      barGroup = container.select('.bars')
      xAxis = container.select('.x.axis')
      yAxis = container.select('.y.axis');

    //Initialize tool tip
      var tooltip = initToolTip("#chartlabels", "tooltipBar" /*class or id*/,"#7cb5ec" /*Border Color*/);

    d3.selectAll('#portfolio svg').attr("width","100%").attr("height", "370px");
            
    // Parse the date / time
    var parseDate = d3.time.format("%Y").parse;

    function toTitleCase(str){
      return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    }   

    function bureauData(data) { 
        var i, dataArray = Array();

        for(i =0; i < data.length; i++){ 
          dataArray.push(data[i].bureau);             
        }
     //console.log(dataArray);
      return dataArray; 
    }

    function bureauDataStats(data) { 
      var i, dataArray = Array();

      for(i =0; i < data.length; i++){ 
        dataArray.push(data[i].regworkpercentcompleted);             
      }
     //console.log(dataArray);
      return dataArray; 
    }

    function initChart() {
      var width = 1140, height = 300, totalyears = Array();

      //Retrieve Data Objects and arrays
      bureausArray = bureauData(domesticData);
      bureuauNumbers = bureauDataStats(domesticData);
         
      // Initialise scales
      xScale = d3.scale.ordinal()
        .domain(bureuauNumbers.map(function(d, i) {return bureausArray[i];}))
        .rangeBands([0, width], 0.04);
      yScale = d3.scale.linear()
        .domain([0, d3.max(bureuauNumbers)])
        .range([height, 0]);
      // Build the x-axis
      xAxisComponent = d3.svg.axis()
        .scale(xScale)
        .orient('bottom');

      xAxis.append("g").attr('transform', 'translate(0,'+height+')').call(xAxisComponent)
      
      xAxis.selectAll(".x.axis text").style("text-anchor", "end")
          .attr("dx", "-.8em")
          .attr("dy", ".15em")
          .attr("transform", "rotate(-30)");

      d3.selectAll(".tick").attr('transform', 'translate(30,20)');
      d3.selectAll(".tick line").attr("y2","0").attr("x2", "0");

      // Build the y-axis
      yAxisComponent = d3.svg.axis()
        .scale(yScale)
        .orient('left');

      yAxis.call(yAxisComponent);
   
      yAxisText = "% of Bureau Regular Workstations Completed";

      var chartheading = d3.select("#chartlabels");
        chartheading.append("h5").attr("id", "chartlabelstext")
          .text(yAxisText);
      
      //chartheading.append("p").attr("id", "chartlabelstext").text(desc);
    }
    function initEvents() {
      // Set up event handler for resizes
      W.addListener(update);
    }
    function update() {
      updateScales();
      updateAxes();
      updateBars();
    }
    function updateScales() {
      var width = d3.min([W.getViewportWidth(), maxWidth]) - rightPadding;
      xScale.rangeBands([0, width], 0.04);
    }
    function updateAxes() {
      xAxis.transition().call(xAxisComponent);
    }

    function updateBars() { 
      var u = barGroup
        .selectAll('rect')
        .data(bureuauNumbers); //Need to get countries to show in tool tip
      u.enter()
        .append('rect')
        .attr("id", function(d,i){
          return bureausArray[i];
        });

      u.on('mouseout', function(){
        tooltip.style("display", "none"); //Hide tool tip 
      })
      .on('mouseover', function(d){ 
        tooltip.style("display", "block"); //Show tool tip

        var bureau = d3.select(this).attr('id'), num = d.toFixed(2),
          textData = "<strong>"+bureau+"</strong>: "+num+"%";

          masterToolTip("#tooltipBar", textData);
      });

      u.exit()
        .remove();
      u.transition()
        .attr('x', function(d, i) {return xScale(bureausArray[i]);})
        .attr('width', xScale.rangeBand())
        .attr('y', function(d) { return yScale(d);})
        .attr('height', function(d) {return yScale(0) - yScale(d);});
    }

    initChart();
    update();
    initEvents();
}

function initToolTip(parentCssIdOrClass, tooltipClassOrId, borderColor){
  var tooltip = d3.select(parentCssIdOrClass).append("div")
        .attr("id", tooltipClassOrId)
          .style("display", "none")
          .style("position", "absolute")
          .style("z-index", 999)
          .style("background", "#fff")
          .style("word-wrap", "break-word")
          .style("padding", "3px")
          .style('border', '3px solid '+borderColor+''); //#7cb5ec borderColor
  return tooltip;
}

function masterToolTip(cssIdOrClass, displayData){
  // Grab the height of the generated tooltip
  var tmPopHeight = $(cssIdOrClass).height();
  var tmPopWidth = $(cssIdOrClass).width() / 2;

  // Position the tooltip based on mouse position - couldn't figure out a way to do this with D3
  $(document).mousemove(function(e){
    $(cssIdOrClass).css({
      "left":e.pageX + 20 + "px","top":e.pageY -50 + "px", "opacity":0.9, "padding-bottom":"10px"
    });
  });
  
  d3.select(cssIdOrClass).attr("display", null).style("opacity","1")
    .style("left", d3.event.x - 300 + "px")
    .style("top", d3.event.y-40 + "px")
    .html(displayData); 
}

function getPoints(bromiumData){
        function populate(pointsBucket){
          var generic, marker, oshaoffice, whdoffice, jobCentersComp, jobCentersCorps, jobCentersAffiliate;
          var controlSearch = new L.Control.Search({layer: markers, initial: false, zoom:13});
          
          controlSearch.on('search_locationfound', function(e) {      
            if(e.layer._popup)
              e.layer.openPopup();
          });

          map.addControl(controlSearch);

          for(w=0; w < pointsBucket.length; w++){
              //var firstWord = pointsBucket[w].post.split(" ");
              var loc = [pointsBucket[w].latitude, 
                        pointsBucket[w].longitude],
                  title = pointsBucket[w].post;

              //console.log(pointsBucket[w]);
              //TODO: Need to adjust these to show actual pins
              switch(pointsBucket[w].region){
                case "NEA":
                  oshaoffice = new LeafIcon({iconUrl: 'js/img/oshaOffice.png'});
                  marker = L.marker(
                    new L.latLng(loc), 
                    {title: title, icon: oshaoffice}
                  );
                  break;
                case "AF":
                  whdoffice = new LeafIcon({iconUrl: 'js/img/whdOffice.png'});
                  marker = L.marker(
                    new L.latLng(loc), 
                    {title: title, icon: whdoffice}
                  );
                  break;
                case "EAP":
                  jobCentersComp = new LeafIcon({iconUrl: 'js/img/jobCentersComp.png'});
                  marker = L.marker(
                    new L.latLng(loc), 
                    {title: title, icon: jobCentersComp}
                  );
                  break;
                case "SCA":
                  jobCentersCorps = new LeafIcon({iconUrl: 'js/img/jobCentersCorps.png'});
                  marker = L.marker(
                    new L.latLng(loc), 
                    {title: title, icon: jobCentersCorps}
                  );
                  break;
                case "WHA":
                  jobCentersAffiliate = new LeafIcon({iconUrl: 'js/img/jobCentersAffiliate.png'});
                  marker = L.marker(
                    new L.latLng(loc), 
                    {title: title, icon: jobCentersAffiliate}
                  );
                  break;
                default:
                  generic = new LeafIcon({iconUrl: 'js/img/jobCentersOther.png'});
                  marker = L.marker(
                    new L.latLng(loc), 
                    {title: title, icon: generic}
                  );
              }
                 
              marker.bindPopup(
                  "Post: <strong>"+pointsBucket[w].post+"</strong><br />"
                  +"Region: <strong>"+pointsBucket[w].region+"</strong><br />"
                  +"Bromium Workstations Completed: <strong>"+pointsBucket[w].regworkpercentcompleted+"%</strong><br />"
                  +"vSentry Workstations Completed: <strong>"+pointsBucket[w].vsenpercentcompleted+"%</strong><br />"
                  +"Bandwidth Capacity: <strong>"+numberWithCommas(pointsBucket[w].capacity) +" Bits/Second</strong>"            
                );                   
            markers.addLayer(marker);
          }
          map.removeLayer(markers);
          return false;
        }
        
        //Begin Layer 2 and 3 Intigration
        var pointsBucket = bromiumData,           
            jsonPoint = L.geoJson(pointsBucket, {
            filter: function(feature, layer) {
                return feature.properties.show_on_map;
            },
        });
        
        populate(pointsBucket);  
        
        // Not is use yet
        var legendPoints = L.control({position: 'bottomright'});
        legendPoints.onAdd = function(map){
          var div = L.DomUtil.create('div', 'info2 legend2');
            div.innerHTML +='<i style="background:#CCFF33"></i>NEA<br>';
            div.innerHTML +='<i style="background:#00FF00"></i>AF<br>';
            div.innerHTML +='<i style="background:#0099FF"></i>EAP<br>'; 
            div.innerHTML +='<i style="background:#0099FF"></i>EUR<br>';           
            div.innerHTML +='<i style="background:#CC3300"></i>SCA<br>';
            div.innerHTML +='<i style="background:#3B0737"></i>US<br>';
            div.innerHTML +='<i style="background:#3B0737"></i>WHA<br>';
            
          return div;
        }  
        //legendPoints.addTo(map); $(".info2").hide();  

    //Zoom based Data Traversal method
    map.on('zoomend', function(e){
      
      if(map.getZoom() >= 4){                 
          map.addLayer(markers);           
          //$(".info2").show();
      }
      // Add circles with job count
      if(map.getZoom() < 5){
          map.removeLayer(markers);
          //$(".info2").hide();
      }
      
      //console.log(map.getZoom());
      if(map.getZoom() > 4){ 
        $(".info").hide(); 
      }else{ 
        $(".info").show(); 
      } 
      if(map.getZoom() >= 5){ map.removeLayer(geojson); }//order matters

      if(map.getZoom() == 4 || map.getZoom() <= 4){ map.addLayer(geojson); }  
    });
  }//End getPoints()

  //Builds ... well you know...
  function treemap(bandwidthData){
      var colorArr = new Array();
          colorArr["AF"] = "#31a354"; 
          colorArr["EUR"] = "#9ecae1"; 
          colorArr["AF"] = "#75A319"; 
          colorArr["EAP"] = "#8080ff"; 
          colorArr["SCA"] = "#fd8d3c"; 
          colorArr["NEA"] = "#3182bd"; 
          colorArr["WHA"] = "#B84D4D";
          colorArr["US" ] = "#FF3D00";
 
      var margin = {top: 40, right: 10, bottom: 10, left: 10},
          width = 1135 - margin.left - margin.right,
          height = 500 - margin.top - margin.bottom;

      var color = d3.scale.category20c();
      console.log(color);

      var treemap = d3.layout.treemap()
          .size([width, height])
          .sticky(true)
          .value(function(d) { return d.size; });

      var div = d3.select("#treemap").append("div")
          .style("position", "relative")
          .style("width", (width + margin.left + margin.right) + "px")
          .style("height", (height + margin.top + margin.bottom) + "px")
          .style("left", margin.left + "px")
          .style("top", margin.top + "px");

      var tooltip = initToolTip("#treemap", "tooltip");   

        var node = div.datum(bandwidthData).selectAll(".node")
            .data(treemap.nodes)
          .enter().append("div")
            .attr("class", "node")
            .attr("id", function(d){
              if(d.post) return d.post.replace(/[\W\s]/g,"");
            })
            .call(position)
            .on("mouseover",function (d) {
              var text = "<div id='tphead'><strong>"+d.parent.name+"</strong></div>"+
              "Post: <strong>"+d.post+"</strong><br /> Bandwidth:<strong>"+numberWithCommas(d.size/1000)+" MB/Second</strong>";

              tooltip.style("display", "block"); //Show tool tip

              masterToolTip("#tooltip", text);
              //Special effect
              d3.selectAll("#tphead")
                .style("color", "#fff")
                .style("background-color", function() {
                  return d.parent ? colorArr[d.parent.name] : colorArr[d.name]; 
                })
                .transition()
                  .duration(500)
                  .style("opacity", .8);
            })
            .on("mouseout",function (d) { 
              tooltip.style("display", "none"); //Hide tool tip
            })
            .style("background", function(d) { 
              return d.children ? colorArr[d.name] : null; 
            })
            .text(function(d) { return d.children ? null : d.post; });

        d3.selectAll("input").on("change", function change() {
          var value = this.value === "count"
              ? function() { return 1; }
              : function(d) { return d.size; };

          node
              .data(treemap.value(value).nodes)
            .transition()
              .duration(1500)
              .call(position);
        });

      function position() {
        this.style("left", function(d) { return d.x + "px"; })
            .style("top", function(d) { return d.y + "px"; })
            .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
            .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
      } 
  }
  
  function onMapClick(e) {//Coordinate pop up
    popup.setLatLng(e.latlng) 
         .setContent("Coordinates clicked are: " + e.latlng.toString())
         .openOn(map);
  }
  //map.on('click', onMapClick); // Coordinates on click in the map

  var popup = L.popup();
  var map = initMap();
  var markers = L.markerClusterGroup(), polyData;
  var region = "US";
  
  //Epoch Test 1075611600000
  //var myDate = new Date(1075611600000);
  // var format = d3.time.format("%Y").parse;
  // console.log(format(myDate));
  //console.log(myDate); //Sun Feb 01 2004 00:00:00 GMT-0500 (EST)
  // var format = d3.time.format("%m - %Y");
  // console.log(format(new Date(1075611600000)));

  //Begin
  buildMapData(GeoData);
  getPoints(bromiumData);
  barChart(bromiumDataLocal,region);
  treemap(bandwidthData);
      