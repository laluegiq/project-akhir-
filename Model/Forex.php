<?php
class Forex
{
    public $negara;
    public $mataUang;
    public $nilaiTukar;

    public function __construct($negara, $mataUang, $nilaiTukar)
    {
        $this->negara = $negara;
        $this->mataUang = $mataUang;
        $this->nilaiTukar = $nilaiTukar;
    }

    public function toArray()
    {
        return[
            'negara'=>$this->negara,
            'mataUang'=>$this->mataUang,
            'nilaiTukar'=>$this->nilaiTukar
        ];
    }
}
