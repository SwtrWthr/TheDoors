<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="{{asset('/css/app.css')}}">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
  <script type="text/javascript">
    var map;

    DG.then(function () {
      map = DG.map('map', {
        center: [52.480724, 62.186541],
        zoom: 13
      });

      DG.marker([52.480724, 62.186541]).addTo(map).bindPopup('HA-HA CLASSIC.');
    });
  </script>
  <title>Home</title>
</head>
<body class="bg-light">
<div id="app">
  <v-app class="bg-light">
    <c-header></c-header>
    <b-container class="mt-4">
      <router-view></router-view>
    </b-container>
    <c-footer></c-footer>
  </v-app>
</div>
<script src="{{asset('/js/app.js')}}"></script>
<script src="{{asset('/js/bootstrap.min.js')}}"></script>
</body>
</html>
