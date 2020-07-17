<?php

/*
 * This file is inspired by Builder from Laravel ChartJS - Brian Faust
 */

namespace Noonenew\LaravelGanttChart;

class Builder
{
    /**
     * @var array
     */
    private $gantt = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $defaults = [
        'datajson'          => [],
        'datasets'          => [],
        'labels'            => [],
        'type'              => [],
        'customcontroller'  => [],
        'customfunction'    => [],
        'size'              => ['width' => null, 'height' => null]
    ];

    

    /**
     * @param $mapid
     *
     * @return $this|Builder
     */
    public function ganttid($ganttid)
    {
        return $this->set('ganttid', $ganttid);
    }

    /**
     * @param $name
     *
     * @return $this|Builder
     */
    public function name($name)
    {
        $this->name          = $name;
        $this->gantt[$name] = $this->defaults;
        return $this;
    }

    /**
     * @param $element
     *
     * @return Builder
     */
    public function element($element)
    {
        return $this->set('element', $element);
    }

    /**
     * @param array $labels
     *
     * @return Builder
     */
    public function labels(array $labels)
    {
        return $this->set('labels', $labels);
    }

    /**
     * @param string|array $datajson
     *
     * @return Builder
     */
    public function datajson($datajson)
    {
        if (is_array($datajson)) {
            $this->set('datajson', json_encode($datajson, true));
            return $this;
        }

        $this->set('datajson', $datajson);
        return $this;
    }

    /**
     * @param string $datasets
     *
     * @return Builder
     */
    public function datasets($datasets)
    {
        return $this->set('datasets', $datasets);
    }

    /**
     * @param string $type
     *
     * @return Builder
     */
    public function type($type)
    {
        return $this->set('type', $type);
    }


    /**
     * @param array $size
     *
     * @return Builder
     */
    public function size($size)
    {
        return $this->set('size', $size);
    }

    /**
     * @param array $options
     *
     * @return $this|Builder
     */
    public function options(array $options)
    {
        foreach ($options as $key => $value) {
            $this->set('options.' . $key, $value);
        }

        return $this;
    }
    
    /**
     * @param string $customcontroller
     *
     * @return Builder
     */
    public function customcontroller($customcontroller)
    {
        if(is_null($customcontroller)){
            $customcontroller = null;
        } else {
            $customcontroller = $customcontroller;
        }
        return $this->set('customcontroller', $customcontroller);
    }

    /**
     * @param string $customfunction
     *
     * @return Builder
     */
    public function customfunction($customfunction)
    {
        if(is_null($customfunction)){
            $customfunction = null;
        } else {
            $customfunction = $customfunction;
        }
        return $this->set('customfunction', $customfunction);
    }
    
    /**
     * @return mixed
     */
    public function create()
    {
        $map = $this->maps[$this->name];
        //dd($map['datasets']);
        return view('map-template::map-template')
                ->with('datasets', $map['datasets'])
                ->with('element', $this->name)
                ->with('labels', $map['labels'])
                ->with('options', isset($map['options']) ? $map['options'] : '')
                ->with('optionsRaw', isset($map['optionsRaw']) ? $map['optionsRaw'] : '')
                ->with('marker', $map['marker'])
                ->with('customcontroller', $map['customcontroller'])
                ->with('tooltip', $map['tooltip'])
                ->with('popup', $map['popup'])
                ->with('type', $map['type'])
                ->with('tile', $map['tile'])
                ->with('size', $map['size'])
                ->with('zoom', $map['zoom']);
    }

    public function container()
    {
        $map = $this->maps[$this->name];

        return view('gantt-template::gantt-template-without-script')
                ->with('element', $this->name)
                ->with('size', $map['size']);
    }


    public function script()
    {
        $map = $this->maps[$this->name];

        return view('gantt-template::gantt-template-script')
            ->with('datasets', $map['datasets'])
            ->with('element', $this->name)
            ->with('labels', $map['labels'])
            ->with('options', isset($map['options']) ? $map['options'] : '')
            ->with('optionsRaw', isset($map['optionsRaw']) ? $map['optionsRaw'] : '')
            ->with('marker', $map['marker'])
            ->with('customcontroller', $map['customcontroller'])
            ->with('tooltip', $map['tooltip'])
            ->with('popup', $map['popup'])
            ->with('type', $map['type'])
            ->with('tile', $map['tile'])
            ->with('location', $map['location'])
            ->with('datajson', $map['datajson'])
            ->with('size', $map['size'])
            ->with('zoom', $map['zoom']);
    }

    public function mapFunctions()
    {
        $map = $this->maps[$this->name];
        return view('map-template::map-template-functions')
        ->with('customfunction', $map['customfunction']);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    private function get($key)
    {
        return array_get($this->maps[$this->name], $key);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this|Builder
     */
    private function set($key, $value)
    {
        array_set($this->maps[$this->name], $key, $value);
        return $this;
    }
}
