<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.11.2022
 * Time: 15:22
 */

namespace Ela;


class MultiSearch
{
    /* @var \Elastica\ResultSet[] $multiSearch */
    public $ResultSet;

    public function create(\Elastica\Multi\ResultSet $ResultSet)
    {
        $this->ResultSet = $ResultSet;
        return $this;
    }

    /**
     * @param string $key
     * @return \Elastica\ResultSet
     */
    public function get(string $key)
    {
        return $this->ResultSet[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return array_key_exists($key, $this->ResultSet);
    }

    /**
     * @return \Elastica\ResultSet[]
     */
    public function getResult()
    {
        return $this->ResultSet;
    }
}
