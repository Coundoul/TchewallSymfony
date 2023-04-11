<?php

namespace App\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomeTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            //le 1er parametre defaultImage c'est le nom du filtre
            //$this le nom de la classe si par exemple on utilise une classe pour implementer la classe il faut utiliser le nom de la classe
            //defaultImage est le nom de la fonction de la classe
            new TwigFilter('defaultImage', [$this, 'defaultImage'])
        ];
    }

    public function defaultImage(string $path) : string {
        if(strlen(trim($path)) == 0 ){
            return 'Lion.jpg';
        }
        return $path;
    }

}