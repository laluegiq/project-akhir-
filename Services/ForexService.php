<?php
class ForexService{

    private $forexRepository;

    public function __construct(ForexRepository $forexRepository) {
        $this->forexRepository = $forexRepository;
    }

    public function getForexData(){
        return $this->forexRepository->getForexData();
    }
}