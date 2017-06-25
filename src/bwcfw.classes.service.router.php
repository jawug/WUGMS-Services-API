<?php

class Router_Service {

    private $raw_routes;

    /**
     *
     * @var array 
     */
    var $configured_routes = array();
    private $Router_status;

    function RouteReturned($route_idx, $parameters) {
        if ($this->configured_routes[$route_idx]["parameter_required"]) {
            unset($parameters[0]);
            $this->configured_routes[$route_idx]["parameters"] = $parameters;
        }
        return $this->configured_routes[$route_idx];
    }

    function CheckForRoute($QueryString) {
        $route_size = sizeof($this->configured_routes);
        for ($j = 0; $j < $route_size; $j++) {
            $pattern = '/^' . $this->configured_routes[$j]["route"] . '$/i';
            if (preg_match($pattern, $QueryString, $matches)) {
                $this->Router_status->setStatusCode("Route found!");
                return $this->RouteReturned($j, $matches);
            }
        }
        $this->Router_status->setStatus(FALSE);
        $this->Router_status->setStatusCode("Route not found");
    }

    private function build_route_parameters($release_ID, $route_ID) {
        $param_array = array();
        $param_array['parameter_required'] = FALSE;
        $parameter_size = sizeof($this->raw_routes["Controllers"]["releases"][$release_ID]["routes"][$route_ID]["parameter"]);
        if ($parameter_size > 0) {
            $param_array['parameter_required'] = TRUE;
        }
        $parameter = "";
        for ($a = 0; $a < $parameter_size; $a++) {
            if ($this->raw_routes["Controllers"]["releases"][$release_ID]["routes"][$route_ID]["parameter"][$a]) {
                $parameter .= "\/" . $this->raw_routes["Controllers"]["releases"][$release_ID]["routes"][$route_ID]["parameter"][$a];
            }
        }
        $param_array['parameters'] = $parameter;
        return $param_array;
    }

    private function build_routes() {
        /* Build the routing table */
        $max = sizeof($this->raw_routes["Controllers"]["service"]);
        for ($i = 0; $i < $max; $i++) {
            $service = $this->raw_routes["Controllers"]["service"];
            $test = sizeof($this->raw_routes["Controllers"]["releases"]);
            for ($j = 0; $j < $test; $j++) {
                $start = $service . "\/v?" . $this->raw_routes["Controllers"]["releases"][$j]["version_major"] . "\." . $this->raw_routes["Controllers"]["releases"][$j]["version_minor"];
                $version = $this->raw_routes["Controllers"]["releases"][$j]["version_major"] . "." . $this->raw_routes["Controllers"]["releases"][$j]["version_minor"];
                $tester = sizeof($this->raw_routes["Controllers"]["releases"][$j]["routes"]);
                for ($r = 0; $r < $tester; $r++) {
                    $area = $this->raw_routes["Controllers"]["releases"][$j]["routes"][$r]["area"];
                    $action = $this->raw_routes["Controllers"]["releases"][$j]["routes"][$r]["action"];
                    $parameter = $this->build_route_parameters($j, $r);
                    $fn = $area . "." . $action;
                    $route_regex = $start . "\/" . $area . "\/" . $action . $parameter['parameters'] . "\/?";
                    $param_req = $parameter["parameter_required"];
                    $atest = array('route' => $route_regex, 'service' => $service, 'version' => $version, 'area' => $area, 'action' => $action, 'filename' => $fn, 'parameter_required' => $param_req, "parameters" => "");
                    array_push($this->configured_routes, $atest);
                }
            }
        }
    }

    function __construct() {
        $this->Router_status = new StatusVO();
        $this->config_path = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . "/../config/";
        $string = file_get_contents($this->config_path . "routes.json");
        $this->raw_routes = json_decode($string, true);
        $this->build_routes();
    }

}
