<?php

namespace Modules\Services\Helpers\Ruc;

use Modules\Services\Functions\HtmlParser;
use Modules\Services\Functions\Http\ClientInterface;
use Modules\Services\Functions\Http\ContextClient;

/**
 * Class Ruc.
 */
class Sunat
{
    const URL_CONSULT = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias';
    const URL_RANDOM = 'http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random';

    /**
     * @var string
     */
    private $error;
    /**
     * @var ClientInterface
     */
    public $client;
    /**
     * @var HtmlParser
     */
    private $parser;

    /**
     * Ruc constructor.
     */
    public function __construct()
    {
        $this->parser = new HtmlParser();
        $this->client = new ContextClient();
    }


    public function search($number)
    {
        $data_company = $this->get($number);
        if ($data_company) {

            $res = null;
            if($data_company->direccion !== '-') {
                $res = Functions::get_location_by_address($data_company->direccion);
            }
            $data_company->razon_social = Functions::get_name($data_company->razon_social);

            $company = new CompanyModel();
            $company->ruc = $data_company->ruc;
            $company->razon_social = $data_company->razon_social;
            $company->nombre_comercial = $data_company->nombre_comercial;
            $company->contribuyente_condicion = $data_company->contribuyente_condicion;
            $company->contribuyente_tipo = $data_company->contribuyente_tipo;
            $company->contribuyente_estado = $data_company->contribuyente_estado;
            $company->direccion = ($res)?$res['address']:null;
            $company->departamento = ($res)?$res['department']:null;
            $company->provincia = ($res)?$res['province']:null;
            $company->distrito = ($res)?$res['district']:null;
            $company->ubigeo = ($res)?$res['location_id']:[];
            $company->fecha_inscripcion = $data_company->fecha_inscripcion;
            $company->sistema_emision = $data_company->sistema_emision;
            $company->sistema_contabilidad = $data_company->sistema_contabilidad;
            $company->emision_electronica = $data_company->emision_electronica;
            $company->fecha_inscripcion_ple = $data_company->fecha_inscripcion_ple;
            $company->fecha_baja = $data_company->fecha_baja;
            $company->representante_legal = $data_company->representante_legal;
            $company->ciiu = $data_company->ciiu;
            $company->fecha_actividad = $data_company->fecha_actividad;
            $company->locales = $data_company->locales;

            return [
                'success' => true,
                'data' => $company
            ];
        } else {
            return [
                'success' => false,
                'message' => $this->getError()
            ];
        }
    }
    /**
     * @param string $ruc
     *
     * @return bool|CompanyModel
     */
    public function get($ruc)
    {
        $random = $this->getRandom();
        $url = self::URL_CONSULT."?accion=consPorRuc&nroRuc=$ruc&numRnd=$random&tipdoc=";
        $dic = $this->getValuesFromUrl($url);

        if ($dic === false) {
            return false;
        }

        return $this->getCompany($dic);
    }

    /**
     * Get Last error message.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    private function getRandom()
    {
        $code = $this->client->get(self::URL_RANDOM);

        return $code;
    }

    private function getValuesFromUrl($url)
    {
        $html = $this->client->get($url);

        if ($html === false) {
            $this->error = 'Ocurrio un problema conectando a Sunat';

            return false;
        }

        $dic = $this->parser->parse($html);
        if ($dic === false) {
            $this->error = 'No se encontro el ruc';

            return false;
        }

        return $dic;
    }

    private function getCompany(array $items)
    {
        $cp = $this->getHeadCompany($items);
        $cp->sistema_emision = $items['Sistema de Emisión de Comprobante:'];
        $cp->sistema_contabilidad = $items['Sistema de Contabilidad:'];
        $cp->actividad_exterior = $items['Actividad de Comercio Exterior:'];
//        $cp->actEconomicas = $items['Actividad(es) Económica(s):'];
//        $cp->cpPago = $items['Comprobantes de Pago c/aut. de impresión (F. 806 u 816):'];
//        $cp->sistElectronica = $items['Sistema de Emision Electronica:'];
//        $cp->fechaEmisorFe = $this->parseDate($items['Emisor electrónico desde:']);
//        $cp->cpeElectronico = $this->getCpes($items['Comprobantes Electrónicos:']);
        $cp->fecha_inscripcion_ple = $this->parseDate($items['Afiliado al PLE desde:']);
//        $cp->padrones = $items['Padrones :'];
//        if ($cp->sistElectronica == '-') {
//            $cp->sistElectronica = [];
//        }
//        $res = Functions::get_location_by_address($cp->direccion);
//        $cp->distrito = $res['address'];
//        $cp->departamento = $res['department'];
//        $cp->provincia = $res['province'];
//        $cp->distrito = $res['district'];
//        $this->fixDirection($cp);

        return $cp;
    }

    private function getHeadCompany(array $items)
    {

        $cp = new CompanyModel();

        list($cp->ruc, $cp->razon_social) = $this->getRucRzSocial($items['Número de RUC:']);
        $cp->nombre_comercial = $items['Nombre Comercial:'];
        $cp->telefonos = $items['Phone'];
        $cp->contribuyente_tipo = $items['Tipo Contribuyente:'];
        $cp->contribuyente_estado = $items['Estado del Contribuyente:'];
        $cp->contribuyente_condicion = $items['Condición del Contribuyente:'];
        $cp->direccion = $items['Dirección del Domicilio Fiscal:'];
        $cp->fecha_inscripcion = $this->parseDate($items['Fecha de Inscripción:']);
//        dd($cp);
        return $cp;
    }

    /**
     * @param $text
     *
     * @return null|string
     */
    private function parseDate($text)
    {
        if (empty($text) || $text == '-') {
            return null;
        }

        $date = \DateTime::createFromFormat('d/m/Y', $text);

        return $date === false ? null : $date->format('Y-m-d').'T00:00:00.000Z';
    }



    private function getDepartment($department)
    {
        $department = strtoupper($department);
        $words = 1;
        switch ($department) {
            case 'DIOS':
                $department = 'MADRE DE DIOS';
                $words = 3;
            break;
            case 'MARTIN':
                $department = 'SAN MARTIN';
                $words = 2;
            break;
            case 'LIBERTAD':
                $department = 'LA LIBERTAD';
                $words = 2;
            break;
        }

        return [$words, $department];
    }

    private function getCpes($text)
    {
        $cpes = [];
        if ($text != '-') {
            $cpes = explode(',', $text);
        }

        return $cpes;
    }

    private function getRucRzSocial($text)
    {
        $pos = strpos($text, '-');

        $ruc = trim(substr($text, 0, $pos));
        $rzSocial = trim(substr($text, $pos + 1));

        return [$ruc, $rzSocial];
    }
}
