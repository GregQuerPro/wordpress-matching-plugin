<?php

class UserSearch
{

    // On récupère l'objet $_GET et $_POST
    private $query;

    private $columns = [];

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function formatData()
    {
        $query = $this->query;
        // var_dump($query);
        foreach ($query as $key => $value) {
            if ($key === '_geolocalisation') {
                $regex = '/(\-?\d+\.\d+),(\-?\d+\.\d+),(\d+)/';
                $matches = [];
                if (preg_match($regex, $value, $matches)) {
                    $this->columns['latitude'] = $matches[1];
                    $this->columns['longitude'] = $matches[2];
                    $this->columns['rayon'] = $matches[3];
                }
            } else if ($key === '_price' || $key === '_surface') {
                $regex = '/^\d+,(\d+)/';
                $matches = [];
                if (preg_match($regex, $value, $matches)) {
                    $this->columns["$key"] = $matches[1];
                }
            } else if ($key === '_room') {
                $this->columns["$key"] = $value;
            }
        }

        $this->columns = array_combine(
            array_map(function ($key) {
                return (strpos($key, '_') === 0) ? substr($key, 1) : $key;
            }, array_keys($this->columns)),
            array_values($this->columns)
        );

        // var_dump($this->columns);

        foreach ($this->columns as $key => $value) {
            if (array_key_exists($key, $query)) {
                // var_dump($query["$key"]);
                // var_dump($key);
                $sign = $query["$key"]['sign'];
                $exist = false;
                foreach ($query["$key"] as $item) {
                    // var_dump($sign);
                    if (is_array($item)) {
                        if ($item['choice'] === 'on') {
                            $exist = true;
                            if ($sign === '>') {
                                $this->columns["$key"] = $this->columns["$key"] + ($this->columns["$key"] * ($item['weight'] / 100));
                                // var_dump($this->columns["$key"]);
                            } else if ($sign === '<') {
                                // var_dump($this->columns["$key"]);
                                // var_dump($item['weight']);
                                $this->columns["$key"] = $this->columns["$key"] - ($this->columns["$key"] * ($item['weight'] / 100));
                                // var_dump(1 - ($item['weight'] / 100));
                            }
                        }
                    }
                }
            }
        }

        return $exist;
    }

    public function insertData()
    {
        // var_dump($this->columns);
        global $wpdb;

        $numberCriteria = 0;

        $types = [];

        foreach ($this->columns as $key => $value) {
            $types[] = '%s';
            if ($key !== 'latitude' && $key !== 'longitude') {
                $numberCriteria++;
            }
        }

        $this->columns['nbr_criteria'] = $numberCriteria;
        $types[] = '%d';

        // var_dump($this->columns);
        // var_dump($types);

        $wpdb->insert("user_searches", $this->columns, $types);
    }
}
