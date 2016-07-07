<?php
namespace System\Lib;

class Redirect
{
    private $path;
    private $is_back=false;
    public function __construct($str='')
    {
        $this->path=$str;
        return $this;
    }
    /**
     * @return \System\Lib\Redirect
     */
    public function back()
    {
        $this->is_back=true;
        return $this;
    }

    /**
     *
     * @param  string|array  $key
     * @param  mixed  $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        $key = is_array($key) ? $key : array($key => $value);
        foreach ($key as $k => $v) {
            session()->flash($k,$v);
        }
    }
    
    public function __destruct()
    {
        if($this->is_back){
            echo '<script>history.go(-1);</script>';
        }else{
            global $_G;
            if (substr($this->path, 0, 1) != '/') {
                $url = $_G['Controller']->base_url . $this->path;
            }
        }
        header("location:$url");
        exit;
    }
}
