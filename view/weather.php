<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mapa Climático | Eco System</title>

  <?php include __DIR__ . '/../public/components/links.php'; ?>

  <script src="https://cdn.maptiler.com/maptiler-sdk-js/v3.6.1/maptiler-sdk.umd.min.js"></script>
  <link href="https://cdn.maptiler.com/maptiler-sdk-js/v3.6.1/maptiler-sdk.css" rel="stylesheet" />
  <script src="https://cdn.maptiler.com/maptiler-weather/v3.1.1/maptiler-weather.umd.min.js"></script>

  <style>
    /* =======================================================
       == CSS CORRIGIDO E FINALIZADO
       ======================================================= */
    
    /* 1. Novo container principal para o mapa, posicionado abaixo do header */
    #map-page-content {
      position: relative; /* Cria um novo contexto de empilhamento e posicionamento */
      height: calc(100vh - 100px); /* Ocupa 100% da altura da tela, menos os 100px do header */
      width: 100%;
    }

    /* 2. Mapa agora é posicionado dentro do seu container pai (#map-page-content) */
    #map {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0; /* Camada base, atrás dos painéis */
    }

    /* 3. Painéis agora são posicionados em relação ao container pai, não à tela toda */
    .map-ui-container, #time-info-panel {
        position: absolute; /* Mudado de 'fixed' para 'absolute' */
        z-index: 1; /* Ficam na frente do mapa */
    }

    .map-ui-container {
      top: 20px;
      left: 20px;
      display: flex;
      flex-direction: column;
      gap: var(--space-md);
      max-width: 280px;
    }

    #time-info-panel {
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 90%;
      max-width: 600px;
      text-align: center;
    }
    
    /* (O restante do CSS de estilização dos elementos permanece o mesmo) */
    .layer-buttons .btn { width: 100%; margin-bottom: var(--space-sm); justify-content: flex-start; }
    .layer-buttons .btn.active { background: var(--primary); color: var(--text-inverse); box-shadow: var(--shadow-neon); }
    .pointer-info .info-label { font-size: var(--font-sm); color: var(--text-muted); text-transform: capitalize; }
    .pointer-info .info-value { font-size: var(--font-3xl); font-weight: var(--font-bold); color: var(--primary); line-height: 1.1; }
    #time-info-panel .card-header { padding-bottom: var(--space-sm); margin-bottom: var(--space-md); }
    #time-text { font-size: var(--font-sm); color: var(--text-secondary); margin-bottom: var(--space-sm); }
    #time-slider { width: 100%; -webkit-appearance: none; appearance: none; height: 8px; background: var(--secondary-dark); border-radius: var(--radius-full); outline: none; transition: var(--transition-fast); margin-top: var(--space-sm); }
    #time-slider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; background: var(--primary); cursor: pointer; border-radius: var(--radius-full); box-shadow: var(--shadow-neon); }
    #time-slider::-moz-range-thumb { width: 20px; height: 20px; background: var(--primary); cursor: pointer; border-radius: var(--radius-full); border: none; }
  </style>
</head>

