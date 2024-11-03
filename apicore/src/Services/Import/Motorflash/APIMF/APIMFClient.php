<?php

namespace App\Services\Import\Motorflash\APIMF;
use App\Services\Comun\Configuration;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Servicio cliente para APIMF
 * @author Eidy Estupiñan Varona <eidy.estupinan@motorflash.com>
 */
class APIMFClient
{
    private ?string $apiMfUrl;
    private ?string $apiMfClientId;
    private ?string $apiMfClientSecret;

    private ?string $token = '';

    private HttpClientInterface $httpClient;

    private bool $debug;



    public function __construct(Configuration $config, HttpClientInterface $httpClient)
    {
        $config->configure();
        $this->apiMfUrl = $config->getParameter('API-MF-URL')->getValue();
        //$this->apiMfClientId = $config->getParameter('API-MF-CLIENT-ID')->getValue();
        //$this->apiMfClientSecret = $config->getParameter('API-MF-CLIENT-SECRET')->getValue();
        $this->httpClient = $httpClient;
        //$this->getToken();
    }


    /**
     *  Función para autenticación y obtener token de acceso al APIMF,
     *  mientras el token sea válido usa el actual, si no pide uno nuevo
     */
    private function getToken(): void
    {
        if ('' === $this->token || !$this->checkTokenExpired()) {
            $endpoint = $this->apiMfUrl.'/api/token';
            $payload = [ 'client_id' => $this->apiMfClientId, 'client_secret' => $this->apiMfClientSecret];
            $response = $this->httpClient->request('POST', $endpoint, ['json' => $payload]);
            if($response->getStatusCode() === 200)
                $this->token = json_decode($response->getContent(),true)['access_token'];
            else
                $this->token = '';
        }
    }

    /**
     * Funcion para verificar la validez del token de autenticación
     * @return bool true si está expirado, false si todavía es válido
     */
    private function checkTokenExpired(): bool
    {
        if ('' === $this->token) {
            return true;
        }

        //ToDo decodificar token y ver si es válido todavía antes de pedir uno nuevo

        return false;
    }


    /**
     * Función para asegurar que el token siempre sea válido con un margen de 5 minutos a la fecha
     * de expiración del mismo, si está vencido o próximo a vencer se renovará.
     * retorna el token
     * */
   /* protected function aseguraToken()
    {
        if ($this->tokenApi == '') {
            return $this->obtenerTokenApi();
        }
        $tokenDecodificado = base64_decode($this->tokenApi);
        $expPos = strpos($tokenDecodificado, '"exp":'); // Encontrar la posición del campo "exp" en la cadena
        $expStartPos = $expPos + strlen('"exp":');
        $expEndPos = strpos($tokenDecodificado, '}', $expStartPos);
        $exp = substr($tokenDecodificado, $expStartPos, $expEndPos - $expStartPos);
        $exp = (int)$exp;

        $margen_minutos = 5;  // Resta 5 minutos a la fecha de expiración (300 segundos)
        $timestamp_margen = $exp - ($margen_minutos * 60);
        $fecha_expiracion_margen = date('Y-m-d H:i:s', $timestamp_margen);

        $fecha_actual = date('Y-m-d H:i:s');  // Obtiene la fecha y hora actual

        if ($fecha_actual > $fecha_expiracion_margen)  // Verifica si el token ha expirado
        {
            $this->output->writeln(__FUNCTION__ . " TOKEN Expirado o muy pronto a expirar, se renovará");
            return $this->obtenerTokenApi();
        }

        // El token sigue siendo válido, devolver actual
        return $this->tokenApi;
    }
    */



















    /**
     * Función para obtener un anuncio especificando el id de motorflash ad_id
     * @param int $ad_id id de anuncio en motorflash
     * @return string (json)
     */
    public function getAdByMfId(int $ad_id): ?string
    {
        $endpoint = $this->apiMfUrl.'/api/advertisement?id='.$ad_id;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }


    /**
     * Función para obtener un anuncio especificando el Vehicle Idintification Number (VIN)
     * @param int $VIN del de anuncio
     * @return string (json)
     */
    public function getAdByVIN(int $VIN): ?string {
        $endpoint = $this->apiMfUrl.'/api/advertisement?vin='.$VIN;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }

    /**
     * Función para obtener un anuncio especificando la placa o matrícula (plate)
     * @param string $plate matricula del de anuncio
     * @return string (json)
     */
    public function getAdByPlate(string $plate): ?string {
        $endpoint = $this->apiMfUrl.'/api/advertisement?plate='.$plate;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }


    /**
     * Función para obtener una lista de anuncios especificando la tienda
     * @param string $shopId id de la tienda
     * @return string (json)
     */
    public function getAdsByShop(string $shopId): ?string {
        $endpoint = $this->apiMfUrl.'/api/advertisements?shop='.$shopId;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }

    /**
     * Función para obtener una lista de anuncios especificando datos de paginado
     * @param string $perPage cantidad por pagina
     * @param string $page pagina solicitada
     * @return string (json)
     */
    public function getAdsByPage(int $perPage = 40, int $page = 1): ?string {
        $endpoint = $this->apiMfUrl.'/api/advertisements?perPage='.$perPage.'&page='.$page;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }



    public function dumpConfig(): void
    {
        echo 'APIMF URL:'.$this->apiMfUrl."\n";
        echo 'APIMF CLIENTID:'.$this->apiMfClientId."\n";
        echo 'APIMF SECRET:'.$this->apiMfClientSecret."\n";
        echo 'APIMF TOKEN (Current): '.$this->token."\n";
    }

    /**
     * @param string|null $apiMfClientId
     * @return APIMFClient
     */
    public function setApiMfClientId(?string $apiMfClientId): APIMFClient
    {
        $this->apiMfClientId = $apiMfClientId;
        return $this;
    }

    /**
     * @param string|null $apiMfClientSecret
     * @return APIMFClient
     */
    public function setApiMfClientSecret(?string $apiMfClientSecret): APIMFClient
    {
        $this->apiMfClientSecret = $apiMfClientSecret;
        return $this;
    }




}