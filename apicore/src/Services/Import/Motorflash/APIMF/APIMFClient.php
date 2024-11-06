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
        $this->httpClient = $httpClient;
    }

    /**
     *  Función para autenticación y obtener token de acceso al APIMF,
     *  mientras el token sea válido usa el actual, si no pide uno nuevo
     */
    public function authenticate(){
        $this->ensureToken();
    }


    /**
     *  Función para obtener token de acceso al APIMF,
     */
    private function getToken(): void
    {
            $endpoint = $this->apiMfUrl.'/api/token';
            $payload = [ 'client_id' => $this->apiMfClientId, 'client_secret' => $this->apiMfClientSecret];
            $response = $this->httpClient->request('POST', $endpoint, ['json' => $payload]);
            if($response->getStatusCode() === 200)
                $this->token = json_decode($response->getContent(),true)['access_token'];
            else
                $this->token = '';
    }

    /**
     * Funcion para verificar la validez del token de autenticación con
     * un margen de 5 minutos a la fecha hora de expiración del mismo.
     * @return bool true si es válido o false si es vacio o ha expirado
     */
    private function isTokenValid(): bool
    {
        if ('' === $this->token) {
            return false;
        }

        // Decodificar el token y obtener la posición del campo "exp" (fecha de expiración)
        $decodedToken = base64_decode($this->token);
        $expPosition = strpos($decodedToken, '"exp":') + strlen('"exp":');
        $expirationTime = (int)substr($decodedToken, $expPosition, strpos($decodedToken, '}', $expPosition) - $expPosition);

        // Ajuste de margen de tiempo de 5 minutos antes de la expiración
        $expirationThreshold = $expirationTime - (5 * 60);

        // Si el token está cerca de expirar
        if (time() > $expirationThreshold) {
            return false;
        }

        return true;
    }


    /**
     * Función para asegurar que el token siempre sea válido antes de usarse
     * retorna el token
     * */
    protected function ensureToken():void
    {
        // Si el token está vacío o ha expirado, se obtiene uno nuevo
        if ( !$this->isTokenValid()) {
            $this->getToken();
        }
    }



    /**
     * Función para obtener un anuncio especificando el id de motorflash ad_id
     * @param int $ad_id id de anuncio en motorflash
     * @return string (json)
     */
    public function getAdByMfId(int $ad_id): ?string
    {
        $this->ensureToken();
        $endpoint = $this->apiMfUrl.'/api/advertisement?id='.$ad_id;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }


    /**
     * Función para obtener un anuncio especificando el Vehicle Identification Number (VIN)
     * @param int $VIN del de anuncio
     * @return string (json)
     */
    public function getAdByVIN(int $VIN): ?string {
        $this->ensureToken();
        $endpoint = $this->apiMfUrl.'/api/advertisement?vin='.$VIN;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }


    /**
     * Función para obtener un anuncio especificando la placa o matrícula (plate)
     * @param string $plate matricula del de anuncio
     * @return string (json)
     */
    public function getAdByPlate(string $plate): ?string {
        $this->ensureToken();
        $endpoint = $this->apiMfUrl.'/api/advertisement?plate='.$plate;
        return $this->httpClient->request('GET', $endpoint, ['headers' => ['Authorization' => ' Bearer '.$this->token]])->getContent();
    }


    /**
     * Función para obtener una lista de anuncios especificando la tienda
     * @param string $shopId id de la tienda
     * @return string (json)
     */
    public function getAdsByShop(string $shopId): ?string {
        $this->ensureToken();
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
        $this->ensureToken();
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
     * @return string|null
     */
    public function getApiMfClientId(): ?string
    {
        return $this->apiMfClientId;
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
     * @return string|null
     */
    public function getApiMfClientSecret(): ?string
    {
        return $this->apiMfClientSecret;
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