<body>
  <script>
        if(!(localStorage.getItem('token')))  window.location.href = "logar.php"; //TODO -- DEIXAR ISSO MAIS SEGURO DPS COM O SERVERSIDE DO PHP
  </script>  

  <?php include __DIR__ . '/../public/components/header.php'; ?>
    
    <script>
        document.querySelectorAll(".navbar li a").forEach(link => {
            if (link.getAttribute('href').includes("weather.php")) {
                link.classList.add("active");
            }
        });
    </script>

  <main id="map-page-content">

    <div class="map-ui-container">
        <div class="card layer-buttons">
            <div class="card-header"><h3 class="card-title text-lg">Camadas</h3></div>
            <button id="temperature" class="btn btn-ghost"><i class="ri-thermometer-line"></i> Temperatura</button>
            <button id="wind" class="btn btn-ghost"><i class="ri-windy-line"></i> Vento</button>
            <button id="precipitation" class="btn btn-ghost"><i class="ri-showers-line"></i> Precipitação</button>
            <button id="pressure" class="btn btn-ghost"><i class="ri-compasses-2-line"></i> Pressão</button>
            <button id="radar" class="btn btn-ghost"><i class="ri-radar-line"></i> Radar</button>
        </div>
        <div class="card pointer-info">
            <div id="variable-name" class="info-label">Vento</div>
            <div id="pointer-data" class="info-value">-- m/s</div>
        </div>
    </div>

    <div id="time-info-panel" class="card">
        <div class="card-header">
            <div id="time-text">Carregando dados...</div>
            <input type="range" id="time-slider" min="0" max="11" step="1">
        </div>
        <button id="play-pause-bt" class="btn btn-primary">Play 3600x</button>
    </div>
    
    <div id="map"></div>

  </main>

  <script>
    maptilersdk.config.apiKey = '7klyOXlTBzazie1U4YF4';
    
    const weatherLayers = {
      "precipitation": { "layer": null, "value": "value", "units": " mm" },
      "pressure": { "layer": null, "value": "value", "units": " hPa" },
      "radar": { "layer": null, "value": "value", "units": " dBZ" },
      "temperature": { "layer": null, "value": "value", "units": "°C" },
      "wind": { "layer": null, "value": "speedMetersPerSecond", "units": " m/s" }
    };

    const map = (window.map = new maptilersdk.Map({
      container: 'map',
      style: maptilersdk.MapStyle.BACKDROP,
      zoom: 2,
      center: [-42.66, 37.63],
      hash: true,
      projection: 'globe',
      projectionControl: true
    }));

    const initWeatherLayer = "wind";
    const timeTextDiv = document.getElementById("time-text");
    const timeSlider = document.getElementById("time-slider");
    const playPauseButton = document.getElementById("play-pause-bt");
    const pointerDataDiv = document.getElementById("pointer-data");
    let pointerLngLat = null;
    let activeLayer = null;
    let isPlaying = false;
    let currentTime = null;

    timeSlider.addEventListener("input", (evt) => {
      const weatherLayer = weatherLayers[activeLayer]?.layer;
      if (weatherLayer) {
        weatherLayer.setAnimationTime(parseInt(timeSlider.value / 1000));
      }
    });

    playPauseButton.addEventListener("click", () => {
      const weatherLayer = weatherLayers[activeLayer]?.layer;
      if (weatherLayer) {
        if (isPlaying) {
          pauseAnimation(weatherLayer);
        } else {
          playAnimation(weatherLayer);
        }
      }
    });

    function pauseAnimation(weatherLayer) {
      weatherLayer.animateByFactor(0);
      playPauseButton.innerHTML = '<i class="ri-play-fill"></i> Play 3600x';
      isPlaying = false;
    }

    function playAnimation(weatherLayer) {
      weatherLayer.animateByFactor(3600);
      playPauseButton.innerHTML = '<i class="ri-pause-fill"></i> Pause';
      isPlaying = true;
    }

    map.on('load', function () {
      map.setPaintProperty("Water", 'fill-color', "rgba(0, 0, 0, 0.4)");
      initWeatherMap(initWeatherLayer);
    });

    function resetPointerValue() {
        pointerLngLat = null;
        const weatherLayerUnits = weatherLayers[activeLayer]?.units || "";
        pointerDataDiv.innerText = `-- ${weatherLayerUnits}`;
    }

    map.on('mouseout', function (evt) {
      if (!evt.originalEvent.relatedTarget) {
        resetPointerValue();
      }
    });

    function updatePointerValue(lngLat) {
    if (!lngLat) return;
    pointerLngLat = lngLat;
    const weatherLayer = weatherLayers[activeLayer]?.layer;

    if (weatherLayer) {
        const value = weatherLayer.pickAt(lngLat.lng, lngLat.lat);

        if (!value) {
            resetPointerValue();
            return;
        }

        let newLabelHTML = '';

        if (activeLayer === 'wind') {
            // Usando '?? 0' para garantir que temos um número mesmo se a propriedade não existir
            const speed = value.speedMetersPerSecond ?? 0;
            const direction = value.directionDegrees ?? 0;
            const gust = value.gustMetersPerSecond ?? 0;
            
            const arrowIcon = `<i class="ri-navigation-fill" style="transform: rotate(${direction}deg); display: inline-block;"></i>`;

            newLabelHTML = `
                ${speed.toFixed(1)} ${weatherLayers.wind.units} ${arrowIcon}
                <div style="font-size: 1rem; color: var(--text-secondary); font-weight: 500;">
                  Rajadas: ${gust.toFixed(1)} ${weatherLayers.wind.units}
                </div>
            `;
            pointerDataDiv.innerHTML = newLabelHTML;

        } else {
            const weatherLayerValueKey = weatherLayers[activeLayer]?.value;
            const weatherLayerUnits = weatherLayers[activeLayer]?.units;
            
            // Verificação de segurança: ?. antes de .toFixed()
            const numericValue = value[weatherLayerValueKey];
            const displayValue = numericValue?.toFixed(1) ?? '--'; // Se for nulo ou undefined, mostra '--'

            newLabelHTML = `${displayValue}${weatherLayerUnits}`;
            pointerDataDiv.innerHTML = newLabelHTML;
        }
    }
  }

    map.on('mousemove', (e) => {
      updatePointerValue(e.lngLat);
    });

    document.querySelector('.layer-buttons').addEventListener('click', function (event) {
        if (event.target && event.target.matches('button.btn')) {
            changeWeatherLayer(event.target.id);
        }
    });

    function changeWeatherLayer(type) {
        if (!type || type === activeLayer) return;

        if (map.getLayer(activeLayer)) {
            const activeWeatherLayer = weatherLayers[activeLayer]?.layer;
            if (activeWeatherLayer) {
                currentTime = activeWeatherLayer.getAnimationTime();
                map.setLayoutProperty(activeLayer, 'visibility', 'none');
            }
        }
        activeLayer = type;
        const weatherLayer = weatherLayers[activeLayer].layer || createWeatherLayer(activeLayer);
        if (map.getLayer(activeLayer)) {
            map.setLayoutProperty(activeLayer, 'visibility', 'visible');
        } else {
            map.addLayer(weatherLayer, 'Water');
        }
        changeLayerLabel(activeLayer);
        activateButton(activeLayer);
        changeLayerAnimation(weatherLayer);
        resetPointerValue();
        return weatherLayer;
    }

    function activateButton(activeLayer) {
      const buttons = document.querySelectorAll('.layer-buttons .btn');
      buttons.forEach(btn => {
        if (btn.id === activeLayer) {
          btn.classList.add('active');
          btn.classList.remove('btn-ghost');
          btn.classList.add('btn-primary');
        } else {
          btn.classList.remove('active', 'btn-primary');
          btn.classList.add('btn-ghost');
        }
      });
    }

    function changeLayerAnimation(weatherLayer) {
      weatherLayer.setAnimationTime(parseInt(timeSlider.value / 1000));
      if (isPlaying) {
        playAnimation(weatherLayer);
      } else {
        pauseAnimation(weatherLayer);
      }
    }

    function createWeatherLayer(type) {
      let weatherLayer = null;
      switch (type) {
        case 'precipitation':
          weatherLayer = new maptilerweather.PrecipitationLayer({ id: 'precipitation' });
          break;
        case 'pressure':
          weatherLayer = new maptilerweather.PressureLayer({ opacity: 0.8, id: 'pressure' });
          break;
        case 'radar':
          weatherLayer = new maptilerweather.RadarLayer({ opacity: 0.8, id: 'radar' });
          break;
        case 'temperature':
          weatherLayer = new maptilerweather.TemperatureLayer({ colorramp: maptilerweather.ColorRamp.builtin.TEMPERATURE_3, id: 'temperature' });
          break;
        case 'wind':
          weatherLayer = new maptilerweather.WindLayer({ id: 'wind' });
          break;
      }

      weatherLayer.on("tick", event => { refreshTime(); updatePointerValue(pointerLngLat); });
      weatherLayer.on("animationTimeSet", event => { refreshTime(); });
      weatherLayer.on("sourceReady", event => {
        const startDate = weatherLayer.getAnimationStartDate();
        const endDate = weatherLayer.getAnimationEndDate();
        if (timeSlider.min > 0) {
          weatherLayer.setAnimationTime(currentTime);
          changeLayerAnimation(weatherLayer);
        } else {
          const currentDate = weatherLayer.getAnimationTimeDate();
          timeSlider.min = +startDate;
          timeSlider.max = +endDate;
          timeSlider.value = +currentDate;
        }
      });
      weatherLayers[type].layer = weatherLayer;
      return weatherLayer;
    }

    function refreshTime() {
      const weatherLayer = weatherLayers[activeLayer]?.layer;
      if (weatherLayer) {
        const d = weatherLayer.getAnimationTimeDate();
        timeTextDiv.innerText = d.toLocaleString('pt-BR', { dateStyle: 'full', timeStyle: 'short' });
        timeSlider.value = +d;
      }
    }

    function changeLayerLabel(type) {
      document.getElementById("variable-name").innerText = type;
    }

    function initWeatherMap(type) {
      const weatherLayer = changeWeatherLayer(type);
    }
  </script>
</body>

</html>