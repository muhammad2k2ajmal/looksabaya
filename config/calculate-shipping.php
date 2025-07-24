<?php
class PincodeDistanceCalculator {
    private $originPincode = '110025';
    private $graphHopperApiKey = '2a61d911-269f-4d36-8754-c7d1272ec27b';
    
    private function fetchData($url) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'PincodeDistanceApp/1.0'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    
    private function geocodePincode($pincode) {
        $url = "https://nominatim.openstreetmap.org/search?format=json&countrycodes=IN&postalcode=$pincode";
        $data = $this->fetchData($url);
        return !empty($data[0]['lat']) ? [$data[0]['lon'], $data[0]['lat']] : false;
    }
    
    private function getGraphHopperDistance($originCoords, $destinationCoords) {
        $url = "https://graphhopper.com/api/1/route?point={$originCoords[1]},{$originCoords[0]}&point={$destinationCoords[1]},{$destinationCoords[0]}&vehicle=car&locale=en&key=$this->graphHopperApiKey";
        $result = $this->fetchData($url);
        return !empty($result['paths'][0]['distance']) ? $result['paths'][0]['distance'] : false;
    }
    
    private function getHaversineDistance($originCoords, $destinationCoords) {
        $lat1 = deg2rad($originCoords[1]);
        $lon1 = deg2rad($originCoords[0]);
        $lat2 = deg2rad($destinationCoords[1]);
        $lon2 = deg2rad($destinationCoords[0]);
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
        $a = sin($dLat/2) * sin($dLat/2) + cos($lat1) * cos($lat2) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return 6371 * $c * 1000;
    }
    
    public function calculatePrice($destinationPincode) {
        $originCoords = $this->geocodePincode($this->originPincode);
        if (!$originCoords) return ['error' => 'Invalid origin pincode'];
        
        $destinationCoords = $this->geocodePincode($destinationPincode);
        if (!$destinationCoords) return ['error' => 'Invalid destination pincode'];
        
        $distance = $this->getGraphHopperDistance($originCoords, $destinationCoords);
        if ($distance === false) {
            $distance = $this->getHaversineDistance($originCoords, $destinationCoords);
        }
        
        $distanceKm = round($distance / 1000, 2);
        return $distanceKm < 300 ? 49 : 99;
    }
}
?>