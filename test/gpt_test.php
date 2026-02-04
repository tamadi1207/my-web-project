<!DOCTYPE html>
<html>
<head>
  <title>埼玉県 警察署ピン表示</title>
  <style>
    #map { height: 100vh; width: 100%; }
  </style>
</head>
<body>
  <div id="map"></div>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8y_JXlfx8444k6pkmfMHMdgsk7CSazn4"></script>
  <script>
    var map;

    var locations = [
      {name: "浦和警察署", address: "埼玉県さいたま市浦和区常盤4丁目11-21"},
      {name: "浦和東警察署", address: "埼玉県さいたま市緑区東浦和"},
      {name: "浦和西警察署", address: "埼玉県さいたま市中央区上落合"},
      {name: "大宮警察署", address: "埼玉県さいたま市大宮区北袋町1-197-7"},
      {name: "大宮東警察署", address: "埼玉県さいたま市見沼区深作35-1"},
      {name: "大宮西警察署", address: "埼玉県さいたま市西区三橋1丁目"},
      {name: "川口警察署", address: "埼玉県川口市西青木3-2-4"},
      {name: "行田警察署", address: "埼玉県行田市大字長野4195-1"},
      {name: "越谷警察署", address: "埼玉県越谷市東越谷7丁目11-6"}
    ];

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 35.8617, lng: 139.6455}, // 埼玉県中心付近
        zoom: 9
      });

      var geocoder = new google.maps.Geocoder();

      locations.forEach(function(loc, index) {
        geocoder.geocode({ 'address': loc.address }, function(results, status) {
          if (status === 'OK') {
            var marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location,
              title: loc.name
            });

            // ランダム連番メモ
            var memo = "No." + (index + 1) + " - 緊急対応基地";

            var infoWindow = new google.maps.InfoWindow({
              content: "<strong>" + loc.name + "</strong><br>" +
                       loc.address + "<br>" + memo
            });

            marker.addListener('click', function() {
              infoWindow.open(map, marker);
            });
          } else {
            console.error("Geocode error: " + status);
          }
        });
      });
    }

    initMap();
  </script>
</body>
</html>
