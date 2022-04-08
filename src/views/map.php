<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/map.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div id="map"></div>
    <div class="route-data">
        <h2>DATA ABOUT SELECTED ROUTE</h2>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                <th scope="col">Start time</th>
                <th scope="col">End time</th>
                <th scope="col">Address</th>
                <th scope="col">Distance</th>
                <th scope="col">Time spent</th>
                </tr>
            </thead>
        </table>
    </div>
    <script>
        function initMap(){
            // Map options
            var options = {
                zoom:10,
                center:{lat: 56.934795, lng: 24.141312}
            };
            // New Map
            var map = new google.maps.Map(document.getElementById('map'),options);

            setMap(map);
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmqQlgXiUgVjdGxZQdkvzLQmkNc12pgKQ&callback=initMap"></script>
</body>
</html>