<?php

class UserSearch
{

    // On récupère l'objet $_GET et $_POST
    private $query;

    private $row = [];
    private $essentials = [];

    public function __construct($query, $essentials)
    {
        $this->query = $query;
        $this->essentials = $essentials;
    }

    public function formatData()
    {
        $query = $this->query;
        if (empty($query)) {
            return false;
        }

        global $wpdb;
        $searches_options = $wpdb->get_results("SELECT * FROM user_searches_options WHERE to_hide = 0");
        // var_dump($query);
        foreach ($query as $key => $value) {
            if ($key === '_geolocalisation') {
                $regex = '/(\-?\d+\.\d+),(\-?\d+\.\d+),(\d+)/';
                $matches = [];
                if (preg_match($regex, $value, $matches)) {
                    $this->row['latitude'] = $matches[1];
                    $this->row['longitude'] = $matches[2];
                    $this->row['rayon'] = $matches[3];
                }
            } else if ($key === '_price' || $key === '_surface') {
                $regex = '/^\d+,(\d+)/';
                $matches = [];
                if (preg_match($regex, $value, $matches)) {
                    $this->row["$key"] = $matches[1];
                }
            } else if ($key === '_room') {
                $this->row["$key"] = $value;
            }
        }

        $this->row = array_combine(
            array_map(function ($key) {
                return (strpos($key, '_') === 0) ? substr($key, 1) : $key;
            }, array_keys($this->row)),
            array_values($this->row)
        );

        // var_dump($this->row);

        foreach ($searches_options as $searches_option) {
            // var_dump(lcfirst($searches_option->name));
            // var_dump($this->row[lcfirst($searches_option->name)]);
            $name = lcfirst($searches_option->name);
            $sign = lcfirst($searches_option->sign);
            if (str_contains($this->essentials, $name)) {
                $weight = $searches_option->weight_essential;
            } else {
                $weight = $searches_option->weight_base;
            }

            // var_dump($weight);

            if (key_exists($name, $this->row)) {
                // var_dump($weight);
                if ($sign === '+') {
                    $this->row[$name] = round($this->row[$name] + ($this->row[$name] * ($weight / 100)));
                } else if ($sign === '-') {
                    $this->row[$name] = round($this->row[$name] - ($this->row[$name] * ($weight / 100)));
                }
            }
        }

        // var_dump($this->row);


        return true;
    }

    public function insertData()
    {
        // var_dump($this->row);
        global $wpdb;

        $numberCriteria = 0;

        $types = [];

        // var_dump($this->row);

        foreach ($this->row as $key => $value) {
            $types[] = '%s';
            if ($key !== 'latitude' && $key !== 'longitude') {
                $numberCriteria++;
            }
        }

        $this->row['nbr_criteria'] = $numberCriteria;
        $this->row['user_id'] = get_current_user_id();
        $types[] = '%d';

        var_dump($this->row);
        // var_dump($types);

        $wpdb->insert("user_searches", $this->row, $types);
    }
}
