<?php
namespace DgbAuroCore\lib\Inventarium;
use DgbAuroCore\lib\Inventarium\Facade;


//Sustitor, Cortador,Cutter, Mimic,Replicant,Pretender,CopyCat,Replacer,Fiter,Thief,Substitutor, Usurpe, Filler,clone,Identity,TwinTrick,TakeOver,Posesion,Infiltrado.Alternate,Switcher,Ilusionista

//Infection,Plague,Infestation,Rampage,
class Render
{


    public static function RenderData($fileAlias, $data = array(), $showEmptys = false)
    {
   
        //spl es abreviacion de supply
        $env = Facade::call('Env');
        $data['splRenderAppName'] = $env::env('APP_NAME');
        $data['splSrc'] =  $env::env('URL') . '/public/assets/img';

        $html = file_get_contents($env::env('ROOT') . '/app/views/' . $fileAlias);
        if (($fileAlias) && ($data)) {

            if ($data) {

                $htmlFilled = self::fillHtml($data, $html);
            }

            //Relleno de nulos
            if(!$showEmptys){

                $htmlFilled = preg_replace('/{{(.*?)}}/', '', $htmlFilled);
            }

        } else {
            //Relleno de nulos

            // if(!$showEmptys){

            //     $html = preg_replace('/{{(.*?)}}/', '', $html);
            // }
            // return $html;
        }

        return $htmlFilled;

    }

    private static function fillHtml($data, $html)
    {
        

        //TODO doble check por si no se reemplazo una clave ??? Eiste otra manera mas optima?? Tal vez un while o un until 


        

        foreach ($data as $clave => $valor) {

            // if (strpos($html, '{{' . $clave . '}}') !== false) {
            //     echo "La subcadena '$clave' fue encontrada en la cadena principal.";
            // } 
            $html = str_replace('{{' . $clave . '}}', $valor, $html);

        }

        foreach ($data as $clave => $valor) {
            if (strpos($html, '{{' . $clave . '}}') !== false) {
                
                self::fillHtml($data, $html);
                // echo "La subcadena '$clave' fue encontrada en la cadena principal.";
            } 

        }

        return $html;

    }

    
}
